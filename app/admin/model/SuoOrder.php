<?php
declare (strict_types=1);

namespace app\admin\model;

use app\admin\model\SuoMaster;
use app\admin\model\SuoProfit;
use app\common\model\User;
use app\common\model\Weixin;
use app\user\model\UserMsg;
use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SuoOrder extends Model
{


    protected $name = 'suo_order';
    protected $autoWriteTimestamp = 'datetime';

    public function dbName()
    {
        return $this->name;
    }

    public function get_pk()
    {
        return "id";
    }

    //更新订单状态/任务完成状态下发放佣金($order_id, $uid, $order_status, $shop_id = 0)
    public function updateOrderStatus($data)
    {
        $update_data = [];
        $order_id = $data['order_id'];
        $uid = $data['uid'];
        $order_status = $data['order_status'];
        $shop_id = $data['shop_id'];

        $finsh_img = $data['finsh_img'];
        $finsh_remarks = $data['finsh_remarks'];
        $reject_remarks = $data['reject_remarks'];

        $map = [];
        $map[] = ['id', '=', $order_id];
        $map[] = ['status', '=', $order_status];
        if ($this->where($map)->count()) {
            return ['status' => 0, 'info' => '请勿重复操作'];
        }

        $user_info = Db::name('suo_master')->where('id', $uid)->find();
        if ($order_status == 2) { //接单

//            if ($user_info['level'] != 1) {
//                return ['status' => 0, 'info' => '锁匠才能接单'];
//            }
            if (!$user_info['status']) {
                return ['status' => 0, 'info' => '账号不可使用'];
            }
            if ($user_info['is_auth'] != 1) {
                return ['status' => 0, 'info' => '请先实名认证'];
            }
            $master_id = $this->where('id', $order_id)->value('master_id');
            if ($master_id && $master_id != $uid) {
                return ['status' => 0, 'info' => '该订单已被抢'];
            }
            $update_data['master_id'] = $uid;

            if($user_info['level']==2){
                if(!$user_info['shop_id']){
                    $shop_id = $uid;
                }
            }else{
                $shop_id = $user_info['shop_id'];
            }
            $update_data['shop_id'] = $shop_id;
            $update_data['taking_time'] = date("Y-m-d H:i:s");
            //更新接单数量
//            Db::name('suo_master')->where([
//                ['id', '=', $uid]
//            ])->update([
//                'jiedan_num' => Db::raw('jiedan_num+1')
//            ]);
            (new SuoMaster())->updateJdnum($user_info['master_id']);
        }

        if ($order_status == 7) { //接单取消
            $info = $this->getInfo($order_id);
            if ($info['master_service_start'] != 1) {
                return ['status' => 0, 'info' => '无权取消'];
            }

        }

        //完成任务发放奖金
        if ($order_status == 4) {
            if (!$finsh_remarks) {
                return ['status' => 0, 'info' => '备注不能为空'];
            }
            if (!$finsh_img) {
                return ['status' => 0, 'info' => '请上传图片'];
            }
            $update_data['finsh_remarks'] = $finsh_remarks;
            $update_data['finsh_img'] = json_encode($finsh_img);
            $order_data = $this->where('id', $order_id)->find();
            $money = $order_data['total'] + $order_data['jia_total'];


            //需要给上级发佣金
            $haoyou_bro = C('haoyou_bro');
            if ($haoyou_bro > 0) {
                $bro = $money * ($haoyou_bro / 100);
                $umodel = (new User());
//                $user_id = $order_data['user_id'];
//                $uinfo = $umodel->getUserInfo($user_id, false);
                if ($order_data['pid'] && $bro > 0) {
                    $umodel->handleUser('bro', $order_data['pid'], $bro, 1, ['ordernum' => $order_data['ordernum'], 'cate' => 5]);
//                    $save['pid'] = $uinfo['pid'];
                    $update_data['bro'] = $bro;
                    $money = $money-$bro;
                }
            }

            $this->appendProfit($order_data['master_id'], $order_data['ordernum'], $user_info['level'], $money);



        }

        if ($order_status == 6) {

            if (!$reject_remarks) {
                return ['status' => 0, 'info' => '驳回原因不能为空'];
            }
            $info = $this->getInfo($order_id);
            $update_data['reject_remarks'] = $reject_remarks;

            if ($info['pay_status'] == 1 && $info['pay_type'] == 1) {
                $res =  $this->tuik($info);
                if($res['status'] != 1){
                    return $res;
                }
            }

        }
        if ($order_status == 3) {
            $update_data['start_service_time'] = date("Y-m-d H:i:s");
        }

        $update_data['status'] = $order_status;

        $this->where('id', $order_id)->update($update_data);
        return ['status' => 1];
    }

    public function handle($v)
    {

        if ($v['content']) {
            $v['content'] = contentHtml($v['content']);
        }

        //自动生成语言包
        $lans = $this->getLan();
        foreach ($lans as $type => $arr) {
            $v["{$type}_str"] = $arr[$v[$type]];
        }
        $status_remark = array('0' => '', '1' => '请等待师傅接单', '2' => '请等待师傅上门', '3' => '', '4' => '', '5' => '');
        $v['status_remark_str'] = $status_remark[$v['status']] . ''; //详情

        //默认为空，防止app端报错
        $v['master_info'] = [
            'realname' => '',
            'headimgurl' => '',
            'remark' => '',
            'tel' => '',
            'shop_id_str'=>''
        ];

        if ($v['master_id']) {
            $minfo = (new SuoMaster())->getInfo($v['master_id']);
            if($minfo['level'] == 1){
                if($minfo['shop_id']){
                    $shop_name = (new SuoMaster())->where('id',$minfo['shop_id']) -> value('shop_name');
                    $shop_id_str = $shop_name;
                }else{
                    $shop_id_str = '个人';
                }
            }else{
                $shop_id_str = '管理员';
            }
            $v['master_info'] = [
                'realname' => $minfo['realname'],
                'headimgurl' => $minfo['headimgurl'],
                'remark' => $minfo['remark'],
                'tel' => $minfo['tel'],
                'shop_id_str'=>$shop_id_str
            ];


        }

        $v['total_str'] = $v['total'] + $v['jia_total'];

        $pingtai_income = C('pingtai_income') / 100;//平台收益百分比
        $shop_income = C('shop_income') / 100;//门店收益百分比

        $master_profit = round($v['total_str'] * (1 - $pingtai_income - $shop_income),2) ;

        $shop_profit = $shop_income * $v['total_str'];

        $master_profit = sprintf("%.2f", $master_profit);
        $shop_profit = sprintf("%.2f", $shop_profit);
        $profit_array = [$master_profit, $shop_profit];

        $v['auto_close_second_str'] = '';
        $v['auto_close_second'] = -1;


        $v['profit_array'] = $profit_array;

        $shifu_income  =C('shifu_income')/100;
        $v['master_profit'] =  round($v['total_str'] * $shifu_income,2);

        $shop_income   = C('shop_income')/100;
        $v['shop_profit'] =  round($v['total_str'] * $shop_income,2);

        $v['user_xiangqing'] = 1; //详情
        $v['user_jiajia'] = 0;//加价.
        $v['user_close'] = 0;//取消

        $v['user_quxiao'] = 0;//取消
        $v['user_tousu'] = 0;//投诉
        $v['user_lxshifu'] = 0;//联系师傅
        $v['user_tousukan'] = 0;//投诉详情
        $v['user_topingjia'] = 0;//去评价
        $v['user_reset'] = 0;//重新生成
        $v['user_pay'] = 0; //去支付
        $v['user_install'] = 0; //去安装
        $v['juli_str'] = '';
        $v['master_taking'] = 0;//开始抢单
        $v['master_service_start'] = 0;//开始服务
        $v['master_service'] = 0;//服务中
        $v['master_taking_again'] = 0;//重新抢单
        $v['master_cacel'] = 0;


        $v['admin_quxiao'] = 0;//取消
        $v['admin_tousukan'] = 0;//投诉
        $v['admin_pay'] = 0;//支付成功
        $v['admin_fp'] = 0;//分配订单
        $v['admin_close'] = 0;//分配订单

        if ($v['status'] == 0) {
            $v['user_pay'] = 1;
            $v['admin_pay'] = 1;
            $v['admin_close'] = 1;
            $v['user_close'] = 1;

            $mall_close_minute = C('mall_close_minute');
            //关闭的时间
            $v['auto_close_time'] = date('Y/m/d H:i:s', (strtotime($v['create_time']) + $mall_close_minute * 60));
            $v['auto_close_second'] = strtotime($v['auto_close_time']) - time();
            if ($mall_close_minute == 0 || $v['auto_close_second'] <= 0) {
                $v['auto_close_second'] = 0;
            }
        }
        if ($v['status'] == 1) {
            $v['user_jiajia'] = 1;
            $v['user_quxiao'] = 1;

            $v['admin_fp'] = 1;

            $v['admin_quxiao'] = 1;

            $v['master_taking'] = 1;
            $v['master_taking_str'] = $v['master_id'] ? '立即接单' : '立即抢单';
            $v['master_taking_title'] = $v['master_id'] ? '接单成功' : '抢单成功';
            $d = '';

            if ($v['pay_time']) {
                tool()->func('str');
                $huamiao = geshihuamiao(time() - strtotime($v['pay_time']));
                if ($huamiao['d'] > 0) {
                    $d = "{$huamiao['d']}天";
                }
            }


            $v['juli_str'] = "距下单已过{$d}{$huamiao['h']}小时{$huamiao['m']}分";
        }
        if ($v['status'] == 2) {
            $v['master_service_start'] = 1;
            $v['master_service_start_str'] = '开始服务';
            $v['master_cacel'] = 1;
        }
        if ($v['status'] == 3) {
            $v['master_service'] = 1;
            $v['master_service_str'] = '完成';
        }
        if ($v['status'] == 4) {
            if ($v['comment_status'] == 0) {
                $v['user_topingjia'] = 1;
            }
            if ($v['apply_install_status'] == 0) {
//                $v['user_install'] = 1;
            }

        }
        if (in_array($v['status'], [2, 3, 4])) {
            if ($v['tousu_status'] == 0) {
                $v['user_tousu'] = 1;
            }
            if (in_array($v['tousu_status'], [1, 2])) {
                $v['user_tousukan'] = 1;

                $v['admin_tousukan'] = 1;
            }
        }
        if ($v['status'] == 5) {
            $v['master_taking_again'] = 1;
            $v['master_taking_again_str'] = '重新抢单';
        }

        if ($v['status'] == 7) {
            $v['user_reset'] = 1;
        }
        $v['shop_id_str'] = '无';
        if ($v['shop_id']) {
            $ss = (new SuoMaster())->getInfo($v['shop_id'], true);
            $v['shop_id_str'] = $ss['shop_name'];

        }
        $s1 = '';
        $s2 = '';

        if ($v['sel_time1']) {
            //开锁（sel_time1 立即上门）
            $v['promptly'] = 0;
            if(!preg_match("/[^\x80-\xff]/i",$v['sel_time1'])){
                $s1 =$v['sel_time1'];
                $s2 = $v['sel_time2'];
                $v['promptly'] = 1;
            }else{
                $s1 = mb_substr($v['sel_time1'], 0, 5);
                $s2 = '';
                if($v['sel_time2']){
                    $s2 = mb_substr($v['sel_time2'], 0, 5);
                }

            }


        }

        if($s1 && $s2){
            $v['up_str'] = "{$v['sel_date']} {$s1}-{$s2}";
        }else{
            $v['up_str'] = "{$v['sel_date']} {$s1}";
        }

        $v['close_remark'] = '';

        $order_type = lang('order_type');
        $v['type_str'] = $order_type[$v['type']];

        $v['product_cate_str'] = "{$v['cate_name']}/{$v['product_name']}";

        $v['imgs_arr'] = [];
        if ($v['imgs']) {
            $v['imgs_arr'] = json_decode($v['imgs'], true);
        }

        $v['finsh_img_arr'] = [];
        if ($v['finsh_img']) {
            $v['finsh_img_arr'] = json_decode($v['finsh_img'], true);
        }


        return $v;
    }

    public function getList($where, $page = 1, $num = 10, $order = '', $field = '*',$having='')
    {
        $page = $page + 0;


        if($having){
            $data = Db::name($this->name)->where($where)->page($page, $num)->field($field)->order($order)->having($having)->select()->toArray();
        }else{
            $data = Db::name($this->name)->where($where)->page($page, $num)->field($field)->order($order)->select()->toArray();
        }

        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function get_data($where = array(), $num = 10)
    {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow, $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array(), $cache = true)
    {
        $pre = md5(json_encode($where));
        $name = "suo_order_1675933232_{$pre}";
        $data = cache($name);
        if (!$data || !$cache) {
            $order = "id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            foreach ($data as &$v) {
                $v = $this->handle($v);
            }
            cache($name, $data);
        }
        return $data;
    }


    public function itemAll()
    {
        $data = $this->getAll();
        $list = array();
        $pk = $this->get_pk();
        foreach ($data as $v) {
            $list[] = array(
                'val' => $v[$pk], 'name' => $v['name']
            );
        }
        return $list;
    }

    //最新的获取项目列表的方法
    public function getMapList($map = [])
    {
        $res['count'] = 0;
        $page_num = C('page_num');
        $page_num = intval(isset($map['page_num']) ? ($map['page_num'] ?: $page_num) : $page_num);
        $list = $this->alias('a')
            ->when(isset($map['id']) && $map['id'], function ($query) use ($map) {
                $query->where('id', $map['id']);
            })
            ->when(isset($map['ordernum']) && $map['ordernum'], function ($query) use ($map) {
                $query->where('ordernum', $map['ordernum']);
            })
            ->when(isset($map['page']) && $map['page'], function ($query) use ($map, $page_num) {
                $query->page(intval($map['page']), $page_num);
            })
            ->when(isset($map['pay_status']) && $map['pay_status'] != '', function ($query) use ($map, $page_num) {
                $query->where('pay_status', $map['pay_status']);
            })
            ->when(isset($map['type']) && $map['type'] != '', function ($query) use ($map, $page_num) {
                $query->where('type', $map['type']);
            })
            ->when(isset($map['keywords']) && $map['keywords'] != '', function ($query) use ($map, $page_num) {
                $query->where('ordernum', 'like', "%{$map['keywords']}%");
            })
            ->when(isset($map['tel']) && $map['tel'] != '', function ($query) use ($map, $page_num) {
                $query->where('tel', 'like', "%{$map['tel']}%");
            })
            ->when(isset($map['status']) && $map['status'] != '', function ($query) use ($map, $page_num) {
                $query->where('status', $map['status']);
            })
            ->when(isset($map['user_id']) && $map['user_id'] != '', function ($query) use ($map, $page_num) {
                $query->where('user_id', $map['user_id']);
            })
            ->when(isset($map['comment_status']) && $map['comment_status'] != '', function ($query) use ($map, $page_num) {
                $query->where('comment_status', $map['comment_status']);
            })
            ->when(isset($map['product_id']) && $map['product_id'] != '', function ($query) use ($map, $page_num) {
                $query->where('product_id', $map['product_id']);
            });
        if (isset($map['page']) && $map['page'] == 1) {
            $res['count'] = $list->count();
            $res['page_num'] = $page_num;

        }

        if (isset($map['find']) && $map['find'] == 1) {
            $obj = $list->find();
            if ($obj) {
                $info = $obj->toArray();
                if ($info) {
                    $info = $this->handle($info);
                }

            } else {
                $info = null;
            }
            return $info;
        }
        $obj = $list->order("id desc")
            ->select();
        if ($obj) {
            $list = $obj->toArray();
            if ($list) {
                foreach ($list as &$v) {
                    $v = $this->handle($v);

                    if (isset($map['type']) && $map['type']) {
                        $v['price'] = $v['price' . $map['type']];
                    }
                }
            }
        } else {
            $list = [];
        }
        $res['list'] = $list;
        return $res;
    }

    public function master_cacel($map)
    {
        if (!$map['id']) {
            return ['status' => 0, 'info' => '错误'];
        }
        $type = $map['type'] ? $map['type'] : 'admin';
        $info = $this->getMapList([
            'find' => 1,
            'id' => $map['id']
        ]);
        if ($info['master_cacel'] != 1) {
            return ['status' => 0, 'info' => '订单错误'];
        }

        if ($type != 'admin') {
            if ($map['master_id']) {
                if ($info['master_id'] != $map['master_id']) {
                    return ['status' => 0, 'info' => '订单不是你的'];
                }
            } else if ($map['shop_id']) {
                if ($info['shop_id'] != $map['shop_id']) {
                    return ['status' => 0, 'info' => '订单不是你的'];
                }
            } else {
                return ['status' => 0, 'info' => '订单不是你的'];
            }
        }

        $save = [
            'master_cacel_msg' => $map['master_cacel_msg'],
            'status' => 7
        ];
        if ($info['pay_status'] == 1 && $info['pay_type'] == 1) {
            $res =  $this->tuik($info);
            if($res['status'] != 1){
                return $res;
            }
        }

//        Db::name('suo_master')->where([
//            ['id', '=', $map['master_id']]
//        ])->update([
//            'jiedan_num' => Db::raw('jiedan_num-1')
//        ]);
        (new SuoMaster())->updateJdnum($map['master_id']);
//        p($save);
        $this->update($save, ['id' => $info['id']]);
        return ['status' => 1, 'info' => lang('s')];
    }

    //退款
    public function tuik($info)
    {
        //todo 测试
        $info['total'] = 0.01;
        $res = (new Weixin())->refund($info['trade_no'], $info['total'], $info['total']);
        if ($res['status'] == 1) {
            $save = [];
            $save['fund_status'] = 1;
            $save['fund_money'] = $info['total_str'];
            Db::name('suo_order')->where(['id' => $info['id']])->save($save);

            if ($info['jia_total'] > 0) {
                $jialist = Db::name('suo_order_jia')->where(['order_id' => $info['id'], 'pay_status' => 1])->select()->toArray();
                foreach ($jialist as $v) {
                    if ($v['trade_no']) {
                        $res2 = (new Weixin())->refund($v['trade_no'], $v['total'], $v['total']);
                        if ($res2['status'] == 1) {
                            Db::name('suo_order_jia')->where(['id' => $v['id'],])->save(['fund_status' => 1, 'fund_money' => $v['total']]);
                        } else {
                            Db::name('suo_order_jia')->where(['id' => $v['id'],])->save(['fund_status' => 2, 'fund_money' => $v['total']]);
                        }
                    }
                }
            }
            return ['status' => 1];

        } else {
            return ['status' => 0, 'info' => "退款失败：" . $res['info']];
        }
    }

    public function reset_order($map)
    {
        if (!$map['id']) {
            return ['status' => 0, 'info' => '错误'];
        }
        $type = $map['type'] ? $map['type'] : 'admin';
        $info = $this->getMapList([
            'find' => 1,
            'id' => $map['id']
        ]);
        if ($info['user_reset'] != 1) {
            return ['status' => 0, 'info' => '订单错误'];
        }

        if ($type != 'admin' && $info['user_id'] != $map['user_id']) {
            return ['status' => 0, 'info' => '订单不是你的'];
        }

//        if($info['sel_date']) $info['sel_time1']
        $this->update([
            'status' => 8,
        ], ['id' => $info['id']]);

        if (strtotime("{$info['sel_date']} {$info['sel_time1']}") < time()) {
            return ['status' => 0, 'info' => '该订单的预约时间已经过期'];
        }

//        $inf
        unset($info['id']);

        $info['ordernum'] = get_ordernum();
        $info['status'] = 0;
        $info['master_id'] = null;
        $info['shop_id'] = null;
        $info['master_cacel_msg'] = '';
        $info['user_cacel_msg'] = '';

        $info['jia_total'] = 0;
        $info['jia_num'] = 0;

        $info['order_type'] = 2;
        $info['create_time'] = date('Y-m-d H:i:s');

        Db::name('suo_order')->insert($info);

        $openid = Db::name('user')->where(['id' => $info['user_id']])->value('openid');


        $wx_pay_type = C('pay_type');
        $notify_url = C('wapurl') . "/mallapi/Pay/suoye";
        tool()->classs('pay/Pay');
        $Pay = new \Pay($wx_pay_type);
        $res = $Pay->pay([
            'appid' => C('appid'),
            'total' => $info['total'],
            'openid' => $openid,
            'ordernum' => $info['ordernum'],
            'notify_url' => $notify_url,
        ]);
        if ($res['status'] == 1) {
            $res['pay_type'] = 4;
            $res['data']['pay_status'] = 0;
            return $res;
        } else {
            return ['status' => 0, 'info' => $res['info']];
        }

        return ['status' => 1, 'info' => lang('s')];
    }

    public function close($map)
    {
        if (!$map['id']) {
            return ['status' => 0, 'info' => '错误'];
        }
        $type = $map['type'] ? $map['type'] : 'admin';
        $info = $this->getMapList([
            'find' => 1,
            'id' => $map['id']
        ]);
        if ($info['admin_close'] != 1) {
            return ['status' => 0, 'info' => '订单错误'];
        }

        if ($type != 'admin' && $info['user_id'] != $map['user_id']) {
            return ['status' => 0, 'info' => '订单不是你的'];
        }
        $this->update([
            'status' => 9
        ], ['id' => $info['id']]);
        return ['status' => 1, 'info' => lang('s')];
    }

    public function user_close($map)
    {
        if (!$map['id']) {
            return ['status' => 0, 'info' => '错误'];
        }
        $type = $map['type'] ? $map['type'] : 'admin';
        $info = $this->getMapList([
            'find' => 1,
            'id' => $map['id']
        ]);
        if ($info['user_close'] != 1) {
            return ['status' => 0, 'info' => '订单错误'];
        }

        if ($type != 'admin' && $info['user_id'] != $map['user_id']) {
            return ['status' => 0, 'info' => '订单不是你的'];
        }
        $save = [
            'status' => 9
        ];


        $this->update($save, ['id' => $info['id']]);
        return ['status' => 1, 'info' => lang('s')];
    }

    public function user_quxiao($map)
    {
        if (!$map['id']) {
            return ['status' => 0, 'info' => '错误'];
        }
        $type = $map['type'] ? $map['type'] : 'admin';
        $info = $this->getMapList([
            'find' => 1,
            'id' => $map['id']
        ]);
        if ($info['user_quxiao'] != 1) {
            return ['status' => 0, 'info' => '订单错误'];
        }

        if ($type != 'admin' && $info['user_id'] != $map['user_id']) {
            return ['status' => 0, 'info' => '订单不是你的'];
        }
        $save = [
            'status' => 5
        ];
        if ($info['pay_status'] == 1 && $info['pay_type'] == 1) {
            $res =  $this->tuik($info);
            if($res['status'] != 1){
                return $res;
            }
        }
        $this->update($save, ['id' => $info['id']]);
        return ['status' => 1, 'info' => lang('s')];
    }

    public function pay_success($info)
    {
        $save = [
            'pay_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'pay_status' => 1,
            'status' => 1,
            'pay_type' => $info['pay_type']
        ];

        if ($info['trade_no']) {
            $save['trade_no'] = $info['trade_no'];
        }
        if ($info['master_id']) {
            (new UserMsg())->addLog($info['master_id'], 1);
        }

        Db::name('suo_order')->where(['id' => $info['id']])->save($save);

//        $count = Db::name('suo_order')->where([
//            ['user_id','=',$info['user_id']],
//            ['pay_status','=',1],
//        ])->count();
//        if($count == 1){

//        }


    }

    public function census($user_id)
    {
        $arr = array(
            "count(*) as all_count",
            "count( status=0  or null) as status0",
            "count( status=1  or null) as status1",
            "count( status=2  or null) as status2",
            "count( status=3  or null) as status3",
            "count( status=4  or null) as status4",
            "count( status=5  or null) as status5",
            "count( status=6  or null) as status6",
        );
        $where = [
            ['user_id', '=', $user_id]
        ];
        $data = Db::name('suo_order')->where($where)->field($arr)->limit(1)->select()->toArray();
        return $data[0];
    }

    public function getInfo($pk, $cache = false)
    {
        if (!$pk) {
            return null;
        }
        $name = "suo_order_info_1675933232_{$pk}";
        $data = cache($name);
        if (!$data || !$cache) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            if ($data) {
                $data = $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field = '')
    {
        $lans = array(
            'status' => array('0' => '待支付', '1' => '待接单', '2' => '待服务', '3' => '服务中',
                '4' => '已完成', '5' => '用户取消订单', '6' => '已驳回', '7' => '锁匠取消订单', '8' => '已重新生成', '9' => '已关闭'),
            'pay_status' => array('0' => '未支付', '1' => '已支付',),
            'fund_status' => array('0' => '无', '1' => '已退款',),
            'pay_type' => array('0' => '无', '1' => '微信', '2' => '余额',),
            'comment_status' => array('0' => '未评论', '1' => '已评论',),
            'is_jiadan' => array('0' => '否', '1' => '是',),
            'tousu_status' => array('0' => '无', '1' => '已投诉', '2' => '已处理'),
            'order_type' => array('0' => '正常', '1' => '加单', '2' => '重新生成'),
            'type' => lang('order_type'),


        );
        if ($field == '') {
            return $lans;
        }
        return $lans[$field];
    }

    public function getOption($name = 'name')
    {
        $as = $this->getAll();
        $this->open_name = $name;
        $names = array_reduce($as, function ($v, $w) {
            $v[$w[$this->get_pk()]] = $w[$this->open_name];
            return $v;
        });
        return $names;
    }


    public function clear($pk = '')
    {
        $name = "suo_order_1675933232";
        cache($name, null);
        if ($pk) {
            $name = "suo_order_info_1675933232_{$pk}";
            cache($name, null);
        }
    }


    public function setVal($id, $key, $val)
    {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key => $val]);
        }

    }

    public function getVal($id, $key, $cache = true)
    {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->cache($cache)->value($key);
        }
    }


    /**
     * 搜索框
     * @return type
     */
    public function searchArr()
    {
        return [
            'id' => '',
            'user_id' => '',
            'user_id' => '',
            'create_time' => '',
            'status' => '',
            'pay_status' => '',
            'fund_status' => '',
            'pay_type' => '',
            'pay_time' => '',
            'comment_status' => '',
            'address' => '',
            'message' => '',
            'update_time' => '',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels()
    {
        return ['id' => '编号',
            'user_id' => '用户',
            'create_time' => '创建时间',
            'status' => '状态',
            'pay_status' => '支付状态',
            'ordernum' => '订单号',
            'fund_status' => '退款状态',
            'fund_money' => '退款金额',
            'trade_no' => '第三方交易号',
            'pay_type' => '支付类型',
            'pay_time' => '支付时间',
            'comment_status' => '评论状态',
            'total' => '订单金额',
            'address' => '地址',
            'message' => '客户留言',
            'province' => '省份',
            'city' => '城市',
            'country' => '区域',
            'reference' => '位置',
            'update_time' => '更新时间',
            'type' => '类型',
        ];
    }

    /**
     * 规则
     * @return type
     */
    public function rules()
    {
        return [
            'rule' => [
                'user_id|用户' => ["integer",],
                'create_time|创建时间' => [],
                'status|状态' => ["integer",],
                'pay_status|支付状态' => ["integer",],
                'sn|订单号' => ["max" => 55,],
                'fund_status|退款状态' => ["integer",],
                'fund_money|退款金额' => ["float",],
                'trade_no|第三方交易号' => ["max" => 55,],
                'pay_type|支付类型' => ["integer",],
                'pay_time|支付时间' => [],
                'comment_status|评论状态' => ["integer",],
                'total|订单金额' => ["float",],
                'address|地址' => ["max" => 255,],
                'message|客户留言' => ["max" => 500,],
                'province|省份' => ["max" => 255,],
                'city|城市' => ["max" => 255,],
                'country|区域' => ["max" => 255,],
                'update_time|更新时间' => [],
                'type|类型' => ["integer",],

            ],
            'message' => []
        ];

    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField()
    {
        return "id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue()
    {
        return ['id' => '',
            'user_id' => '',
            'create_time' => '',
            'status' => '',
            'pay_status' => '0',
            'sn' => '',
            'fund_status' => '',
            'fund_money' => '0.00',
            'trade_no' => '',
            'pay_type' => '',
            'pay_time' => '',
            'comment_status' => '',
            'total' => '0.00',
            'address' => '',
            'message' => '',
            'province' => '',
            'city' => '',
            'country' => '',
            'update_time' => '',
            'type' => '',
        ];
    }

    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr()
    {
        return [];
    }

    /**
     * 要转成日期的字段
     * @return type
     */
    public function dateAttr()
    {
        return ['pay_time', 'update_time','create_time'];
    }

    /**
     * 字段类型
     * @return type
     * #fieldType#
     */
    public function fieldType()
    {
        return [];
    }

    //数据统计
    public function statistics($post_data)
    {
        $uid = $post_data['uid'];
        $user_type = $post_data['user_type']; //用户类型 1=师傅,2=门店
        $date_type = $post_data['date_type']; //时间类型 1=今日，2=近七天，3=30天内
        $start_time = $post_data['start_time'];
        $end_time = $post_data['end_time'];
        $map = [];

        $data = [];
//        if ($user_type == 1) {
            $map['master_id'] = $uid;
//        } else {
//            $map['shop_id'] = $uid;
//        }

        $field = 'type,status,jia_total,total';

        $field1 = 'type,count(id) as value';
        $list = $this->where($map)->field($field)->select()->toArray();

        $total_profit = 0;//累计佣金
        if($user_type==1){
            $map_p = [];
            $map_p[] = ['master_id','=',$uid];
            $map_p[] = ['type','=',1];
            $total_profit = (new SuoProfit())-> where($map_p) -> sum('money');
        }else{
            $map_p = [];
            $map_p[] = ['shop_id','=',$uid];
            $map_p[] = ['type','=',2];
            $total_profit = (new SuoProfit())-> where($map_p) -> sum('money');
        }


        if ($date_type) {
            if ($date_type == 1) {
                $list = $this->where($map)->whereDay('create_time')->field($field)->select()->toArray();

                if($user_type==1){
                    $map_p = [];
                    $map_p[] = ['master_id','=',$uid];
                    $map_p[] = ['type','=',1];
                    $total_profit = (new SuoProfit())-> where($map_p) ->whereDay('create_time')-> sum('money');
                }else{
                    $map_p = [];
                    $map_p[] = ['shop_id','=',$uid];
                    $map_p[] = ['type','=',2];
                    $total_profit = (new SuoProfit())-> where($map_p) ->whereDay('create_time')-> sum('money');
                }
            } else {
                $day_star_time = strtotime(date('Y-m-d'));
                $time2 =date("Y-m-d",strtotime("+1 day"));
                $time1 = $day_star_time - $date_type * 24 * 3600;
                $time1 = date('Y-m-d', $time1);

                $list = $this->where($map)->whereBetweenTime('create_time', $time1, $time2)->field($field)->select();

                if($user_type==1){
                    $map_p = [];
                    $map_p[] = ['master_id','=',$uid];
                    $map_p[] = ['type','=',1];
                    $total_profit = (new SuoProfit())-> where($map_p) ->whereBetweenTime('create_time', $time1, $time2)-> sum('money');
                    echo (new SuoProfit())-> getLastSql();exit;
                }else {
                    $map_p = [];
                    $map_p[] = ['shop_id', '=', $uid];
                    $map_p[] = ['type', '=', 2];
                    $total_profit = (new SuoProfit())->where($map_p)->whereBetweenTime('create_time', $time1, $time2)->sum('money');
                }

            }

        }


        if ($start_time && $end_time) {
            if ($start_time == $end_time) {
                $list = $this->where($map)->whereDay('create_time', $start_time)->field($field)->select()->toArray();


                if($user_type==1){
                    $map_p = [];
                    $map_p[] = ['master_id','=',$uid];
                    $map_p[] = ['type','=',1];
                    $total_profit = (new SuoProfit())-> where($map_p) ->whereDay('create_time', $start_time)-> sum('money');
                }else {
                    $map_p = [];
                    $map_p[] = ['shop_id', '=', $uid];
                    $map_p[] = ['type', '=', 2];
                    $total_profit = (new SuoProfit())->where($map_p)->whereDay('create_time', $start_time)->sum('money');
                }

            } else {

                $end_time =date("Y-m-d H:i:s",strtotime($end_time)+24*3600-1);

                $list = $this->where($map)->whereBetweenTime('create_time', $start_time, $end_time)->field($field)->select()->toArray();

                if($user_type==1){
                    $map_p = [];
                    $map_p[] = ['master_id','=',$uid];
                    $map_p[] = ['type','=',1];
                    $total_profit = (new SuoProfit())-> where($map_p) ->whereBetweenTime('create_time', $start_time, $end_time)-> sum('money');
                }else {
                    $map_p = [];
                    $map_p[] = ['shop_id', '=', $uid];
                    $map_p[] = ['type', '=', 2];
                    $total_profit = (new SuoProfit())->where($map_p)->whereBetweenTime('create_time', $start_time, $end_time)->sum('money');

                }
            }

        }

        $total_order = 0;//总单量
        $finish_order = 0;//已完成量
        $cancel_order = 0;//取消单量
        $total_price = 0;//金额
        $total_jia_price = 0;//加价金额


        if ($list) {
            foreach ($list as $key => $value) {

                $total_order += 1;
                if ($value['status'] == 4) {
                    $finish_order += 1;

                    $total_price += $value['total'];
                    $total_jia_price += $value['jia_total'];

                }
                if ($value['status'] == 5) {
                    $cancel_order += 1;
                }
            }
        }
//        dump($finish_order);exit;
        $order_type = lang('order_type');
        $group_list = [];
        $i = 0;
        foreach ($order_type as $key => $value) {

            if ($list && is_array($list)) {
                foreach ($list as $k => $v) {
                    if ($key == $v['type']) {
                        $group_list[$i][$key] = 1;
                        $group_list[$i]['value'] += 1;
                        $group_list[$i]['name'] = $value;
                    } else {
                        if (!isset($group_list[$i][$key])) {
                            $group_list[$i][$key] = 2;
                            $group_list[$i]['value'] = 0;
                            $group_list[$i]['name'] = $value;
                        }

                    }
                }
            } else {
                $group_list[$i]['value'] = 0;
                $group_list[$i]['name'] = $value;
            }

            $i++;
        }


        $data['sum_price'] = $total_profit;

        $data['total_order'] = $total_order;
        $data['finish_order'] = $finish_order;
        $data['cancel_order'] = $cancel_order;

        $series[0]['data'] = $group_list;
        $chart_data['series'] = $series;
        $data['chart_data'] = $chart_data;

        return $data;

    }

    //添加收益记录
    public function appendProfit($uid, $ordernum = '', $type = 1, $money = 0)
    {
        //、如果是个人锁匠，分佣统一设置为平台（a%）、锁匠（100%-a%）；2、若是挂靠门店锁匠，分佣设置为平台（a%）、门店（b%）、锁匠（100%-a%-b%)；
        if ($money > 0) {
            $master_income = C('shifu_income') /100;//师傅收益百分比
            $shop_income = C('shop_income') / 100;//门店收益百分比
            $pingtai_income = C('pingtai_income') / 100;//平台收益百分比

            //$master_money = ($money * (1 - $pingtai_income));
            // $master_money = number_format($master_money, 2);

            $shop_money = ($money * $shop_income);
            // $shop_money = number_format($shop_money, 2);
            $master_money = $money *  $master_income;


            $user = (new SuoMaster())->where('id', $uid)->find();
            $shop_id = $user['shop_id'];
            $headimgurl = $user['headimgurl'];
            $realname = $user['realname'];
            $tel = $user['tel'];
            $is_shop = 0;

            //挂靠门店的锁匠
            if ($shop_id && $shop_id > 0 && $shop_id != $uid) {
                $is_shop = 1;
            }

            //当师傅拿收益时，门店也拿
            if ($type == 1) {
//                if ($is_shop == 1) {
//                    $master_money = ($money * (1 - $pingtai_income - $shop_income));
//                }
                // $shop_data = (new SuoMaster())->where('id',$shop_id) -> find();
                $data = [];
                $data['master_id'] = $uid;
                $data['shop_id'] = $shop_id;
                $data['username'] = $realname;
                $data['headimgurl'] = $headimgurl;
                $data['tel'] = $tel;
                $data['type'] = $type;
                $data['ordernum'] = $ordernum;
                $data['money'] = $master_money;
                $data['create_time'] = date('Y-m-d H:i:s');
                $data['is_devote'] = 1; //锁匠收益（工单收益）

                (new SuoProfit())->save($data);
                (new SuoMaster())->where('id', $uid)->inc('balance', $master_money)->update();


                if ($is_shop == 1) {
                    $data1 = [];
                    $data1['master_id'] = $uid;
                    $data1['shop_id'] = $shop_id;
                    $data1['username'] = $realname;
                    $data1['headimgurl'] = $headimgurl;
                    $data1['tel'] = $tel;
                    $data1['type'] = 2;
                    $data1['ordernum'] = $ordernum;
                    $data1['money'] = $shop_money;
                    $data1['is_devote'] = 3;//锁匠服务贡献收益
                    $data1['create_time'] = date('Y-m-d H:i:s');
                    if ((new SuoMaster())->where('id', $shop_id)->where('level', 2)->count()) {
                        (new SuoProfit())->save($data1);
                        (new SuoMaster())->where('id', $shop_id)->inc('balance', $shop_money)->update();
                    }
                }


            } else { //当门店端是门店又是师傅的时候，门店的佣金以及师傅的佣金都得
                //门店收益
                $s_data = [];
                $s_data['shop_id'] = $uid;
                $s_data['username'] = $realname;
                $s_data['headimgurl'] = $headimgurl;
                $s_data['tel'] = $tel;
                $s_data['type'] = $type;
                $s_data['ordernum'] = $ordernum;
                $s_data['money'] = $shop_money;
                $s_data['is_devote'] = 2;//门店收益
                $s_data['create_time'] = date('Y-m-d H:i:s');

                (new SuoProfit())->save($s_data);
                (new SuoMaster())->where('id', $uid)->inc('balance', $shop_money)->update();

                //工单收益
                $order_profit = $master_income;
                $data = [];
                $data['shop_id'] = $uid;
                $data['username'] = $realname;
                $data['headimgurl'] = $headimgurl;
                $data['tel'] = $tel;
                $data['type'] = $type;
                $data['ordernum'] = $ordernum;
                $data['money'] = $order_profit;
                $data['is_devote'] = 1;//工单收益
                $data['create_time'] = date('Y-m-d H:i:s');

                (new SuoProfit())->save($data);
                (new SuoMaster())->where('id', $uid)->inc('balance', $order_profit)->update();

            }
        }
        return;

    }


    //获取收益汇总列表
    public function getProfitList($uid, $level = 1,$is_devote=1)
    {
        $map = [];
        if ($level == 1) {
            $map[] = ['master_id', '=', $uid];
            $map[] = ['type', '=', 1];
        } else {
            $map[] = ['shop_id', '=', $uid];
            $map[] = ['type', '=', 2];
        }

        //贡献收益条件
        if ($level == 2 && $is_devote == 3) {
            $map[] = ['is_devote', '=', $is_devote];
        }
        $order = 'id desc';
        $field = '*';

        //当天开始时间
        $day_start_time = strtotime(date("Y-m-d", time()));
        //当天结束之间
        $day_end_time = $day_start_time + 60 * 60 * 24;

        $day_start_time_date = date("Y-m-d H:i:s", $day_start_time);
        $day_end_time_date = date("Y-m-d H:i:s", $day_end_time);

        $month = intval(date('m'));
        $year = intval(date('Y'));
        $day = intval(date('t'));

        $begin_month = mktime(0, 0, 0, $month, 1, $year);
        $end_month = mktime(23, 59, 59, $month, $day, $year);

        $begin_month_date = date("Y-m-d H:i:s", $begin_month);
        $end_month_date = date("Y-m-d H:i:s", $end_month);

        $list = (new SuoProfit())->where($map)->orderRaw($order)->field($field)->select()->toArray();

        $array = [];
        foreach ($list as $key => $value) {
            if (isset($array[$value['master_id']])) {
                $array[$value['master_id']]['total_profit_money'] += $value['money'];
                if ($value['create_time'] >= $day_start_time_date && $value['create_time'] < $day_end_time_date) {
                    $array[$value['master_id']]['day_profit_money'] += $value['money'];
                }
                if ($value['create_time'] >= $begin_month_date && $value['create_time'] <= $end_month_date) {
                    $array[$value['master_id']]['month_profit_money'] += $value['money'];
                }
            } else {
                $value['tel_text'] = substr_replace($value['tel'], '****', 3, 4);
                $array[$value['master_id']] = $value;
                $array[$value['master_id']]['day_profit_money'] = $value['money'];
                $array[$value['master_id']]['month_profit_money'] = $value['money'];
                $array[$value['master_id']]['total_profit_money'] = $value['money'];
            }
        }
        return $array;

    }

    //获取收益明细
    public function getProfitDetail($uid, $level = 1, $time = '', $page = 1, $is_devote = 1)
    {
        $map = [];
        //贡献收益条件
        if ($level == 1) {
            $map[] = ['master_id', '=', $uid];
            $map[] = ['is_devote', '=', $is_devote];
        }else {
            $map[] = ['shop_id', '=', $uid];
            $map[] = ['type', '=', 2];
        }


        $order = 'id desc';
        $field = '*';
        $page_size = 10;
        if ($time) {
            $list = (new SuoProfit())->where($map)->whereMonth('create_time', $time)->orderRaw($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
        } else {
            $list = (new SuoProfit())->where($map)->orderRaw($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
        }
        return $list;
    }


    //获取收益统计
    public function getProfitStatistics($uid, $level = 1, $is_devote = 1)
    {
        $map = [];
        if ($level == 1) {
            $map[] = ['master_id', '=', $uid];
            $map[] = ['is_devote', '=', $is_devote];

        } else {
            $map[] = ['shop_id', '=', $uid];
            $map[] = ['type', '=',2];
        }


        //今日收益
        $day_profit_money = (new SuoProfit())->where($map)->whereDay('create_time')->sum('money');

        // 上月收益
        $last_month_profit_money = (new SuoProfit())->where($map)->whereMonth('create_time', 'last month')->sum('money');

        // 本月收益
        $month_profit_money = (new SuoProfit())->where($map)->whereMonth('create_time')->sum('money');

        //总收益
        $total_profit_money = (new SuoProfit())->where($map)->sum('money');
        $data = [];
        $data['day_profit_money'] = $day_profit_money;
        $data['month_profit_money'] = $month_profit_money;
        $data['last_month_profit_money'] = $last_month_profit_money;
        $data['total_profit_money'] = $total_profit_money;
        return $data;
    }

    //评论列表
    public function getcommentlist($map)
    {
        $res['count'] = 0;
        $page_num = C('page_num');
        $page_num = intval(isset($map['page_num']) ? ($map['page_num'] ?: $page_num) : $page_num);
        $list = Db::name('suo_order_comment')->alias('a')
            ->when(isset($map['id']) && $map['id'], function ($query) use ($map) {
                $query->where('id', $map['id']);
            })
            ->when(isset($map['order_id']) && $map['order_id'] != '', function ($query) use ($map) {
                $query->where('order_id', $map['order_id']);
            })
            ->when(isset($map['page']) && $map['page'], function ($query) use ($map, $page_num) {
                $query->page(intval($map['page']), $page_num);
            })
            ->when(isset($map['master_id']) && $map['master_id'] != '', function ($query) use ($map) {
                $query->where('master_id', $map['master_id']);
            })
            ->when(isset($map['user_id']) && $map['user_id'] != '', function ($query) use ($map, $page_num) {
                $query->where('user_id', $map['user_id']);
            })
            ->when(isset($map['status']) && $map['status'] != '', function ($query) use ($map, $page_num) {
                $query->where('status', $map['status']);
            });
        if (isset($map['page']) && $map['page'] == 1) {
            $res['count'] = $list->count();
            $res['page_num'] = $page_num;
        }

        if (isset($map['find']) && $map['find'] == 1) {
            $info = $list->find();
            if ($info) {

                    $info = $this->chandle($info);

            } else {
                $info = null;
            }
            return $info;
        }
        $obj = $list->order("id desc")
            ->select();
        if ($obj) {
            $list = $obj->toArray();
            if ($list) {
                foreach ($list as &$v) {
                    $v = $this->chandle($v);
                }
            }
        } else {
            $list = [];
        }
        $res['list'] = $list;
        return $res;
    }

    public function chandle($v)
    {
        if ($v) {
            $v['images_arr'] = [];
            if ($v['images']) {
                $v['images_arr'] = json_decode($v['images'], true);
                if ($v['images_arr']) {
                    $v['images_arr'] = array_map('get_img_url', $v['images_arr']);
                }
            }
            $v['address'] = '广西南宁';
            $uinfo = (new User())->getUserInfo($v['user_id']);
            $v['user_id_str'] = getname($v['user_id'], 2);
            $v['headimgurl'] = $uinfo['headimgurl'];


            $uinfo = (new User())->getUserInfo($v['user_id']);
            $v['user_id_str'] = getname($v['user_id'], 2);
            $v['headimgurl'] = $uinfo['headimgurl'];
//            $v['star_arr'] =
            $v['master_info'] = null;
            if ($v['master_id']) {
                $minfo = (new SuoMaster())->getInfo($v['master_id']);
                $v['master_info'] = [
                    'realname' => $minfo['realname'],
                    'headimgurl' => $minfo['headimgurl'],
                    'remark' => $minfo['remark'],
                    'shop_name' => $minfo['shop_name'],
                    'shop_class' => $minfo['shop_class'],

                    'remark' => $minfo['remark'],

                    'tel' => $minfo['tel'],
                ];
            }
        }
        return $v;
    }

}
