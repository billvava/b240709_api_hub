<?php

include_once 'Fuyou.class.php';
$fuyou = new Fuyou();
//支付
$map = array(
    'total' => 0.01, //订单金额
    'appid' => 'wx195521252225', //APPID
    'openid' => 'opdsfjdshjfndsyu', //openid
    'notify' => 'http://a.com', //你接收异步的地址
    'ordernum' => '1434XXXXXXXXXXXXXXXX',  //注意订单号必须1434开头,每个订单号只能发起一次支付
    'pay_type' => 'let_pay', //小程序：let_pay，公众号：js_pay
);
$res = $fuyou->pay($map);
//返回的参数就跟微信支付JS的参数一样的
var_dump($res);




//异步回调验证
$res = $fuyou->notify_check();
$flag = 0;
if ($res['status'] == 1) {
    $flag = 1;
} else {
	//验签失败的时候使用接口来查询订单状态
    if ($res['data']['mchnt_order_no']) {
        //查询接口只能查出3日内订单
        $query_res = $fuyou->query($res['data']['mchnt_order_no']);
        if ($query_res['trans_stat'] == 'SUCCESS') {
            $flag = 1;
        }
    }
}
if ($flag == 1) {
    //验证成功，你的业务
//    $mall_order_no = M('mall_order_no');
//    $info = $mall_order_no->where(array('no' => $res['data']['mchnt_order_no']))->find();
//    $mall_order_no->where(array('id' => $info['id']))->save(array('is_pay' => 1, 'transaction_id' => $res['data']['transaction_id'], 'pay_time' => date('Y-m-d H:i:s')));
	//异步成功输出1
    echo 1;
}


//退款接口
$fuyou->refeed(array(
    'mchnt_order_no'=>'订单号',
    'total'=>'订单原始金额',
    'refund'=>'退款金额',
    'reserved_origi_dt'=>'20210501' //选填，订单交易时间，加上该参数，最大可退款90天内订单，不加的就是只能30天内
));