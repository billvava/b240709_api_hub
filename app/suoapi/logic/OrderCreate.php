<?php

namespace app\suoapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoCate;
use app\admin\model\SuoMaster;
use app\admin\model\SuoOrder;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use app\common\model\User;
use app\user\model\UserAddress;
use think\App;
use think\facade\Db;

class OrderCreate
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

    public function create(){
//        address_id:t.address_id,
//				product_id:t.product_id,
//				sel_date:t.sel_date,
//				sel_time:t.sel_time,
//				imgs:t.imgs,
//				type:t.info.type,
//				num:1,
//				message:t.message,
//				flag:'get'
        if(!in_array($this->in['flag'],['get','sub'])){
            return ['status'=>0,'info'=>'错误'];
        }
        $this->data['order_type_arr'] = lang('order_type_arr');
        $this->data['address_info'] = null;
        if($this->in['address_id']){
            $this->data['address_info'] = (new UserAddress())->get_info($this->uinfo['id'],$this->in['address_id']);
        }
        if($this->in['type'] && $this->in['product_id']){
           $productInfo =  (new SuoProduct())->getInfo($this->in['product_id']);
           $this->data['product_info'] =  $productInfo;
           $this->data['total'] =  $productInfo['price'.$this->in['type']];
        }
        if(in_array($this->in['type'],[3] )){
            if($this->in['num'] <= 0 ){
                return ['status'=>0,'info'=>'请输入数量'];
            }
            $this->in['num'] = ceil($this->in['num']);
        }else{
            $this->in['num'] = 1;
        }
        if($this->data['total'] <= 0){
            $this->data['total'] = '请选择';
        }else{
            $this->data['total'] =  $this->data['total'] *  $this->in['num'];
        }

        if($this->in['flag'] == 'sub'){
            if(!$this->data['address_info'] ){
                return ['status'=>0,'info'=>'请选择地址'];
            }
            if(!$this->in['sel_date'] ){
                return ['status'=>0,'info'=>'请选择上门时间'];
            }
            if(!$this->in['sel_time'] ){
                return ['status'=>0,'info'=>'请选择上门时间'];
            }
            if($this->data['total'] <= 0 ){
                return ['status'=>0,'info'=>'请选择门锁类型'];
            }

            if(!$this->in['imgs']){
                return ['status'=>0,'info'=>'请上传图片'];
            }


            $tt = explode('-',$this->in['sel_time']);
            $cc =  (new SuoCate())->getInfo($this->data['product_info']['cate_id']);
            $pid = $this->uinfo['pid'] + 0;
            $clear = [
                'user_id'=>$this->uinfo['id'],
                'ordernum'=>get_ordernum(),
                'total'=>$this->data['total'] ,
                'address'=> $this->data['address_info']['address'],
                'message'=>$this->in['message'].'',
                'province'=>$this->data['address_info']['province_name'],
                'city'=>$this->data['address_info']['city_name'],
                'country'=>$this->data['address_info']['country_name'],

                'lat'=>$this->data['address_info']['lat'],
                'lng'=>$this->data['address_info']['lng'],
                'reference'=>$this->data['address_info']['reference'],
                'pid'=>$pid,

                'type'=>$this->in['type'],
                'imgs'=>$this->in['imgs']? json_encode($this->in['imgs'],323):'',
                'product_id'=> $this->data['product_info']['id'],
                'product_name'=> $this->data['product_info']['name'],
                'tel'=> $this->data['address_info']['tel'],
                'linkman'=> $this->data['address_info']['name'],
                'sel_date'=> $this->in['sel_date'],
                'sel_time1'=>$tt[0],
                'sel_time2'=>$tt[1],
                'num'=> $this->in['num'],
                'cate_id'=>$cc['id'],
                'cate_name'=>$cc['name'],
            ];
            if($this->in['master_id']){
                $model = (new SuoMaster());
                $info =  $model->getMapList([
                    'status'=>1,
                    'is_auth'=>1,
                    'is_work'=>1,
                    'type'=>1,
                    'find'=>1,
                    'id'=>$this->in['master_id']
                ]);
                if(!$info){
                    return ['status'=>0,'info'=>'该锁匠不可以预约'];
                }
                $clear['master_id'] = $this->in['master_id'];
                $clear['shop_id'] =$info['shop_id'];
                $clear['taking_time'] =date('Y-m-d H:i:s');
            }
            $this->model->save($clear);

            //去支付
            $wx_pay_type = C('pay_type');
            $notify_url = C('wapurl') . "/mallapi/Pay/suoye";
            tool()->classs('pay/Pay');
            $Pay = new \Pay($wx_pay_type);
            $clear['total'] = 0.01;
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
        return ['status' => 1, 'data' => $this->data];

    }
}
