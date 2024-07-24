<?php

namespace app\Masterapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoCate;
use app\admin\model\SuoOrder;
use app\common\lib\Util;
use app\common\model\SuoMaster;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use app\user\model\UserAddress;
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
        $this->SuoCate = new SuoCate();
        $this->SuoMaster = new SuoMaster();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }


    //订单列表
    public function getOrderListData()
    {

        $page = $this->in['page'];
        $type = $this->in['type'];
        $order_type = $this->in['order_type'];

        $page_size = 10;
        $cate_id = $this->in['cate_id'];
        $map = [];
        $map[] = ['status', '=', 1];
        $map[] = ['pay_status', '=', 1];
        $map[] = ['master_id', '=', null];
        if ($cate_id) {
            $map[] = ['cate_id', '=', $cate_id];
        }

        if ($type) {
            $map[] = ['type', '=', $type];
        }

        $lat = $this->uinfo['lat']?:0;
        $lng = $this->uinfo['lng']?:0;
        $field = [
            "*,concat(province,city,country) as province_address,concat(reference,address) as address",
            "ROUND(6378.138*2*ASIN(SQRT(POW(SIN(({$lat}*PI()/180-lat*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(lat*PI()/180)*POW(SIN(({$lng}*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance"];


        $order = 'id desc';
        if ($order_type == 1) {
            $order = 'distance asc';
        }
        if ($order_type == 2) {
            $order = 'total desc';
        }

        $range = intval(C('range')) * 1000;
        $having = "distance <= {$range}";


        $list = $this->model->getList($map, $page, $page_size, $order, $field,$having);


        foreach ($list as $key => &$value) {
            if (isset($value['distance'])) {
                switch ($value['distance']) {
                    case ($value['distance'] >= 1000):
                        $value['distance_text'] = round(($value['distance'] / 1000), 2) + 0 . 'km';
                        break;
                    default:
                        $value['distance_text'] = $value['distance'] . 'm';
                        break;
                }
            }
        }

        $data = [];
        $data['list'] = $list;
        return ['status' => 1, 'data' => $data];
    }


    //订单详情
    public function getOrderDetail()
    {
        $id = $this->in['id'];
        $uid = $this->uinfo['id'];
        $ordernum = $this->model->where('id', $id)->value('ordernum');
        $this->in['ordernum'] = $ordernum;
        $this->in['find'] = 1;
        $level = $this->uinfo['level'];
        $order_data = $this->model->getMapList($this->in);

        $order_data['is_shop'] = 0;
        //师傅挂靠门店，师傅抢单或者被指定：当锁匠师傅挂靠门店的时候，门店下的师傅所抢的订单，或者被指定的订单，只能由师傅去操作,而门店这里只能看工单详情和收益即

        if($order_data['master_id'] != $order_data['shop_id'] && $order_data['shop_id'] == $uid){
            $order_data['is_shop'] = 1;
        }

        $old_ordernum = $order_data['old_ordernum'];
        $old_id = $this->model->where('ordernum', $old_ordernum)->value('id');
        $order_data['old_id'] = $old_id;
        $order_data['province_address'] = $order_data['province'] . $order_data['city'] . $order_data['country'];
        $order_data['address'] = $order_data['reference'] . $order_data['address'];


//        $order_data['profit'] = $order_data['profit_array'][$level-1]??0;
        if($level == 1){
            $order_data['profit'] = $order_data['master_profit'];
        }elseif($level == 2 && $uid != $order_data['master_id']){
            $order_data['profit'] =  $order_data['shop_profit'];
        }elseif($level == 2 && $uid == $order_data['master_id']){
            $order_data['profit'] = $order_data['master_profit']+$order_data['shop_profit'];
        }else{
            $order_data['profit']  = 0;
        }
        $order_data['profit'] = "{$order_data['profit']}";

        $user = Db::name('user')->where('id', $order_data['user_id'])->field('*')->find();
        $data = [];
        $data['user'] = $user;
        $data['data'] = $order_data;
        return ['status' => 1, 'data' => $data];
    }

    //我的工单
    public function getMyOrderList()
    {
        $page = $this->in['page'];
        $status = $this->in['status'];
        $master_id = $this->in['master_id'];
        $keywords = $this->in['keywords'];
        $uid = $this->uinfo['id'];
        $level = $this->uinfo['level'];
        $page_size = 10;

        $map = [];
        if ($level == 1) {
            $map[] = ['master_id', '=', $uid];
//            $map[] = ['pay_status', '=', 1];
        } else {
            $map[] = ['master_id|shop_id', '=', $uid];
            if ($master_id) {
                $map[] = ['master_id', '=', $master_id];
            }
        }


        if ($status) {
            $map[] = ['status', '=', $status];
        }
        if ($keywords !== '') {
            $map[] = ['ordernum|linkman|tel', 'like', '%' . $keywords . '%'];
        }
//      sqlListen();
        $lat = $this->uinfo['lat']?:0;
        $lng = $this->uinfo['lng']?:0;
        $field = ["*,concat(province,city,country) as province_address,concat(reference,address) as address", "ROUND(6378.138*2*ASIN(SQRT(POW(SIN(({$lat}*PI()/180-lat*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(lat*PI()/180)*POW(SIN(({$lng}*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance"];

        $order = 'taking_time desc, id desc';

        $list = $this->model->getList($map, $page, $page_size, $order, $field);
        foreach ($list as $key => &$value) {
            if($level == 1){
                $value['profit'] = $value['master_profit'];
            }elseif($level == 2 && $uid != $value['master_id']){
                $value['profit'] =  $value['shop_profit'];
            }elseif($level == 2 && $uid == $value['master_id']){
                $value['profit'] = $value['master_profit']+$value['shop_profit'];
            }else{
                $value['profit']  = 0;
            }



            //为了防止JS显示出问题
            $value['profit']  = "{$value['profit']}";

            $value['is_shop'] = 0;
            //师傅挂靠门店，师傅抢单或者被指定：当锁匠师傅挂靠门店的时候，门店下的师傅所抢的订单，或者被指定的订单，只能由师傅去操作,而门店这里只能看工单详情和收益即

            if($value['master_id'] != $value['shop_id'] && $value['shop_id'] == $uid){
                $value['is_shop'] = 1;
            }

            if (isset($value['distance'])) {
                switch ($value['distance']) {
                    case ($value['distance'] >= 1000):
                        $value['distance_text'] = round(($value['distance'] / 1000), 2) + 0 . 'km';
                        break;
                    default:
                        $value['distance_text'] = $value['distance'] . 'm';
                        break;
                }
            }
        }

        $data = [];
        $data['data'] = $list;
        $data['level'] = $level;
        $data['yylist'] = (new SystemGroup())->getCache('suojquiao');

        return ['status' => 1, 'data' => $data];

    }

    //取消
    public function master_cacel()
    {
        $add = ['id' => $this->in['id'], 'master_cacel_msg' => $this->in['master_cacel_msg']];
        if ($this->uinfo['level'] == 2) {
            $add['shop_id'] = $this->uinfo['id'];
        } else {
            $add['master_id'] = $this->uinfo['id'];
        }
        return $this->model->master_cacel($add);
    }

    //接单
    public function jiedan()
    {
        $info = $this->model->getInfo($this->in['id']);
        if ($info['master_taking'] != 1 && $info['master_taking_again'] != 1) {
            return ['status' => 1, 'info' => '该订单已不存在'];
        }
        $minfo = (new \app\admin\model\SuoMaster())->getInfo($this->in['master_id']);
        if ($minfo['shop_id'] != $this->uinfo['id']) {
            return ['status' => 1, 'info' => '无权操作'];
        }

        $order_id = $this->in['id'];
        $data = [];
        $data['order_id'] = $order_id;
        $data['order_status'] = 2;
        $data['uid'] = $this->in['master_id'];

        return $this->model->updateOrderStatus($data);
    }

    //更新订单状态
    public function updateOrderStatus()
    {
        $order_id = $this->in['id'];
        $order_status = $this->in['status'];
        $uid = $this->uinfo['id'];
        $shop_id = $this->uinfo['shop_id'];
        $finsh_img = $this->in['finsh_img']??[];
        $finsh_remarks = $this->in['finsh_remarks']??'';
        $reject_remarks = $this->in['reject_remarks']??'';

        $data = [];
        $data['order_id'] = $order_id;
        $data['order_status'] = $order_status;
        $data['uid'] = $uid;
        $data['finsh_img'] = $finsh_img;
        $data['finsh_remarks'] = $finsh_remarks;
        $data['reject_remarks'] = $reject_remarks;

        return $this->model->updateOrderStatus($data);
    }

    //订单服务完成
    public function orderServiceFinish()
    {
        $id = $this->in['id'];
        $uid = $this->uinfo['id'];
        $update_data = [];
        $update_data['status'] = 4;
        $this->model->where('id', $id)->update($update_data);
        return ['status' => 1, 'info' => '提交成功'];
    }


    public function getServiceList()
    {

        // $field = 'id,name';
        // $list = Db::name('suo_service') -> field($field) -> select() -> toArray();
        $list = lang('order_type_arr');
        $arr = [];
        $arr[0]['type'] = 1;
        $arr[0]['name'] = '全部';
        $arr[0]['icon'] = 'home/icon-type-1.png';

        array_walk($arr, function ($item) use (&$list) {
            array_unshift($list, $item);
        });
        $data = [];
        $data['list'] = $list;


        $data['list'] = $list;

        return ['status' => 1, 'data' => $data];
    }

    //加单
    public function create()
    {
        if (!in_array($this->in['flag'], ['get', 'sub'])) {
            return ['status' => 0, 'info' => '错误'];
        }

        $old_ordernum = $this->in['old_ordernum'];
        $order_data = $this->model->where('ordernum', $old_ordernum)->field('*,lat,lng,user_id,master_id,concat(province,city,country) as province_address,concat(reference,address) as address,tel,linkman name')->find();
        //客户
        $user_id = $order_data['user_id'];
        $lat = $order_data['lat'];
        $lng = $order_data['lng'];
        $master_id = $order_data['master_id'];//师傅
        $this->data['order_type_arr'] = lang('master_order_type_arr');
        $this->data['address_info'] = $order_data;
        // dump($order_data);exit;
        // if($this->in['address_id']){
        //     $this->data['address_info'] = (new UserAddress())->get_info($user_id,$this->in['address_id']);

        // }
        if ($this->in['type'] && $this->in['product_id']) {
            $productInfo = (new SuoProduct())->getInfo($this->in['product_id']);
            $this->data['product_info'] = $productInfo;
            $this->data['total'] = $productInfo['price' . $this->in['type']];
        }
        if (in_array($this->in['type'], [3])) {
            if ($this->in['num'] <= 0) {
                return ['status' => 0, 'info' => '请输入数量'];
            }
            $this->in['num'] = ceil($this->in['num']);
        } else {
            $this->in['num'] = 1;
        }
        if ($this->data['total'] <= 0) {
            $this->data['total'] = '请选择';
        }else{
            $this->data['total'] =  $this->data['total'] *  $this->in['num'];
        }
        if ($this->in['flag'] == 'sub') {
//            if (!$this->data['address_info']) {
//                return ['status' => 0, 'info' => '请选择地址'];
//            }
            if (!$this->in['sel_date']) {
                return ['status' => 0, 'info' => '请选择上门时间'];
            }
            if (!$this->in['sel_time']) {
                return ['status' => 0, 'info' => '请选择上门时间'];
            }
            if ($this->data['total'] <= 0) {
                return ['status' => 0, 'info' => '请选择门锁类型'];
            }

            $tt = explode('-', $this->in['sel_time']);
            $cc = (new SuoCate())->getInfo($this->data['product_info']['cate_id']);
            $clear = [
                'user_id' => $user_id,
                'master_id' => $master_id,
                'old_ordernum' => $old_ordernum,
                'ordernum' => get_ordernum(),
                'total' => $this->data['total'],
                'address' =>  $order_data['address'],
                'message' => $this->in['message'] . '',
                'province' =>  $order_data['province_name'],
                'city' =>  $order_data['city_name'],
                'pid'=> $order_data['pid'],
                'country' => $order_data['country_name'],
                'reference' => $order_data['reference'],
                'type' => $this->in['type'],
                'imgs' => $this->in['imgs'] ? json_encode($this->in['imgs'], 323) : '',
                'product_id' => $this->data['product_info']['id'],
                'product_name' => $this->data['product_info']['name'],
                'tel' => $order_data['tel'],
                'linkman' => $order_data['linkman'],
                'sel_date' => $this->in['sel_date'],
                'sel_time1' => $tt[0],
                'sel_time2' => $tt[1],
                'lat' => $lat,
                'lng' => $lng,
                'num' => $this->in['num'],
                'cate_id' => $cc['id'],
                'status' => 0,
                'cate_name' => $cc['name'],
                'remarks' => $this->in['remarks'],
                'is_jiadan' => 1,
                'order_type' => 1,
            ];
            $this->model->save($clear);

        }
        return ['status' => 1, 'data' => $this->data];

    }


    public function get_cxx_ewm() {
        //本地环境使用测试二维码
        $temp_ewm_path = C('temp_ewm_path').'order/';
        if(!is_dir($temp_ewm_path)){
            mkdir($temp_ewm_path,0775,true);
        }
        $scene = "{$this->in['ordernum']}";

        $file = $temp_ewm_path.$scene.'.png';
        if(!file_exists($file)){

            if (!class_exists('weixin')) {
                include INCLUDE_PATH . 'weixin/weixin.class.php';
            }
            $weixin = new \weixin(C('appid'), C('apppwd'));
            $access_token = $weixin->get_access_token();
            $url2 = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
            $url = "pages/suo/order/item/item";

            $data = json_encode(
                array(
                    'scene' => ($scene),
                    'page' => "{$url}",
                )
            );
            $res = $weixin->request_post($url2, $data);
            $t = json_decode($res, true);
            if (is_array($t)) {
                return array('status' => 0, 'info' => $t['errmsg']);
            } else {
                file_put_contents($file,$res);
            }

        }
        $this->data['img_url'] =  C('wapurl').trim($file,'.');
        return ['status' => 1, 'data' => $this->data];


    }
}
