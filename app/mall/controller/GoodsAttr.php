<?php

namespace app\mall\controller;




use app\mall\model\GoodsAttrField;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 商品属性组
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-computer_fill
 */
class GoodsAttr extends Common
{


    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\GoodsAttr();
        $this->name = '商品属性组';
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
     * 属性组列表
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
     * 添加属性组
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
        if (!$in[$this->pk]) {
            $where[] = [$this->pk,'=',$in[$this->pk]];
            $info = $this->model->where($where)->find();
            View::assign('info', $info);
        }
        return $this->display();
    }

    /**
     * 删除属性组
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

    //快速设置
    public function set() {
        $this->check('set');
        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field']=> $this->in['val']]);
        $this->success(lang('s'));
    }

    //子属性
    public function attr_manage() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        $GoodsAttrField = new GoodsAttrField();
        $data = $GoodsAttrField->where(array('attr_id' => $in['attr_id']))->select()->toArray();
        $info = $this->model->find($in[$this->pk]);
        View::assign('info', $info);
        View::assign('data', $data);
        View::assign('types', $GoodsAttrField->getType());
        View::assign('title', $info['name']);
        return $this->display();
    }

    public function set_attr() {
        $this->check('set');
        $GoodsAttrField = new GoodsAttrField();

        $attr_id = $GoodsAttrField->cache(true)->where(array('field_id' => $this->in['keyid']))->value('attr_id');
        $this->model->clear($attr_id);


        $GoodsAttrField->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field']=> $this->in['val']]);
        $this->success(lang('s'));
    }

    //子属性编辑
    public function attr_show() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        $GoodsAttrField = new GoodsAttrField();
        if (request()->isPost()) {
            if ($in['field_id']) {
                $GoodsAttrField->where(array('field_id' => $in['field_id']))->save($in);
            } else {
                $GoodsAttrField->insertGetId($in);
            }
            $this->model->clear($in[$this->pk]);
            $this->success(lang('s'), url('attr_manage', array('attr_id' => $in[$this->pk])));
        }
        $info = $this->model->find($in[$this->pk]);
        View::assign('info', $info);
        View::assign('types', $GoodsAttrField->getType());
        View::assign('title', $info['name']);
        return $this->display();
    }

    //子属性删除
    public function attr_del() {
        $in = input();
        if (!$in['field_id']) {
            $this->error('缺少主键参数');
        }
        $where = [];
        if (is_array($in['field_id'])) {
            $where[] = ['field_id','in',$in['field_id']];
        } else {
            $where[] = ['field_id','=',$in['field_id']];
        }
        $GoodsAttrField = new GoodsAttrField();
        $GoodsAttrField->where($where)->delete();
        $this->success(lang('s'));
    }



}