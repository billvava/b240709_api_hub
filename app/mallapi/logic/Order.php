<?php

namespace app\mallapi\logic;

use think\App;
use think\facade\Db;

class Order
{

    public $clear;
    public $uinfo;
    public $data;
    public $model;
    public $in;
    public $orderModel;

    public function __construct()
    {
        $this->model = new \app\common\model\User();
        $this->goodsModel = new \app\mall\model\Goods();
        $this->goodsLogicModel = new Goods();
        $this->orderModel = new \app\mall\model\Order();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }

    //订单列表
    public function index()
    {
        $uid = $this->uinfo['id'];
        $this->data['navs'] = array(
            array(
                'flag' => '',
                'name' => '全部',
            ),
            array(
                'flag' => 'wait_pay',
                'name' => '待付款',
            ),
            array(
                'flag' => 'wait_send',
                'name' => '待发货',
            ),
            array(
                'flag' => 'wait_get',
                'name' => '待收货',
            ),
            array(
                'flag' => 'wait_com',
                'name' => '待评价',
            ),
            array(
                'flag' => 'after',
                'name' => '售后',
            ),
            // array(
            //     'flag' => 'children',
            //     'name' => '下级订单',
            // ),
        );
        $this->data['nav_act'] = 'order';
        $map = array('user_id' => $this->uinfo['id'], 'is_u_del' => 0, 'get_status' => 1, 'page' => $this->in['page']);
        $items = array(
            'wait_pay' => array(
                'status' => 0,
            ),
            'wait_send' => array(
                'status' => 1,
            ),
            'wait_get' => array(
                'status' => 2,
            ),
            'wait_com' => array(
                'status' => 3,
                'comment_status' => 0,
            ),
            'after' => array(
                'after_status' => 'Y',
            ),
            // 'children' => array(
            //     'pid' => $uid,
            // ),
        );
        if ($this->in['flag']) {
            $item = $items[$this->in['flag']];
            if ($item) {
                $map = array_merge($map, $item);
            }
        }

        if ($this->in['title']) {
            $map['order_ids'] = $this->orderModel->search($this->in['title'], $this->uinfo['id']);
        }

        $res = $this->orderModel->get_data($map);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);

        return array('status' => 1, 'data' => $this->data);
    }

     //下级订单列表
    public function getChildrenList()
    {
        $uid = $this->uinfo['id'];
        $this->data['navs'] = array(
            array(
                'flag' => '',
                'name' => '全部',
            ),
            array(
                'flag' => 'wait_pay',
                'name' => '待付款',
            ),
            array(
                'flag' => 'wait_send',
                'name' => '待发货',
            ),
            array(
                'flag' => 'wait_get',
                'name' => '待收货',
            ),
            array(
                'flag' => 'wait_com',
                'name' => '待评价',
            ),
            array(
                'flag' => 'after',
                'name' => '售后',
            ),
            // array(
            //     'flag' => 'children',
            //     'name' => '下级订单',
            // ),
        );
        $this->data['nav_act'] = 'order';
        $map = array('pid' => $this->uinfo['id'],'is_stock' => 2, 'is_u_del' => 0, 'get_status' => 1, 'page' => $this->in['page']);
        $items = array(
            'wait_pay' => array(
                'status' => 0,
            ),
            'wait_send' => array(
                'status' => 1,
            ),
            'wait_get' => array(
                'status' => 2,
            ),
            'wait_com' => array(
                'status' => 3,
                'comment_status' => 0,
            ),
            'after' => array(
                'after_status' => 'Y',
            ),
            
        );
        if ($this->in['flag']) {
            $item = $items[$this->in['flag']];
            if ($item) {
                $map = array_merge($map, $item);
            }
        }

        if ($this->in['title']) {
            $map['order_ids'] = $this->orderModel->search($this->in['title'], $this->uinfo['id']);
        }
       
        $res = $this->orderModel->get_data($map);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);

        return array('status' => 1, 'data' => $this->data);
    }

    //订单详情
    public function item()
    {
        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        $res['data']['companytel'] = C('companytel');
        $res['data']['money'] = $this->model -> where('id',$this ->uinfo['id']) -> value('money');


        return $res;
    }

    public function item_ordernum()
    {
        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        return $res;
    }

    //关闭订单
    public function close()
    {
        return $this->orderModel->close(0, array(
            'type' => 'home',
            'user_id' => $this->uinfo['id'],
            'ordernum' => $this->in['ordernum']
        ));
    }

    //放弃订单
    public function closeOrder()
    {   
        $ordernum = $this->in['ordernum'];

        $update_data = [];
        $update_data['status'] = 4;
        $update_data['is_stock'] = 1;
        $order_data = $this->orderModel-> where('ordernum',$ordernum) -> find();
        if($order_data['status']==4){
           return array('status' => 0, 'info' => '请勿重复操作');
        }
        $user_id = $order_data['user_id'];
        $total = $order_data['total'];
        $this->model -> handleUser('money', $user_id, $total, 1);
        $this->orderModel-> where('ordernum',$ordernum) ->update($update_data);
        return array('status' => 1, 'info' => '操作成功');
        // return $this->orderModel->close(0, array(
        //     'type' => 'home',
        //     'user_id' => $this->uinfo['id'],
        //     'ordernum' => $this->in['ordernum']
        // ));

    }

    //完成订单
    public function finish()
    {
        return $this->orderModel->finish(0, array(
            'type' => 'home',
            'user_id' => $this->uinfo['id'],
            'ordernum' => $this->in['ordernum']
        ));
    }

    //订单支付
    public function pay()
    {
        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        if (!$res['data']['info']) {
            return array('status' => 0, 'info' => '不存在');
        }
        if ($res['data']['info']['is_pay'] != 0) {
            return array('status' => 0, 'info' => '已支付');
        }
        if ($res['data']['info']['is_close'] != 0) {
            return array('status' => 0, 'info' => '已关闭');
        }
        $OrderCreate = new OrderCreate();
        $OrderCreate->config(array(
            'uinfo' => $this->uinfo,
            'in' => $this->in
        ));
        return $OrderCreate->json_pay($res['data']['info']);
    }


    //推荐人操作订单
    public function updateOrser(){
      $money = $this->model -> where('id',$this->uinfo['id']) -> value('money');
      $ordernum = $this->in['ordernum'];
      $cost_price = $this->in['cost'];//成本价
      $total = $this->in['total'];//售价
      if($money < $cost_price){
        return array('status' => 0, 'info' => '余额不足','url' => '/pages/user/index/index');
      }
      $this->model -> where('id',$this->uinfo['id']) -> dec('money',$cost_price)->update();
      $order_data = $this->orderModel -> where('ordernum',$ordernum) -> find();
      //需要给上级发佣金
      $haoyou_bro = C('haoyou_bro');

      if ($haoyou_bro > 0) {
          // $bro = $order_data['total'] * ($haoyou_bro / 100);
          $bro = $total;
          if ($order_data['pid'] &&  $bro > 0) {
              $this->model -> handleUser('bro', $order_data['pid'], $bro, 1, ['ordernum' => $order_data['ordernum'], 'cate' => 6]);
          }
      }
      $this->orderModel -> where('ordernum',$ordernum) -> update(['is_stock'=>1]);
      return ['status' => 1,'info' => '操作成功'];
    }

    //订单评论
    public function comment()
    {
//        return array('status' => 0, 'info' => '该接口已取消');

        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        $info = $res['data']['info'];
        if ($res['data']['app_btn']['comment'] != 1) {
            return array('status' => 0, 'info' => '该订单无法评论');

        }

        $gs = array();
//        $ces = [];
        foreach ($res['data']['goods_list'] as $g) {
            $gs[] = $g['goods_id'];
//            $ces["{$g['order_id']}_{$g['item_id']}"] = $g['id'];
        }
        $temp = array();
        $time = date('Y-m-d H:i:s');
        $mall_goods_comment = Db::name('mall_goods_comment');
        foreach ($this->in['list'] as $v) {
            if (in_array($v['goods_id'], $gs)) {
                $temp[] = array(
                    'headimgurl' => $this->uinfo['headimgurl'],
                    'order_id' => $info['order_id'],
                    'order_goods_id'=> $v['order_goods_id'] ,
                    'goods_id' => $v['goods_id'],
                    'images' => json_encode($v['images']),
                    'content' => $v['content'],
                    'user_id' => $this->uinfo['id'],
                    'nickname' => $this->in['is_anonymous'] ? '****' : $this->uinfo['nickname'],
                    'star' => $v['star'],
                    'is_anonymous' => $this->in['is_anonymous'],
                    'time' => $time
                );
            }
        }
        if ($temp) {
            $mall_goods_comment->insertAll($temp);
            Db::name('mall_order')->where(array('order_id' => $info['order_id']))->save(array('comment_status' => 1));
            $this->orderModel->add_log($info['order_id'], array('cate' => '5'));
            return array('status' => 1, 'info' => '评论成功');
        }
        return array('status' => 0, 'info' => '评论失败');
        //goods_list
    }

    //申请售后
    public function feed()
    {
        if (!$this->orderModel->checkOrderOwner($this->in['order_id'], $this->uinfo['id'])) {
            return array('status' => 0, 'info' => lang('e'));
        }
        if ($this->in['flag'] == 'sub') {
            $info = $this->orderModel->getInfo($this->in['order_id'], 0, $this->uinfo['user_id']);
            if (!$info) {
                json_msg(0, '订单不存在');
            }
            if ($info['is_pay'] != 1) {
                json_msg(0, '订单未支付');
            }
            if (!$this->in['type']) {
                json_msg(0, '请选择类型');
            }

            Db::name('mall_order_feedback')->insert(array(
                'order_id' => $this->in['order_id'],
                'create_time' => date('Y-m-d H:i:s'),
                'content' => $this->in['content'],
                'type' => $this->in['type'], 'is_chai' => $this->in['is_chai'],
                'tel' => $this->in['tel'] . '',
                'name' => $this->in['name'] . '',
                'img' => $this->in['img'] ? json_encode(array_filter($this->in['img'])) : ''
            ));
            Db::name('mall_order')->where(array('order_id' => $this->in['order_id']))->save(array('feedback_status' => 1));

            Db::name('mall_order_flog')->insert(array(
                'order_id' => $this->in['order_id'],
                'time' => date('Y-m-d H:i:s'),
                'msg' => "您的订单已提交售后申请"
            ));
            json_msg(1, lang('s'));
        }
        $data['goods_list'] = $this->orderModel->getGoods($this->in['order_id']);

        $feedback_type = lang('feedback_type');
        $data['feedback_type'] = $feedback_type;
        json_msg(1, '', '', $data);
    }

    //售后记录
    public function feed_log()
    {
        if (!$this->orderModel->checkOrderOwner($this->in['order_id'], $this->uinfo['id'])) {
            return array('status' => 0, 'info' => lang('e'));
        }
        $info = $this->orderModel->getInfo($this->in['order_id']);
        $this->data['ordernum'] = $info['ordernum'];
        $this->data['info'] = $this->orderModel->get_feedback($this->in['order_id']);
        $this->data['list'] = $this->orderModel->get_feedback_log($this->in['order_id']);


        $shopInfo = Db::name('shop')->where(array('id' => $info['shop_id']))->find();
        if ($info['shop_id'] > 0) {
            $this->data['th_tel'] = $shopInfo['tel'] . '';
            $this->data['th_name'] = $shopInfo['name'] . '';
            $this->data['th_address'] = $shopInfo['address'] . '';
        } else {
            $this->data['th_tel'] = C('th_tel');
            $this->data['th_name'] = C('th_name');
            $this->data['th_address'] = C('th_address');
        }

        $this->data['copy_text'] = "{$this->data['th_tel']}， {$this->data['th_name']}，{$this->data['th_address']}";
        return array('status' => 1, 'data' => $this->data);
    }

    //填写退货快递单号
    public function feed_sub()
    {
        if (!$this->orderModel->checkOrderOwner($this->in['order_id'], $this->uinfo['id'])) {
            return array('status' => 0, 'info' => lang('e'));
        }
        $status = Db::name('mall_order_feedback')->where(array('order_id' => $this->in['order_id']))->value('status');
        if ($status != 3) {
            return array('status' => 0, 'info' => '修改失败');
        }
        if (!$this->in['ex_name'] || !$this->in['ex_num']) {
            return array('status' => 0, 'info' => '填写完信息');
        }

        $this->in['status'] = 4;
        $info = Db::name('mall_order_feedback')->where(array('order_id' => $this->in['order_id']))->find();
//      p($info);
        Db::name('mall_order_feedback')->where(array('order_id' => $this->in['order_id']))->field("ex_name,ex_num,status")->save($this->in);


        Db::name('mall_order_flog')->insert(array(
            'msg' => '已填写退货快递单号',
            'time' => date('Y-m-d H:i:s'),
            'order_id' => $this->in['order_id']
        ));
        return array('status' => 1, 'info' => lang('s'), 'data' => $this->data);
    }

    //支付成功
    public function ok()
    {
        $r = (new \app\mall\model\Goods())->get_data(array(
            'status' => 1
        ));
        $this->data['like_goods'] = $r['list'];
        $this->data['time'] = date('Y-m-d H:i');
        return array('status' => 1, 'data' => $this->data);
    }

    //收银台
    public function sel_pay_new()
    {
        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        if (!$res['data']['info']) {
            return array('status' => 0, 'info' => '不存在');
        }
        if ($res['data']['info']['is_pay'] != 0) {
            return array('status' => 0, 'info' => '已支付');
        }
        if ($res['data']['info']['is_close'] != 0) {
            return array('status' => 0, 'info' => '已关闭');
        }
        $info = $res['data']['info'];
        //倒计时到了就关闭
        if ($info['auto_close_second'] <= 0) {
            $this->orderModel->close($info['order_id'], array('type' => 'admin'));
            return array('status' => 0, 'info' => '已关闭');
        }
        $com_cdn = C('com_cdn');
        $user = new \app\common\model\User();
        $finfo = $user->getFinance($this->uinfo['id']);
        $pay_list = array(
            array(
                'name' => '微信支付',
                'pay_type' => 4,
                'thumb' => $com_cdn . 'pay/wx.jpg'
            ),
            array(
                'name' => "消费金支付（￥{$finfo['consumption']}）",
                'pay_type' => 5,
                'thumb' => $com_cdn . 'pay/yu.png'
            ),
        );

        $this->data['pay_type'] = 4;
        $this->data['pay_list'] = $pay_list;
        $this->data['info'] = $res['data']['info'];
        return array('status' => 1, 'data' => $this->data);
    }

    public function sel_pay()
    {
        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        $goods_type = $res['data']['info']['goods_type'];

        if (!$res['data']['info']) {
            return array('status' => 0, 'info' => '不存在');
        }
        if ($res['data']['info']['is_pay'] != 0) {
            return array('status' => 0, 'info' => '已支付');
        }
        if ($res['data']['info']['is_close'] != 0) {
            return array('status' => 0, 'info' => '已关闭');
        }
        $info = $res['data']['info'];
        //倒计时到了就关闭
        if ($info['auto_close_second'] <= 0) {
            $this->orderModel->close($info['order_id'], array('type' => 'admin'));
            return array('status' => 0, 'info' => '已关闭');
        }
        $com_cdn = C('com_cdn');
        $user = new \app\common\model\User();
        $finfo = $user->getFinance($this->uinfo['id']);

        $wechat_array = [
            'name' => '微信支付',
            'pay_type' => 4,
            'thumb' => $com_cdn . 'pay/wx.jpg'
        ];
        $money_array =  [
            'name' => "余额支付（￥{$finfo['money']}）",
            'pay_type' => 5,
            'thumb' => $com_cdn . 'pay/yu.png'
        ];
        $duihuan_array =  [
            'name' => "兑换券（￥{$finfo['duihuanquan']}）",
            'pay_type' => 6,
            'thumb' => $com_cdn . 'pay/yu.png'
        ];
        $daijinquan_array =  [
            'name' => "代金券（￥{$finfo['daijinquan']}）",
            'pay_type' => 7,
            'thumb' => $com_cdn . 'pay/yu.png'
        ];
        $jinhuoquan_array =  [
            'name' => "进货券（￥{$finfo['jinhuoquan']}）",
            'pay_type' => 8,
            'thumb' => $com_cdn . 'pay/yu.png'
        ];

        if($goods_type =='huodong'){
            $pay_list[] = $wechat_array;
            $pay_list[] = $money_array;
            $pay_type = 5;
        }elseif($goods_type =='duihuan'){
            $pay_list[] = $duihuan_array;
            $pay_type = 6;
        }elseif($goods_type =='pingjia'){
            $pay_list[] = $wechat_array;
            $pay_list[] = $money_array;
            $pay_list[] = $daijinquan_array;
            $pay_type = 7;
        }elseif($goods_type =='jinhuo'){
            $pay_list[] = $jinhuoquan_array;
            $pay_type = 8;
        }else{
            $pay_list[] = $wechat_array;
            $pay_list[] = $money_array;
            $pay_type = 5;
        }


        $this->data['pay_type'] = $pay_type;
        $this->data['pay_list'] = $pay_list;
        $this->data['info'] = $res['data']['info'];
        return array('status' => 1, 'data' => $this->data);
    }

    //联合支付
    public function pay_union()
    {
        $res = $this->orderModel->getOrderItemOrdernum($this->in['ordernum'], 'home', $this->uinfo['id']);
        $info = $res['data']['info'];

        if (!$info) {
            return array('status' => 0, 'info' => '不存在');
        }
        if ($info['pay_status'] != 0) {
            return array('status' => 0, 'info' => '已支付');
        }
        if ($info['status'] != 0) {
            return array('status' => 0, 'info' => '状态不对');
        }
        if ($info['total'] <= 0) {
            return array('status' => 0, 'info' => '无需支付');
        }

        if (in_array($this->in['pay_type'], array(5,6,7,8))) {
            $cate_array = ['5'=>2,'6'=>2,'7'=>7,'8' =>6];
            $field_array = ['5'=>'money','6'=>'duihuanquan','7'=>'daijinquan','8' =>'jinhuoquan'];
            $cate = $cate_array[$this->in['pay_type']]??2;
            $field = $field_array[$this->in['pay_type']]??'money';
            return $this->money_pay($info,$this->in['pay_type'],$cate,$field);
        }
        if (in_array($this->in['pay_type'], array(4))) {
            return $this->wx_pay($info);
        }
        return array('status' => 0, 'info' => '请选择支付方式');
    }

    //微信支付
    public function wx_pay($order_info)
    {
        $wx_pay_type = C('pay_type');
        $notify_url = C('wapurl') . "/mallapi/Pay/mall";
        tool()->classs('pay/Pay');
        $Pay = new \Pay($wx_pay_type);

        $res = $Pay->pay([
            'appid' => C('appid'),
            'total' => $order_info['total'],
            'openid' => $this->uinfo['openid'],
            'ordernum' => $order_info['ordernum'],
            'notify_url' => $notify_url,
        ]);

        $order_id = $order_info['order_id'];
        $goods_id = Db::name('mall_order_goods') -> where('order_id',$order_id) -> value('goods_id');

        $category_id = Db::name('mall_goods') -> where('goods_id',$goods_id) -> value('category_id');
        $goods_type = Db::name('mall_goods_category') -> where('category_id',$category_id) -> value('english');//商品类型

        if ($res['status'] == 1) {
//            unset($res['status']);
            $res['pay_type'] = 4;
            $res['goods_type'] = $goods_type;
            $res['data']['pay_status'] = 0;
            return $res;
        } else {
            return ['status' => 0, 'info' => $res['info']];
        }

    }

    //消费金支付
    private function money_pay_new($info)
    {
        $finfo = $this->model->getFinance($this->uinfo['id']);
        if (bccomp($finfo['consumption'], $info['total']) == -1) {
            return array('status' => 0, 'info' => '消费金不足');
        }
        $a = date('Y-m-d H:i:s');
        $this->model->handleUser('money', $this->uinfo['id'], $info['total'], 2, array('ordernum' => $info['ordernum'], 'cate' => '8'));
        $info['pay_type'] = 5;
        $orderLogic = new \app\mall\logic\Order();
        $orderLogic->pay_success($info);
        $msg = '购买成功';
//        if($info['is_stock'] == 2){
//            $msg = '您上级库存不足';
//        }

        $order_id = $info['order_id'];
        $goods_id = Db::name('mall_order_goods') -> where('order_id',$order_id) -> value('goods_id');

        $category_id = Db::name('mall_goods') -> where('goods_id',$goods_id) -> value('category_id');
        $goods_type = Db::name('mall_goods_category') -> where('category_id',$category_id) -> value('english');//商品类型

        return array('status' => 1, 'info' => $msg, 'data' => array('pay_type' => 5,
            'ordernum' => $info['ordernum'],
            'order_id' => $info['order_id'],
            'pay_time' => $a,
            'is_pay' => 1,
            'type' => $info['type'],
            'goods_type' => $goods_type,
        ));
    }


    //余额支付
    private function money_pay($info,$pay_type = 5,$cate=2,$field='money')
    {
        $finfo = $this->model->getFinance($this->uinfo['id']);
        if (bccomp($finfo[$field], $info['total']) == -1) {
            return array('status' => 0, 'info' => '余额不足');
        }
        $a = date('Y-m-d H:i:s');
        $this->model->handleUser($field, $this->uinfo['id'], $info['total'], 2, array('ordernum' => $info['ordernum'], 'cate' => $cate));
        $info['pay_type'] = $pay_type;
        $orderLogic = new \app\mall\logic\Order();
        $orderLogic->pay_success($info);
        $msg = '购买成功';
//        if($info['is_stock'] == 2){
//            $msg = '您上级库存不足';
//        }

        $order_id = $info['order_id'];
        $goods_id = Db::name('mall_order_goods') -> where('order_id',$order_id) -> value('goods_id');

        $category_id = Db::name('mall_goods') -> where('goods_id',$goods_id) -> value('category_id');
        $goods_type = Db::name('mall_goods_category') -> where('category_id',$category_id) -> value('english');//商品类型

        return array('status' => 1, 'info' => $msg, 'data' => array('pay_type' => $pay_type,
            'ordernum' => $info['ordernum'],
            'order_id' => $info['order_id'],
            'pay_time' => $a,
            'is_pay' => 1,
            'type' => $info['type'],
            'goods_type' => $goods_type,
        ));
    }
    public function send_item()
    {
        $where = [];
        if ($this->in['order_id']) {
            $where[] = ['order_id', '=', $this->in['order_id']];
        }
        if ($this->in['ordernum']) {
            $where[] = ['ordernum', '=', $this->in['ordernum']];
        }
        if (!$where) {
            return ['status' => 0, 'info' => '参数错误'];
        }
        $where[] = ['user_id', '=', $this->uinfo['id']];
        $order_id = $this->orderModel->removeOption()->where($where)->value('order_id');
        if (!$order_id) {
            return ['status' => 0, 'info' => '不存在'];
        }
        $this->data['list'] = $this->orderModel->getSendInfo($order_id);
        return ['status' => 1, 'data' => $this->data];
    }
}

