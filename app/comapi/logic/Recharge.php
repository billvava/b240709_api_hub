<?php

namespace app\comapi\logic;

use app\com\model\RechargeItem;
use app\com\model\RechargeOrder;
use think\App;
use think\facade\Db;

class Recharge {

    public $clear;
    public $uinfo;
    public $data;
    public $model;
    public $usermodel;
    public $rechargeOrder;

    public function __construct() {
        $this->model = new RechargeItem();
        $this->rechargeOrder = new RechargeOrder();
        $this->usermodel = new \app\common\model\User();
        if (!$this->uinfo['id']) {
            return array('status' => -1, 'info' => '请先登陆');
        }
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }

    public function index() {
        $this->data['rechargeInfo'] = C('recharge_info');
        $this->data['finance'] = $this->usermodel->getFinance($this->uinfo['id']);
        $this->data['list'] = $this->model->getList(['status' => 1]);
        return array('status' => 1, 'data' => $this->data);
    }

    public function pay_sub() {
        $money = number_format($this->in['money'], 2, '.', '');
        if ($money != $this->in['money'] || $money <= 0) {
            return array('status' => 0, 'info' => '金额格式错误');
        }
        $order_info = array(
            'total' => $money,
            'goods_total' => $money,
            'ordernum' => get_ordernum(),
            'user_id' => $this->uinfo['id'],
            'create_time' => date('Y-m-d H:i:s'),
            'pay_type' => 1,
            'is_pay' => 0
        );
        if ($this->in['id']) {
            $info = $this->model->getInfo($this->in['id']);
            if ($info) {
                $order_info['total'] = $info['money'];
                $order_info['give'] = $info['give'];
                $order_info['goods_total'] = $info['money'];
            }
        }
        if ($order_info['total'] <= 0) {
            return array('status' => 0, 'info' => '金额错误');
        }

        $this->rechargeOrder->insertGetId($order_info);
        $notify_url = C('wapurl') . "/comapi/RechargePay/index";
        $wx_pay_type = C('wx_pay_type');
        tool()->classs('pay/Pay');
        $Pay = new \Pay($wx_pay_type);
        $res = $Pay->pay([
            'appid' => C('appid'),
            'total' => $order_info['total'],
            'openid' => $this->uinfo['openid'],
            'ordernum' => $order_info['ordernum'],
            'notify_url' => $notify_url,
        ]);
        if ($res['status'] == 1) {
            unset($res['status']);
            $res['pay_type'] = 4;
            $res['is_pay'] = 0;
            $res['id'] = $order_info['id'];
            return ['status' => 1, 'data' => $res];
        } else {
            return ['status' => 0, 'info' => $res['info']];
        }
    }

}
