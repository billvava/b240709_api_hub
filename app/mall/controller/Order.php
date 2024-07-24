<?php

namespace app\mall\controller;

use app\common\lib\Lib;
use app\common\lib\Plug;
use app\common\Lib\Util;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 订单管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-createtask_fill
 */
class Order extends Common {

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app) {
        parent::__construct($app);
        $this->model = new \app\mall\model\Order();
        $this->pk = $this->model->get_pk();
        View::assign('title', '订单管理');
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);


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

        View::assign('order_type', lang('order_type'));

        View::assign('order_status', lang('order_status'));
        View::assign('order_status_field', lang('order_status_field'));
    }

    /**
     * 订单列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
//        $info =   Db::name('mall_order')->find(24);
//        (new \app\mall\logic\Order())->pay_success($info);
        $this->in['page_type'] = 'admin';
        $this->in['get_status'] = 1;
        $this->in['type'] = ['in',[1,2,3,4,6]];

        $this->in['is_a_del'] = $this->in['is_a_del'] == '' ? 0 : $this->in['is_a_del'];
        $data = $this->model->get_data($this->in);
//        p($data);
        $census = $this->model->census();
        View::assign('census', $census);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 多选设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function mul_set() {
        if (!$this->in[$this->pk]) {
            $this->error('请先选择');
        }

        $this->model->where(array($this->pk => array('in', $this->in[$this->pk])))->save([$this->in['field'] => $this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $in = input();
        $this->check('set');
        $where[$in['key']] = $in['keyid'];
        $this->model->where($where)->save([$in['field'] => $in['val']]);
        $this->model->add_log($in['keyid'], array('msg' => "{$in['field']}：{$in['val']}", 'status' => 0, 'cate' => 10));
        $this->success(lang('s'));
    }

    /**
     * 修改金额
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_total() {
        file_put_contents(time() . '.txt', 1);
        $res = $this->model->up_total($this->in['order_id'], $this->in['total']);
        if ($res['status'] == 1) {
            $this->success($res['info']);
        } else {
            $this->error($res['info']);
        }
    }

    /**
     * 软删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function del() {
        $res = $this->model->del($this->in['order_id'], 'admin');
        if ($res['status'] == 1) {
            $this->success($res['info']);
        } else {
            $this->error($res['info']);
        }
    }

    /**
     * 永久删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function forever_del() {
        foreach ($this->in['order_id'] as $v) {
            $res = $this->model->forever_del($v, array('type' => 'admin'));
            if ($res['status'] == 0) {
                $this->error($res['info']);
            }
        }
        $this->success($res['info']);
    }

    /**
     * 退款
     * @auto true
     * @auth true
     * @menu false
     */
    public function refund() {
        $res = $this->model->refund($this->in['order_id'], $this->in['refund_money']);
        if ($res['status'] == 1) {
            $this->success($res['info']);
        } else {
            $this->error($res['info']);
        }
    }

    /**
     * 发货
     * @auto true
     * @auth true
     * @menu false
     */
    public function send() {
        if ($this->in['order_id']) {
            $res = $this->model->send($this->in['order_id'], $this->in);
        } else if (is_array($this->in['send_type'])) {
            foreach ($this->in['send_type'] as $k => $v) {
                $res = $this->model->send($k, array('express_key' => $this->in['express_key'][$k], 'express_code' => $this->in['express_code'][$k]));
                if ($res['status'] == 0) {
                    $this->error($res['info']);
                }
            }
        }

        if ($res['status'] == 1) {
            $this->success($res['info']);
        } else {
            $this->error($res['info']);
        }
    }

    /**
     * 物流信息
     * @auto true
     * @auth true
     * @menu false
     */
    public function send_item() {
        $SendInfo = $this->model->getSendInfo($this->in['order_id']);
        View::assign('sendInfo', $SendInfo);
        return $this->display();
    }

    /**
     * 设置支付
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_pay() {
        $res = $this->model->set_pay($this->in['order_id'], $this->in['pay_type']);
        if ($res['status'] == 1) {
            $this->success($res['info']);
        } else {
            $this->error($res['info']);
        }
    }

    /**
     * 关闭订单
     * @auto true
     * @auth true
     * @menu false
     */
    public function close() {
        $arr = array();
        if (is_array($this->in['order_id'])) {
            $arr = $this->in['order_id'];
        } else if ($this->in['order_id']) {
            $arr = array($this->in['order_id']);
        }

        foreach ($arr as $order_id) {
            $res = $this->model->close($order_id, array('type' => 'admin'));
            if ($res['status'] == 0) {
                $this->error($res['info']);
            }
        }
        $this->success($res['info']);
    }

    /**
     * 完成订单
     * @auto true
     * @auth true
     * @menu false
     */
    public function finish() {
        $arr = array();
        if (is_array($this->in['order_id'])) {
            $arr = $this->in['order_id'];
        } else if ($this->in['order_id']) {
            $arr = array($this->in['order_id']);
        }
        foreach ($arr as $order_id) {
            $res = $this->model->finish($order_id, array('type' => 'admin'));
            if ($res['status'] == 0) {
                $this->error($res['info']);
            }
        }
        $this->success($res['info']);
    }

    public function feedback() {
        if ($this->request->isPost()) {
            $this->model->where(array('order_id' => $this->in['order_id']))->save(['feedback_status' => $this->in['feedback_status']]);
            Db::name('mall_order_feedback')->where(array('id' => $this->in['id']))->save($this->in);
            $this->model->add_log($this->in['order_id'], array('cate' => 10, 'msg' => '售后处理'));
            $this->success(lang('s'));
        }
        $orderInfo = $this->model->getInfo($this->in['order_id']);
        $feedback = $this->model->get_feedback($this->in['order_id']);
        View::assign('feedback', $feedback);
        View::assign('orderInfo', $orderInfo);
        return $this->display();
    }

    public function up_status() {
        $bindtap = $this->in['bindtap'];
        $this->$bindtap();
    }

    /**
     * 设置地址
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_address() {
        if ($this->request->isPost()) {
            $this->model->where(array('order_id' => $this->in['order_id']))->save($this->in);
            $this->success(lang('s'));
        }
        $orderInfo = $this->model->getInfo($this->in['order_id']);
        View::assign('orderInfo', $orderInfo);
        return $this->display();
    }

    /**
     * 订单日志
     * @auto true
     * @auth true
     * @menu false
     */
    public function log() {
        $data = $this->model->get_log($this->in['order_id']);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 导出订单
     * @auto true
     * @auth true
     * @menu false
     */
    public function xls() {

        if (!$this->in['xls_field']) {
            $this->error('没有导出字段', '', -1);
        }
//        $Plug = new \app\common\model\Plug();

        $xls_field = implode(',', $this->in['xls_field']);
        Db::name('system_config')->where("`field`='order_xls_field'")->update(['val' => $xls_field]);
        $this->in['page_num'] = 5000;
        $this->in['field'] = $this->in['xls_field'];
        $this->in['xls'] = 1;
		
//		$this->in['pay_status'] = 1;
//		$this->in['refund_status'] = 0;
		
        $data = $this->model->get_data($this->in);

        if (!$data['list']) {
            $this->error('暂无数据', '', -1);
        }
        $titles = array();
        $xls_field = lang('xls_field');
        $temp = [];

        foreach ($data['list'] as $v) {
            $i = [];
            $v['address'] = $v['province'].$v['city'] .$v['country'] .$v['address'];
            foreach ($v as $kk => $vv) {

                if (in_array($kk,  $this->in['xls_field'])) {
                    $i[$kk] = $vv;

                }
            }

            if(is_array($v['goods_list']) && $v['goods_list'] ){
                //多个商品拆分成多行
                foreach ($v['goods_list'] as $ik => $iv) {
                    if ($ik == 0) {
                        $i['goods_list'] = $iv['name'] . ' | ' . $iv['spec_str'];
                        $i['goods_num'] = $iv['num'];
                        $temp[] = $i;
                    } else {
                        $j = $i;
                        foreach ($j as $jk => $jv) {
                            $j[$jk] = "";
                        }
                        $j['goods_list'] = $iv['name'] . ' | ' . $iv['spec_str'];
                        $j['goods_num'] = $iv['num'];
                        $temp[] = $j;
                    }
                }
            }

           
        }
        foreach ($xls_field as $v) {
            if (in_array($v['field'], $this->in['xls_field'])) {
                $titles[] = $v['name'];
            }
        }
		$titles[] = "商品数量";

        (new Util())->xls("订单数据" . date('Y-m-d'), $titles, $temp);
    }

    /**
     * 网页打印
     * @auto true
     * @auth true
     * @menu false
     */
    public function print_info() {
        $data = $this->model->get_data($this->in);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 飞鹅打印
     * @auto true
     * @auth true
     * @menu false
     */
    public function feie_print() {
        $orderInfo = $this->model->getInfo($this->in['order_id']);
        $oderLogic = new \app\mall\logic\Order();
        $res = $oderLogic->print_feie($orderInfo);
        if ($res['status'] == 0) {
            $this->error($res['info']);
        }
        $this->success(lang('s'));
    }

    /**
     * 佣金信息
     * @auto true
     * @auth true
     * @menu false
     */
    public function bro_info() {
        $info = $this->model->getBroDetail($this->in['order_id']);
        if (!$info) {
            $this->msg('没有数据');
        }
        View::assign('info', $info);
        return $this->display();
    }

    /**
     * 批量发货
     * @auto true
     * @auth true
     * @menu false
     */
    public function mul_send() {
        $a = array(
            'status' => 1,
        );
        $d1 = $this->model->get_data($a);

        $data = $d1['list']; 
        View::assign('data', $data);
        return $this->display();
    }

}
