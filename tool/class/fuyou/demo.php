<?php

class FuyouController {

    public $fuyou;

    public function __construct() {
        tool()->classs('fuyou/Fuyou');
        $this->fuyou = new Fuyou();
    }

    //支付
    public function pay() {
        $map = array(
            'total' => 0.01,
            'appid' => 1234,
            'openid' => 1234,
            'notify' => 'http://a.com',
            'ordernum' => 1234,
            'pay_type' => 'js_pay', //小程序：let_pay，公众号：js_pay
        );
        $res = $this->fuyou->pay($map);
        p($res);
    }

    //回调
    public function notify() {
        $res = $this->fuyou->notify_check();
        $flag = 0;
        if ($res['status'] == 1) {
            $flag = 1;
        } else {
            if ($res['data']['mchnt_order_no']) {
                $query_res = $this->fuyou->query($res['data']['mchnt_order_no']);
                if ($query_res['trans_stat'] == 'SUCCESS') {
                    $flag = 1;
                }
            }
        }
        if ($flag == 1) {
            $mall_order_no = M('mall_order_no');
            $info = $mall_order_no->where(array('no' => $res['data']['mchnt_order_no']))->find();
            $mall_order_no->where(array('id' => $info['id']))->save(array('is_pay' => 1, 'transaction_id' => $res['data']['transaction_id'], 'pay_time' => date('Y-m-d H:i:s')));
            echo 1;
        }
    }

}
