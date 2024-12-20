<?php

class JSSDK {

    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage($url="") {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if(!$url){
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        }

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $cache_file = RUNTIME_PATH . "jsapi_ticket.json";
        if(!file_exists($cache_file)){
            file_put_contents($cache_file,'');
        }
        $data = json_decode(file_get_contents($cache_file),true);
        if (!isset($data['expire_time']) && $data['expire_time'] < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url),true);
            $ticket = $res['ticket'];
            if ($ticket) {
                $data['expire_time'] = time() + 7000;
                $data['jsapi_ticket'] = $ticket;
                file_put_contents($cache_file,json_encode($data));
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }

        return $ticket;
    }

    /**
     * 获取accesstoken
     * @return type
     */
    public function getAccessToken() {
        $file_name = RUNTIME_PATH . $this->appId . '.txt';
        if (!file_exists($file_name)) {
            file_put_contents($file_name, '');
        }
        $data = json_decode(file_get_contents($file_name), true);
        if ($data['expire_time'] < time()) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
            $res = json_decode($this->httpGet($url), true);
            $access_token = $res['access_token'];
            if ($res['access_token']) {
                $res['expire_time'] = time() + 7000;
                file_put_contents($file_name, json_encode($res));
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
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
     * 获取微信用户基本信息
     * @param type $openid
     * @return type
     */
    public function get_baseinfo($openid) {
        return json_decode($this->httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->getAccessToken()}&openid={$openid}&lang=zh_CN"), true);
    }

}
