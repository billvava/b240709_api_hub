<?php

class wx_app {

    public $option;

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
        $check = array('total', 'ordernum', 'notify_url');
        foreach ($check as $v) {
            if (!$map[$v]) {
                return array('status' => 0, 'info' => "缺少{$v}参数");
            }
        }
        include_once INCLUDE_PATH . 'WxPayPubHelper/lib/WxPay.Api.php';
        $WxPayUnifiedOrder = new \WxPayUnifiedOrder();
        $WxPayApi = new \WxPayApi();
        $conf = $this->option;
        $appid = $conf['appid'];
        $wxshopid = $conf['wxshopid'];
        $wxshoppwd = $conf['wxshoppwd'];
        $ordernum = get_ordernum();
        $map = array(
            'appid' => $appid,
            'out_trade_no' => $map['ordernum'],
            'body' => $map['body'] ?: "购物",
            'total_fee' => $map['total'] * 100,
            'mch_id' => $wxshopid,
            'trade_type' => 'APP',
            'notify_url' => $map['notify_url'],
            'nonce_str' => md5(uniqid()),
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
        );
        foreach ($map as $k => $v) {
            $WxPayUnifiedOrder->setParameter($k, $v);
        }
        $res = $WxPayApi->unifiedOrder($WxPayUnifiedOrder);
        
        if(is_string($res)){
            $res = json_decode($res,true);
        }
        if ($res['return_code'] == 'FAIL') {
            return array('status' => 0, 'info' => $res['return_msg']);
        }
         $parameters = $this->getOrder($res['prepay_id']);
        $data = array(
            'ordernum' => $map['ordernum'],
            'is_pay' => 0,
            'orderid' => $map['orderid'],
            'parameters' => $parameters,
            'parameters_str'=>json_encode($parameters)
        );
        return array('status' => 1, 'data' => $data);
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

    /**
     * 获得发起支付包
     *
     * @param   [type]  $prepayId  [$prepayId description]
     *
     * @return  [type]             [return description]
     */
    private function getOrder($prepayId) {
        $conf = $this->option;
        $appid = $conf['appid'];
        $wxshopid = $conf['wxshopid'];

        $data["appid"] = $appid;
        $data["noncestr"] = md5(uniqid());
        $data["package"] = "Sign=WXPay";
        $data["partnerid"] = $wxshopid;
        $data["prepayid"] = $prepayId;
        $data["timestamp"] = time();
        $data["sign"] = $this->getSign($data);
//        $data["packagestr"] = "Sign=WXPay";
        return $data;
    }

    /**
     * 签名
     *
     * @param   [type]  $Obj  [$Obj description]
     *
     * @return  [type]        [return description]
     */
    private function getSign($Obj) {
        foreach ($Obj as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo "【string】 =".$String."</br>";
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->option['wxshoppwd'];
//        echo "<textarea style='width: 50%; height: 150px;'>$String</textarea> <br />";
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    /**
     * 将数组转成uri字符串
     *
     * @param   [type]  $paraMap    [$paraMap description]
     * @param   [type]  $urlencode  [$urlencode description]
     *
     * @return  [type]              [return description]
     */
    private function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

}
