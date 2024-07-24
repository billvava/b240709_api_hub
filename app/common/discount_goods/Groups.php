<?php

namespace app\common\discount_goods;

use think\App;
use think\facade\Db;

class Groups {

    //执行顺序，越小越在前面
    public $sort = 0;
    public $in;
    public $uinfo;
    public $data;
    public $model;
    public $pt_order;
    public $is_can = 0;
    public $info;
    public $close_pt;

    public function __construct() {
        $this->model = Db::name('pt_goods');
        $this->pt_order = 'pt_order';

        //是否开启
        $this->is_can = 1;

        //拼团关闭时间（天）
        $this->close_pt = C('close_pt');
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
        $this->in['groups_id'] = $this->in['groups_id'] ?: $this->in['pt_id'];
    }

    public function get() {
        if ($this->is_can == 1 && $this->in['groups_id'] > 0 && $this->in['item_id'] > 0 && $this->data['type'] == 1) {
            $this->data['type'] = 3;
            $this->data['pt_id'] = $this->in['groups_id'];
            //请标注命中的活动
            $this->data['discount_goods_class'] = __CLASS__;
            $where = [
                    ['id', '=', $this->in['pt_id']],
                    ['status', '=', 1],
                    ['end', '>', date('Y-m-d H:i:s')]
            ];
            $info = $this->model->where($where)->find();
            if (!$info) {
                return array('status' => 0, 'info' => '拼团活动已结束！');
            }
            $this->info = $info;
            $items = json_decode($info['items'], true);
            if ($items[$this->in['item_id']]) {
                $this->data['unit_price'] = $items[$this->in['item_id']] + 0;
            }


            if ($this->info['num'] <= 0) {
                return array('status' => 0, 'info' => '拼团该名额已满了');
            }
            $this->data['pt_num'] = $this->info['pt_num'];
        }
        return $this->returnData();
    }

    public function sub() {
        if ($this->is_can == 1 && $this->in['groups_id'] > 0 && $this->in['item_id'] > 0) {

            $this->data['p_ordernum'] = $this->in['p_ordernum'] ? $this->in['p_ordernum'] : $this->data['ordernum'];
            if ($this->data['p_ordernum']) {
                $pt_porder = Db::name($this->pt_order)->where(
                                [
                                        ['p_ordernum', '=', $this->data['p_ordernum']],
                                        ['type', '=', 1],
                                ]
                        )->find();
                if ($pt_porder && $pt_porder['status'] != 0) {
                    return array('status' => 0, 'info' => '已经成团，请加入其它团');
                }
            }

            $where = [
                    ['id', '=', $this->in['groups_id']],
            ];
            $this->model->where($where)->update([
                'num' => Db::raw('num-1')
            ]);
        }
        return $this->returnData();
    }

    //支付成功后
    public function pay_success() {

        $order_info = $this->data['order_info'];
        $M_Order = new \app\mall\model\Order();
        if (is_numeric($order_info)) {
            $order_info = $M_Order->removeOption()->where([
                            ['order_id', '=', $order_info]
                    ])->find();
        }
        if (!$order_info) {
            return ['status' => 1];
        }
        if ($order_info['type'] == 3) {
            $close_pt = C('close_pt');
            //减少数量
            $goods_info = Db::name('mall_order_goods')->where(array('order_id' => $order_info['order_id']))->field('goods_id,name')->find();
            $pt_add = array(
                'ordernum' => $order_info['ordernum'],
                'user_id' => $order_info['user_id'],
                'time' => date('Y-m-d H:i:s'),
                'type' => 2,
                'p_ordernum' => $order_info['p_ordernum'],
                'status' => 0,
                'need_num' => $order_info['pt_num'],
                'end_time' => date('Y-m-d H:i:s', strtotime("+{$close_pt} day")),
                'goods_id' => $goods_info['goods_id'],
                'pt_id' => 1,
                'goods_name' => $goods_info['name'],
            );

            //开团单号跟订单号一致就是开团
            if ($order_info['p_ordernum'] == $order_info['ordernum'] || !$order_info['p_ordernum']) {
                $pt_add['type'] = 1;
                $M_Order->removeOption()->where(array('order_id' => $order_info['order_id']))->save(['p_ordernum' => $order_info['ordernum']]);
            }
            Db::name($this->pt_order)->save($pt_add);
            if ($pt_add['type'] == 1) {
                $need_count = $order_info['pt_num'];
            } else {
                $need_count = $M_Order->removeOption()->where(array('ordernum' => $order_info['p_ordernum']))->value('pt_num');
            }
            $count = Db::name($this->pt_order)->where(array('p_ordernum' => $order_info['p_ordernum'], 'status' => 0))->count();
            $save = array('num' => $count);
            if ($count >= $need_count) {
                $save['status'] = 2;
            }

            Db::name($this->pt_order)->where(
                    [
                            ['p_ordernum', '=', $order_info['p_ordernum']],
                            ['status', '<>', 3],
                    ]
            )->save($save);
        }

        return $this->returnData();

    }

    public function returnData() {
        return [
            'status' => 1,
            'data' => $this->data,
            'clear_field' => ['pt_num', 'p_ordernum', 'pt_id'],
            'desc' => [
                    [
                    'field' => 'pt_num',
                    'msg' => '拼团成功所需数量'
                ],
                    [
                    'field' => 'pt_id',
                    'msg' => '拼团ID'
                ],
                    [
                    'field' => 'p_ordernum',
                    'msg' => '拼团的单号'
                ],
            ],
        ];
    }

}
