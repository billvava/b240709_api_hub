<?php

namespace app\mallapi\logic;

use app\admin\model\SuoOrder;
use app\admin\model\SuoOrderJia;
use think\App;
use think\facade\Db;

class Pay {

    public function __construct() {
        
    }

    public function ali_pay() {
        $this->_mall('ali_app');
    }

    public function wx_js() {
        $this->_mall('wx_js');
    }

    public function fy_miniprogram() {
        $this->_mall('fy_miniprogram');
    }

    //微信和富友
    public function mall() {
        $this->_mall(C('pay_type'));
    }

    public function _mall($pay_type) {
        tool()->classs('pay/Pay');
        $Pay = new \Pay($pay_type);
        $pay_types = C('pay_types');
        $item = $pay_types[$pay_type];
        $res = $Pay->notify();
        if ($res['status'] == 1) {
            $Order = new \app\mall\model\Order();
            $info = $Order->where(['ordernum' => $res['data']['ordernum'], 'pay_status' => 0])->find();
            if ($info) {
                $info = $info->toArray();
                $info['trade_no'] = $res['data']['transaction_id'];
                $info['pay_type'] = $item['type'];
                $orderLogic = new \app\mall\logic\Order();
                $orderLogic->pay_success($info);
            }
            echo $res['echo'];
        }
    }

    public function suoye(){
        $pay_type =  C('pay_type');
        tool()->classs('pay/Pay');
        $Pay = new \Pay($pay_type);
        $pay_types = C('pay_types');
        $item = $pay_types[$pay_type];
        $res = $Pay->notify();
        if ($res['status'] == 1) {
            $Order = new SuoOrder();
            $info = $Order->where(['ordernum' => $res['data']['ordernum'], 'pay_status' => 0])->find();
            if ($info) {
                $info = $info->toArray();
                $info['trade_no'] = $res['data']['transaction_id'];
                $info['pay_type'] = 1;
                $Order->pay_success($info);
            }
            echo $res['echo'];
        }


    }

    public function suoye_jiajia(){
        $pay_type =  C('pay_type');
        tool()->classs('pay/Pay');
        $Pay = new \Pay($pay_type);
        $pay_types = C('pay_types');
        $item = $pay_types[$pay_type];
        $res = $Pay->notify();
        if ($res['status'] == 1) {
            $Order = new SuoOrderJia();
            $info = $Order->where(['ordernum' => $res['data']['ordernum'], 'pay_status' => 0])->find();
            if ($info) {
                $info = $info->toArray();
                $info['trade_no'] = $res['data']['transaction_id'];
                $info['pay_type'] = 1;
                $Order->pay_success($info);
            }
            echo $res['echo'];
        }

    }
}
