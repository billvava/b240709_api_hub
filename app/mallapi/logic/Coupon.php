<?php

namespace app\mallapi\logic;

use think\App;
use think\facade\Db;

class Coupon {

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct() {
        $this->model = new \app\mall\model\MallCoupon();
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    //会员中心我的优惠券
    public function my_list() {
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $where = [
                ['user_id', '=', $this->uinfo['id']]
        ];
        if ($this->in['flag'] == 'no_use') {
            $where[] = ['status', '=', 0];
        }
        if ($this->in['flag'] == 'used') {
            $where[] = ['status', '=', 1];
        }
        if ($this->in['flag'] == 'expire') {
            $where[] = ['end', '<', date('Y-m-d H:i:s')];
        }
        $this->data['list'] = $this->model->getList($where, $page);

        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //可用
    public function user_list() {
        $page = $this->in['page'] ? $this->in['page'] : 1;
        if ($this->in['flag'] == 1) {
            //可用
            $where = [
                    ['user_id', '=', $this->uinfo['id']],
                    ['status', '=', 0],
                    ['end', '>', date('Y-m-d H:i:s')],
            ];
            if ($this->in['goods_total'] > 0) {
                $where[] = ['base_money', '<=', $this->in['goods_total']];
            }
        } else {
            //不可用的（已经使用 or 已经过期）
             $where = function ($query) use($in) {
                    $w1[]=['user_id','=',$this->uinfo['id']];
                    $query->where($w1)->where(function ($query){
                        $w[]= ['status', '=', 1];
                        $w[]= ['end', '<', date('Y-m-d H:i:s')];
                        $query->whereOr($w);
                    });
                };
        }

        $this->data['list'] = $this->model->getList($where, $page);
        return array('status' => 1, 'data' => $this->data);
    }

    //领取公开的优惠券
    public function draw() {
        $mall_coupon_tpl = Db::name('mall_coupon_tpl');
        $info = $mall_coupon_tpl->where(array('id' => $this->in['id']))->find();
        if ($info['type'] == 2) {
            $userModel = D('Common/User');
            $get_coupon = $userModel->getExtField($this->uinfo['id'], 'get_coupon');
            if ($get_coupon == 1) {
                return array('status' => 0, 'info' => '您已经领取了');
            }
            $userModel->setExtField($this->uinfo['id'], 'get_coupon', 1);
        } else if ($info['type'] == 4) {
            $is = $this->model->where(array('user_id' => $this->uinfo['id'], 'tpl_id' => $info['id']))->value('id');
            if ($is) {
                return array('status' => 0, 'info' => '您已经领过了');
            }
        } else {
            return array('status' => 0, 'info' => '该券不存在');
        }
        $this->model->send_tpl($this->uinfo['id'], $info['id'], 1, 4);
        return array('status' => 1, 'info' => '领取成功', 'coupon_info' => $res['data']['coupon_info']);
    }

    //公开的优惠券列表
    public function check_draw() {
        $userModel = new app\common\model\User();
        //新人优惠券
        $get_coupon = $userModel->getExtField($this->uinfo['id'], 'get_coupon');
        $mall_coupon_tpl = Db::name('mall_coupon_tpl');
        if ($get_coupon == 0) {
            $this->data['coupon_info'] = $mall_coupon_tpl->where(array('type' => 2))->order("money desc")->field('name,money,id')->find();
        }
        if (!$this->data['coupon_info']) {
            $this->data['coupon_info'] = $mall_coupon_tpl->where(array('type' => 4))->field('name,money,id')->find();
            $is = $this->model->where(array('user_id' => $this->uinfo['id'], 'tpl_id' => $this->data['coupon_info']['id']))->value('id');
            if ($is) {
                $this->data['coupon_info'] = null;
            }
        }
        if ($this->data['coupon_info']) {
            $this->data['coupon_info']['msg'] = "亲，您有 [{$this->data['coupon_info']['name']}] {$this->data['coupon_info']['money']}元优惠券待领取~";
        }
        return array('status' => 1, 'data' => $this->data);
    }

    /**
     * 领取中心
     */
    public function my_coupon() {
        $where = function ($query) use($in) {

            $w1[]=['type','=',4];
           

            $query->where($w1)->where(function ($query){
                $w[]=['day', '>', 0];
                $w[]=['end', '>', date('Y-m-d H:i:s')];
                $query->whereOr($w);
            });
        };
        $MallCouponTpl = new \app\mall\model\MallCouponTpl();
        $this->data['coupon'] = $MallCouponTpl->getAll($where);
        $cs = $this->model->where(array('user_id' => $this->uinfo['id']))->column('tpl_id');
        foreach ($this->data['coupon'] as &$v) {
            $v['is_get'] = in_array($v['id'], $cs) ? true : false;
        }
        $this->data['list'] = $this->data['coupon'];
        return array('status' => 1, 'data' => $this->data);
    }

}
