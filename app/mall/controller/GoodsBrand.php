<?php

namespace app\mall\controller;




use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 商品品牌
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-barrage_fill
 */
class GoodsBrand extends Common {

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\GoodsBrand();
        $this->name = '商品品牌';
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
     * 商品品牌列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $in = input();
        $where = array();
        foreach ($in as $key => $value) {
            if ($value !== '' && $key != 'p') {
                $where['a.' . $key] = array('like', "%{$value}%");
            }
        }
        $count = $this->model->alias('a')->where($where)->count();
             tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        if ($this->pk) {
            $order = "{$this->pk} desc";
        }
        $data['list'] = $this->model
            ->alias('a')
            ->field('a.*')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order($order)
            ->select()->toArray();
        View::assign('data', $data);
        View::assign('title', '列表');
        return $this->display();
    }

    /**
     * 添加商品品牌
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
        $in = input();

        if (request()->isPost()) {
            $rule = $this->model->rules();
            $this->validate($this->in,$rule['rule']??[],$rule['message']??[]);
            if ($in[$this->pk]) {
                $where[] = [$this->pk,'=',$in[$this->pk]];
                $this->model->where($where)->save($in);
            } else {
                $this->model->insertGetId($in);
            }
            $this->success(lang('操作成功'), url('index'));
        }
        if ($in[$this->pk]) {
            $where[] = [$this->pk,'=',$in[$this->pk]];
            $info = $this->model->where($where)->find()->toArray();
            View::assign('info', $info);
        }

        View::assign('title', '编辑');
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
        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field']=> $this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 删除商品品牌
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $where[] = [$this->pk,'in',$in[$this->pk]];
        } else {
            $where[] = [$this->pk,'=',$in[$this->pk]];
        }
        $r = $this->model->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }

}
