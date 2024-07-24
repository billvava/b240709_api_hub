<?php

namespace app\common\discount_act;

use think\App;
use think\facade\Db;

class Dot {

    //执行顺序，越小越在前面
    public $sort = 1;
    public $in;
    public $uinfo;
    public $data;
    public $userModel;
    public $is_can = 0;
    public $dot_scale;

    public function __construct() {
        $this->userModel = new \app\common\model\User();
        //是否开启
        $this->is_can = C('mall_can_dot');

        //兑换比例 1元 = dot_scale 个积分
        $this->dot_scale = C('dot_scale');
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function get() {
        $this->data['dot'] = 0;
        $this->data['dot_total'] = 0;

        $this->data['mall_can_dot'] = $this->is_can;

        if ($this->in['use_dot'] == 1 && $this->is_can == 1 && $this->data['total'] > 0 && $this->dot_scale > 0  && $this->data['type'] == 1) {
            //默认使用全部积分
            $this->in['dot'] = $this->data['finfo']['dot'];
            //默认只能整数
            $this->in['dot'] = floor($this->in['dot']);

            if (bccomp($this->data['finfo']['dot'], $this->in['dot']) == -1) {
                return array('status' => 0, 'info' => '积分不足!');
            }

            $d = $this->in['dot'] / $this->dot_scale;
            if (bccomp($d, $this->data['total']) == 1) {
                $d = $this->data['total'];
                $this->data['total'] = 0;
            }
            //积分可抵扣的金额
            $this->data['dot_total'] = $d;
            //实际所用的积分
            $this->data['dot'] = $d * $this->dot_scale;
            //优惠金额加
            $this->data['discount_total'] += $d;
            $this->data['total'] = $this->data['total'] - $d;
        }
        return $this->returnData();
    }

    public function sub() {
        if ($this->data['dot'] > 0) {
            if (bccomp($this->data['finfo']['dot'], $this->data['dot']) == -1) {
                return array('status' => 0, 'info' => '积分不足!!');
            }
            $this->userModel->handleUser('dot', $this->uinfo['id'], $this->data['dot'], 2, array(
                'ordernum' => $this->clear['ordernum'],
                'cate' => '2'
            ));
        }
        return $this->returnData();
    }

    public function returnData() {
        return [
            'status' => 1,
            'data' => $this->data,
            'clear_field' => ['dot', 'dot_total'],
            'desc' => [
                    [
                    'field' => 'dot',
                    'msg' => '本次所用积分'
                ],
                    [
                    'field' => 'dot_total',
                    'msg' => '本次所用积分可抵金额'
                ],
            ],
        ];
    }

}
