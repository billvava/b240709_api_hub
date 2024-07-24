<?php

namespace app\mallapi\logic;

use think\facade\Db;

class Seckill
{

    public $uinfo;
    public $in;
    public $data;
    public $request;
    public $model;
    public $goodsModel;

    public function __construct()
    {
        $this->model = new \app\common\model\User();
        $this->goodsModel = new \app\mall\model\Goods();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    //秒杀列表
    public function index()
    {
        $MsCate = new \app\mall\model\MsCate();
        $MsGoods = new \app\mall\model\MsGoods();
        $date = date('Y-m-d H:i:s');
        $where = [
            ['status', '=', 1],
            ['end', '>', $date],
            ['start', '<', $date],
        ];
        $page = $this->in['page'] ? intval($this->in['page']) : 1;
        $this->data['list'] = $MsGoods->getList($where, $page);
        $this->data['second'] = 0;
        if ($this->data['list']) {
            $this->data['second'] = $this->data['list'][0]['second'];
        }
        return array('status' => 1, 'data' => $this->data);
    }


}
