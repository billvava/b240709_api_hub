<?php
namespace app\admin\controller;

use app\admin\model\AdminUser;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 地区管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-undo
 */
class SystemAreas extends Common {

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\SystemAreas();
        $this->name = '地区';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array(app()->request->action(), array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }

    }

    /**
     * 配置列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $in = input();
        $where = array();

        unset($in['p']);
        $search_field = lang('search_field');
        foreach ($in as $key => $value) {
            if ($value !== '') {
                if (in_array($key, $search_field)) {
                    $where[] = array('a.' . $key,'like', "%{$value}%");
                } else {
                    $where[] = ['a.' . $key,'=',$value];
                }
            }
        }


        $count = Db::name($this->model->dbName())->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        if ($this->pk) {
            $order = "level asc,sort asc,{$this->pk} asc";
        }
        $data['list'] = Db::name($this->model->dbName())
                ->alias('a')
                ->field('a.*')
                ->where($where)
                ->limit($Page->firstRow , $Page->listRows)
                ->order($order)
                ->select()->toArray();
        View::assign('title', '列表');
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * xls
     * @auto true
     * @auth true
     * @menu false
     */
    public function xls() {
        $in = input();
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        foreach ($in as $key => $value) {
            if ($value !== '' && in_array($key, $fields)) {
                $where['a.' . $key] = array('like', "%{$value}%");
            }
        }
        $data = Db::name($this->model->dbName())
                ->alias('a')
                ->field($fields)
                ->where($where)
                ->limit(500)
                ->select()->toArray();
        $all_lan = $this->model->getLan();
        foreach ($data as &$v) {
            foreach ($all_lan as $ak => $av) {
                if (isset($v[$ak])) {
                    $v[$ak] = $all_lan[$ak][$v[$ak]];
                }
            }
            if (isset($v['user_id'])) {
                $v['user_id'] = getname($v['user_id']);
            }
        }
        (new \app\common\lib\Util())->xls($this->name, array_values($as), $data);
    }

    /**
     * 添加配置
     * @auto true
     * @auth true
     * @menu false
     */
    public function add() {
        $in = input();
        if ($in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }

    /**
     * 编辑配置
     * @auto true
     * @auth true
     * @menu false
     */
    public function edit() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }

    private function item() {
        $in = input();

        if (app()->request->isPost()) {
            $rule = $this->model->rules();
            $this->validate($this->in,$rule['rule'],$rule['message']?$rule['message']:[]);

            if ($in[$this->pk]) {
                $r = $this->model->update($in);
                $r = $in[$this->pk];
            } else {
                $r = $this->model->save($in);
            }
            if ($r === 0 || $r) {
                $this->success(lang('s'), url('index'));
            } else {
                $this->error(lang('e'), url('index'));
            }
        }

        if ($in[$this->pk]) {
            $where[$this->pk] = $in[$this->pk];
            $info = Db::name($this->model->dbName())->where($where)->find();
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }
        View::assign('title', '编辑');
        return $this->display();
    }

    /**
     * 删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $in = $this->request->param();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $where[$this->pk] = array('in', $in[$this->pk]);
            foreach ($in[$this->pk] as $v) {
                $this->model->clear($v);
            }
        } else {
            $where[$this->pk] = $in[$this->pk];
            $this->model->clear($in[$this->pk]);
        }
        $r = Db::name($this->model->dbName())->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }




    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
      public function set_val() {
        $in = $this->request->param();
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
//        $this->model->clear( $in['keyid']);
       $this->model->where($where)->update([$in['field']=> $in['val']]);
        $this->success(lang('s'));
    }


}
