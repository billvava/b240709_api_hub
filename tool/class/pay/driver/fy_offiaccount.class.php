<?php

class fy_offiaccount {

    public $option;
    public $fuyou;

    public function __construct($option) {
        $this->option = $option;
        tool()->classs('fuyou/Fuyou');
        $this->fuyou = new \Fuyou();
    }

    /**
     * 查询3日内的订单
     * @param type $mchnt_order_no
     * @return type
     */
    public function query($mchnt_order_no) {
        return $this->fuyou->query($mchnt_order_no);
    }

    /**
     * 支付
     *
     * @param   array  $map  [$map description]
     *
     * @return  []           [return description]
     */
    public function pay(array $map = []) {
        $config = $this->option;
        $res = $this->fuyou->pay([
            'appid' => $map['appid'],
            'openid' => $map['openid'],
            'total' => $map['total'],
            'notify' => $map['notify_url'],
            'ordernum' => $map['ordernum'],
            'pay_type' => 'js_pay',
        ]);
        if ($res['status'] == 1) {
            $res = array(
                'ordernum' => $map['ordernum'],
                'is_pay' => 0,
                'orderid' => $map['orderid'],
                'parameters' => $res['parameters']
            );
            return array('status' => 1, 'data' => $res);
        } else {
            return array('status' => 0, 'info' => "失败：{$res['info']}");
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
        return $this->fuyou->refeed($map);
    }

    /**
     * 异步验签
     *
     * @return  [type]  [return description]
     */
    public function notify() {
        $res = $this->fuyou->notify_check();
        $flag = 0;
        if ($res['status']) { // 返回成功
            $flag = 1;
        } else { // 查询是否真实支付
            if ($res['data']['mchnt_order_no']) {
                $query_res = $fuyou->query($res['data']['mchnt_order_no']);
                if ($query_res['trans_stat'] == 'SUCCESS') {
                    $flag = 1;
                }
            }
        }
        // 默认失败
        $result = ['status' => 0, 'echo' => 1, 'info' => '异步验证失败'];
        // 验签成功
        if ($flag) {
            $mall_order_no = \think\facade\Db::name('mall_order_no');
            $order_no = $mall_order_no->where(array(
                        'no' => $res['data']['mchnt_order_no']
                    ))->find();
            if ($order_no) {
                $mall_order_no->where([
                    'no' => $res['data']['mchnt_order_no']
                ])->save(['is_pay' => 1, 'pay_time' => date('Y-m-d H:i:s'), 'transaction_id' => $res['data']['transaction_id']]);
            }
            $res['data']['ordernum'] = $order_no['ordernum'];
            $res['data']['total'] = $order_no['total'];
            $res['data']['remark'] = ''; // 支付时候提交什么就返回什么 具体需要查询文档看是哪个字段
            $result = [
                'status' => 1,
                'echo' => 1,
                'data' => $res['data']];
        }
        return $result;
    }

}
