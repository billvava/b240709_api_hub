<?php

namespace app\admin\controller;



use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 菜单权限管理
 * @auto true
 * @auth true
 * @menu true
 */
class AdminNav extends Common
{


    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\AdminNav();
        View::assign('title', '后台菜单管理');
        $nav_type = lang('nav_type');
        View::assign('nav_type', $nav_type);

    }



    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set() {
        $this->check('set');
        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field']=>$this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 删除菜单
     * @auto true
     * @auth true
     * @menu false
     */
    public function del() {
        $r = $this->model->getContentRes($this->in['id']);

        $ids = array_reduce($r, function($v,$w){$v[]=$w["id"];return $v;});

        $ids[] = $this->in['id'];
        if ($ids) {
            $this->model->where('id','in', $ids)->delete();
        }
        $this->success(lang('s'));
    }

    /**
     * 添加菜单
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
        if ($this->request->isPost()) {
            $info = input();
            $info['url'] = input('url', '', 'trim');
            $info['param'] = input('param', '', 'trim');
            $info['target'] = $info['target'] ? $info['target'] : 'main';
            if ($info['id'])
                $r = $this->model->update($info);
            else
                $r = $this->model->insert($info);
            if ($r === 0 || $r) {
                $this->success(lang('s'));
            } else {
                $this->error(lang('e'));
            }
        }
        if ($this->in['id']) {
            $info = $this->model->find($this->in['id'])->toArray();
            View::assign('info', $info);
        }
        $plist = $this->model->get_all(false, 2);
        $plist = $this->model->recursionPinfo($plist, 0);
        View::assign('plist', $plist);
        return $this->display();
    }


    /**
     * 列表管理
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $plist = $this->model->get_all(false, 2);
        $plist = $this->model->recursionPinfo($plist, 0);
        View::assign('plist', $plist);
        return $this->display();
    }

    public function json() {
        $plist = $this->model->get_all(false, 2);
        $data = ['code' => 0, 'msg' => '成功', 'data' => $plist];
        return json($data);
        
    }

    /**
     * 复制菜单
     * @auto true
     * @auth true
     * @menu false
     */
    public function copy() {
        if ($this->in['id']) {
            $info = $this->model->find($this->in['id'])->toArray();
            unset($info['id']);
            $this->model->insert($info);
            $this->success(lang('s'));
        }
    }

    /**
     * 更新菜单
     * @auto true
     * @auth true
     * @menu false
     */
    public function up_all_node() {
        //删除不存在的
        $AdminNav = new \app\admin\model\AdminNav();
        $AdminNav->up_all_node();
        $this->success(lang('s'));
    }

}