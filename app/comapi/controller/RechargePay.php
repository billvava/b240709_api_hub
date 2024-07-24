<?php

namespace app\comapi\controller;

use app\BaseController;
use think\App;
use think\facade\Db;

class RechargePay extends BaseController {

    public function index() {
        tool()->classs('pay/Pay');
        $wx_pay_type = C('wx_pay_type');
        $wx_pay_types = C('wx_pay_types');
        $Pay = new \Pay($wx_pay_type);
        $item = $wx_pay_types[$wx_pay_type];
        $pay_type = $item['type'];
        $res = $Pay->notify();

        if ($res['status'] == 1) {
            $Order = new \app\com\model\RechargeOrder();
            $info = $Order->where(['ordernum' => $res['data']['ordernum'], 'is_pay' => 0])->find();

            if ($info) {
                $info = $info->toArray();
                $total = $info['goods_total'] + $info['give'];
                $Order->removeOption()->where(['ordernum' => $res['data']['ordernum'], 'is_pay' => 0])->save([
                    'tra_no' =>$res['data']['transaction_id'],
                    'pay_type' => 4,
                    'is_pay' => 1,
                    'pay_time' => date('Y-m-d H:i:s'),
                ]);
                $userModel = new \app\common\model\User();
                $userModel->handleUser('money', $info['user_id'], $total, 1, array('ordernum' => $info['ordernum'], 'cate' => '5'));
            }
            echo $res['echo'];
        }

    }

}
