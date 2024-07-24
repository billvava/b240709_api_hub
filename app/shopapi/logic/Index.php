<?php


namespace app\shopapi\logic;

use think\facade\Db;
use app\shopapi\model\MsGoods;
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
      
        $Ad = new \app\admin\model\Ad();
        $this->data['banner'] = $Ad->get_ad('mall_banner');
     
        $category_list = Db::name('mall_goods_category')->where(array('is_show' => 1, 'pid' => 0))->limit(3)->order("sort desc")->cache(true)->select()->toArray();

        foreach ($category_list as &$v) {
            $v['thumb'] = get_img_url($v['thumb']);
        }
        $this->data['category_list2'] = [];
        if($category_list){
            $this->data['category_list2'] = array_slice($category_list,0,7);
        }

        array_unshift($category_list,['name'=>'全部','category_id'=>0]);
        $this->data['category_list'] = $category_list;

        $map = ['status'=>1,'page'=>1,'is_recommend'=>1];
        if($this->in['page'] > 0){
            $map['page'] = 1;
        }
        if($this->in['category_id'] > 0){
            $map['category_id'] = $this->in['category_id'];
        }
        $r = \app\mall\model\Goods::get_data($map);
        $this->data['goods_list'] = $r['list'];
        foreach( $this->data['goods_list']  as &$v){
            $v['min_price'] =   $v['min_price2'];
        }
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

    public function load_baozhang(){

    }
}
