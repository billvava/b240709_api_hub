<?php

namespace app\common\discount_act;

use app\user\model\UserAddress;
use think\App;
use think\facade\Db;

//地址处理
class SendType {

    //执行顺序，越小越在前面
    public $sort = -99;
    public $in;
    public $uinfo;
    public $data;
    public $userModel;
    public $is_can = 0;
    public $dot_scale;

    public function __construct() {
        $this->userModel = new \app\common\model\User();
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function get() {

        //快递相关
        //默认方式
        $def_send_type = 1;

        $this->data['can_ps_type'] = [1,2];
        //默认的配送方式
        if (!$this->in['send_type']) {
            $this->data['send_type'] = $def_send_type;
            $this->in['send_type'] = $def_send_type;
        }

        if (!in_array($this->in['send_type'], $this->data['can_ps_type'])) {
            return array('status' => 0, 'info' => '缺少配送类型');
        }

        $is_master = 0;
        $is_master = $this->in['is_master'];
        if ($this->in['send_type'] == 1) {
            $UserAddress = new UserAddress();
            if (!$this->in['address_id']) {
                $ad_info = $UserAddress->get_def_address($this->uinfo['id'],$is_master);
            } else {

                $ad_info = $UserAddress->get_info($this->uinfo['id'], $this->in['address_id'],$is_master);
            }
            $send_free = C('send_free');
            $send_free_money = C('send_free_money');
            $send_money = C('send_money');
            if ($send_free == 2 && $send_free_money > $this->data['goods_total']) {
                $this->data['delivery_total'] = $send_money;
            }
            $this->data['ad_info'] = $ad_info;
        }
        //自提
        if ($this->in['send_type'] == 2) {
            $this->data['ziti_info'] = array(
                'name' => C('mall_ziti_name'),
                'address' => C('mall_ziti_address'),
                'tel' => C('mall_ziti_tel'),
            );
        }
        $this->data['old_total'] += $this->data['delivery_total'];
        $this->data['total'] += $this->data['delivery_total'];




        return $this->returnData();
    }

    public function sub() {
        if (!$this->in['send_type'] ) {
            $this->in['send_type']  = 1;
        }
        if ($this->in['send_type'] == 1 && !$this->data['ad_info']) {
            return array('status' => 0, 'info' => '请选择地址');
        }

        $this->data['delivery_type'] =  $this->in['send_type'];
        $this->data['province'] = '';
        $this->data['city'] = '';
        $this->data['country'] = '';
        $this->data['address'] = '';
        $this->data['linkman'] = '';
        $this->data['tel'] = '';
        if($this->data['delivery_type']==1){
            $this->data['province'] = $this->data['ad_info']['province_name'];
            $this->data['city'] = $this->data['ad_info']['city_name'];
            $this->data['country'] = $this->data['ad_info']['country_name'];
            $this->data['address'] = $this->data['ad_info']['address'];
            $this->data['linkman'] = $this->data['ad_info']['name'];
            $this->data['tel'] = $this->data['ad_info']['tel'];
        }


        return $this->returnData();
    }

    public function returnData() {
        return [
            'status' => 1,
            'data' => $this->data,
            'clear_field' => ['delivery_total','delivery_type','province','city','country','address','linkman','tel'],
            'desc' => [

            ],
        ];
    }

}
