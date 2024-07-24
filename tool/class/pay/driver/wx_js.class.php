<?php

class wx_js {

    public $option;
    public $fuyou;

    public function __construct($option) {
        $this->option = $option;
        include_once INCLUDE_PATH . '/WxPayPubHelper/WxPayPubHelper.php';
    }

    /**
     * 查询订单
     * @param type $mchnt_order_no
     * @return type
     */
    public function query($mchnt_order_no) {
        $OrderQuery_pub = new \OrderQuery_pub();
        $OrderQuery_pub->setParameter('out_trade_no', $mchnt_order_no);
        $OrderQuery_pub->createXml();
        return $OrderQuery_pub->getResult();
    }

    /**
     * 支付
     *
     * @param   array  $map  [$map description]
     *
     * @return  []           [return description]
     */
    public function pay(array $map = []) {
        $check = array('total', 'openid', 'ordernum', 'notify_url');
        foreach ($check as $v) {
            if (!$map[$v]) {
                return array('status' => 0, 'info' => "缺少{$v}参数");
            }
        }

        try {
            $total = $map['total'] * 100;
            $conf = $this->option;
//            p($conf);
            $unifiedOrder = new \UnifiedOrder_pub($conf);
            $unifiedOrder->setParameter("openid", $map['openid']);
            $unifiedOrder->setParameter("body", $map['body'] ?: "购物"); //商品描述
            //$out_trade_no = 'wx'.str_shuffle(WxPayConf_pub::SHOP_PRE) . "$timeStamp";
            $unifiedOrder->setParameter("out_trade_no", $map['ordernum']); //商户订单号 
            $unifiedOrder->setParameter("total_fee", "{$total}"); //总金额
            $unifiedOrder->setParameter("notify_url", $map['notify_url']); //通知地址 
            $unifiedOrder->setParameter("trade_type", "JSAPI"); //交易类型
            $prepay_id = $unifiedOrder->getPrepayId();
            $jsApi = new \JsApi_pub($conf);
            $jsApi->setPrepayId($prepay_id);
            $jsApiParameters = $jsApi->getParameters();
            $parameters = json_decode($jsApiParameters, true);
            $ps = explode('=', $parameters['package']);
            $parameters['prepay_id'] = $ps[1];
            $res = array(
                'ordernum' => $map['ordernum'],
                'is_pay' => 0,
                'orderid' => $map['orderid'],
                'parameters' => $parameters
            );
            return array('status' => 1, 'data' => $res);
        } catch (\SDKRuntimeException $e) {
            return array('status' => 0, 'info' => $e->errorMessage());
        }
    }

    /**
     * 退款
     *
     * @param   array  $map  [$map description]
     *
     * @return  []           [return description]
     */
    public function refund(array $map = []) {
        //$map['transaction_id']  
        $check = array('transaction_id', 'total', 'refund');
        foreach ($check as $v) {
            if (!$map[$v]) {
                return array('status' => 0, 'info' => "缺少{$v}参数");
            }
        }
        if (!class_exists('WxPayRefund')) {
            require_once INCLUDE_PATH . "WxPayPubHelper/lib/WxPay.Api.php";
        }
        $conf = $this->option;
        $total_fee = $map['total'] * 100;
        $refund_fee = $map['refund'] * 100;
        $input = new \WxPayRefund();
        $input->SetTransaction_id($map['transaction_id']);
        $input->SetTotal_fee($total_fee);
        $input->SetRefund_fee($refund_fee);
        $input->SetOut_refund_no($conf['wxshopid'] . date("YmdHis"));
        $input->SetOp_user_id($conf['wxshopid']);
        $WxPayApi = new \WxPayApi();
        $res = $WxPayApi->refund($input);
        if ($res['result_code'] == 'SUCCESS') {
            return array('status' => 1, 'info' => '退款成功');
        } else {
            return array('status' => 0, 'info' => $res['err_code_des'] . $res['return_msg']);
        }
    }

    /**
     * 异步验签
     *
     * @return  [type]  [return description]
     */
    public function notify() {
        tool()->func('weixin_pay');
        $res = weixin_pay_check();
        // 消息通知参数 @link https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/open/chapter8_8.shtml
        if ($res['status'] == 1) {
            
            $res['data']['ordernum'] = $res['data']['out_trade_no'];
            $res['data']['total'] = $res['data']['total_fee'] / 100; // 分转元
            $res['data']['remark'] = $res['data']['attach']; // 附加数据，在查询API和支付通知中原样返回，可作为自定义参数使用 
            return [
                'status' => 1,
                'echo' => 'success',
                'data' => $res['data']
            ];
        }
        $res['echo'] = 'success';
        return $res;
    }

}
