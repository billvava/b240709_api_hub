<?php

namespace app\shopapi\logic;

use think\App;
use think\facade\Db;

class Auto {

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct() {
        $this->model = new \app\shopapi\model\Order();
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    //执行所有脚本
    public function order_shell() {
        $this->close_pt();
        $this->close_order();
        $this->sub_order();
        Db::name('mall_cart')->where("1=1")->delete();
        return ['status'=>1];
    }

//关闭订单
   public function close_order()
    {
        $close_days = C('mall_close_minute');
        if ($close_days <= 0) {
            return;
        }
        $time = date('Y-m-d H:i:s', strtotime("-{$close_days} minute"));
        $where = [
            ['pay_status', '=', 0],
            ['status', '=', 0],
            ['total', '>', 0],
            ['create_time', '<', $time],
        ];
        $data = $this->model->where($where)->field('order_id')->select()->toArray();
        $data = $data ?: [];
        foreach ($data as $v) {
            $this->model->close($v['order_id'], array('type' => 'admin'));
        }
    }


    //确认订单
    public function sub_order() {
        $MallOrder = $this->model;
        $receive_days = C('receive_days');
        if ($receive_days <= 0) {
            return;
        }
        $day = date('Y-m-d H:i:s', strtotime("-{$receive_days} day"));
        $where = [
            ['status', '=', 2],
            ['delivery_status', '=', 1],
            ['pay_status', '=', 1],
            ['refund_total', '=', 0],
            ['delivery_time', '<', $day],
        ];

        $data = Db::name('mall_order')->where($where)->field('order_id')->select()->toArray();
        $data = $data ?: [];

        foreach ($data as $v) {
            $MallOrder->finish($v['order_id'], array('type' => 'admin'));
        }
    }

    //关闭拼团
    public function close_pt() {
        $pt_order = Db::name('pt_order');
        $d = date('Y-m-d H:i:s');
        $MallOrder = $this->model;
        $where = [
                ['status', '=', 0],
                ['end_time', '<', $d],
        ];
        $data = $pt_order->where($where)->select()->toArray();
        foreach ($data as $v) {
            $t = $MallOrder->where(array('ordernum' => $v['ordernum']))->field('order_id,total')->find();
            $r = $MallOrder->refund($t['order_id'], $t['total']);
            if ($r['status'] == 1) {
                $pt_order->where(array('id' => $v['id']))->update(array('status' => 3));
                $MallOrder->close($t['order_id'], array('type' => 'admin'));
            }
        }
    }

}
