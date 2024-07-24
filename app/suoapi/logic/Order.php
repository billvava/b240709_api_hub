<?php

namespace app\suoapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoCate;
use app\admin\model\SuoMaster;
use app\admin\model\SuoOrder;
use app\admin\model\SuoOrderJia;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use app\com\model\HelpMsg;
use think\App;
use think\facade\Db;

class Order
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {
        $this->model = new SuoOrder();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function index()
    {
        $navs = $this->model->getLan('status');
        $tmp = [
            ['name' => '全部', 'status' => '', 'badge' => 0]
        ];
        $census = $this->model->census($this->uinfo['id']);
        foreach ($navs as $k => &$v) {
            if ($k != 0) {
                $tmp[] = ['name' => $v, 'status' => $k, 'badge' => $census["status{$k}"]];
            }
        }
        $this->data['navs'] = $tmp;
        $this->data['flag'] = '';

        return ['status' => 1, 'data' => $this->data];
    }

    public function load_list()
    {
        $this->in['user_id'] = $this->uinfo['id'];
//        $this->in['pay_status'] = 1;
        $this->data = $this->model->getMapList($this->in);
        return ['status' => 1, 'data' => $this->data];

    }
    public function user_pay(){
        $clear =  $this->model->getMapList([
            'find'=>1,
            'id'=>$this->in['id']
        ]);
        $wx_pay_type = C('pay_type');
        $notify_url = C('wapurl') . "/mallapi/Pay/suoye";
        tool()->classs('pay/Pay');
        $Pay = new \Pay($wx_pay_type);

        $clear['total'] = 0.01;
        $res = $Pay->pay([
            'appid' => C('appid'),
            'total' =>$clear['total'],
            'openid' => $this->uinfo['openid'],
            'ordernum' => $clear['ordernum'],
            'notify_url' => $notify_url,
        ]);
        if ($res['status'] == 1) {
            $res['pay_type'] = 4;
            $res['data']['pay_status'] = 0;
            return $res;
        } else {
            return ['status' => 0, 'info' => $res['info']];
        }
    }
    public function reset_order(){
        return $this->model->reset_order([
            'id'=>$this->in['id'],
            'user_id'=>$this->uinfo['id'],
            'type'=>'user'
        ]);
    }
    public function user_close(){
        return $this->model->user_close([
            'id'=>$this->in['id'],
            'user_id'=>$this->uinfo['id'],
            'type'=>'user'
        ]);
    }
    public function user_quxiao(){
        return $this->model->user_quxiao([
            'id'=>$this->in['id'],
            'user_id'=>$this->uinfo['id'],
            'type'=>'user'
        ]);
    }
    public function tousu_cacel(){
        $minfo = Db::name('help_msg')->where(['id'=> $this->in['id'],'user_id'=>$this->uinfo['id']])->find();
        if(!$minfo){
            return ['status'=>0,'info'=>'不存在'];
        }
        if($minfo['status'] != 0){
            return ['status'=>0,'info'=>'状态不正确'];
        }
        Db::name('help_msg')->where(['id'=> $this->in['id'],'user_id'=>$this->uinfo['id']])->save(['status'=>2,'up_time'=>date('Y-m-d H:i:s')]);
        $this->model->update(['tousu_status'=>0,],['id'=>$minfo['order_id']]);
        return ['status' => 1, 'info' => lang('s')];
    }
    //投诉
    public function tousu_sub(){
        if(!$this->in['content']){
            return ['status'=>0,'info'=>'请输入详细原因'];
        }
        if(!$this->in['user_qidai']){
            return ['status'=>0,'info'=>'请输入希望的解决方式'];
        }
        $this->in['user_id'] = $this->uinfo['id'];
        $this->in['ordernum'] = $this->in['ordernum'];
        $this->in['find'] = 1;

        $info = $this->model->getMapList($this->in);
        if($info['user_tousu'] != 1){
            return ['status'=>0,'info'=>'订单错误'];
        }
        Db::name('help_msg')->insert([
            'content'=>$this->in['content'],
            'imgs'=>$this->in['imgs']?json_encode($this->in['imgs'],323):'',
            'time'=>date('Y-m-d H:i:s'),
            'user_id'=>$this->uinfo['id'],
            'tel'=>$info['tel'],
            'title'=>'投诉',
            'user_qidai'=>$this->in['user_qidai'],
            'order_id'=>$info['id']
        ]);
        $this->model->update(['tousu_status'=>1,],['id'=>$info['id']]);
        return ['status' => 1, 'info' =>lang('s')];
    }

    public function item(){
        $this->in['user_id'] = $this->uinfo['id'];
        $this->in['ordernum'] = $this->in['ordernum'];
        $this->in['find'] = 1;
        if($this->in['id']){
            $this->in['id'] = $this->in['id'];
            unset(   $this->in['ordernum']);
        }

//        $this->in['type'] = 'admin';

        $this->data['info'] = $this->model->getMapList($this->in);
        if( $this->data['info']['tousu_status'] != 0){

            $minfo = Db::name('help_msg')->where(['order_id'=> $this->data['info']['id']])->order('id desc')->find();
            if($minfo){
                $minfo = (new HelpMsg())->handle($minfo);
            }
            $this->data['tousu_info'] = $minfo;
        }
        $this->data['companytel'] = C('companytel');
        return ['status' => 1, 'data' => $this->data];
    }
    public function comment(){
        $this->in['user_id'] = $this->uinfo['id'];
        $this->in['ordernum'] = $this->in['ordernum'];
        $this->in['find'] = 1;

        $info = $this->model->getMapList($this->in);
        if($info['user_topingjia'] != 1){
            return ['status'=>0,'info'=>'订单错误'];
        }
        Db::name('suo_order_comment')->insert([
            'content'=>$this->in['content'],
            'images'=>$this->in['imgs']?json_encode($this->in['imgs'],323):'',
            'is_img'=>$this->in['imgs']?1:0,
            'time'=>date('Y-m-d H:i:s'),
            'user_id'=>$this->uinfo['id'],
            'tel'=>$info['tel'],
            'star'=>$this->in['star'],
            'order_id'=>$info['id'],
            'master_id'=>$info['master_id'],
        ]);
        $this->model->update(['comment_status'=>1,],['id'=>$info['id']]);
        (new SuoMaster())->updateStar($info['id']);



        return ['status' => 1, 'info' =>lang('s')];

    }
    //加价
    public function jiajia()
    {
        $money = round($this->in['money'], 2);
        if ($money < 0.01) {
            return ['status' => 0, 'info' => '最低0.01'];
        }
        $info = $this->model->getMapList([
            'user_id' => $this->uinfo['id'],
            'id' => $this->in['id'],
            'find' => 1
        ]);
        if (!$info || $info['status'] != 1) {
            return ['status' => 0, 'info' => '订单错误'];
        }
        $clear = [
            'user_id' => $this->uinfo['id'],
            'ordernum' => get_ordernum(),
            'total' =>$money,
            'order_id' => $this->in['id']
        ];
        (new SuoOrderJia())->save($clear);
        //去支付
        $wx_pay_type = C('pay_type');
        $notify_url = C('wapurl') . "/mallapi/Pay/suoye_jiajia";
        tool()->classs('pay/Pay');
        $Pay = new \Pay($wx_pay_type);
        $res = $Pay->pay([
            'appid' => C('appid'),
            'total' => $clear['total'],
            'openid' => $this->uinfo['openid'],
            'ordernum' => $clear['ordernum'],
            'notify_url' => $notify_url,
        ]);
        if ($res['status'] == 1) {
            $res['pay_type'] = 4;
            $res['data']['pay_status'] = 0;
            return $res;
        } else {
            return ['status' => 0, 'info' => $res['info']];
        }
    }


}
