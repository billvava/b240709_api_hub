<?php

namespace app\shopapi\model;



use app\common\lib\Util;

class O {
	
	 //分享海报
    public function create_ad_share($master_id, $type = 1)
    {
        $temp_ewm_path = C('temp_ewm_path');
        $path = $temp_ewm_path . "{$master_id}/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $Ad = new \app\admin\model\Ad();
        $ad3 = $Ad->get_ad('share_bg');
        $agenturl = C('agenturl');
        if(!$agenturl){
            $agenturl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        }
        $img = array();
        foreach ($ad3 as $k => &$v) {
            $name = "{$master_id}_{$k}_{$type}.png";
            $file = "{$path}{$name}";
            if (!file_exists($file) || 1) {
                $BaseImg = imagecreatefromstring(file_get_contents($v['big']));
                //二维码
                $res = $this->get_cxx_ewm($master_id);

                if ($res['status'] == 1) {
                    $QRcodeImg = imagecreatefromstring($res['img']);
                    $QRcodeImg_width = imagesx($QRcodeImg);
                    $QRcodeImg_height = imagesy($QRcodeImg);
                    imagecopyresampled($BaseImg, $QRcodeImg, 158, 516, 0, 0, 225, 225, $QRcodeImg_width, $QRcodeImg_height);
                }
                $bool = ImagePng($BaseImg, $file);
                imagedestroy($BaseImg);
            }
            $img[] = $agenturl .'/'. trim($file, './');
        }
        foreach ($img as &$v) {
            $v = $v . '?v=' . rand(10, 9999);
        }
        return $img;
    }

    //分享海报
    public function create_goods_share($master_id) {
        $path = "./uploads/goods_ewm/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $name = "{$master_id}.png";
        $file = "{$path}{$name}";
        $wapurl = C('wapurl');
        if (!file_exists($file) ) {

            $base_img = "./public/img/empHeader.jpg";
            $BaseImg = imagecreatefromstring(file_get_contents($base_img));

            //二维码
            $res = $this->get_cxx_ewm($master_id);
            if ($res['status'] == 1) {
                $QRcodeImg = imagecreatefromstring($res['img']);
                $QRcodeImg_width = imagesx($QRcodeImg);
                $QRcodeImg_height = imagesy($QRcodeImg);
                imagecopyresampled($BaseImg, $QRcodeImg, 60, 99, 0, 0, 120, 120, $QRcodeImg_width, $QRcodeImg_height);
            }
            $bool = ImagePng($BaseImg, $file);
            imagedestroy($BaseImg);
        }
        return C('wapurl') . trim($file, '.');
    }

    public function get_cxx_ewm($master_id) {
        $util = new Util();
        //本地环境使用测试二维码
        if($util->check_debug()){
            return array('status' => 1, 'img' => file_get_contents("./img/warter.png"));
        }
        if (!class_exists('weixin')) {
            include INCLUDE_PATH . 'weixin/weixin.class.php';
        }
        $weixin = new \weixin(C('appid'), C('apppwd'));
        $access_token = $weixin->get_access_token();
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $scene = "pid_{$master_id}";
        $data = json_encode(
                array(
                    'scene' => ($scene),
                    'page' => "pages/index/index",
                )
        );
        $res = $weixin->request_post($url, $data);
        $t = json_decode($res, true);
        if (is_array($t)) {
            return array('status' => 0, 'info' => $t['errmsg']);
        } else {
            return array('status' => 1, 'img' => $res);
        }
    }

}
