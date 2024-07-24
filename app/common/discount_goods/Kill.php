<?php

namespace app\common\discount_goods;

use think\App;
use think\facade\Db;

class Kill {

    //执行顺序，越小越在前面
    public $sort = 0;
    public $in;
    public $uinfo;
    public $data;
    public $model;
    public $is_can = 0;

    public function __construct() {
        $this->model = Db::name('ms_goods');
        //是否开启
        $this->is_can = 1;
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];

        $this->in['kill_id'] = $this->in['kill_id'] ?: $this->in['ms_is'];
    }

    public function get() {
        if ($this->is_can == 1 && $this->in['kill_id'] > 0 && $this->in['item_id'] > 0 && $this->data['type'] == 1) {
            $this->data['type'] = 2;
            //标注命中的活动
            $this->data['discount_goods_class'] = __CLASS__;
            $where = [
                    ['id', '=', $this->in['kill_id']],
                    ['status', '=', 1],
                    ['end', '>', date('Y-m-d H:i:s')]
            ];
            $minfo = $this->model->where($where)->find();
            if (!$minfo) {
                return array('status' => 0, 'info' => '秒杀活动已结束！');
            }
            if ($minfo['num'] <= 0) {
                return array('status' => 0, 'info' => '秒杀名额已没有！');
            }
            $items = json_decode($minfo['items'], true);
            if ($items[$this->in['item_id']]) {
                $this->data['unit_price'] = $items[$this->in['item_id']] + 0;
            }
        }
        return $this->returnData();
    }

    public function sub() {
        if ($this->is_can == 1 && $this->in['kill_id'] > 0 && $this->in['item_id'] > 0) {
            $where = [
                    ['id', '=', $this->in['kill_id']],
            ];
            $this->model->where($where)->update([
                'num' => Db::raw('num-1')
            ]);
        }
        return $this->returnData();
    }

    public function returnData() {
        return [
            'status' => 1,
            'data' => $this->data,
            'clear_field' => [],
            'desc' => [
            ],
        ];
    }

}
