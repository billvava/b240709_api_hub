<?php

namespace app\admin\controller;


use app\admin\model\AdminNav;
use app\admin\model\AdminNode;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 管理员角色管理
 * @auto true
 * @auth true
 * @menu true
 */
class AdminRole extends Common
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\AdminRole();
        View::assign('title', '管理员角色管理');

    }


    public function set()
    {
        $this->check('set');
        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field'] => $this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 添加
     * @auto true
     * @auth true
     * @menu false
     */
    public function item()
    {
        if (request()->isPost()) {
            if ($this->in['role_id']) {
                $this->model->update($this->in);
            } else {
                $this->model->save($this->in);
            }
            $this->success(lang('s'), url('index'));
        }
        return $this->display('item');
    }

    /**
     * 列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index()
    {
        $data = $this->model->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function del()
    {

        $this->in['role_id'] || $this->error(lang('e'));
        $this->model->where(['role_id'=>$this->in['role_id']])->delete();
        $this->success(lang('s'), url('index'));
    }


    public function auth()
    {
        $this->in['role_id'] || $this->error(lang('e'));
        if (request()->isPost()) {
            $Access = Db::name('admin_role_node');
            $_node = Db::name('admin_node');
            $Access->where(array('role_id' => $this->in['role_id']))->delete();
            $temp = array();
            $i = 0;
            foreach ($this->in['node_id'] as $value) {
                $temp[$i] = array(
                    'role_id' => $this->in['role_id'],
                    'node_id' => $value,
                    'level' => $_node->where('id', $value)->value('level'),
                );
                $i++;
            }
            if ($temp) {
                $Access->insertAll($temp);
            }
            $this->success(lang('s'), url('index'));
        }
        $AdminNode = new AdminNode();
        $alls = $AdminNode->getAll();
        $oneNode = $this->model->getOneNode($this->in['role_id']);
        $info = $this->model->find($this->in[$this->model->getPk()]);
        View::assign('alls', $alls);
        View::assign('oneNode', $oneNode);
        View::assign('info', $info);
        return $this->display();
    }

    /**
     * 菜单设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function nav()
    {
        $this->in['role_id'] || $this->error(lang('e'));
        if (request()->isPost()) {
            $AdminRoleNav = Db::name('AdminRoleNav');
            $AdminRoleNav->where(array('role_id' => $this->in['role_id']))->delete();
            $temp = array();
            $i = 0;
            foreach ($this->in['nodes'] as $v) {
                $temp[$i] = array(
                    'role_id' => $this->in['role_id'],
                    'nav_id' => $v,
                );
                $i++;
            }
            if ($temp) {
                $AdminRoleNav->insertAll($temp);
            }
            $this->success(lang('s'), url('index'));
        }
        return $this->display();
    }


    public function apply() {
        $where = array();
        $nav = $this->model->getOneNav($this->adminInfo['role_id']);
        if (!$this->is_dev) {
            if (!$nav) {
                echo '{}';
                die;
            }
            $where[] = array('id','in', $nav);
        }
        $check_nav = $this->model->getOneNav($this->in['role_id']);
        $data = (new AdminNav())->getZtree(0, $check_nav, $where);
        return json($data);

//        $data = array(
//            'code' => 1,
//            'info' => '成功',
//            'data' => array(
//                array(
//                    'node' => 'admin',
//                    'title' => 'admin',
//                    'pnide' => '',
//                    "checked" => false,
//                    "_sub_" => array(
//                        array(
//                            'node' => 'admin1',
//                            'title' => 'admin1',
//                            'pnide' => '',
//                            "checked" => false,
//                            "_sub_" => array(
//                                array(
//                                    'node' => 'admin2',
//                                    'title' => 'admin2',
//                                    'pnide' => '',
//                                    "checked" => false,
//                                ),
//                                array(
//                                    'node' => '234',
//                                    'title' => '4234',
//                                    'pnide' => '',
//                                    "checked" => false,
//                                ),
//                                array(
//                                    'node' => '3423',
//                                    'title' => '2234',
//                                    'pnide' => '',
//                                    "checked" => false,
//                                ),
//                            )
//                        )
//                    )
//                )
//            ),
//        );
    }


}