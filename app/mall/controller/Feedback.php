<?php

namespace app\mall\controller;




use app\mall\model\GoodsCategory;
use app\mall\model\GoodsExt;
use app\mall\logic\GoodsAdmin;
use think\App;
use think\facade\Db;
use think\facade\View;


class Feedback extends Common {

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\mall\model\Feedback();
        View::assign('feedback_status', lang('feedback_status'));

    }



    public function index() {
        $this->in['page_type'] = 'admin';
        $data = $this->model->get_data($this->in);

        View::assign('data', $data);
        return $this->display();
    }

    public function item() {
        if (app()->request->isPost()) {

            if ($this->in['status'] == 0) {
                $this->error('请选择状态');
            }
            $info = Db::name('mall_order_feedback')->where(array('id' => $this->in['id']))->find();
            Db::name('mall_order')->where(array('order_id' => $info['order_id']))->save(array('feedback_status' => $this->in['status']));

            $feedback_status = lang('feedback_status');
    
            if ($this->in['status'] != $info['status']) {
                $msg = $feedback_status[$this->in['status']];
                Db::name('mall_order_flog')->insertGetId(array(
                    'order_id' => $info['order_id'],
                    'time' => date('Y-m-d H:i:s'),
                    'msg' => $msg
                ));
            }
            $this->in['up_time'] = date('Y-m-d H:i:s');
            Db::name('mall_order_feedback')->where(array('id' => $this->in['id']))->save($this->in);
            (new \app\mall\model\Order())->add_log( $info['order_id'], array('cate' => 10, 'msg' => '售后处理'));
            $this->success(lang('s'));
        }
        $info = $this->model->where(array('id' => $this->in['id']))->find()->toArray();
        View::assign('info', $info);
        return $this->display();
    }

}