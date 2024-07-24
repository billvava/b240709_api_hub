<?php

namespace app\mall\model;

use app\common\model\User;
use app\common\model\Weixin;
use think\facade\Db;
use think\facade\Log;
use think\Model;

class OrderHandle
{

    public $name = 'mall_order';
    //根据ordernum获取
    public function get_info_num($ordernum)
    {
        $where = array('ordernum' => $ordernum);
        return Db::name('mall_order')->where($where)->find();
    }

    public function get_info_id($order_id)
    {
        $where = array('order_id' => $order_id);
        return Db::name('mall_order')->where($where)->find();
    }


    /**
     * 订单的异步处理
     * @param type $ordernum 异步接收到的订单号
     * @param type $tro_no 异步接收到的流水号
     * @param type $order_tatal 异步接收到的金额
     * @param type $pay_type 支付方式  4=微信，1=支付宝
     */
    public function pay_handle($ordernum, $tro_no, $order_tatal, $pay_type)
    {
        if (!$ordernum) {
            Log::info('缺少ordernum参数');
            exit();
        }
        if (!$tro_no) {
            Log::info('缺少tro_no参数');
            exit();
        }
        if (!$order_tatal) {
            Log::info('缺少order_tatal参数');
            exit();
        }
        if (!$pay_type) {
            Log::info('缺少pay_type参数');
            exit();
        }
        $M_Order = new \app\mall\model\Order();
        $order_info = $M_Order->where("ordernum", $ordernum)->find();
        if ($order_info) {
            $order_info = $M_Order->toArray();
        }

        if (!$order_info) {
            Log::info("{$ordernum}这笔订单不存在");
            echo "success";
            exit();
        }
        if ($order_info['pay_status'] == 1) {
            Log::info("{$ordernum}这笔订单已经付款了");
            echo "success";
            exit();
        }
        if ($order_info['total'] != $order_tatal) {
            Log::info("{$ordernum}订单金额不相等,数据库：{$order_info['total']},异步的：{$order_tatal}");
            echo "success";
            exit();
        }

        Db::startTrans();
        try {
            $orderUpdate = array(
                'pay_status' => 1,
                'pay_type' => $pay_type,
                'pay_time' => date('Y-m-d H:i:s'),
                'trade_no' => $tro_no
            );
            $save_res = $M_Order->where(array('ordernum' => $order_info['ordernum']))->save($orderUpdate);
            if ($save_res) {
                Db::commit();
                echo "success";
            } else {
                Db::rollback();
                die;
            }
        } catch (\Exception $e) {
            // 回滚事务
//                p($e->getMessage());
            Db::rollback();
        }
    }

    //订单支付成功
    public function pay_success($order_info)
    {
        //用户统计
        $DB_PREFIX = DB_PREFIX;
        $t = date('Y-m-d H:i:s');
        $sql = "update {$DB_PREFIX}user_ext set mall_total= mall_total + {$order_info['total']},mall_new_time='{$t}',mall_order_num=mall_order_num+1 where user_id={$order_info['user_id']}";
        tool()->classs('Mysql_query');
        $Mysql_query = new \Mysql_query();
        $Mysql_query->query($sql);
        $sql = "update {$DB_PREFIX}user_ext set mall_order_avg=(mall_total/mall_order_num)  where user_id={$order_info['user_id']}";
        $Mysql_query->query($sql);

        $M_Order = new \app\mall\model\Order();
        $save = [
            'pay_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'pay_status' => 1,
            'status' => 1
        ];
        if ($order_info['pay_type']) {
            $save['pay_type'] = $order_info['pay_type'];
        }
        if ($order_info['trade_no']) {
            $save['trade_no'] = $order_info['trade_no'];
        }

        $M_Order->removeOption()->where(['order_id' => $order_info['order_id']])->save($save);


        //执行商品活动回调，例如拼团
        $Discount = new \app\common\logic\Discount();
        $Discount->config([
            'data' => ['order_info' => $order_info],
        ]);
        $Discount->handle('goods', 'pay_success');
        $Discount->config([
            'data' => ['order_info' => $order_info],
        ]);
        $Discount->handle('act', 'pay_success');
        $M_Order->add_log($order_info['order_id'], array('cate' => 2));
        if (C('feie_print') == 1) {
            $this->print_feie($order_info);
        }
    }

    //飞蛾打印
    public function print_feie($order_info)
    {
        $feie_sn = C('feie_sn');
        if (!$feie_sn) {
            return array('status' => 0, 'info' => '缺少sn');
        }
        $time = time();         //请求时间
        $sn = $feie_sn; //打印机编号
        $user = "429001807@qq.com";
        $ukey = "平台密钥";

        header("Content-type: text/html; charset=utf-8");
        tool()->classs('HttpClient');
        // 打印内容  预计宽度16个中文字符
        $printNumber = 1;
        $content = '';
        $content .= "<B>{$order_info['send_type']}</B><BR>";
        $content .= "订单号:{$order_info['ordernum']}<BR>";
        $content .= "下单时间:{$order_info['create_time']}<BR>";
        $content .= "--------------------------------<BR>";
        $goods_list = (new \app\mall\model\Order())->getGoods($order_info['order_id']);
        foreach ($goods_list as $v) {
            $no_zw = substr_count($v['name'], '+') + substr_count($v['name'], ')') + substr_count($v['name'], '(') + substr_count($v['name'], ' ') + substr_count($v['name'], '1');
            $len = mb_strlen($v['name'], 'utf-8');
            $len = ($len - $no_zw) * 2 + $no_zw;
            $num = 20 - $len;
            $v['param'] = json_decode($v['param'], true);
            $p = "";
            foreach ($v['param'] as $gp) {
                if ($gp['v']) {
                    $p .= "【{$gp['v']}】";
                }
            }
            $v['name'] = $v['name'] . $p . str_repeat(" ", $num);

            $v['num'] = " X " . $v['num'];
            $content .= "<L>{$v['name']} {$v['num']}  {$v['total_price']}</L><BR>";
        }
        $content .= "--------------------------------<BR>";
        $content .= "商品价：{$order_info['goods_total']}元<BR>";
        if ($order_info['delivery_total'] > 0) {
            $content .= "物流费：{$order_info['delivery_total']}元<BR>";
        }
        $pay_type = lang('pay_type');
        $pay_status = lang('pay_status');
        $content .= "订单金额：{$order_info['total']}元<BR>";
        $content .= "支付状态：{$pay_status[$order_info['pay_status']]} {$pay_type[$order_info['pay_type']]}<BR>";
        $arr = array(
            array('field' => 'pay_time', 'name' => '支付时间'),
            array('field' => 'username', 'name' => '用户名'),
            array('field' => 'linkman', 'name' => '联系人'),
            array('field' => 'tel', 'name' => '电话'),
            array('field' => 'address', 'name' => ''),
            array('field' => '客户留言', 'name' => 'message'),
        );
        foreach ($arr as $v) {
            if ($order_info[$v['field']]) {
                $content .= "{$v['name']}：{$order_info[$v['field']]}<BR>";
            }
        }
        $msgInfo = array(
            'user' =>$user,
            'stime' => $time,
            'sig' => sha1($user . $ukey . $time),
            'apiname' => 'Open_printMsg',
            'sn' => $sn,
            'content' => $content,
            'times' => $printNumber
        );
        $client = new \HttpClient('api.feieyun.cn', 80);
        if (!$client->post('/Api/Open/', $msgInfo)) {
            return array('status' => 0, 'info' => '发送失败');
        } else {
            $result = $client->getContent();
            $res = json_decode($result, true);
            return array('status' => 1, 'info' => '发送成功');
        }
    }


    /**
     * 关闭订单
     * @param type $order_id
     * @param type $map
     * @return type
     */
    public function close($order_id, $map)
    {

        if ($order_id == 0) {
            $order_info = $this->get_info_num($map['ordernum']);
            $order_id = $order_info['order_id'];
        } else {
            $order_info = $this->get_info_id($order_id);
        }

        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if (!in_array($map['type'], array('home', 'admin'))) {
            return array('status' => 0, 'info' => '缺少参数type');
        }
        if ($map['type'] == 'home' && $order_info['user_id'] != $map['user_id']) {
            return array('status' => 0, 'info' => '这个订单不属于你');
        }
        if ($order_info['status'] > 0 && in_array($map['type'], array('home'))) {
            return array('status' => 0, 'info' => '该订单不能关闭');
        }
        if ($order_info['status'] == 4) {
            return array('status' => 0, 'info' => '该订单已关闭');
        }
        Db::name('mall_order')->removeOption()->where(array('order_id' => $order_id))->save(array('status' => 4));
        if ($order_info['coupon_id']) {
            Db::name('mall_coupon')->where(array('id' => $order_info['coupon_id']))->save(['status' => 0]);
        }
        $userModel = new User();
//        if ($order_info['money']) {
//            $userModel->handleUser('money', $order_info['user_id'], $order_info['money'], 1, array('cate' => 3, 'ordernum' => $order_info['ordernum']));
//        }
        if ($order_info['dot']) {
            $userModel->handleUser('dot', $order_info['user_id'], $order_info['dot'], 1, array('cate' => 3, 'ordernum' => $order_info['ordernum']));
        }
        if (C('weixin_msg') == 1) {
            $commonWx = new Weixin();
            $msg = "您的订单【{$order_info['ordernum']}】已经关闭。";
            $openid = (new User())->getOpenid($order_info['user_id']);
            $openid && $commonWx->sendTxt($openid, $msg);
        }
        $this->add_log($order_id, array('cate' => 7));
        return array('status' => 1, 'info' => '关闭成功');
    }

    /**
     * 软删除
     * @param type $order_id
     * @param type $map
     * @return type
     */
    public function del($order_id, $map)
    {
        $order_info = $this->get_info_id($order_id);
        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if (!in_array($map['type'], array('home', 'admin'))) {
            return array('status' => 0, 'info' => '缺少参数type');
        }
        if ($map['type'] == 'home' && $order_info['user_id'] != $map['user_id']) {
            return array('status' => 0, 'info' => '这个订单不属于你');
        }
        if ($order_info['status'] > 0) {
            $field = 'is_u_del';
            if ($map['type'] == 'admin') {
                $field = 'is_a_del';
            }
            $this->removeOption()->where(array('order_id' => $order_id))->save(array($field => 1));
            $this->add_log($order_id, array('cate' => 10, 'msg' => '软删除'));
            return array('status' => 1, 'info' => '删除成功');
        } else {
            return array('status' => 0, 'info' => '该订单不能删除');
        }
    }

    /**
     * 永久删除订单
     * @param type $order_id
     * @param type $map
     * @return type
     */
    public function forever_del($order_id, $map)
    {
        $order_info = $this->get_info_id($order_id);
        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if (!in_array($map['type'], array('home', 'admin'))) {
            return array('status' => 0, 'info' => '缺少参数type');
        }
        if ($map['type'] == 'home' && $order_info['user_id'] != $map['user_id']) {
            return array('status' => 0, 'info' => '这个订单不属于你');
        }
        if ($order_info['status'] != 4) {
            return array('status' => 0, 'info' => '请先关闭订单才能删除');
        }
        Db::name('mall_order_bro')->where(array('order_id' => $order_id))->delete();
        Db::name('mall_order_feedback')->where(array('order_id' => $order_id))->delete();
        Db::name('mall_order_goods')->where(array('order_id' => $order_id))->delete();
        Db::name('mall_order_log')->where(array('order_id' => $order_id))->delete();
        Db::name('mall_order_send')->where(array('order_id' => $order_id))->delete();
        Db::name('mall_order')->where(array('order_id' => $order_id))->delete();
        return array('status' => 1, 'info' => '删除成功');
    }

    /**
     * 修改价格
     * @param type $order_id
     * @param type $total
     * @return type
     */
    public function up_total($order_id, $total)
    {
        $order_info = $this->get_info_id($order_id);
        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if ($order_info['pay_status'] == 1) {
            return array('status' => 0, 'info' => '已支付订单不修改价格');
        }
        if ($order_info['status'] == 3) {
            return array('status' => 0, 'info' => '已完成订单不修改价格');
        }
        if ($total <= 0) {
            return array('status' => 0, 'info' => '新价格不能小于0.01');
        }
        $newOrdernum = get_ordernum();
        $this->where(array('order_id' => $order_id))->save(array('total' => $total, 'ordernum' => $newOrdernum));
        Db::name('user_bro')->where(array('ordernum' => $order_info['ordernum'], 'user_id' => $order_info['user_id']))->save(array('ordernum' => $newOrdernum));
        Db::name('user_dot')->where(array('ordernum' => $order_info['ordernum'], 'user_id' => $order_info['user_id']))->save(array('ordernum' => $newOrdernum));
        Db::name('user_money')->where(array('ordernum' => $order_info['ordernum'], 'user_id' => $order_info['user_id']))->save(array('ordernum' => $newOrdernum));
        $this->add_log($order_id, array(
            'msg' => "价格{$order_info['total']}修改为{$total},订单号{$order_info['ordernum']}修改为{$newOrdernum}",
            'cate' => 10
        ));
        if (C('weixin_msg') == 1) {
            $commonWx = new Weixin();
            $msg = "您的订单价格修改为{$total}元。";
            $openid = (new User())->getOpenid($order_info['user_id']);
            $openid && $commonWx->sendTxt($openid, $msg);
        }
        return array('status' => 1, 'info' => '修改完成');
    }

    /**
     * 退款
     * @param type $order_id
     * @param type $refund_money
     * @return type
     */
    public function refund($order_id, $refund_money)
    {
        $order_info = $this->get_info_id($order_id);
        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if ($refund_money <= 0) {
            return array('status' => 0, 'info' => '请填写退款金额');
        }
        if (($refund_money + $order_info['refund_total']) > $order_info['total']) {
            return array('status' => 0, 'info' => '退款金额不能超过实际付款的金额');
        }
        //微信退款
        $flag = 0;
        if ($order_info['pay_type'] == 4) {
            $res = (new Weixin())->refund($order_info['trade_no'], $order_info['total'], $refund_money);
            if ($res['status'] == 1) {
                $flag = 1;
            }else{
                return ['status'=>0,'info'=>$res['info']];
            }
        }

        if ($order_info['pay_type'] == 5) {
            $userModel = new \app\common\model\User();
            $userModel->handleUser('money', $order_info['user_id'], $refund_money, 1, array('cate' => 3, 'ordernum' => $order_info['ordernum']));
            $flag = 1;
        }

        if ($flag == 1) {
            Db::name('mall_order')->where(array('order_id' => $order_info['order_id']))->inc('refund_total', $refund_money)->update();
            Db::name('mall_order')->where(array('order_id' => $order_info['order_id']))->save([
                'refund_status' => 2,
                'pay_status' => 2,
                'after_status'=>3
            ]);
            $this->add_log($order_id, array('cate' => 6));
            $res = ['status' => 1, 'info' => lang('s')];
        }else{
            $res = ['status' => 0, 'info' =>'未识别的支付方式' ];

        }

        return $res;
    }

    /**
     * 发货
     * @param type $order_id
     * @return type
     */
    public function send($order_id, $map = array())
    {
        $order_info = $this->get_info_id($order_id);
        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if ($order_info['status'] != 1) {
            return array('status' => 0, 'info' => '状态不对');
        }
        //快递
        if ($map['send_type'] == 1) {
            if (!$map['express_code']) {
                return array('status' => 0, 'info' => '请输入快递单号');
            }
            $com_info = Db::name('mall_express_company')->where(array('expresskey' => $map['express_key']))->cache(true)->find();
            if (!$com_info) {
                return array('status' => 0, 'info' => '请选择快递公司');
            }
        } else {
            unset($map['express_key']);
            unset($map['express_code']);
        }

        Db::name('mall_order_send')->insert(
            array('time' => date('Y-m-d H:i:s'),
                'order_id' => $order_id,
                'express_name' => $com_info['expressname'],
                'express_key' => $map['express_key'],
                'express_code' => $map['express_code'],
                'send_type' => $map['send_type'],
            ));
        Db::name('mall_order')->where(array('order_id' => $order_id))->save(['delivery_status' => 1, 'status' => 2, 'delivery_time' => date('Y-m-d H:i:s')]);
        if (C('weixin_msg') == 1) {
            $commonWx = new Weixin();
            $send_type = lang('send_type');
            $send_type_str = $map['send_type'] ? "【" . $send_type[$map['send_type']] . "】" : "";
            $msg = "您的订单【{$order_info['ordernum']}】已发货，{$send_type_str} {$express_name} {$express_code}。";
            $openid = (new User())->getOpenid($order_info['user_id']);
            $openid && $commonWx->sendTxt($openid, $msg);
        }
        $this->add_log($order_id, array('cate' => 3));
        return array('status' => 1, 'info' => '操作成功');
    }

    /**
     * 已支付
     * @param type $order_id
     * @param type $pay_type
     * @return type
     */
    public function set_pay($order_id, $pay_type)
    {
        $order_info = $this->get_info_id($order_id);
        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if ($order_info['pay_status'] > 0) {
            return array('status' => 0, 'info' => '订单已付款');
        }
        if ($order_info['status'] > 0) {
            return array('status' => 0, 'info' => '状态不对');
        }
        Db::name($this->name)->where(array('order_id' => $order_id))->save(array('pay_status' => 1, 'status' => 1, 'pay_type' => $pay_type, 'pay_time' => date('Y-m-d H:i:s')));
        $this->add_log($order_id, ['cate' => 11]);
        return array('status' => 1, 'info' => '操作成功');
    }

    /**
     * 已完成
     * @param type $order_id
     * @param type $map
     * @return type
     */
    public function finish($order_id, $map)
    {
        if ($order_id == 0) {
            $order_info = $this->get_info_num($map['ordernum']);
            $order_id = $order_info['order_id'];
        } else {
            $order_info = $this->get_info_id($order_id);
        }

        if (!$order_info) {
            return array('status' => 0, 'info' => '订单不存在');
        }
        if (!in_array($map['type'], array('home', 'admin'))) {
            return array('status' => 0, 'info' => '缺少参数type');
        }
        if ($map['type'] == 'home' && $order_info['user_id'] != $map['user_id']) {
            return array('status' => 0, 'info' => '这个订单不属于你');
        }
        if ($order_info['status'] == 3) {
            return array('status' => 0, 'info' => '订单已完成');
        }
        if (!in_array($order_info['status'], [1, 2])) {
            return array('status' => 0, 'info' => '状态不对');
        }
        Db::name($this->name)->where(array('order_id' => $order_id))->save(array('status' => 3));
        $this->add_log($order_id, array('cate' => 4));
        $this->send_bro($order_info);
        $this->send_dot($order_info);
        return array('status' => 1, 'info' => '操作成功');
    }

    //添加积分
    public function send_dot($order_info)
    {
        if (bccomp($order_info['goods_total'], 0) == 1 && C('dot_mall') == 1) {
            $dot = floor($order_info['total']);
            if ($dot > 0) {
                (new User())->handleUser('dot', $order_info['user_id'], $dot, 1, array('cate' => 4, 'ordernum' => $order_info['ordernum']));
            }
        }
    }

    //发放佣金
    public function send_bro($order_info)
    {

        $broInfo = Db::name('mall_order_bro')->where(array('order_id' => $order_info['order_id'], 'status' => 0))->find();
        if ($broInfo) {
            // $Weixin = new Weixin();
            $userModel = (new User());
            if ($broInfo['bro1'] && $broInfo['pid1']) {
                // $pidInfo = $userModel->getUserInfo($broInfo['pid1']);
                $userModel->handleUser('bro', $broInfo['pid1'], $broInfo['bro1'], 1, array('cate' => 4, 'ordernum' => $order_info['ordernum']));
                //$Weixin->sendTxt($pidInfo['openid'], "分销订单{$order_info['ordernum']}已经确认完成，您因此获得了{$broInfo['bro1']}元佣金");
            }
            if ($broInfo['bro2'] && $broInfo['pid2']) {
                // $pidInfo = $userModel->getUserInfo($broInfo['pid2']);
                $userModel->handleUser('bro', $broInfo['pid2'], $broInfo['bro2'], 1, array('cate' => 4, 'ordernum' => $order_info['ordernum']));
                // $Weixin->sendTxt($pidInfo['openid'], "分销订单{$order_info['ordernum']}已经确认完成，您因此获得了{$broInfo['bro2']}元佣金");
            }
            if ($broInfo['bro3'] && $broInfo['pid3']) {
                //  $pidInfo = $userModel->getUserInfo($broInfo['pid3']);
                $userModel->handleUser('bro', $broInfo['pid3'], $broInfo['bro3'], 1, array('cate' => 4, 'ordernum' => $order_info['ordernum']));
                //  $Weixin->sendTxt($pidInfo['openid'], "分销订单{$order_info['ordernum']}已经确认完成，您因此获得了{$broInfo['bro3']}元佣金");
            }
            Db::name('mall_order_bro')->where(array('order_id' => $order_info['order_id']))->save(array('status' => 1));
            cache("getBroDetail{$order_info['order_id']}", null);
        }
    }

    //订单日志
    public function add_log($order_id, $map = array())
    {
        $add = array('order_id' => $order_id, 'msg' => '', 'time' => date('Y-m-d H:i:s'));
        if ($map['msg']) {
            $add['msg'] = $map['msg'];
        }
        if ($map['type']) {
            $add['type'] = $map['type'];
        }
        if ($map['cate']) {
            $add['cate'] = $map['cate'];
        }
        if ($map['status'] !== '') {
            $add['status'] = $map['status'];
        }
        return Db::name('mall_order_log')->insertGetId($add);
    }

}
