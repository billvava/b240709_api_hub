<?php

namespace app\mall\controller;

use app\common\lib\Lib;
use app\common\lib\Plug;
use app\common\Lib\Util;
use think\App;
use think\facade\Db;
use think\facade\View;


class PtOrder extends Common {

    public $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\PtOrder();
        $this->name = '拼团订单';
        View::assign('pay_type', lang('pay_type'));
        View::assign('is_pay', lang('is_pay'));
        View::assign('is', lang('is'));
        View::assign('txt_class', lang('txt_class'));
        View::assign('order_is_send', lang('order_is_send'));

        View::assign('send_type', lang('send_type'));
        View::assign('feedback_status', lang('feedback_status'));
        View::assign('order_log_cate', lang('order_log_cate'));
        View::assign('order_log_type', lang('order_log_type'));
        View::assign('money_field', lang('money_field'));
        View::assign('xls_field', lang('xls_field'));
    }

    /**
    * 拼团订单
    * @auto true
    * @auth true
    * @menu true
    * @icon icon-scan
    */
    public function index() {
        $this->in['page_type'] = 'admin';
       
        $data = $this->model->get_data($this->in);
        View::assign('data', $data);
        return $this->display();
    }
    /**
    * 拼团订单详情
    * @auto true
    * @auth true
    * @menu false
    */
    public function item() {
        
        
        $ordernums = $this->model->where(array('p_ordernum' => $this->in['p_ordernum']))->column('ordernum');
        if (!$ordernums) {
            $this->success('不存在', '', -1);
        }
        $in['ordernums'] = $ordernums;
        $in['get_status'] = 1;
        $in['type'] = 2;
        $data = (new \app\mall\model\Order())->get_data($in);
        View::assign('data', $data);
        return $this->display();
    }

  

}
