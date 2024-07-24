<?php

namespace app\mall\model;

use app\admin\model\Shop;
use app\common\model\User;
use app\common\model\Weixin;
use think\facade\Db;
use think\Model;

class Order extends Model
{

    protected $name = 'mall_order';
    public $orderHandle;

    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->orderHandle = new OrderHandle();
    }

    public function get_pk()
    {
        return 'order_id';
    }

    //检测订单的拥有者
    public function checkOrderOwner($order_id, $user_id)
    {
        if (!$order_id || !$user_id) {
            return false;
        }
        $user_id2 = $this->where(array('order_id' => $order_id))->value('user_id');
        if ($user_id != $user_id2) {
            return false;
        }
        return true;
    }

    public function handle_info($info)
    {
        $data = array();

//        $feedback_status = lang('feedback_status');
//        $data['feedback_status'] = $feedback_status[$info['feedback_status']];
//        if ($info['feedback_status'] == 2) {
//            $data['feedback_remark'] = Db::name('mall_order_feedback')->where(array('order_id' => $info['order_id']))->value('remark');
//        }
        $data['show_feed'] = 0;
        if ($info['is_pay'] == 1 && $info['feedback_status'] == 0) {
            $data['show_feed'] = 1;
        }

        $mall_close_minute = C('mall_close_minute');
        //关闭的时间
        $info['auto_close_time'] = date('Y/m/d H:i:s', (strtotime($info['create_time']) + $mall_close_minute * 60));
        //剩余多少秒关闭
        $info['auto_close_second'] =  -1;
        $info['auto_close_second_str'] = '';
        if($info['status'] == 0){
            $info['auto_close_second'] = strtotime($info['auto_close_time']) - time();
            if ($mall_close_minute == 0  ||  $info['auto_close_second']<=0) {
                $info['auto_close_second'] = 0;
            }
        }



        $data['goods_list'] = $this->getGoods($info['order_id']);
        $total_cost = 0;
        $total_profit = 0;
        foreach ($data['goods_list'] as $key => $value) {
           $total_cost +=  $value['total_cost'];
           $total_profit +=  $value['total_profit'];
        }
        $orderLogic = new \app\mall\logic\Order();
        $data['status_data'] = $orderLogic->getOption($info);
        $data['btn_data'] = $orderLogic->handle_btn($info);
        $data['app_btn'] = $orderLogic->handle_app_btn($info);

        if ($info['is_send'] == 1 && $info['send_type'] == 1) {
            $data['express_info'] = $this->getSendInfo($info['order_id']);
        }
        if($info['shop_id']){
            $sinfo = (new Shop())->getInfo($info['shop_id'],true);
            $info['shop_name'] = $sinfo['name'];
            $info['shop_thumb'] = $sinfo['thumb_url'];

        }
        $info['total_cost']=$total_cost;
        $info['total_profit']=$total_profit;
        $data['info'] = $info;


        return array('status' => 1, 'data' => $data);
    }

    public function getOrderItemOrdernum($ordernum, $type, $user_id = null)
    {
        if (!in_array($type, array('home', 'admin'))) {
            return array('status' => 0, 'info' => '缺少参数type');
        }
        $info = $this->getInfoForNum($ordernum);

        if (!$info) {
            return array('status' => 0, 'info' => '不存在');
        }
        // if ($type == 'home' && $info['user_id'] != $user_id) {
        //     return array('status' => 0, 'info' => '这个订单不属于你');
        // }
        return $this->handle_info($info);
    }

    //订单详情
    public function getOrderItem($order_id, $type, $user_id = null)
    {
        if (!in_array($type, array('home', 'admin'))) {
            return array('status' => 0, 'info' => '缺少参数type');
        }
        $info = $this->getInfo($order_id);
        if (!$info) {
            return array('status' => 0, 'info' => '不存在');
        }
        if ($type == 'home' && $info['user_id'] != $user_id) {
            return array('status' => 0, 'info' => '这个订单不属于你');
        }
        return $this->handle_info($info);
    }

    //根据order_id获取
    public function getInfo($order_id, $cache = false)
    {
        $where = array('order_id' => $order_id);
        return Db::name('mall_order')->where($where)->cache($cache)->find();
    }

    //根据ordernum获取
    public function getInfoForNum($ordernum)
    {
        $where = array('ordernum' => $ordernum);
        return Db::name('mall_order')->where($where)->find();
    }

    //获取list
    public function get_data($in = array())
    {
        $where = array();
        $cache_name = "order_data_" . md5(serialize($in));
        if ($in['cache'] == true) {
            $data = cache($cache_name);
            if ($data) {
                return $data;
            }
        }
        $like_arr = array('ordernum', 'username', 'province', 'city', 'country', 'address', 'linkman', 'tel', 'admin_remark');
        $status_arr = array('delivery_type', 'p_ordernum', 'comment_status', 'user_id', 'status', 'type', 'is_u_del', 'is_a_del', 'after_status', 'ip', 'order_id', 'pay_type', 'shop_id', 'pay_status','is_stock','pid');
        foreach ($in as $k => $v) {
            if (in_array($k, $like_arr) && $v) {
                $where[] = array('a.' . $k, 'like', "%{$v}%");
            }
            if (in_array($k, $status_arr) && $v !== '') {
                if (is_array($v)) {
                    $where[] = ['a.' . $k, $v[0], $v[1]];
                } else if ($v === 'Y') {
                    $where[] = ['a.' . $k, '>', 0];
                } else {
                    $where[] = ['a.' . $k, '=', $v];
                }
            }
            if ($k == 'send_type') {
                if ($v == 4) {
                    $where[] = array('a.' . $k, '<>', 2);
                } else {
                    $where[] = ['a.' . $k, '=', $v];
                }
            }
        }
//        sqlListen();
        if ($in['ordernums']) {
            $where[] = array('a.ordernum', 'in', $in['ordernums']);
        }
        if ($in['order_ids']) {
            $where[] = array('a.order_id', 'in', $in['order_ids']);
        }
        if ($in['mul_time']) {
            $mul_times = explode(' - ', $in['mul_time']);
            if (strtotime($mul_times[0]) > 0 && strtotime($mul_times[1]) > 0) {
                $where[] = array('create_time', 'between', array($mul_times[0], "{$mul_times[1]} 23:59:59"));
            }
        }
        if ($in['mul_pay_time']) {
            $mul_times = explode(' - ', $in['mul_pay_time']);
            if (strtotime($mul_times[0]) > 0 && strtotime($mul_times[1]) > 0) {
                $where[] = array('pay_time', 'between', array($mul_times[0], "{$mul_times[1]} 23:59:59"));
            }
        }
        if ($in['order']) {
            $order = $in['order'];
        } else {
            $order = "a.order_id desc";
        }

        //是否需要分页
        $page_num = $in['page_num'] ? $in['page_num'] : 10;
       
        if ($in['page_type']) {
            $count = $this->alias('a')->where($where)->count();
            if ($in['page_type'] == 'admin') {
                tool()->classs('PageForAdmin');
                $Page = new \PageForAdmin($count, $page_num);
            } else {
                tool()->classs('Page');
                $Page = new \Page($count, $page_num);
            }
            $data['page'] = $Page->show();
            $data['total'] = $count;
            $start = $Page->firstRow;
        } else {
            $page = $in['page'] ? $in['page'] : 1;
            $start = ($page - 1) * $page_num;
        }
        $field = "a.*";
        if ($in['field']) {
            $field = $in['field'];
            $goods_key = array_search('goods_list', $field);
            if ($goods_key !== false) {
                $field[$goods_key] = "order_id as goods_list";
            }
        }
        $data['list'] = [];
        $a = $this
            ->removeOption()
            ->alias('a')
            ->field($field)
            ->where($where)
            ->limit($start, $page_num)
            ->order($order)
            ->select();
        if ($a) {
            $data['list'] = $a->toArray();
        }

        $pay_type = lang('pay_type');
        $is_pay = lang('is_pay');
        $is = lang('is');
        $order_is_send = lang('order_is_send');
        $send_type = lang('send_type');
        $orderLogic = new \app\mall\logic\Order();
        foreach ($data['list'] as &$v) {
            if ($in['xls'] == 1) {
                foreach ($field as $fv) {
                    if ($fv == 'order_id as goods_list') {
//                        $v['goods_list'] = $this->getGoodsStr($v['goods_list']);
                        $v['goods_list'] = $this->getGoods($v['goods_list']);
                    
                    } else if ($fv == 'pay_status') {
                        $v['pay_status'] = $is_pay[$v['pay_status']];
                    } else if ($fv == 'pay_type') {
                        $v['pay_type'] = $pay_type[$v['pay_type']];
                    } else if ($fv == 'send_type') {
                        $v['send_type'] = $send_type[$v['send_type']];
                    } else if ($fv == 'is_send') {
                        $v['delivery_status'] = $order_is_send[$v['delivery_status']];
                    } else if ($fv == 'ordernum') {
                        $v['ordernum'] = "{$v['ordernum']}";
                    } else if ($fv == 'tel') {
                        $v['tel'] = "{$v['tel']}";
                    } else if ($fv == 'create_time') {
                        $v['create_time'] = date('Y年m月d号H点i分', strtotime($v['create_time']));
                    } else if ($fv == 'pay_time') {
                        $s = strtotime($v['pay_time']);
                        if ($s > 0) {
                            $v['pay_time'] = date('Y年m月d号H点i分', $s);
                        } else {
                            $v['pay_time'] = "";
                        }
                    }
                }
            } else {

                $afters = [
                    0=>'未申请退款',
                    1=>'申请退款中',
                    2=>'等待退款',
                    3=>'退款成功',
                ];
                $v['after_status_str'] = $afters[$v['after_status']];
                $no_cache = $in['no_cache'] ? false : true;
                $v['goods_list'] = $this->getGoods($v['order_id'], $no_cache);

                if ($in['get_status'] == 1) {
                    $v['status_data'] = $orderLogic->getOption($v);
                    $v['status_btn'] = $orderLogic->handle_btn($v);
                    $v['app_btn'] = $orderLogic->handle_app_btn($v);
                }
                if ($in['page_type'] == 'admin') {
                    $v['admin_btn'] = $orderLogic->admin_btn($v);
                }
                $v['auto_close_second'] = -1;
                $v['auto_close_second_str'] = '';

                if($v['status'] == 0){
                    $mall_close_minute = C('mall_close_minute');
                    //关闭的时间
                    $v['auto_close_time'] = date('Y/m/d H:i:s', (strtotime($v['create_time']) + $mall_close_minute * 60));
                    //剩余多少秒关闭
                    $v['auto_close_second'] = strtotime($v['auto_close_time']) - time();
                    if ($mall_close_minute == 0 ||  $v['auto_close_second']<=0) {
                        $v['auto_close_second'] = 0;
                    }
                }
                $shop_info = (new Shop())->getInfo($v['shop_id'],true);
                $v['shop_info'] = [];
                if($shop_info){
                    $v['shop_info'] = [
                        'address'=>$shop_info['address'],
                        'thumb'=>$shop_info['thumb_url'],
                        'name'=>$shop_info['name'],

                    ];
                }

            }
        }
        if ($in['cache'] == true) {
            cache($cache_name, $data);
        }
        return $data;
    }

    //获取商品列表
    public function getGoods($order_id, $cache = true)
    {
        $name = "mall_order_goods_{$order_id}";
        $data = cache($name);
        if (!$data || !$cache) {
            $after_status = lang('after_status');
            $data = Db::name('mall_order_goods')->where(array('order_id' => $order_id))->select()->toArray();
            $cost = 0;
            foreach ($data as &$v) {
                $v['can_after'] = 0;
                if ($pay_status == 1 && $v['after_status'] == 0) {
                    $v['can_after'] = 1;
                }
                $v['after_status_txt'] = $after_status[$v['after_status']];

                $v['spec'] = json_decode($v['spec'], true);
                $v['thumb'] = get_img_url($v['thumb']);
                // $cost += $v['min_price2'];
            }
            // $data['cost']=$cost;
            cache($name, $data);
        }
        return $data;
    }

    //获取商品列表字符串
    public function getGoodsStr($order_id)
    {
        $list = $this->getGoods($order_id);
        $html = "";
        foreach ($list as $v) {


            $html .= "{$v['name']}{$v['spec_str']} X {$v['num']}";
        }
        return $html;
    }


    //物流
    public function getSendInfo($order_id)
    {
        tool()->func('str');
        $data = Db::name('mall_order_send')->where(array('order_id' => $order_id))->field("express_name,express_key,express_code")->select()->toArray();

        foreach ($data as &$v) {
            if ($v['express_key'] && $v['express_code']) {
                $name = "getExpressInfo{$v['express_code']}";
                $r = cache($name);
                if (!$r) {
                    $apiurl = C('apiurl');
                    $url = "{$apiurl}/api/index/query?type={$v['express_key']}&num={$v['express_code']}&token=" . C('apitoken');
                    $r = json_decode(file_get_contents($url), true);
                    if ($r['status'] == 1) {
                        foreach ($r['data']['list'] as &$vv) {
                            $vv['m1'] = date('m-d', strtotime($vv['time']));
                            $vv['m2'] = date('H:i', strtotime($vv['time']));
                        }
                        $r = $r['data']['list'];
                    } else {
                        $r = [
                            [
                                'time' => '',
                                'content' => '暂无信息'
                            ]
                        ];
                    }
                    cache($name, $r, 3600);
                }
                $v['data'] = $r;
            }
        }
        return $data;
    }


    //获取佣金细节
    public function getBroDetail($order_id)
    {
        $caceName = "getBroDetail{$order_id}";
        $info = cache($caceName);
        if (!$info) {
            $info = Db::name('mall_order_bro')->where(array('order_id' => $order_id))->find();
            if (!$info) {
                $info = 0;
            } else {
                $user = Db::name('user');
                if ($info['pid1']) {
                    $info['name1'] = $user->where(array('id' => $info['pid1']))->cache(true)->value('username');
                }
                if ($info['pid2']) {
                    $info['name2'] = $user->where(array('id' => $info['pid2']))->cache(true)->value('username');
                }
                if ($info['pid3']) {
                    $info['name3'] = $user->where(array('id' => $info['pid3']))->cache(true)->value('username');
                }
            }
            cache($caceName, $info);
        }
        return $info;
    }

    //获取售后信息
    public function get_feedback($order_id)
    {
        $info = Db::name('mall_order_feedback')->where(array(
            'order_id' => $order_id,
        ))->find();
        $feedback_status = lang('feedback_status');
        $feedback_type = lang('feedback_type');
        if ($info) {
            $info['status_str'] = $feedback_status[$info['status']];
            $info['type_str'] = $feedback_type[$info['type']];
        }
        return $info;
    }

    public function get_feedback_log($order_id)
    {
        return Db::name('mall_order_flog')->where(array(
            'order_id' => $order_id,
        ))->order("id desc")->select()->toArray();
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

    /**
     * 获取订单日志
     */
    public function get_log($order_id)
    {
        return Db::name('mall_order_log')->where(array('order_id' => $order_id))->order("id desc")->select()->toArray();
    }

    //用户统计
    public function getUserAnay($user_id, $cache = false)
    {
        $where = array('user_id' => $user_id, 'pay_status' => 1);
        $data['goods_total'] = $this->where($where)->cache($cache)->sum('goods_total') + 0;
        $data['count'] = $this->where($where)->cache($cache)->count();
        return $data;
    }

    /**
     * 订单统计
     */
    public function census($field = "", $incomeWhere = array())
    {
        $arr = array(
            'all_count' => "count(*) as all_count",
            'wait_pay_count' => "count( status=0  or null) as wait_pay_count",
            'wait_send_count' => "count( status=1  or null) as wait_send_count",
            'wait_get_count' => "count( status=2  or null) as wait_get_count",
            'wait_finish_count' => "count( status=2 or null) as wait_finish_count",
            'finish_count' => "count( status=3 or null) as finish_count",
            'wait_com_count' => "count( (status=3 and comment_status=0)  or null) as wait_com_count",

            'close_count' => "count( status=4  or null) as close_count",
            'unline_count' => "count(pay_type=1  or null) as unline_count",
            'after_count' => "count(after_status>0  or null) as after_count",
        );
        $select = [];
        if ($field == "") {
            $select = array_values($arr);
        } else if (is_array($field)) {
            foreach ($field as $k) {
                $select[] = $arr[$k];
            }
        } else {
            $select = array($arr[$field]);
        }
        $select = array_filter($select);

        if (!$select) {
            return null;
        }
        $select = implode(',', $select);
        // 处理where条件
        if ($incomeWhere) {
            foreach ($incomeWhere as $k => $v) {
                $where[] = [$k, '=', $v];
            }
        }
        $where[] = ['type', '=', 1];
        $data = Db::name('mall_order')->where($where)->field($select)->limit(1)->select()->toArray();
        return $data[0];
    }

    //获取分销订单
    public function getSaleOrder($user_id, $page = 1, $date = '')
    {
        if ($page > 0) {
            $_GET['p'] = $page;
        }
        $data = [
            'page' => '',
            'count' => 0,
            'list' => [],
        ];
        $res = Db::name('mall_order_bro')
            ->whereOr(
                ['pid1' => $user_id]
            )->whereOr(
                ['pid2' => $user_id]
            )->whereOr(
                ['pid3' => $user_id]
            )
            ->select()->toArray();
        $orederids = array_column($res, 'order_id');
        if (!$orederids) {
            return $data;
        }
        $rs = array_reduce($res, function ($v, $w) {
            $v[$w["order_id"]] = $w;
            return $v;
        });

        $where[] = ['o.order_id', 'in', $orederids];
        $where[] = ['o.pay_status', '=', 1];
        if ($date) {
            $where[] = ['o.create_time', '>=', "{$date} 00:00:00"];
            $where[] = ['o.create_time', '<=', "{$date} 23:59:59"];
        }
        $where[] = ['o.status', '<>', 4];
        $count = $this->removeOption()->alias('o')->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 10);
        $data = array();
        $order = "o.order_id desc";
        $data['page'] = $Page->show();
        $data['count'] = $count;
        $data['list'] = Db::name('mall_order')->alias('o')->leftJoin('user u', 'o.user_id=u.id')->where($where)->field(
            "o.*,u.headimgurl,u.nickname"
        )->order($order)->page($page, 10)->select()->toArray();
        $Logic = new \app\mall\logic\Order();
        $userModel = new User();
        $data['list'] = $data['list'] ?: [];
        foreach ($data['list'] as &$v) {
            $v['goods_list'] = $this->getGoods($v['order_id']);
            $v['goods_count'] = count($v['goods_list']);
            $v['goods_one'] = reset($v['goods_list']);
            $v['option'] = $Logic->getOption($v);
            $v['headimgurl'] = get_img_url($v['headimgurl']);
            $v['bro'] = 0;
            if ($rs[$v['order_id']]['pid1'] == $user_id) {
                $v['bro'] = $rs[$v['order_id']]['bro1'];
                $v['lev'] = '一级';
            } else if ($rs[$v['order_id']]['pid2'] == $user_id) {
                $v['bro'] = $rs[$v['order_id']]['bro2'];
                $v['lev'] = '二级';
            } else if ($rs[$v['order_id']]['pid3'] == $user_id) {
                $v['bro'] = $rs[$v['order_id']]['bro3'];
                $v['lev'] = '三级';
            }
//            $v['user_info'] = $userModel->getUserInfo($v['user_id']);
        }
        return $data;
    }

    public function search($str, $user_id)
    {
        $where[] = ['a.user_id', '=', $user_id];
        $where[] = ['b.name', 'like', "%{$str}%"];
        return Db::name('mall_order')->alias('a')->join('mall_order_goods b', 'a.order_id=b.order_id')
            ->where($where)->column('a.order_id');
    }

    public function handle($order_info)
    {
        if ($order_info) {
            $order_info['order_status'] = $this->get_status($order_info);
            $order_info['admin_btn'] = $this->admin_btn($order_info);
            $order_info['app_btn'] = $this->admin_btn($order_info);
        }
        return $order_info;
    }

    /**
     * 获取当前状态和操作,请使用flag来判断
     * @param array $order_info
     * @return type
     */
    public function order_status($order_info)
    {
        $arr = array(
            0 => array(
                'status' => '已关闭',
                'info' => '当前订单已经被关闭',
                'color' => 'black',
                'flag' => 'close',
                'btns' => array('item'),
            ),
            1 => array(
                'status' => '已完成',
                'info' => '订单已完成，期待您下次光临',
                'color' => 'black',
                'flag' => 'finish',
                'btns' => array('item'),
            ),
            2 => array(
                'status' => '待付款',
                'info' => '请尽快付款，以免被关闭',
                'color' => 'red', 'flag' => 'wait_pay',
                'btns' => array('item', 'close', 'pay'),
            ),
            3 => array(
                'status' => '待发货',
                'info' => '努力发货中，请耐心等待',
                'color' => '#5842ff', 'flag' => 'wait_send',
                'btns' => array('item'),
            ),
            4 => array(
                'status' => '待自取',
                'info' => '请前往相应门店取走您的宝贝',
                'color' => '#01AAED', 'flag' => 'wait_get',
                'btns' => array('item', 'finish'),
            ),
            5 => array(
                'status' => '待收货',
                'info' => '商品配送中，请及时关注物流信息',
                'color' => '#009688', 'flag' => 'wait_shou',
                'btns' => array('item', 'finish'),
            ),
            6 => array(
                'status' => '拼团中',
                'info' => '赶紧分享给您的朋友吧',
                'color' => '#009688', 'flag' => 'gourping',
                'btns' => array('item'),
            ),
        );
        $index = 0;
        if ($order_info['status'] == 4) {
            $index = 0;
        } else if ($order_info['status'] == 3) {
            $index = 1;
        } else if ($order_info['status'] == 2) {
            $index = 5;
        } else if ($order_info['status'] == 1) {
            $index = 3;
        } else if ($order_info['status'] == 0) {
            $index = 2;
        } else if ($order_info['status'] == 5) {
            $index = 6;
        }
        //追加下售后状态
        $after_status = lang('after_status');
        $order_info['after_status_str'] = $after_status[$order_info['after_status']];
        if ($order_info['after_status'] != 0) {
            $arr[$index]['status'] .= "，{$order_info['after_status_str']}";
        }
        return $arr[$index];
    }

    /*
     * 生成后台管理员的按钮
     */

    public function admin_btn($order_info)
    {
        $arr = array();
        $btn_class = array(
            'item' => array(
                'name' => '详情',
                'bindtap' => 'item',
                'class' => 'order-btn1',
            ),
            'feed' => array(
                'name' => '售后',
                'bindtap' => 'feed',
                'class' => 'order-btn1',
            ),
            'send' => array(
                'name' => '发货',
                'bindtap' => 'send',
                'class' => 'order-btn1',
            ),
            'close' => array(
                'name' => '关闭',
                'bindtap' => 'close',
                'class' => 'order-btn1',
            ),
            'refund' => array(
                'name' => '退款并关闭',
                'bindtap' => 'refund',
                'class' => 'order-btn2',
            ),
            'finish' => array(
                'name' => '确认收货',
                'bindtap' => 'finish',
                'class' => 'order-btn2',
            ),
        );
        if ($order_info['feedback_status'] != 0) {
            $arr[] = 'feed';
        }
        if ($order_info['status'] == 1 && in_array($order_info['delivery_type'], [1, 3])) {
            $arr[] = 'send';
        }
        if ($order_info['status'] == 2) {
            $arr[] = 'finish';
        }
        $temp = array();
        foreach ($arr as $v) {
            $temp[] = $btn_class[$v];
        }
        return $temp;
    }

    /**
     * 前端的按钮
     * @param type $order_info
     * @return int
     */
    public function app_btn($order_info)
    {
        $data = [
            'item' => 1,
            'close' => 0,
            'pay' => 0,
            'comment' => 0,
            'delivery' => 0,
            'finish_name' => '确认收货',
            'finish' => 0,
            'offline' => 0,
            'ziti' => 0,

            'after_apply' => 0,
            'after_res' => 0,

            'group_share' => 0,
            'apply_install'=>0


        ];

        if ($order_info['status'] == 0) {

            if ($order_info['pay_type'] != 1) {
                $data['pay'] = 1;
            } else {
                $data['offline'] = 1;
            }
            $data['close'] = 1;
        }
        if($order_info['status'] == 3){
            $data['apply_install'] = 1;
        }
        if ($order_info['comment_status'] == 0 && $order_info['status'] == 3) {
            $data['comment'] = 1;
        }

        if ($order_info['delivery_type'] == 1 && $order_info['pay_status'] == 1 && $order_info['delivery_status'] == 1) {
            $data['delivery'] = 1;
        }

        if ($order_info['status'] == 5 && $order_info['pay_status'] == 1) {
            $data['group_share'] = 1;
        }
        if($order_info['type'] == 3){
            $temp_info = Db::name('pt_order')->where([
                ['ordernum', '=', $order_info['ordernum']]
            ])->find();
            $data['group_share_obj'] = [
                'path' => "/pages/goods/item/item?goods_id={$temp_info['goods_id']}&group_id={$temp_info['group_id']}&p_ordernum={$order_info['ordernum']}",
                'title' => '快来拼团吧',
//                'imageUrl'
            ];
            $pt_status = lang('pt_status');
            $pt_status_str = $pt_status[$temp_info['status']];
            $pre = '';
            if ($temp_info['status'] == 0) {
                $sy = strtotime($temp_info['end_time']) - time();
                if ($sy <= 0) {
                    $temp_info['status'] = 3;
                    $pt_status_str = $pt_status[$temp_info['status']];

                } else {
                    $cha = $temp_info['group_num'] - $temp_info['num'];
                    tool()->func('str');
                    $sy3 = countdown( strtotime($temp_info['end_time']), 2);
                    $pre = " 还差{$cha}人拼成，{$sy3}";
                }
            }
            $str = "[{$pt_status_str}]{$pre}";
            $data['group_share_msg'] = $str;
        }

        if ($order_info['delivery_type'] == 2 && $order_info['pay_status'] == 1 && $order_info['status'] == 1) {
            $data['ziti'] = 1;
        }
//        p($order_info,0);
        if ($order_info['delivery_type'] == 1 && (in_array($order_info['status'],[1,2]))) {
            if ($order_info['type'] == 1 &&  (in_array($order_info['status'],[2]))) {
                $data['finish'] = 1;
            }
            if ($order_info['pay_status'] == 1 && $order_info['after_status'] == 0) {
                $data['after_apply'] = 1;
            }
            if ($order_info['pay_status'] == 1 && $order_info['after_status'] != 0) {
                $data['after_res'] = 1;
            }
        }
//        p($data);
        return $data;
    }

    //关闭订单
    public function close($order_id, $map)
    {
        return $this->orderHandle->close($order_id, $map);
    }

    //隐藏
    public function del($order_id, $map)
    {
        return $this->orderHandle->del($order_id, $map);
    }

    //永久删除订单
    public function forever_del($order_id, $map)
    {
        return $this->orderHandle->forever_del($order_id, $map);
    }


    //修改价格
    public function up_total($order_id, $total)
    {
        return $this->orderHandle->up_total($order_id, $total);
    }

    //退款
    public function refund($order_id, $refund_money)
    {
        return $this->orderHandle->refund($order_id, $refund_money);
    }

    //发货
    public function send($order_id, $map = array())
    {
        return $this->orderHandle->send($order_id, $map);

    }

    //已支付
    public function set_pay($order_id, $pay_type)
    {
        return $this->orderHandle->set_pay($order_id, $pay_type);
    }

    //已完成
    public function finish($order_id, $map)
    {
        return $this->orderHandle->finish($order_id, $map);

    }

    //添加积分
    public function sendInts($orderInfo)
    {
        return $this->orderHandle->send_dot($orderInfo);

    }

    //发放佣金
    public function sendBro($orderInfo)
    {
        return $this->orderHandle->send_bro($orderInfo);
    }
}
