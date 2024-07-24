<!DOCTYPE html>
<html>
<head>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="blue">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>微信支付</title>
<script type="text/javascript">
var root = "__ROOT__";
</script>
<script src="__LIB__/public.js" type="text/javascript"></script>
<script src="__LIB__/jquery.min.js" type="text/javascript"></script>
<script src="__LIB__/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript">

</script>
<style>
    .weixinbtn{ width:95%; height:41px; overflow:hidden; margin:0 auto; font-size:16px;background: #44b549 ; border:none; text-align: center;color: white;}
    .guide-order{ width: 80%;
height: auto;
overflow: hidden;
border: 1px solid #f0e7db;
padding: 20px 5% 20px 5%;

margin: 0 auto;
    margin-top: 20px;}
    .msg-success{line-height: 25px; margin-left: 10px}
        input[type="button"], input[type="submit"], input[type="reset"] {
-webkit-appearance: none;
}
</style>
    <body> 
        
        <div class="guide-order mo">
<!--            <div class="qr-show">
                <div class="qrs-title">
                    <span class="fl qrsl">{{$v.title}}</span>
                </div>
            </div>-->
            <div class="qr-hide contentshow">
                <div class="msg-success">
                    <span class="msg-cont">您的订单已经提交成功.</span>
                    <div class="slxx-0" style="height: auto;">
                        <!--<div class='pbci'><b>商品数量：</b>{{$data.number}}</div>-->
                        <div class='pbci'><b>您需支付：</b>{$data.total}元</div>

                        <form  method="post" target="_blank">
                            <input type="hidden" value="{$orderid}" name="orderid">
                            <input type="button" value="微信支付" onclick="callpay()" class="weixinbtn" style="margin-top:10px;">
                        </form>
                    </div>
                </div>
            </div>
        </div>   
        <script type="text/javascript" src="__HOME__/js/jweixin-1.0.0.js" ></script>
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
                                                layer.msg('支付成功',{shade: 0.3},function(){
                                                     location.href="{$success_url}";
                                                });
                                               
                                            
                                        }else if(res.err_msg == "get_brand_wcpay_request:fail"){
                                            alert(res.err_code+res.err_desc+res.err_msg);
//                                           layer.msg('支付失败',{shade: 0.3},function(){
//                                                });
                                                
                                        }
                                        
//					alert(res.err_code+res.err_desc+res.err_msg);
				}
			);
                        layer.closeAll();
		}

		function callpay()
		{
                     layer.msg('拼命加载中', {icon: 16,time: 5*1000,shade: [0.2, '#535252']});
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
                $(function(){
                    callpay();
                });
                
	</script>
    </body>
</html>