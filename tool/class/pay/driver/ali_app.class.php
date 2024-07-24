<?php

class ali_app
{

    public $option;
    public $api;

    public function __construct($option)
    {
        $this->option = $option;
        include_once INCLUDE_PATH . 'alipay/alipay.class.php';
        include INCLUDE_PATH . 'alipay/config.php';
        $this->api = new \alipayApi();
        if (!$this->api->setConfig($config)) {
            return ['status' => 0, 'info' => $this->api->getErrMessage()];
        }

    }

    /**
     * 查询订单
     * @param type $mchnt_order_no
     * @return type
     */
    public function query($mchnt_order_no)
    {

        $objData = array(
            'out_trade_no' => "{$mchnt_order_no}"
        );
        $res = $this->api->query($objData);
        if (!$res) {
            return ['status' => 0, 'info' => $this->api->getErrMessage()];
        } elseif ($res['status'] == 1) {
            return ['status' => 1, 'data' => $res['data']];
        } else {
            return ['status' => 0, 'info' => $res['info']];

        }

    }

    /**
     * 支付
     *
     * @param array $map [$map description]
     *
     * @return  []           [return description]
     */
    public function pay(array $map = [])
    {
        $check = array('total', 'ordernum', 'notify_url');
        foreach ($check as $v) {
            if (!$map[$v]) {
                return array('status' => 0, 'info' => "缺少{$v}参数");
            }
        }
        $objData = array(
            'body' => C('title'),
            'subject' => C('title'),
            'out_trade_no' => $map['ordernum'],
            'timeout_express' => '1h',
            'total_amount' => $map['total'],
            'passback_params' => 'goods',
        );
        $pubData = array(
            'notify_url' => $map['notify_url'],
        );
        $res = $this->api->appPay($objData, $pubData);
        if ($res) {
            $data = array(
                'ordernum' => $map['ordernum'],
                'is_pay' => 0,
                'orderid' => $map['orderid'],
                'parameters' => $res
            );
            return array('status' => 1, 'data' => $data);

        } else {
            return array('status' => 0, 'info' => $this->api->getErrMessage());
        }

    }

    /**
     * 退款
     *
     * @param array $map [$map description]
     *
     * @return  []           [return description]
     */
    public function refund(array $map = [])
    {
        $check = array('ordernum', 'total', 'refund');
        foreach ($check as $v) {
            if (!$map[$v]) {
                return array('status' => 0, 'info' => "缺少{$v}参数");
            }
        }
        $objData = array(
            'out_trade_no' => "{$map['ordernum']}",
            'refund_amount' => $map['refund'], //申请退款金额，单位：元
            'refund_reason' => '', //可选，退款理由
            'out_request_no' => '', //可选，标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
            'operator_id' => '', //可选，商户的操作员编号
            'store_id' => '', //可选，商户的门店编号
            'terminal_id' => '', //可选，商户的终端编号
        );
        $res = $this->api->refund($objData);
        if (!$res) {
            return array('status' => 0, 'info' => $this->api->getErrMessage());

        } elseif ($res['status'] == 1) {
            return array('status' => 1, 'info' => '退款成功');
        } else {
            return array('status' => 0, 'info' => $res['info']);
        }


    }

    /**
     * 异步验签
     *
     * @return  [type]  [return description]
     */
    public function notify()
    {

        $flag = $this->api->verifyData($_POST);
        $res = [
            'status' => 0,
            'echo' => 'success',
        ];
        if ($flag) {

            if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $res = [
                    'status' => 1,
                    'echo' => 'success',
                    'data' => [
                        'ordernum' => $_POST['out_trade_no'],
                        'trade_no' => $_POST['trade_no'],
                        'transaction_id' => $_POST['trade_no'],

                    ],
                ];
            }
        }
        return $res;
    }


}
