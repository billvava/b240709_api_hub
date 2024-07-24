<?php

namespace app\mall\controller;

use app\common\lib\Lib;
use app\common\lib\Plug;
use app\common\Lib\Util;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 拼团商品管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-prompt_fill
 */
class PtGoods extends Common {

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\PtGoods();
        $this->name = '拼团商品管理';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        View::assign('goods_status', lang('goods_status'));
        if (!$this->pk && in_array(request()->action(), array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }
    }

    /**
     * 拼团商品管理
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $where = array();

        $data = $this->model->get_data($where);
//        if (!$gs) {
//            $this->success('暂无商品', '', -1);
//        }
//        $gs_where = array('page_type' => 'admin', 'goods_ids' => $gs);
//        $data = D('Mall/goods')->getData($gs_where);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 添加拼团商品
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
//        if (!$this->in['id'] && $this->adminInfo['role_id'] != 2) {
//            $this->error('店铺管理员才能操作');
//        }
        if (request()->isPost()) {
            if (!$this->in['goods_id']) {
                $this->error('请选择商品');
            }
            $this->in['min_price'] = min(array_filter($this->in['goods_id_items']));
            $this->in['items'] = json_encode(array_filter($this->in['goods_id_items']));
            if ($this->in['min_price'] < 0.01) {
                $this->error('价格最低0.01');
            }
            if (!$this->in['id']) {
                $this->in['id'] = $this->model->insertGetId($this->in);
            } else {
                $this->model->where(array('id' => $this->in['id']))->save($this->in);
            }
            $this->model->clear($this->in['id']);
            $this->success(lang('s'), url('index'));
        }
        if ($this->in['id']) {
            $info = $this->model->getInfo($this->in['id']);
            View::assign('info', $info);
        }
        return $this->display();
    }

    /**
     * 快速设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set() {
        $this->check('set');

        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save(array($this->in['field']=>$this->in['val']));
        $this->success(lang('s'));
    }

    /**
     * 删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function del() {

        if ($this->in['id']) {
            $this->model->where(array('id' => $this->in['id']))->delete();
            $this->model->clear($this->in['id']);
            $this->success(lang('s'));
        }
    }

}
