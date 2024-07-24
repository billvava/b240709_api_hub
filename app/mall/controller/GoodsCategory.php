<?php

namespace app\mall\controller;




use think\App;
use think\facade\Db;
use think\facade\View;
/**
 * 商品分类
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-stealth_fill
 */
class GoodsCategory extends Common {


    public $model;
    public $name;
    public $pk;

    public function __construct(App $app) {
        parent::__construct($app);
        $this->model = new \app\mall\model\GoodsCategory();
        $this->name = '商品类目';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array($this->request->action(), array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }
    }

    /**
     * 分类列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $in = input();
        $data = $this->model->getTree();
        View::assign('data', $data);
        View::assign('title', '列表');
        return $this->display();
    }

    /**
     * 添加分类
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
        $in = input();
        if ($this->request->isPost()) {

            $rule = $this->model->rules();

            if ($in[$this->pk]) {
                $where[] = [$this->pk,'=',$in[$this->pk]];
                $this->model->where($where)->save($in);
            } else {
                $this->model->insertGetId($in);
            }
            $this->model->clear();
            $this->success(lang('操作成功'), url('index'));
        }
        if ($in[$this->pk]) {
            $where[] = [$this->pk,'=',$in[$this->pk]];
            $info = $this->model->where($where)->find()->toArray();
            View::assign('info', $info);
        }
        $data = $this->model->getTree();
        View::assign('data', $data);
        View::assign('title', '添加');
        return $this->display();
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $this->check('set');
        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field']=>$this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 转移商品
     * @auto true
     * @auth true
     * @menu false
     */
    public function move_goods() {
        if ($this->request->isPost()) {
            if (!$this->in['old_id'] || !$this->in['new_id']) {
                $this->error('请选择分类');
            }
            $this->model->move_goods($this->in['old_id'], $this->in['new_id']);
            $this->success(lang('s'));
        }
        return $this->display();
    }

    /**
     * 删除分类
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        $r = $this->model->deleteRela($in[$this->pk]);
        if ($r) {
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }

}
