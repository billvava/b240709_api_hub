<?php

namespace app\comapi\logic;

use think\App;
use think\facade\Db;

class Help
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {
        $this->model = new \app\com\model\HelpItem();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function get_cate()
    {
        $HelpCate = new \app\com\model\HelpCate();
        $this->data['cate'] = $HelpCate->getAll(['status' => 1]);
        return ['status' => 1, 'data' => $this->data];
    }

     //分类列表
    public function getCateList()
    {
      $HelpCate = new \app\com\model\HelpCate();
      $array = ['fuwudd','shangchengdd','shouhoufw','qita'];
      $map = [];
      $map[] = ['token','in',$array];
      $list = $HelpCate -> where($map) -> select();
      $data = [];
      $data['list'] = $list;
      return ['status' => 1, 'data' => $data];
    }



    public function load_list()
    {
        $page = $this->in['page'] ?: 1;
        $where = [
            ['status', '=', 1],
        ];
        if ($this->in['cate_id']) {
            $where[] = ['cate_id', '=', $this->in['cate_id']];
        }
        if ($this->in['cate_token']) {
            $cate_id = Db::name('help_cate')->where([
                ['token', '=', $this->in['cate_token']]
            ])->cache(true)->value('id');
            $cate_id = $cate_id + 0;
            $where[] = ['cate_id', '=', $cate_id];
        }
        $this->data['list'] = $this->model->getList($where, $page);
        return ['status' => 1, 'data' => $this->data];
    }

    public function item()
    {
        $this->data['info'] = $this->model->getInfo($this->in['id']);
        return ['status' => 1, 'data' => $this->data];
    }

    public function message()
    {
//        if (isset($this->in['tel']) && strlen($this->in['tel']) != 11) {
//            return ['status' => 0, 'info' => '手机个数不正确'];
//        }
        if (!($this->in['content'])) {
            return ['status' => 0, 'info' => '请输入内容'];
        }
        $imgs = '';
        if ($this->in['imgs'] && is_string($this->in['imgs'])) {
            $this->in['imgs'] = array_filter(explode(',', $this->in['imgs']));
        }
        if (is_array($this->in['imgs'])) {
            $imgs = json_encode($this->in['imgs']);
        }
        $add = [
            'content' => $this->in['content'],
            'imgs' => $imgs,
            'time' => date('Y-m-d H:i:s'),
            'user_id' => $this->uinfo['id'] + 0,
            'tel' => $this->in['tel'].'',
            'title' => $this->in['title'] . '',
        ];
        $HelpMsg = new \app\com\model\HelpMsg();
        $HelpMsg->insert($add);
        return ['status' => 1, 'info' => lang('s')];
    }

}
