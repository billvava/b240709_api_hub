<?php

namespace app\mallapi\logic;

use app\admin\model\Shop;
use app\mall\logic\Delivery;
use app\mall\model\MallBroRule;
use app\user\model\UserAddress;
use think\App;
use think\facade\Db;

class OrderCreate
{

    public $clear;
    public $uinfo;
    public $data;
    public $shop_info;
    public $goodsLogicModel;
    public $goodsModel;
    public $cartModel;
    public $orderModel;
    public $userModel;
    private $goods_add;
    public $in;
    public $Discount;

    public function __construct()
    {
        $this->goodsLogicModel = new \app\mall\logic\Goods();
        $this->goodsModel = new \app\mall\model\Goods();
        $this->cartModel = new Cart();
        $this->orderModel = new \app\mall\model\Order();
        $this->userModel = new \app\common\model\User();
        $this->Discount = new \app\common\logic\Discount();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $this->cartModel->config($map);
    }

    //主入口
    public function create_order()
    {
        bcscale(2);
        //|1=普通订单,2=秒杀订单,3=拼团订单,4=砍价订单'
        $this->data['type'] = 1;
        //应付金额
        $this->data['old_total'] = 0;
        //商品金额
        $this->data['goods_total'] = 0;
        //优惠总金额
        $this->data['discount_total'] = 0;
        //实付金额
        $this->data['total'] = 0;

        //运费
        $this->data['finfo'] = $this->userModel->getFinance($this->uinfo['id']);


        $rank = $this->uinfo['rank'];
       

        $this->check_return($this->check_start());
        $this->check_return($this->accept_goods());
        $is_stock = 1;
        $res = $this->handle_goods();
        // $res = $this->check_return($this->handle_goods());
        $is_stock = $res['is_stock'];
        $this->get_source();
        $this->check_return($this->cal_total());
        if ($this->in['flag'] == 'sub') {
            $goods_id = input('goods_id');
            $category_id = Db::name('mall_goods') -> where('goods_id',$goods_id) -> value('category_id');
            $type = Db::name('mall_goods_category') -> where('category_id',$category_id) -> value('english');
//            if($rank!=2){
//                if($type=='vip' || $type=='fuli'){
//                    return array('status' => 0, 'info' => 'vip等级才能购买此商品');
//                }
//
//            }
           $this->in['goods_type'] = $type;
           return $this->sub($is_stock);
        }
        return array('status' => 1, 'data' => $this->data);
    }

    //检测返回的数据
    private function check_return($r)
    {
        if ($r['status'] != 1) {
            json_msg(0, $r['info']);
        }
    }

    //检测
    private function check_start()
    {
        if (!$this->uinfo) {
            return array('status' => 0, 'info' => '请先登陆');
        }
        if (!in_array($this->in['flag'], array('get', 'sub'))) {
            return array('status' => 0, 'info' => 'flag不存在');
        }
        return array('status' => 1);
    }


    //获取来源
    private function get_source()
    {
        // 1=web,2=小程序，3=ios,4=android
        $this->data['source'] = 'web';
        $system = $this->in['system'] ? $this->in['system'] : $this->in['source'];
        if (in_array($system, array('ios', 'android', 'minpro', 'mp_weixin', 'h5'))) {
            $this->data['source'] = $system;
        }
    }

    //提交
    public function sub($is_stock)
    {

        $this->data['ordernum'] = get_ordernum();
        
        $this->clear = array(
            'ordernum' => $this->data['ordernum'],
            'type' => $this->data['type'],
            'old_total' => $this->data['old_total'],
            'discount_total' => $this->data['discount_total'],
            'goods_total' => $this->data['goods_total'],
            'total' => $this->data['total'],
            'user_id' => $this->uinfo['id'],
            'username' => $this->uinfo['username'],
            'ip' => get_client_ip(),
            'message' => $this->in['message'][0] . '',
            'create_time' => date('Y-m-d H:i:s'),
            'is_new' => 0, //是否新用户
            'source' => $this->data['source'],
            'status' => $this->data['total'] > 0 ? 0 : 1,
            'pay_status' => $this->data['total'] > 0 ? 0 : 1,
            'goods_num' => $this->data['goods_num'],
            'goods_type' => $this->in['goods_type'],
            'pid' => $this->uinfo['pid'],
        );

        $this->orderModel->startTrans();
        //提交优惠活动信息
        $this->Discount->config([
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data,
        ]);
        $res = $this->Discount->handle('act', 'sub');
        $this->check_return($res);
        $this->data = $res['data'];
        if ($res['clear_field']) {
            foreach ($res['clear_field'] as $v) {
                $this->clear[$v] = $this->data[$v];
            }
        }
        //提交商品活动信息
        if ($this->data['discount_goods_class']) {
            $this->Discount->config([
                'in' => $this->in,
                'uinfo' => $this->uinfo,
                'data' => $this->data,
            ]);
            $res = $this->Discount->single_handle($this->data['discount_goods_class'], 'sub');
            $this->check_return($res);
            $this->data = $res['data'];

            if ($res['clear_field']) {
                foreach ($res['clear_field'] as $v) {
                    $this->clear[$v] = $this->data[$v];
                }
            }
        }

        //支付方式在线抵扣
        if ($this->clear['status'] == 1) {
            $this->clear['pay_type'] = 2;
        }


        $oid = $this->orderModel->where(array('user_id' => $this->uinfo['id']))->value('order_id');
        if (!$oid) {
            $this->clear['is_new'] = 1;
        }

        if(count($this->data['shop_list'])  == 1){
            $this->clear['shop_id'] = $this->data['shop_list'][0]['shop_id'];
        }
       
        $order_id = $this->orderModel->insertGetId($this->clear);




        $shop_list=$this->data['shop_list'];
        $last_key=count($shop_list) - 1;
        if($last_key >= 1){
            $this->orderModel->where(['order_id'=>$order_id])->save(['type'=>5]);
            $shop_total = array_sum(array_column($shop_list,'goods_total'));
            $tmp_discount_total = $this->data['discount_total'];
            $tmp_total = $this->data['total'];
            $tmp_delivery_total =  $this->data['delivery_total'];
            $tmp_coupon_total =  $this->data['coupon_total'];
            $tmp_old_total =  $this->data['old_total'];
            $tmp_delivery_total =   $this->data['delivery_total'];

            foreach ($shop_list as $key => $v){
                $clear_child=$this->clear;
                $clear_child['ordernum']=get_ordernum();
                $bili = round( $v['goods_total']/ $shop_total,2);

                //优惠券
                $clear_child['shop_id']=$v['shop_id'];

                $clear_child['coupon_id']=0;
                $clear_child['coupon_total']=0;
                $clear_child['goods_total']=$v['goods_total'];

                //积分
                $clear_child['dot']=0;
                $clear_child['dot_total']=0;

                $clear_child['discount_total']=0;


                $clear_child['old_total'] = 0;

                $clear_child['delivery_total'] = 0;

                //分摊优惠金额
                if ($this->data['delivery_total'] > 0) {
                    if ($last_key != $key) {
                        $tmp = round( $bili * $this->data['delivery_total'] , 2);
                    } else {
                        $tmp = $tmp_delivery_total;
                    }
                    $tmp_delivery_total -= $tmp;
                    $clear_child['delivery_total'] = $tmp;
                }
                if ($this->data['old_total'] > 0) {
                    if ($last_key != $key) {
                        $tmp = round( $bili * $this->data['old_total'] , 2);
                    } else {
                        $tmp = $tmp_old_total;
                    }
                    $tmp_old_total -= $tmp;
                    $clear_child['old_total'] = $tmp;
                }
                if ($this->data['discount_total'] > 0) {
                    if ($last_key != $key) {
                        $tmp = round( $bili * $this->data['discount_total'] , 2);
                    } else {
                        $tmp = $tmp_discount_total;
                    }
                    $tmp_discount_total -= $tmp;
                    $clear_child['discount_total'] = $tmp;
                }
                if ($this->data['coupon_total'] > 0) {
                    if ($last_key != $key) {
                        $tmp = round( $bili * $this->data['coupon_total'], 2);
                    } else {
                        $tmp = $tmp_coupon_total;
                    }
                    $tmp_coupon_total -= $tmp;
                    $clear_child['coupon_total'] = $tmp;
                }
                if ($this->data['total'] > 0) {
                    if ($last_key != $key) {
                        $tmp = round( $bili * $this->data['total'], 2);
                    } else {
                        $tmp = $tmp_total;
                    }
                    $tmp_total -= $tmp;
                    $clear_child['total'] = $tmp;
                }
                if ($this->data['delivery_total'] > 0) {
                    if ($last_key != $key) {
                        $tmp = round( $bili * $this->data['total'], 2);
                    } else {
                        $tmp = $tmp_delivery_total;
                    }
                    $tmp_delivery_total -= $tmp;
                    $clear_child['delivery_total'] = $tmp;
                }
                $clear_child['message']=$this->in['message'][$key];
                $clear_child['shop_id']=$v['shop_id'];
                $clear_child['p_orderid']=$order_id;
                $this->orderModel->add_log($clear_child, array('cate' => 1));
                $child_order_id = $this->orderModel->insertGetId($clear_child);

                $temp_coupon_money = $clear_child['discount_total'];
                $last_key_child = count($v['goods_add']) - 1;
                foreach ($v['goods_add'] as $gk => &$g){
                    $g['order_id']=$child_order_id;
                    //分摊优惠金额
                    if ($temp_coupon_money > 0) {
                        if ($last_key != $gk) {
                            $item_coupon_money = round(floor($g['total_pay_price'] / $v['goods_total'] * $temp_coupon_money * 100) / 100, 2);
                        } else {
                            $item_coupon_money = $temp_coupon_money;
                        }
                        $temp_coupon_money -= $item_coupon_money;
                        $g['discount_total'] = $item_coupon_money;
                        $real_goods_money = $g['total_pay_price'] - $item_coupon_money;
                        $real_goods_money = $real_goods_money < 0 ? 0 : $real_goods_money;
                        $g['total_pay_price'] = $real_goods_money;
                        $g['unit_price'] = round($real_goods_money / $g['num'], 2);
                        $g['total_price'] = $real_goods_money;
                    }
                    //增加销量
                    Db::name('mall_goods')->where(array('goods_id' => $g['goods_id']))->inc('sale_num', $g['num'])->update();
                    //减少库存
                    $this->goodsModel->reduceStore($g['goods_id'], $g['item_id'], $g['num']);
                }

                //增加销量
                Db::name('mall_goods')->where(array('goods_id' => $g['goods_id']))->inc('sale_num', $g['num'])->update();
                //减少库存
                $this->goodsModel->reduceStore($g['goods_id'], $g['item_id'], $g['num']);
                Db::name('mall_order_goods')->insertAll($v['goods_add']);

            }
        }else{
            $temp_coupon_money = $this->data['discount_total'];
            $last_key = count($this->goods_add) - 1;
            foreach ($this->goods_add as $gk => &$v) {
                $v['order_id'] = $order_id;
                //分摊优惠金额
                if ($this->data['discount_total'] > 0) {
                    if ($last_key != $gk) {
                        $item_coupon_money = round(floor($v['total_pay_price'] / $this->data['goods_total'] * $this->data['discount_total'] * 100) / 100, 2);
                    } else {
                        $item_coupon_money = $temp_coupon_money;
                    }
                    $temp_coupon_money -= $item_coupon_money;
                    $v['discount_total'] = $item_coupon_money;
                    $real_goods_money = $v['total_pay_price'] - $item_coupon_money;
                    $real_goods_money = $real_goods_money < 0 ? 0 : $real_goods_money;
                    $v['total_pay_price'] = $real_goods_money;
                    $v['unit_price'] = round($real_goods_money / $v['num'], 2);
                    $v['total_price'] = $real_goods_money;
                }
                //增加销量
                Db::name('mall_goods')->where(array('goods_id' => $v['goods_id']))->inc('sale_num', $v['num'])->update();
                //减少库存
                // $this->goodsModel->reduceStore($v['goods_id'], $v['item_id'], $v['num']);
            }
           
            Db::name('mall_order_goods')->insertAll($this->goods_add);

            $this->orderModel->add_log($order_id, array('cate' => 1));
        }


        $this->clear['id'] = $order_id;

        $this->orderModel->commit();
        //清空购物车
        if ($this->data['from_cart'] == 1) {
            $this->cartModel->del_sel_list();
        }
        $this->data['is_pay'] = $this->clear['pay_status'];
        $this->clear['order_id'] = $order_id;

        //执行一些提交后的方法
        $this->data['order_info'] = $this->clear;
        $this->Discount->config([
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data,
        ]);
//        $this->Discount->handle('act', 'sub_after');
        if ($this->clear['pay_status'] == 0) {
            return array('status' => 1, 'data' => array('ordernum' => $this->data['ordernum'], 'order_id' => $order_id, 'is_pay' => 0));
        } else {
            (new \app\mall\logic\Order())->pay_success($this->clear);
            return array('status' => 1, 'data' => array('ordernum' => $this->data['ordernum'], 'order_id' => $order_id, 'is_pay' => 1));
        }
    }


    //计算金额
    private function cal_total()
    {

        $this->data['total'] = $this->data['old_total'] ;
        //调取优惠活动信息
        $this->Discount->config([
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data,
        ]);
        $res = $this->Discount->handle('act');
        $this->check_return($res);
        $this->data = $res['data'];


        if ($this->data['total'] <= 0) {
            $this->data['total'] = 0;
        }


        return array('status' => 1);
    }

    //接收商品
    private function accept_goods()
    {
        $this->data['from_cart'] = 0;
        //立即购买
        if ($this->in['goods_id'] && $this->in['num'] > 0) {
            $ginfo = $this->goodsModel->getItemInfo($this->in['goods_id'], $this->in['item_id']);
            if (!$ginfo) {
                return array('status' => 0, 'info' => '请选择商品');
            }

            //调取优惠商品信息，重置商品价格
            $this->Discount->config([
                'in' => $this->in,
                'uinfo' => $this->uinfo,
                'data' => $this->data,
            ]);
            $res = $this->Discount->handle('goods');
            $this->check_return($res);
            $this->data = $res['data'];
            if ($res['data']['unit_price'] > 0) {
                $ginfo['price'] = $res['data']['unit_price'];
            }

            $this->data['goods_list'] = array(array(
                'num' => $this->in['num'],
                'price' => $ginfo['price'],
                'item_id' => $this->in['item_id'],
                'goods_id' => $this->in['goods_id'],
                'check' => 1,
            ));
        } //一般APP的格式
        else if ($this->in['goods']) {
            if (is_string($this->in['goods'])) {
                $this->in['goods'] = cinputFilter(json_decode($_REQUEST['goods'], true));
            }
            foreach ($this->in['goods'] as $v) {
                $ginfo = $this->goodsModel->getItemInfo($v['goods_id'], $v['item_id']);
                if (!$ginfo) {
                    return array('status' => 0, 'info' => '商品规格不存在');
                }
                $this->data['goods_list'][] = array(
                    'num' => $v['num'],
                    'price' => $ginfo['price'],
                    'item_id' => $v['item_id'],
                    'goods_id' => $v['goods_id'],
                    'check' => 1,
                );
            }
        } //读取购物车
        else {
            $this->data['goods_list'] = $this->cartModel->get_sel_list();
            $this->data['from_cart'] = 1;
        }
        return array('status' => 1);
    }

    //处理商品
    private function handle_goods()
    {
        $this->data['bro1'] = 0;
        $this->data['bro2'] = 0;
        $this->data['bro3'] = 0;
        $this->data['shop_list'] = [];
        foreach ($this->data['goods_list'] as $k => &$v) {
            //防止传小数
            if ($v['num'] != ceil($v['num'])) {
                return array('status' => 0, 'info' => "数量错误！");
            }
            //防止0
            if ($v['num'] <= 0) {
                return array('status' => 0, 'info' => "数量错误！！");
            }
            $v['info'] = $this->goodsModel->getInfo($v['goods_id'], true, false);
            if ($v['info']['status'] != 1) {
                return array('status' => 0, 'info' => "{$v['info']['name']}已下架");
            }
            $v['ginfo'] = $this->goodsModel->getItemInfo($v['goods_id'], $v['item_id']);
            $unit_price = $v['price'];
            $unit_price2 = $v['ginfo']['price2'];

            if ($unit_price <= 0) {
                return array('status' => 0, 'info' => "价格错误");
            }
            $is_stock =1;

            if($v['info']['is_stock'] != 1){
                $is_stock =2;
            }
            // if ($v['num'] > $v['ginfo']['num']) {
                
            //     return array('status' => 0, 'info' => '商品库存不足');
            // }

            $this->data['goods_total'] += ($unit_price * $v['num']);
            $this->data['goods_num'] += $v['num'];
            $gs = explode('_', $v['ginfo']['gids']);
            $v['spec_str'] = $v['ginfo']['spec_value_str'] . '';
            $total_price = bcmul($unit_price, $v['num']);
            $total_cost = bcmul($unit_price2, $v['num']);

            $total_profit = $total_price -  $total_cost;

            $a = array(
                'name' => $v['info']['name'],
                'thumb' => $v['info']['thumb_file'],
                'num' => $v['num'],
                'unit_price' => $unit_price,
                'total_price' => $total_price,
                'total_pay_price' => $total_price,
                'discount_total' => 0,
                'old_unit_price' => $unit_price,
                'old_total_price' => $total_price,
                'goods_id' => $v['info']['goods_id'],
                'category_id' => $v['info']['category_id'],
                'sku' => $v['ginfo']['bar_code'],
                'item_id' => $v['ginfo']['id'],
                'spec_str' => $v['spec_str'],
                'user_id' => $this->uinfo['id'],
                'total_cost' => $total_cost,
                'total_profit' => $total_profit,
             
                
            );

            //佣金相关
            if ($v['info']['bro_id'] > 0 && $this->in['flag'] == 'sub' && $this->uinfo['pid'] > 0) {
                $bro_info = Db::name('mall_bro_rule')->where(array('id' => $v['info']['bro_id']))->find();
                $a['bro1'] = bcmul($bro_info['bro1'], $v['info']['bro']);
                $a['bro2'] = bcmul($bro_info['bro2'], $v['info']['bro']);
                $a['bro3'] = bcmul($bro_info['bro3'], $v['info']['bro']);
                $this->data['bro1'] += $a['bro1'];
                $this->data['bro2'] += $a['bro2'];
                $this->data['bro3'] += $a['bro3'];
            }
            $this->goods_add[] = $a;
            $temp_shop_id = $v['info']['shop_id'] + 0;
            if (!$this->data['shop_list'][$temp_shop_id]) {
               $shop_info =  (new Shop())->getInfo($temp_shop_id);
                $this->data['shop_list'][$temp_shop_id] = [
                    'goods_list' => [$v],
                    'shop_id' => $temp_shop_id,
                    'shop_name' => $shop_info['name'].'',
                    'shop_thumb' => $shop_info['thumb_url'].'',

                ];
            } else {
                $this->data['shop_list'][$temp_shop_id]['goods_list'][] = $v;
            }
            $this->data['shop_list'][$temp_shop_id]['goods_add'][] = $a;
            $this->data['shop_list'][$temp_shop_id]['goods_total'] += $total_price;
            $this->data['shop_list'][$temp_shop_id]['goods_num'] += $v['num'];


        }
        $this->data['shop_list'] = array_values($this->data['shop_list']);

        $volume = [];
        foreach ($this->data['shop_list'] as $key => $row)
        {
            $volume[$key]  = $row['goods_total'];
        }

//        array_multisort($volume, SORT_DESC, $this->data['shop_list']);
//        p($this->data['shop_list']);






        if ($this->data['goods_total'] <= 0) {
            return array('status' => 0, 'info' => '商品金额错误');
        }

        if (!$this->data['goods_list']) {
            return array('status' => 0, 'info' => '请选择商品');
        }

        $this->data['old_total'] += $this->data['goods_total'];
        
        return array('status' => 1,'is_stock' => $is_stock);
    }

}
