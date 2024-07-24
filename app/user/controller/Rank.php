<?php

namespace app\user\controller;



use app\user\model\UserRank;
use think\App;
use think\facade\Db;
use think\facade\View;


/**
 * 用户等级
 * @auto true
 * @auth true
 * @menu true
 */
class Rank extends Common {

    public function __construct(App $app)
    {
        parent::__construct($app);
        View::assign('title', '用户等级');
        $this->model = new UserRank();
    }
    /**
     * 用户等级列表
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
        $where = array();
        $count = Db::name('user_rank')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $data['list'] = Db::name('user_rank')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }
    /**
     * 添加用户等级
     * @auto true
     * @auth true
     * @menu true
     */
    public function item() {
        if ($this->request->isPost()) {
            $in = input();
            $in=array_filter($in);
            if ($in['id'])
                $r = $this->model->where('id',$in['id'])->save($in);
            else
                $r = $this->model->save($in);
            if ($r === 0 || $r) {
                $this->success(lang('s'));
            } else {
                $this->error(lang('e'));
            }
            die;
        }
        if (input('id')) {
            $info = $this->model->find(input('id'));
            View::assign('info', $info);
        }

        return $this->display();
    }
    /**
     * 删除用户等级
     * @auto true
     * @auth true
     * @menu false
     */
    public function del() {
        $id=input('id');
        if ($this->model->where('id',$id)->delete()) {
            $this->success(lang('s'));
        } else {
            $this->error(lang('e'));
        }
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $in = input();
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
        $this->model->where($where)->save([$in['field']=> $in['val']]);
        $this->model->clear($in['keyid']);
        $this->success(lang('s'));
    }


}