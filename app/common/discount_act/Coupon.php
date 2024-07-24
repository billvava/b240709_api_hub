<?php

namespace app\common\discount_act;

use think\App;
use think\facade\Db;

class Coupon {

    //执行顺序，越小越在前面
    public $sort = 0;
    public $in;
    public $uinfo;
    public $data;
    public $model;
    public $is_can = 0;
    public $dot_scale;

    public function __construct() {
        $this->model = Db::name('mall_coupon');
        //是否开启
        $this->is_can = C('mall_can_coupon');
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function get() {
        $this->data['coupon_count'] = 0;
        $this->data['coupon_total'] = 0;
        $this->data['coupon_info'] = null;
        if ($this->is_can == 1 && $this->data['total'] > 0 && $this->in['use_coupon'] == 1  && $this->data['type'] == 1) {
            $where = [
                    ['user_id', '=', $this->uinfo['id']],
                    ['end', '>', date('Y-m-d H:i:s')],
                    ['status', '=', 0],
                    ['base_money', '<=', $this->data['goods_total']],
            ];
            $count = Db::name('mall_coupon')->where($where)->count();
            //可用的数量
            $this->data['coupon_count'] = $count;
            $this->data['coupon_total'] = 0;
            if ($this->in['coupon_id'] > 0) {
                //指定优惠券
                $where[] = ['id', '=', $this->in['coupon_id']];
                $this->data['coupon_info'] =Db::name('mall_coupon')->where($where)->order("money desc")->find();
            } else {
                //获取默认优惠券
                $this->data['coupon_info'] = Db::name('mall_coupon')->where([
                                ['user_id', '=', $this->uinfo['id']],
                                ['end', '>', date('Y-m-d H:i:s')],
                                ['status', '=', 0],
                                ['base_money', '<=', $this->data['goods_total']],
                        ])->order("money desc")->find();
            }
            if ($this->data['coupon_info']) {
                $this->data['coupon_total'] = $this->data['coupon_info']['money'];
            }
            $this->data['discount_total'] += $this->data['coupon_total'];
            //如果优惠券金额超过实付
            if (bccomp($this->data['coupon_total'], $this->data['total']) == 1) {
                $this->data['coupon_total'] = $this->data['total'];
            }
            $this->data['total'] = $this->data['total'] - $this->data['coupon_total'];

        }
        return $this->returnData();
    }

    public function sub() {
        if ($this->data['coupon_info']) {
            $this->model->where(array('id' => $this->data['coupon_info']['id']))->save(array('status' => 1));
        }
        return $this->returnData();
    }

    public function returnData() {
        return [
            'status' => 1,
            'data' => $this->data,
            'clear_field' => ['coupon_id', 'coupon_total'],
            'desc' => [
                    [
                    'field' => 'coupon_count',
                    'msg' => '本单可用优惠券数量'
                ],
                    [
                    'field' => 'coupon_total',
                    'msg' => '当前优惠券抵扣的金额'
                ],
                    [
                    'field' => 'coupon_info',
                    'msg' => '所用优惠券的具体信息'
                ],
            ],
        ];
    }

}
