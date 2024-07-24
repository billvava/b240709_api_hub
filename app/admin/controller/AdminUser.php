<?php

namespace app\admin\controller;


use app\admin\model\AdminNav;
use app\admin\model\AdminNode;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 管理员管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-people_fill
 * */
class AdminUser extends Common
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\AdminUser();
        View::assign('title', '后台管理员');
        View::assign('admin_status', lang('admin_status'));
    }


    /**
     * 列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        View::assign('data', $this->model->getAll());
        return $this->display();
    }

    /**
     * 编辑添加
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
        $in = $this->in;
        if ($this->request->isPost()) {
            $this->check('AdminUser');
            if (!$in['pwd']) {
                unset($in['pwd']);
            } else {
                $in['pwd'] = xf_md5($in['pwd']);
            }

            if(!$in['id']){
                $r = $this->model->save($in);
            }else{
                $r = $this->model->where('id',$in['id'])->save($in);
            }


            if ($r === 0 || $r) {
                $this->success(lang('s'), url('index'));
            } else {
                $this->error(lang('e'), url('index'));
            }
        }
        $roles = Db::name('admin_role')->select()->toArray();
        View::assign('roles', $roles);
        if ($in['id']) {
            $info = $this->model->getInfo($in['id']);
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }
        return $this->display();
    }

    /**
     * 删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function del() {
        $info = $this->model->getInfo($this->in['id']);
        $info || $this->error(lang('e'));
        if ($info['is_superadmin'] == 1) {
            $this->error('该用户不能被删除');
        }

        $this->model->find($this->in['id'])->delete();
        $this->success(lang('s'));
    }

    /**
     * 登陆日志
     * @auto true
     * @auth true
     * @menu false
     */
    public function log() {
        $data = $this->model->getLgoData($this->in);
        View::assign('data', $data);
        return $this->display();
    }
}