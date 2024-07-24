<?php

class Yunpian {

    public $ch;

    public function __construct() {
        header("Content-Type:text/html;charset=utf-8");
        $this->ch = curl_init();
        /* 设置验证方式 */
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'));
        /* 设置返回结果为流 */
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间 */
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
    }

//获得账户
    public function get_user($apikey) {
        curl_setopt($this->ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/user/get.json');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $apikey)));
        $result = curl_exec($this->ch);
        $error = curl_error($this->ch);

        return $result;
    }

    //短信自动匹配
    public function send($data) {
        curl_setopt($this->ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($this->ch);
        $error = curl_error($this->ch);

        return $result;
    }

    //短信模板
    public function tpl_send($data) {
        curl_setopt($this->ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/tpl_single_send.json');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($this->ch);
        $error = curl_error($this->ch);

        return $result;
    }

    //语音
    public function voice_send($data) {
        curl_setopt($this->ch, CURLOPT_URL, 'http://voice.yunpian.com/v2/voice/send.json');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($this->ch);
        $error = curl_error($this->ch);

        return $result;
    }

    // 发送语音通知，务必要报备好模板
    public function notify_send($data) {
        curl_setopt($this->ch, CURLOPT_URL, 'https://voice.yunpian.com/v2/voice/tpl_notify.json');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($this->ch);
        $error = curl_error($this->ch);

        return $result;
    }

//发送云片网短信
    public function send_sms($mobile, $tpl_id, $tpl_value) {
        //tpl_value : #code#=urlencode('1234')
        $yunpian_apikey = C('yunpian_apikey');
        if (!$yunpian_apikey) {
            return array('status' => 0, 'info' => '秘钥不能为空');
        }
//        tool()->classs('Yunpian');
//        $yunpian = new \Yunpian();
        $data = array('tpl_id' => $tpl_id, 'tpl_value' => $tpl_value
            , 'apikey' => $yunpian_apikey, 'mobile' => $mobile);
        $json_data = $this->tpl_send($data);
        $array = json_decode($json_data, true);
        if ($array && $array['code'] == 0) {
            return array('status' => 1, 'info' => $array['msg']);
        } else {
            return array('status' => 0, 'info' => $array['msg'] . $array['detail']);
        }
    }

}
