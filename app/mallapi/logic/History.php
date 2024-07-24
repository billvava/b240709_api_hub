<?php

namespace app\mallapi\logic;

use think\App;
use think\facade\Db;

class History {

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;
    public $a_model;

    public function __construct() {
        $this->model = new \app\common\model\User();
        $this->a_model = new \app\mall\model\GoodsHistory();
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function index() {
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $this->data['list'] = $this->a_model->getUserList($this->uinfo['id'], $page);
        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    public function del() {
        $this->a_model->where(array('user_id' => $this->uinfo['id'], 'goods_id' => $this->in['goods_id']))->delete();
        return array('status' => 1, 'info' => lang('s'));
    }

}
