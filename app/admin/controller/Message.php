<?php
namespace app\admin\controller;

use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 留言管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-document_fill
 */
class Message extends Common {
    /**
     * 列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $where = array();
        $in = $this->in;
        $this->model = Db::name('system_message');
        //用户名查询
        if ($in['name']) {
            $where[]=['a.name','like', "%{$in['name']}%"];
        }
        if ($in['start_datetime'] && $in['end_datetime']) {
            $where[]=['a.time','between', [strtotime($in['start_datetime']), strtotime($in['end_datetime'])]];
        } elseif ($in['start_datetime']) {
            $where[]=['a.time','>', strtotime($in['start_datetime'])];
        } elseif ($in['end_datetime']) {
            $where[]=['a.time','<', strtotime($in['end_datetime'])];
        }
        if($in['type']){
            $where[]=['a.type','=', $in['type']];
        }
        $count = $this->model->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $this->show = $Page->show();
        $this->list = $this->model->alias('a')
            ->where($where)
            ->order('id desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select()->toArray();
        return $this->display();
    }
    /**
     * 修改状态
     * @auto true
     * @auth true
     * @menu false
     */
    public function status() {
        $in = $this->in;
        $where = array();
        $where['id'] = $in['id'];
        $this->model->where($where)->update([$in['status']=> $in['status']]);
        $this->success(lang('s'));
    }
}