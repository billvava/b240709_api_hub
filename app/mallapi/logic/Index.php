<?php


namespace app\mallapi\logic;

use app\home\model\O;
use app\mall\model\MsGoods;
use think\facade\Db;
use app\user\model\UserAddress;
class Index {

    public $uinfo;
    public $in;
    public $data;
    public $request;

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function index() {
        $rank = $this->uinfo; //2=vip
        $Ad = new \app\admin\model\Ad();
        $this->data['banner'] = $Ad->get_ad('mall_banner');

        $map = [];
        $map[] = ['english','<>','manghe'];
        $category_list = Db::name('mall_goods_category') -> where($map)->where(array('is_show' => 1, 'pid' => 0)) ->limit(4) ->order("sort asc")->cache(true)->select()->toArray();
        $category_list1 = Db::name('mall_goods_category') -> where($map)->where(array('is_show' => 1)) -> where('pid','<>',0) ->order("sort asc")->select()->toArray();

        $category_id_array = [];
        foreach ($category_list as $k => &$v) {
            $v['thumb'] = get_img_url($v['thumb']);

        }

        foreach ($category_list1 as $k => &$v) {
            $category_id_array[] = $v['category_id'];
        }

        $this->data['category_list2'] = [];
        if($category_list){
            $this->data['category_list2'] = array_slice($category_list,0,7);
        }

//        array_unshift($category_list,['name'=>'全部','category_id'=>0]);
        $this->data['category_list'] = $category_list;
        $map = [];
        $map = ['status'=>1,'page'=>1,'is_recommend'=>1];
        if($this->in['page'] > 0){
            $map['page'] = 1;
        }

        if($this->in['category_id'] > 0){
            $category_id_array[] = $this->in['category_id'];
        }
        $map1 = [];
        if($category_id_array){
            $map1[] = ['category_id','in',$category_id_array];
        }

        $r = \app\mall\model\Goods::get_data($map,$map1);
        $this->data['goods_list'] = $r['list'];
        $where = [
            ['start','<',date('Y-m-d H:i:s')],
            ['end','>',date('Y-m-d H:i:s')],
            ['status','=',1],
            ['type','=',1],
        ];
       $this->data['msg_list'] =  (new MsGoods())->getList($where);
        $this->data['end_second'] = 0;
        if($this->data['msg_list']){
            $this->data['end_time'] =  $this->data['msg_list'][0]['end'];
            $this->data['end_second'] = strtotime( $this->data['end_time']) - time();

        }

        return array('status' => 1, 'data' => $this->data);
    }


    //获取盲盒商品
    public function getMangheGoodList(){

        $category_id = Db::name('mall_goods_category') -> where('english','manghe') -> value('category_id');
        $list = Db::name('mall_goods') -> where('category_id',$category_id) -> select();
        $count = count($list) -1;
        $rand = rand(0,$count);
        $goods = $list[$rand]??'';
        $goods['thumb'] = get_img_url($goods['thumb']);
        $data['goods'] = $goods;
        $this -> autoCreateOrder($goods);
        return array('status' => 1, 'data' => $data);
    }


    public function autoCreateOrder($data){

        $where = array('user_id' => $this->uinfo['id']);
        $address_data = UserAddress::where($where) -> order('id desc')->find();
        $oh=new O();
        if ($address_data) {
            $province =$oh->getAreas($address_data['province']);
            $city =$oh->getAreas($address_data['city']);
            $country=$oh->getAreas($address_data['country']);
            $address = $address_data['address'];
            $linkman = $address_data['name'];
            $tel =  $address_data['tel'];
        }

        $ordernum = get_ordernum();
        $order_data = [
            'ordernum' => $ordernum,
            'type' => 6,
            'old_total' => 0,
            'discount_total' => 0,
            'goods_total' => $data['min_price'],
            'total' => $data['min_price'],
            'user_id' => $this->uinfo['id'],
            'username' => $this->uinfo['username'],
            'ip' => get_client_ip(),
            'message' =>  '',
            'create_time' => date('Y-m-d H:i:s'),
            'pay_time' => date('Y-m-d H:i:s'),
            'source' => '盲盒',
            'status' =>  1,
            'pay_type' => 2,
            'pay_status' => 1,
            'goods_num' => 1,
            'pid' => $this->uinfo['pid'],
            'province' => $province,
            'city' => $city,
            'country' => $country,
            'address' => $address,
            'linkman' => $linkman,
            'tel' => $tel,
        ];
        $order_id = Db::name('mall_order') -> insertGetId($order_data);
        $mall_order_goods['order_id'] = $order_id;
        $mall_order_goods['name'] = $data['name'];
        $mall_order_goods['thumb'] = $data['thumb'];
        $mall_order_goods['num'] = 1;
        $mall_order_goods['unit_price'] = $data['min_price'];
        $mall_order_goods['total_price'] = $data['min_price'];
        $mall_order_goods['total_pay_price'] = 0;
        $mall_order_goods['goods_id'] = $data['id'];
        $mall_order_goods['category_id'] = $data['category_id'];
        $mall_order_goods['old_unit_price'] = $data['min_price'];
        $mall_order_goods['old_total_price'] = $data['min_price'];
        $mall_order_goods['user_id'] = $this->uinfo['id'];
        Db::name('mall_order_goods') -> insert($mall_order_goods);

    }

    public function load_baozhang(){

    }
}
