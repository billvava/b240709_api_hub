<?php

/**
 * 微信支付 
 * @param type $pay_url 支付的URL
 * @param type $ordernum 订单号
 * @param type $total 金额
 * @param type $notify_url  异步URL
 * @param type $success_url 同步URL
 */
function weixin_pay($pay_url, $ordernum, $total, $notify_url, $success_url) {

    //开始引入微信支付
    if (!class_exists('JsApi_pub')) {
        include INCLUDE_PATH . '/WxPayPubHelper/WxPayPubHelper.php';
    }

    //使用jsapi接口
    $jsApi = new \JsApi_pub();
    //=========步骤1：网页授权获取用户openid============
    if (!session('weixin.openid')) {
        if (!isset($_GET['code'])) {
            $appid = C('appid');
            $pay_url = urlencode($pay_url);
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&response_type=code&scope=snsapi_base&state=123&redirect_uri={$pay_url}";
            header("location:{$url}");
            die;
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }
    } else {
        $openid = session('weixin.openid');
    }

    //=========步骤2：使用统一支付接口，获取prepay_id============
    //使用统一支付接口
    $unifiedOrder = new \UnifiedOrder_pub();

    $unifiedOrder->setParameter("openid", $openid);
    $unifiedOrder->setParameter("body", C('title') . "支付"); //商品描述
    //$out_trade_no = 'wx'.str_shuffle(WxPayConf_pub::SHOP_PRE) . "$timeStamp";
    $unifiedOrder->setParameter("out_trade_no", "{$ordernum}"); //商户订单号 
    $total = $total * 100;
    $unifiedOrder->setParameter("total_fee", "{$total}"); //总金额
    $unifiedOrder->setParameter("notify_url", $notify_url); //通知地址 
    $unifiedOrder->setParameter("trade_type", "JSAPI"); //交易类型
    $prepay_id = $unifiedOrder->getPrepayId();
    //=========步骤3：使用jsapi调起支付============
    $jsApi->setPrepayId($prepay_id);
    $jsApiParameters = $jsApi->getParameters();
    $weixinjs = __ROOT__ . '/public/lib/weixin/js/jweixin-1.0.0.js';
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
    die;
}

/**
 * 微信支付验证
 */
function weixin_pay_check() {
    if (!class_exists('Notify_pub')) {
        include INCLUDE_PATH . '/WxPayPubHelper/WxPayPubHelper.php';
    }
    //使用通用通知接口
    $notify = new \Notify_pub();
    //存储微信的回调
    $xml = file_get_contents('php://input', 'r');
    $notify->saveData($xml);
//    $returnXml = $notify->returnXml();
    if ($notify->checkSign() == TRUE) {
        if ($notify->data["result_code"] == "SUCCESS") {
            /*
              $notify->data['out_trade_no']
              $notify->data['transaction_id']
              $notify->data['total_fee'] / 100
             *              */
            return array(
                'status' => 1,
                'data' => $notify->data
            );
        }
    }
    return array(
        'status' => 0,
    );
}
