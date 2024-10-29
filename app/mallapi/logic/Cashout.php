<?php

namespace app\mallapi\logic;

use app\admin\model\SystemGroup;
use app\common\model\Weixin;
use app\user\model\UserBrodj;
use app\user\model\UserCashoutChannel;
use think\facade\Db;

class Cashout
{

    public $clear;
    public $uinfo;
    public $data;
    public $model;
    public $channel_model;
    public $cashout_name;

    public function __construct()
    {
        $this->model = new \app\common\model\User();
        $this->channel_model = new UserCashoutChannel();
        $this->cashout_name = 'user_cashout_apply';
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }


    //提现
    public function take()
    {
        $cate = $this->in['cate'] ? $this->in['cate'] : 'bro';
        $type = input('type',1);
        $can_cate = [
            'bro' => '礼包',
           'money' => '余额',
            'jinhuoquan' => '进货券',
        ];
        $this->data['card_name'] = "银行卡管理";

        if (!in_array($cate, array_keys($can_cate))) {
            return ['status' => 0, 'info' => '缺少cate'];
        }
        // $min_bro_money = C("qiti_{$cate}");
        $min_bro_money = C("min_bro_money");
        $this->data['finfo'] = $this->model->getFinance($this->uinfo['id']);
        $this->data['total'] = $this->data['finfo'][$cate];

        if ($cate == 'bro') {
            $this->data['djz'] = Db::name('user_brodj')->where([
                    ['user_id', '=', $this->uinfo['id']],
                    ['expire', '>', date('Y-m-d H:i:s')],
                ])->sum('total') + 0;
            $this->data['total'] = $this->data['total'] - $this->data['djz'];
        }
        $where['user_id'] = $this->uinfo['id'];
        $this->data['bank_list'] = $this->channel_model->getList($where, 1);
        if (!$this->data['bank_list']) {
            $this->data['bank_list'][] = ['text' => '没有渠道信息！'];
        }
        $agency_fee = C("{$cate}_agency_fee");
        $agency_fee = $agency_fee ?: 0;

        $agency_ratio = C("bro_agency_ratio");
        $agency_ratio = $agency_ratio ?: 0;

        if ($this->in['flag'] == 'get') {
            $is = $this->channel_model->where(['user_id' => $this->uinfo['id']])->value('id');
//            p(((new SystemGroup())->getCacheOne('tixianguize')));
            $this->data['msg'] = ((new SystemGroup())->getCacheOne('tixianguize'))['content'];
            $this->data['show_yh'] = $is ? 0 : 1;
            $this->data['name'] = $can_cate[$cate];
            $this->data['as'] = $can_cate;
            $this->data['show_yh'] = $is ? 0 : 1;

            $this->data['shouxu'] = "手续费 {$agency_ratio}%，";



            return array('status' => 1, 'data' => $this->data);
        }
        if ($this->in['flag'] == 'sub') {
            /*$shi = date('H');
            if ($shi < 8 || $shi > 18) {
                return array('status' => 0, 'info' => "提现时间为8：00—18：00");
            }*/



//            if ($this->in['money'] <= 0) {
//                return array('status' => 0, 'info' => "请输入金额");
//
//            }
            $money = number_format($this->in['money'], 2, '.', '');
            $tixian_money = Db::name($this->cashout_name) -> where('user_id',$this->uinfo['id']) -> sum('money');
            $order_total_price = $this->uinfo['order_total_price'];
            $tixian_jine = C('tixian_jine');
            $ketixian = $order_total_price + $tixian_jine - $tixian_money;

            if($order_total_price + $tixian_jine <= $tixian_money+$money){
                if($ketixian > 0 && $ketixian < $money){
                    return array('status' => 0, 'info' => "可提现金额：".$ketixian.'元');
                }else{
                    return array('status' => 0, 'info' => "已超出可提现金额");
                }

            }
            if ($money <= 0) {
                return array('status' => 0, 'info' => "请输入金额");
            }
            if ($this->data['total'] < $money) {
                return array('status' => 0, 'info' => "金额不足");
            }

//            if($type == 2){ //兑换股票
//                $this->model->handleUser('gongxianzhi', $this->uinfo['id'], $money, 1, array('cate' => 1, 'ordernum' => ''));
//                $cate_num = $cate =='jinhuoquan'?5:11;
//                $this->model->handleUser($cate, $this->uinfo['id'], $money, 2, array('cate' => $cate_num));
//
//                return array('status' => 1, 'info' => '兑换成功');
//            }


            $bankInfo = $this->channel_model->getInfo($this->in['bank_id'], false);

//             if (!$this->in['bank_id']) {
//                return array('status' => 0, 'info' => "请选择银行卡");
//            }
//
//
//            if (!$bankInfo) {
//                return array('status' => 0, 'info' => "请选择银行卡!");
//            }
//            if ($bankInfo['user_id'] != $this->uinfo['id']) {
//                return array('status' => 0, 'info' => "请选择银行卡!!");
//            }

            // dump($min_bro_money);exit;
            if ($money < $min_bro_money && $bankInfo['cate'] == 'bank') {
                return array('status' => 0, 'info' => "最低{$min_bro_money}元");
            }
            if ($money < 1) {
                return array('status' => 0, 'info' => "最低1元");
            }




            $plus_total =  $agency_ratio  * $money / 100;
//            if ($agency_fee > 0) {
//                $plus_total = $plus_total + (($agency_fee / 100) * $money);
//            }
            if ($plus_total >= $money) {
                return array('status' => 0, 'info' => "提现金额需大于手续费");
            }
            $cate_num = $cate =='jinhuoquan'?5:11;
            $this->model->handleUser($cate, $this->uinfo['id'], $money, 2, array('cate' => $cate_num));

            Db::name($this->cashout_name)->insert(array(
                'cate' => $cate,
                'user_id' => $this->uinfo['id'],
                'time' => date('Y-m-d H:i:s'),
                'status' => 0,
                'money' => $money,
                'real_total' => ($money - $plus_total),
                'plus_total' => $plus_total,


                'name' => $bankInfo['name'],
                'tel' => $bankInfo['tel'],
                'address' => $bankInfo['address'],
                'num' => $bankInfo['num'],
                'realname' => $bankInfo['realname'],
                'channel_cate' => $bankInfo['cate'],

            ));
            $msg = "已申请提现，请等待客服打款";
            $this->data['title'] = '系统提示';
            $this->data['content'] = $msg;
            return array('status' => 1, 'data' => $this->data);
        }

        return array('status' => 1, 'data' => $this->data);

    }

    public function updateMoney(){
        $this->data['finfo'] = $this->model->getFinance($this->uinfo['id']);
        $bro = $this->data['finfo']['bro'];
        $this->model->where('id',$this->uinfo['id']) -> inc('money',$bro) -> update();
        $this->model->where('id',$this->uinfo['id']) -> update(['bro'=>0]);
        return array('status' => 1, 'info' => '操作成功');
    }
    //提现日志
    public function log()
    {
        $userModel = new \app\mall\model\User();
        $this->in['page'] = $this->in['page'] ?: 1;
        $data = $userModel->put_bro_log($this->uinfo['id'], $this->in['page']);
        $this->data['list'] = $data;
        $this->data['count'] = count($data);
        return array('status' => 1, 'data' => $this->data);
    }

    //渠道列表
    public function channel_list()
    {
        $where = array('user_id' => $this->uinfo['id']);
        $this->data['list'] = $this->channel_model->getList($where, $this->in['page'] ? intval($this->in['page']) : 1);
        $this->filter_name();
        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);

    }

    //删除渠道
    public function channel_del()
    {
        $where = array('user_id' => $this->uinfo['id'], 'id' => $this->in['id']);
        $this->channel_model->where($where)->limit(1)->delete();
        return ['status' => 1, 'info' => lang('s')];
    }

    //添加渠道
    public function channel_item()
    {
        $cate = [];
        $cs = $this->channel_model->getLan('cate');
        foreach ($cs as $k => $v) {
            $cate[] = [
                'name' => $v,
                'val' => $k
            ];
        }
        $rules = [
            'weixin' => ['realname', 'tel','num'],
            'bank' => ['realname', 'tel', 'name', 'address', 'num'],
            'alipay' => ['realname', 'tel', 'num'],
        ];
        if ($this->in['flag'] == 'sub') {
            tool()->func('check');
            if (!check_tel($this->in['tel'])) {
                return ['status' => 0, 'info' => '手机格式不正确'];
            }
            $rule = $rules[$this->in['cate']];
            if (!$rule) {
                return ['status' => 0, 'info' => '渠道错误'];
            }
            $save = [
                'cate' => $this->in['cate']
            ];
            foreach ($rule as $v) {
                // if (!$this->in[$v]) {
                //     return ['status' => 0, 'info' => '请完善表单信息'];
                // }
                $save[$v] = $this->in[$v];
            }
            if ($this->in['id']) {
                $this->channel_model->removeOption()->where(array('id' => $this->in['id'], 'user_id' => $this->uinfo['id']))->save($save);
            } else {
                $save['user_id'] = $this->uinfo['id'];
                $this->channel_model->removeOption()->insert($save);
            }
            return array('status' => 1, 'info' => '操作成功', 'data' => $this->data);

        }
        if ($this->in['id'] && $this->in['flag'] == 'get') {
            $info = $this->channel_model->getInfo($this->in['id'], false);
            if ($info['user_id'] != $this->uinfo['id'] || !$info['user_id']) {
                unset($info);
            } else {
                $this->data['info'] = $info;
            }
        }
        $this->data['cate'] = $cate;


        $this->filter_name();
        return array('status' => 1, 'data' => $this->data);
    }

    private
    function filter_name()
    {
        $this->data['card_name'] = '银行卡';
        $this->data['bank_name'] = '银行';
        $this->data['khh_name'] = '开户行';
        $this->data['card_num'] = '账号';
        $this->data['khr_name'] = '姓名';


    }
}
