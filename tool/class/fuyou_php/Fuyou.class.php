<?php

class Fuyou {

    public $xml;
    public $config;

    public function __construct() {
        include_once 'A2Xml.class.php';
        $this->xml = new A2Xml();
        $this->config = include 'config.php';
//        header("Content-type: text/html; charset=gb2312");
    }

    /**
     * 订单号
     * @return type
     */
    public function get_order_no() {
        return $this->config['no_code'] . date('Ymd') . rand(100000000, 999999999);
    }

    /**
     * 直接页面支付
     * @param type $jsApiParameters
     * @param type $success_url
     */
    public function h5_js($jsApiParameters, $success_url) {
        $weixinjs = '/public/lib/weixin/js/jweixin-1.0.0.js';
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
        <title>微信支付</title>
         </head>
             <body>
                 <script type="text/javascript" src="{$weixinjs}" ></script>
        	<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
                                {$jsApiParameters},
				function(res){
					//WeixinJSBridge.log(res.err_msg);
                                        if(res.err_msg == "get_brand_wcpay_request:ok"){
                                                location.href="{$success_url}";
                                        }else if(res.err_msg == "get_brand_wcpay_request:fail"){
                                            alert(res.err_code+res.err_desc+res.err_msg);
                                        }
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
                       
		}
                                         
                callpay();
	</script>
    </body>
</html>
EOT;
    }

    /**
     * 支付请求  小程序  公众号
     * @param type $map
     * @return type
     */
    public function pay($map = array()) {
        if (!$map['appid']) {
            return array('status' => 0, 'info' => 'appid is empty');
        }
        if (!$map['openid']) {
            return array('status' => 0, 'info' => 'openid is empty');
        }
        if (!$map['total']) {
            return array('status' => 0, 'info' => 'total is empty');
        }
        if (!$map['notify']) {
            return array('status' => 0, 'info' => 'notify is empty');
        }
        if (!$map['ordernum']) {
            return array('status' => 0, 'info' => 'ordernum is empty');
        }
        //小程序：let_pay，公众号：js_pay
        $pay_type = $map['pay_type'] ? $map['pay_type'] : 'js_pay';
        $mchnt_order_no = $this->get_order_no();

        //插入sql语句
//        M('mall_order_no')->add(array(
//            'ordernum' => $map['ordernum'],
//            'no' => $mchnt_order_no,
//            'time' => date('Y-m-d H:i:s'),
//            'total' => $map['total'],
//        ));
        $notify_url = $map['notify'];
        $appid = $map['appid'];
        $openid = $map['openid'];
        $url = "{$this->config['api_host']}index/{$pay_type}?total={$map['total']}&mchnt_order_no={$mchnt_order_no}&mchnt_cd={$this->config['mchnt_cd']}&notify_url={$notify_url}&sub_openid={$openid}&sub_appid={$appid}";
        $res = json_decode(file_get_contents($url), true);
        if ($res['status'] == 1) {
            $reserved_pay_info = json_decode($res['reserved_pay_info'], true);
            return array('status' => 1, 'parameters' => $reserved_pay_info);
        } else {
            return array('status' => 0, 'info' => "失败：{$res['info']}");
        }
    }

    /**
     * 退款
	 mchnt_order_no 订单号
	 refund 退款金额
     */
    public function refeed($map = array()) {
        if (!$map['mchnt_order_no']) {
            return array('status' => 0, 'info' => 'mchnt_order_no 不能为空');
        }
        if ($map['refund'] <= 0) {
            return array('status' => 0, 'info' => 'refund 不能为空');
        }
        $res = file_get_contents($this->config['api_host'] . "index/refeed?mchnt_order_no={$map['mchnt_order_no']}&reserved_origi_dt={$map['reserved_origi_dt']}&total={$map['total']}&refund={$map['refund']}&mchnt_cd={$this->config['mchnt_cd']}&key={$this->config['key']}");
        return json_decode($res, true);
    }

    /**
     * 订单结果查询
     */
    public function query($mchnt_order_no) {
        /**
         * 已支付
         *  [trans_stat] => SUCCESS
          [transaction_id] => 4200000397201908082121616661
         * * 未支付
         *  [trans_stat] => NOTPAY
          [transaction_id] =>
         *  已退款
         *  [trans_stat] => REFUND
         *  关闭
         *  [trans_stat] => CLOSED
         */
        $res = file_get_contents($this->config['api_host'] . "index/query?mchnt_order_no={$mchnt_order_no}&mchnt_cd={$this->config['mchnt_cd']}&key={$this->config['key']}");
        return json_decode($res, true);
    }

    /**
     * 异步验证
     */
    public function notify_check() {
        $content = file_get_contents('php://input', 'r');
        $a = urldecode(urldecode($content));
        $b = mb_substr($a, 4);
        $ob = simplexml_load_string($b);
        $res = object_to_array($ob);
        ksort($res);
        $dataStr = '';
        foreach ($res as $key => $val) {
            if ($key == 'sign' || substr($key, 0, strlen('reserved')) == 'reserved') {
                continue;
            }
            if (is_array($val) && empty($val)) {
                $val = '';
            }
            $dataStr.="&" . $key . "=" . $val;
        }
        $dataStr = ltrim($dataStr, '&');
        $check_res = $this->xml->sign_check(iconv('UTF-8', 'GBK//ICNORE', $dataStr), $res['sign']);
        if ($check_res == 1) {
            return array('status' => 1, 'data' => $res);
        } else {
            return array('status' => 0, 'data' => $res);
        }
    }

}
