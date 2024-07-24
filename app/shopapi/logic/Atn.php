<?php

namespace app\shopapi\logic;

use think\App;
use think\facade\Db;

class Atn {

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;
    public $goodsLogic;
    public $atn;

    public function __construct() {
        $this->model = new \app\common\model\SuoMaster();
        $this->goodsLogic = new \app\mall\logic\Goods();
        $this->atn = Db::name('mall_goods_atn');
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function atn() {
        if (!$this->uinfo['id']) {
            return array('status' => 0, 'info' => '请先登陆');
        }
        if (!$this->in['goods_id']) {
            return array('status' => 0, 'info' => '缺少参数');
        }
        $is_atn = $this->goodsLogic->isAtn2($this->in['goods_id'], $this->uinfo['id']);
        $f = false;
        $info = '已取消';
        if (!$is_atn) {
            $this->goodsLogic->addAtn2($this->in['goods_id'], $this->uinfo['id']);
            $f = true;
            $info = '已收藏';
        } else {
            $this->goodsLogic->delAtn2($this->in['goods_id'], $this->uinfo['id']);
        }

        $this->data['is_atn'] = $f;
        return array('status' => 1, 'info' => $info, 'data' => $this->data);
    }

    public function index() {
        $page = $this->in['page'] ? $this->in['page'] : 1;
    
        $where = [
            ['a.master_id','=', $this->uinfo['id']],
            ['b.status','=', 1],
            
        ];
        $field = array('thumb', 'name', 'a.goods_id', 'min_price', 'min_market_price', 'small_title');
        $list = $this->atn->alias('a')->join("mall_goods b", "a.goods_id=b.goods_id")
                        ->where($where)->field($field)->page($page, 10)->select()->toArray();
        foreach ($list as &$v) {
            $v['thumb'] = get_img_url($v['thumb']);
        }
        $this->data['list'] = $list;
        $this->data['count'] = count($list);
        return array('status' => 1, 'data' => $this->data);
    }

    public function atn_count(){
        $where = [
            ['a.master_id','=', $this->uinfo['id']],
            ['b.status','=', 1],

        ];
        return $this->atn->alias('a')->join("mall_goods b", "a.goods_id=b.goods_id")
            ->where($where)->count();

    }


}
