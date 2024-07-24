<?php


namespace app\#module#\logic;

use think\facade\Db;

class Index {

    public $uinfo;
    public $in;
    public $data;
    public $request;

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function index() {
        $this->data['name'] = '你好';
        return array('status' => 1, 'data' => $this->data);
    }

}
