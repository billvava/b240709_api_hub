<?php

namespace app\mall\logic;

use app\admin\model\ErrorLog;
use app\common\model\User;

use app\mall\model\OrderHandle;
use think\facade\Db;
use think\facade\Log;

class Order {

    public $user_id;
    public $orderHandle;
    public $order;


    public function __construct() {
        $this->user_id = get_user_id();
        $this->orderHandle = new OrderHandle();
        $this->order = new \app\mall\model\Order();
        $this->OrderModel = new \app\shopapi\model\Order();

    }

    //获取用户地址
    public function getAddressInfo() {
        $in = input();
        $addres_where['user_id'] = $this->user_id;
        if ($in['address_id'] || $in['to_ad_id']) {
            $addres_where['id'] = $in['address_id'] ? $in['address_id'] : $in['to_ad_id'];
            $address_info = Db::name('user_address')->where($addres_where)->find();
        }
        if (!$address_info) {
            $addres_where['is_default'] = 1;
            $address_info = Db::name('user_address')->where($addres_where)->find();
            if (!$address_info) {
                $addres_where['is_default'] = 0;
                $address_info = Db::name('user_address')->where($addres_where)->find();
            }
        }
        return $address_info;
    }

    //判断库存
    public function judgment_stock($cart_data) {
        if (C('is_stock') == 0) {
            return array('status' => 1);
        }
        foreach ($cart_data as $k => $v) {
            if ($v['num'] > $v['ginfo']['num']) {
                return array('status' => 0, 'info' => "【{$v['info']['name']}】的库存不足", 'key' => $k);
            }
        }
        return array('status' => 1);
    }


    //计算邮费
    public function cal_send_money($send_type, $goods_total, $address_info) {
        $send_free = C('send_free');
        $send_money = 0;
        $send_type = $send_type ? $send_type : C('default_send');
        $no_send = explode('@', C('no_send'));
        if (!in_array($send_type, $no_send)) {
            if ($send_free == 1) {
                return 0;
            } else if ($send_free == 2) {
                $send_free_money = C('send_free_money');
                if ($send_free_money > $goods_total) {
                    $send_money = C('send_money');
                } else {
                    $send_money = 0;
                }
            }
        }
        return $send_money;
    }

    //扩展
    public function order_ext($oderInfo, $cart_data) {
        (new \app\mall\model\Order())->add_log($oderInfo['order_id'], '创建订单');
        $this->add_bro($oderInfo, $cart_data);
        return true;
    }

    //佣金关联
    public function add_bro($oderInfo, $cart_data) {
        if (C('is_sale') == 1) {
            $userModel = new User();
            $pinfo = $userModel->getPinfo($this->user_id);
            if ($pinfo['pid1'] <= 0) {
                return;
            }
            $order_bro = array(
                'order_id' => $oderInfo['order_id'],
                'bro1' => 0,
                'bro2' => 0,
                'bro3' => 0,
                'pid1' => $pinfo['pid1'],
                'pid2' => $pinfo['pid2'],
                'pid3' => $pinfo['pid3'],
            );
            foreach ($cart_data as $v) {
                $info = $this->getGoodBroInfo($v['goods_id']);
                if ($info) {
                    $order_bro['bro1'] += ($v['ginfo']['price'] * $info['bro1'] * $v['num']);
                    $order_bro['bro2'] += ($v['ginfo']['price'] * $info['bro2'] * $v['num']);
                    $order_bro['bro3'] += ($v['ginfo']['price'] * $info['bro3'] * $v['num']);
                }
            }
            Db::name('mall_order_bro')->insert($order_bro);
        }
    }

    //获取佣金关联
    public function getGoodBroInfo($goods_id) {
        return Db::name('mall_goods_ext')->alias("a")
                        ->join('mall_bro_rule b', "a.bro_id=b.id")
                        ->where(array('a.goods_id' => $goods_id))->field('b.*')->find();
    }



    /**
     * 订单的异步处理
     * @param type $ordernum  异步接收到的订单号
     * @param type $tro_no 异步接收到的流水号
     * @param type $order_tatal 异步接收到的金额 
     * @param type $pay_type 支付方式  4=微信，1=支付宝
     */
    public function pay_handle($ordernum, $tro_no, $order_tatal, $pay_type) {
        return $this->orderHandle->pay_handle($ordernum, $tro_no, $order_tatal, $pay_type);

    }

    //订单支付成功
    public function pay_success($order_info) {
        //需要给上级发佣金
        $haoyou_bro = C('haoyou_bro');
        $order_id = $order_info['order_id'];
        $umodel = (new User());
        $user_data = $umodel -> where('id',$order_info['user_id']) -> find();
        $pid = $user_data['pid'];
        $referee_path = $user_data['referee_path'];

//        $total_cost = Db::name('mall_order_goods') -> where('order_id',$order_id) -> sum('total_cost');
//        $agent_fee = $order_info['total'] - $total_cost - 1;
//        $agent_id = C('agent_id');

//        if ($haoyou_bro > 0) {
//            $bro = $order_info['total'] * ($haoyou_bro / 100);
//            $umodel = (new User());
//
//            if ($order_info['pid'] && $order_info['is_stock'] ==1 && $bro > 0) {
//                $umodel->handleUser('bro', $order_info['pid'], $bro, 1, ['ordernum' => $order_info['ordernum'], 'cate' => 5]);
//
//            }
//        }
//        $umodel->handleUser('bro', $agent_id, $agent_fee, 1, ['ordernum' => $order_info['ordernum'], 'cate' => 7]);

        $goods_id = Db::name('mall_order_goods') -> where('order_id',$order_id) -> value('goods_id');
        
        $category_id = Db::name('mall_goods') -> where('goods_id',$goods_id) -> value('category_id');
        $type = Db::name('mall_goods_category') -> where('category_id',$category_id) -> value('english');


//        $bonus = Db::name('mall_goods') -> where('goods_id',$goods_id) -> value('bonus');
        //极差奖
//        $this->OrderModel -> jichajiang($order_info['user_id'],$pid,$bonus,$order_info['ordernum']);
        if($type =='huodong'){
            $umodel -> zhituijiang($order_info['total'] ,$order_info['user_id'],$pid);
//
            $umodel -> tuanduijiang($order_info['total'] ,$order_info['user_id']);
            $umodel -> saveYeji($order_info['user_id'],$order_info['total']);
            $umodel -> updateRank($order_info['user_id'],$referee_path,$order_info['total']);//更改用户等级
            $umodel -> shengji($referee_path);
        }






        if($order_info['type'] == 5){
            $arr = Db::name('mall_order')->where(['p_orderid'=>$order_info['order_id']])->select()->toArray();
            $res = null;
            foreach($arr as $v){
                $res =  $this->orderHandle->pay_success($v);
            }
            return $res;
        }else{
            return $this->orderHandle->pay_success($order_info);

        }

    }

    

    //获取当前状态和操作
    public function getOption($orderInfo) {
       return $this->order->order_status($orderInfo);
    }

    //处理按钮,准备废弃
    public function handle_btn($orderInfo) {
        $btns = array(
            'item',
            'close',
            'pay',
            'finish',
            'comment',
        );
        $arr = array();
        $arr[] = 'item';

        $btn_class = array(
            'item' => array(
                'name' => '详情',
                'bindtap' => 'item',
                'class' => 'order-btn1',
            ),
            'close' => array(
                'name' => '关闭',
                'bindtap' => 'close',
                'class' => 'order-btn1',
            ),
            'pay' => array(
                'name' => '支付',
                'bindtap' => 'pay',
                'class' => 'order-btn2',
            ),
            'finish' => array(
                'name' => '确认',
                'bindtap' => 'finish',
                'class' => 'order-btn2',
            ),
            'delivery' => array(
                'name' => '物流',
                'bindtap' => 'delivery',
                'class' => 'order-btn2',
            ),
        );

        if ($orderInfo['status'] == 0) {
            $arr[] = 'close';
            $arr[] = 'pay';
        }
        if ($orderInfo['status'] == 2) {
            $arr[] = 'finish';
            $arr[] = 'delivery';
        }

        $temp = array();
        foreach ($arr as $v) {
            $temp[] = $btn_class[$v];
        }
        return $temp;
    }

    //生成管理员的按钮
    public function admin_btn($order_info) {
        return $this->order->admin_btn($order_info);

    }

    //飞蛾打印
    public function print_feie($order_info) {
       return $this->orderHandle->print_feie($order_info);
    }

    
    //生产APP端的按钮
    public function handle_app_btn($order_info)
    {
        return $this->order->app_btn($order_info);

    }
}
