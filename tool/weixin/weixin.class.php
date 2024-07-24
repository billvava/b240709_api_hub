<?php

class weixin {

    public $appid;
    public $apppwd;

    public function __construct($appid, $apppwd) {
        header("content-type:text/html;charset=utf8");
        $this->appid = $appid;
        $this->apppwd = $apppwd;
    }

    /**
     * 获取基础功能的access_token
     * @param string $this->appid  公众号ID
     * @param string $this->apppwd 密钥
     * @return res
     */
    public function get_access_token() {
        $file_name = RUNTIME_PATH . $this->appid . '.txt';
        if(!file_exists($file_name)){
            file_put_contents($file_name, '');
        }
        $data = json_decode(file_get_contents($file_name), true);
         if (!isset($data['expire_time'] ) || $data['expire_time'] < time()) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->apppwd}";
            $res = json_decode($this->http_request_json($url), true);


            if ($res['access_token']) {

                $res['expire_time'] = time() + 7000;

                file_put_contents($file_name, json_encode($res));
            }
            return $res['access_token'];
        } else {
            return $data['access_token'];
        }
    }

    /**
     * 刷新token
     * @param string $refresh_token 
     * @return array
     */
    public function refresh_token($refresh_token) {
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->appid}&grant_type=refresh_token&refresh_token=$refresh_token";
        $json = self::http_request_json($url);
        $data = json_decode($json, true);
        if ($data) {
            return $data;
        } else {
            return '';
        }
    }

    /**
     * 发送文本消息
     * @param type $openid openid
     * @param type $msgtxt 具体消息
     * @param type $access_token 授权token
     * @return type
     */
    public function sendtxtmsg($openid, $msgtxt) {
        $postdata = array(
            'touser' => $openid,
            "msgtype" => "text",
            "text" => array('content' => $msgtxt)
        );
        $postdata = $this->_encode($postdata);

        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->get_access_token();
        $ch = curl_init($post_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postdata))
        );
        $respose_data = curl_exec($ch);
        return $respose_data;
    }

    private function _encode($arr) {
        $na = array();
        foreach ($arr as $k => $value) {
            $na[$this->_urlencode($k)] = $this->_urlencode($value);
        }
        return addcslashes(urldecode(json_encode($na)), "\r\n");
    }

    private function _urlencode($elem) {
        if (is_array($elem)) {
            foreach ($elem as $k => $v) {
                $na[$this->_urlencode($k)] = $this->_urlencode($v);
            }
            return $na;
        }
        return urlencode($elem);
    }

    /**
     * 拉取用户信息
     * @param string $access_token  
     * @param string $openid 
     * @return array
     */
    public function get_user_info($access_token, $openid) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $json = self::http_request_json($url);
        $data = json_decode($json, true);
        if ($data) {
            return $data;
        } else {
            return '';
        }
    }

    /**
     * 获取用户基本信息，可判断是否关注该公众号
     * @param string $openid 
     * @return array
     */
    public function get_user_baseinfo($openid) {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->get_access_token()}&openid=$openid&lang=zh_CN";
        $json = self::http_request_json($url);
        $data = json_decode($json, true);
        if ($data) {
            return $data;
        } else {
            return '';
        }
    }

    /**
     * 授权的accesstoken
     * @param string $this->appid  公众号ID
     * @param string $this->apppwd 密钥
     * @param string $code CODE参数
     * @return array
     */
    public function get_base_access_token($code) {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->apppwd}&code=$code&grant_type=authorization_code";
        $json = self::http_request_json($url);
        $data = json_decode($json, true);
        return $data;
    }

    /**
     * 生成微信URL
     * @return string
     */
    public function create_url($callurl) {
        $callurl = urlencode($callurl);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&response_type=code&scope=snsapi_base&state=123&redirect_uri={$callurl}";
    }

    function curls($callurl) {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$callurl}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        return $url;
    }

    /**
     * 生成微信URL2 需要用户授权的
     * @return string
     */
    public function create_url2($callurl) {
        $callurl = urlencode($callurl);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&response_type=code&scope=snsapi_userinfo&state=123&redirect_uri={$callurl}";
    }

    /**
     * 发送红包
     * @return res
     */
    public function send_hongbao($data) {
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        return $this->http_post($url, $data);
    }

    /**
     * 生成签名
     * @return string
     */
    public function create_sign($data) {
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            if ($value) {
                $str.= $key . '=' . $value . '&';
            }
        }
        $pay_key = C('wxshoppwd');
        $str .='key=' . $pay_key;
        $str = md5($str);

        return strtoupper($str);
    }

    /**
     * 构造GET请求
     * @return res
     */
    private function http_request_json($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 发送POST数据包，带证书
     * @return res
     */
    public function http_post($url, $xmlData) {
        $curl = curl_init($url);
        $path = getcwd() . '/../tool/WxPayPubHelper/cacert/';
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlData);
        curl_setopt($curl, CURLOPT_SSLCERT, $path . 'apiclient_cert.pem');
        curl_setopt($curl, CURLOPT_SSLKEY, $path . 'apiclient_key.pem');
//        curl_setopt($curl, CURLOPT_CAINFO,$path. 'rootca.pem');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * 发送POST请求
     * @param type $url string
     * @param type $param json
     * @return boolean
     */
    public function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }

    /**
     * 发送自定义的模板消息
     * @param $touser
     * @param $template_id
     * @param $url
     * @param $data
     * @param string $topcolor
     * @return bool
     */
    public function doSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE') {

        /*
         * data=>array(
          'first'=>array('value'=>urlencode("您好,您已购买成功"),'color'=>"#743A3A"),
          'name'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
          'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#FFFFFF'),
          )
         */
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $data
        );
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->get_access_token();

        $dataRes = json_decode($this->request_post($url, urldecode($json_template)), true);
        if ($dataRes['errcode'] == 0 && isset($dataRes['errcode'])) {
            return array('status' => 1);
        } else {
            return array('status' => 0, 'info' => $dataRes['errmsg']);
        }
    }


    /**
     * Notes:发送小程序订阅消息
     * User: lingyingyao
     * Date: 2022/1/5
     * Time: 3:19 下午
     * @param $openid
     * @param $template_id
     * @param $page
     * @param $data
     */

    public function minDoSend($openid, $template_id, $page, $data)
    {

        /*

        $data = array(
                    'character_string1' => array(
                        'value' => $orderinfo['ordernum']
                    ),
                    'phrase7' => array(
                        'value' => '待付款'
                    ),
                    'thing8' => array(
                        'value' => "订单已结束，金额为：{$orderinfo['total']} 请尽快付款结算！"
                    ),
                );
        */

        $template = array(
            'access_token' => $this->get_access_token(),
            'touser' => $openid,
            'template_id' => $template_id,
            'data' => $data,
            'miniprogram_state' => 'trial',  //developer为开发版；trial为体验版；formal为正式版；默认为正式版
            'page' => $page
        );
        $json_template = json_encode($template);
        $res = $this->request_post('https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $this->get_access_token(), $json_template);
        return $res;

    }

    /**
     * 生成微信菜单
     * @param type $xjson
     * @return type
     */
    public function create_meun($xjson) {
        $post_url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->get_access_token();
        $ch = curl_init($post_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xjson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($xjson))
        );
        $respose_data = curl_exec($ch);
        return json_decode($respose_data, TRUE);
    }

    /**
     * 使用用户手动网页授权  获取用户信息
     * @param type $token
     * @param type $openid
     * @return type
     */
    function getUserInfo($token, $openid) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN ";
        return json_decode($this->httpGet($url), true);
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * 获取单个素材
     * @param string $media_id [description]
     */
    public function GetOneMaterial($media_id = '') {
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=" . $this->get_access_token();
        $param = array(
            "media_id" => $media_id,
        );

        $result = $this->request_post($url, json_encode($param));
        return json_decode($result, true);
    }

    /**
     * 获取素材总数
     *
     * voice_count  语音总数量
     * video_count  视频总数量
     * image_count  图片总数量
     * news_count   图文总数量
     */
    public function GetMaterialCount() {
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=" . $this->get_access_token();
        $result = file_get_contents($url);
        return json_decode($result, true);
    }

    /**
     * 获取图文消息
     */
    public function GetMaterial($type = '', $offset = 0, $count = 1) {
        if (!in_array($type, array('image', 'video', 'voice', 'news')))
            return null;
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=" . $this->get_access_token();
        $param = array(
            "type" => $type,
            'offset' => $offset,
            'count' => $count,
        );
        $param = json_encode($param);
        $result = $this->request_post($url, urldecode($param));
        return json_decode($result, true);
    }

    /**
     *
     * Latitude    地理位置纬度
     * Longitude   地理位置经度
     * Precision   地理位置精度
     * @param [type] $lng       [description]
     * @param [type] $lat       [description]
     * @param [type] $precision [description]
     */
    protected function LocationInfo($openid, $lng, $lat, $precision) {
        $Model = Db::name('weixin_location');
        $data = array(
            'lng' => $lng,
            'lat' => $lat,
            'precision' => $precision,
            'update_time' => time(),
        );
        if ($Model->where(array('openid' => $openid))->find()) {
            $Model->where(array('openid' => $openid))->save($data);
        } else {
            $data['openid'] = $openid;
            $Model->add($data);
        }
    }

    /**
     * 上传临时图片
     */
    public function upload_images($filepath) {
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$this->get_access_token()}&type=image";
        $ch = curl_init();
        if (version_compare(PHP_VERSION, '5.6.0') >= 0) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        }
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        $fields = array();
        $fields ['media'] = "@{$filepath}";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return json_decode($response, true);
//
//上传永久
//        $access_token = $this->get_access_token();
//        $type = "image";
//        $filedata = array("media" => "@" . $filepath);
//        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type={$type}";
//        $result = $this->request_post($url, $filedata);
//        return json_decode($result, true);
    }

}
