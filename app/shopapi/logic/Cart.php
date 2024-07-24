<?php

namespace app\shopapi\logic;

use think\App;
use think\facade\Db;

class Cart {

    public $in;
    private $master_id;
    public $uinfo;
    public $data;
    public $request;
    public $model;
    private $items = array();

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];

        $this->master_id = $this->uinfo['id'];
        $this->model = Db::name('mall_cart');
        $info = $this->model->where(array('master_id' => $this->master_id))->find();
       
        if (!$info) {
            $this->model->insert(array('master_id' => $this->master_id));
        } else {
            $this->items = json_decode($info['data'], true);
        }

        $this->items = $this->items ?: [];
        $this->clear_empty();
    }

    public function up_data($item = null) {
        if (!$this->master_id) {
            die;
        }
        if (isset($item)) {
            $data = $item;
        } else {
            $data = $this->items;
        }
        $this->model->where(array('master_id' => $this->master_id))->update(array(
            'data' => json_encode($data)
        ));
    }
    //下单页面的修改数量
    public function change_goods_num(){
        $new_num = ceil($this->in['new_num']);
        if($new_num <= 0){
            return array('status' => 0, 'info' => '数量不能小于1');

        }
        if($this->in['num'] > 0){
            return array('status' => 1, 'data' =>[
                'flag'=>'goods_update',
                'num'=>$new_num
            ]);
        }else{
            $data = $this->get_sel_list();
            $new_k = "{$this->in['new_goods_id']}|{$this->in['new_item_id']}";
            foreach($data as $k=>$v){
                if($v['check'] == 1 && $new_k==$k){
                    $this->setNum($new_k, $new_num);
                    break;
                }
            }
            return array('status' => 1, 'data' =>[
                'flag'=>'cart_update',
            ]);

        }


    }
    //添加商品到购物车
    public function add() {
        $tinfo = Db::name('mall_goods')->where(array('goods_id' => $this->in['goods_id']))->field('status,goods_id')->find();
        if ($tinfo['status'] != 1) {
            return array('status' => 0, 'info' => '商品不存在或已下架');
        }
        if ($this->in['num'] <= 0 || (ceil($this->in['num']) != $this->in['num'])) {
            return array('status' => 0, 'info' => '请输入数量');
        }
        $GoodsModel = new \app\shopapi\model\Goods();
        $info = $GoodsModel->getItemInfo($this->in['goods_id'], $this->in['item_id']);
        if (!$info) {
            return array('status' => 0, 'info' => '请选择商品规格');
        }
        $id = "{$info['goods_id']}|{$info['id']}";
        $cartNum = $this->getItemNum($id);
        if (($cartNum + $this->in['num']) > $info['num']) {
            return array('status' => 0, 'info' => '商品库存不足');
        }
        if (array_key_exists($id, $this->items)) {
            $this->items[$id]['num'] += $this->in['num'];
        } else {
            $this->items[$id] = array();
            $this->items[$id]['num'] = $this->in['num'];
            $this->items[$id]['price'] = $info['price'];
            $this->items[$id]['item_id'] = $info['id'];
            $this->items[$id]['goods_id'] = $info['goods_id'];
            $this->items[$id]['check'] = 1;
        }
        $this->up_data();
        $this->data['cart_num'] = $this->get_total_num();
        $this->data['sel_num'] = $this->get_sel_num();
        $this->data['sel_total'] = $this->get_sel_total();
        return array('status' => 1, 'data' => $this->data);
    }

    public function index() {
        $this->data['cart_num'] = $this->get_total_num();
        $this->data['sel_total'] = $this->get_sel_total();
        $this->data['sel_num'] = $this->get_sel_num();
        $list = $this->get_list();
        $is_all = 1;
        $GoodsModel = new \app\shopapi\model\Goods();

        $temp = array();
        foreach ($list as $k => &$v) {
            $v['id'] = $k;
            $v['info'] = $GoodsModel->getInfo($v['goods_id'], false, true);
            $v['ginfo'] = $GoodsModel->getItemInfo($v['goods_id'], $v['item_id']);
            $v['spec_str'] = $v['ginfo']['spec_value_str'];
            if ($v['check'] == 0) {
                $is_all = 0;
            }
            $temp[] = $v;
        }
        //app端去掉那个字符串的key
        $this->data['list'] = array_values($list);
        $this->data['is_all'] = $is_all;

        return array('status' => 1, 'data' => $this->data);
    }

    public function change_num() {
        $this->setNum($this->in['id'], $this->in['num']);
        return array('status' => 1);
    }

    public function setNum($id, $num = 1) {
        if (array_key_exists($id, $this->items)) {
            $this->items[$id]['num'] = $num;
            if ($this->items[$id]['num'] <= 0) {
                $this->del($id);
            }
            $this->up_data();
        }
    }

    public function set_num() {
        if (!$this->in['flag']) {
            $this->reduce($this->in['id']);
        } else {
            $this->append($this->in['id']);
        }
        return array('status' => 1);
    }

    public function append($id, $num = 1) {
        if (array_key_exists($id, $this->items)) {
            $n = $this->items[$id]['num'];
            $this->items[$id]['num'] = $n + $num;
            $this->up_data();
        }
    }

    //删除选中的
    public function del_sel_list() {
        foreach ($this->items as $k => $v) {
            if ($v['check'] == 1) {
                unset($this->items[$k]);
            }
        }
        $this->up_data();
        return array('status' => 1, 'info' => '删除成功');
    }

    //获取某个集合数量
    public function getItemNum($id) {
        if (array_key_exists($id, $this->items)) {
            return $this->items[$id]['num'];
        } else {
            return 0;
        }
    }

    public function reduce($id, $num = 1) {
        if (array_key_exists($id, $this->items)) {
            $n = $this->items[$id]['num'];
            $this->items[$id]['num'] = $n - $num;
            if ($this->items[$id]['num'] <= 0) {
                $this->del($id);
            }
            $this->up_data();
        }
    }

    public function del() {
        $ids = explode(',', $this->in['id']);
        foreach ($ids as $id) {
            if (array_key_exists($id, $this->items)) {
                unset($this->items[$id]);
                $this->up_data();
            }
        }

        return array('status' => 1, 'info' => '删除成功');
    }

    public function clear() {
        $this->items = array();
        $this->model->where(array('master_id' => $this->master_id))->delete();
        return array('status' => 1);
    }

    public function get_list() {
        return $this->items;
    }

    //获取选中的列表
    public function get_sel_list() {
        $temp = array();
        foreach ($this->items as $k => $v) {
            if ($v['check'] == 1) {
                $temp[$k] = $v;
            }
        }
        return $temp;
    }

    //获取总计数量
    public function get_total_num() {
        $num = 0;
        foreach ($this->items as $v) {
            $num += $v['num'];
        }
        return $num;
    }

    //获取选中的数量
    public function get_sel_num() {
        $num = 0;
        foreach ($this->items as $v) {
            if ($v['check'] == 1) {
                $num += $v['num'];
            }
        }
        return $num;
    }

//获取选中的商品编号
    public function get_sel_goods_id() {
        $gs = array();
        foreach ($this->items as $v) {
            if ($v['check'] == 1) {
                $gs[] = $v['goods_id'];
            }
        }
        return $gs;
    }

    public function get_sel_total() {
        $total = 0;
        $GoodsModel = new \app\shopapi\model\Goods();
        foreach ($this->items as $v) {
            if ($v['check'] == 1) {
                $v['ginfo'] = $GoodsModel->getItemInfo($v['goods_id'], $v['item_id']);
                $total +=  $v['ginfo']['price'] * $v['num'];
            }
        }
        return number_format($total, 2, '.', '');
    }

    public function change_check() {
        $key = $this->in['id'];
        if (array_key_exists($key, $this->items)) {
            $this->items[$key]['check'] = $this->items[$key]['check'] == 1 ? 0 : 1;
            $this->up_data();
        }
        return array('status' => 1);
    }

    public function change_all() {
        $status = $this->in['check'] ? 1 : 0;
        foreach ($this->items as $k => $v) {
            $this->change_check2($k, $status);
        }
        return array('status' => 1);
    }

    public function change_check2($key, $status) {
        if (array_key_exists($key, $this->items)) {
            $this->items[$key]['check'] = $status == 1 ? 1 : 0;
            $this->up_data();
        }
        return array('status' => 1);
    }

    //获取选中的商品信息
    public function get_sel_goods() {
        $cart_data = $this->get_sel_list();
        $GoodsModel = new \app\shopapi\model\Goods();

        foreach ($cart_data as &$v) {
            $v['info'] = $GoodsModel->getInfo($v['goods_id'], false, true);
            $v['ginfo'] = $GoodsModel->getItemInfo($v['goods_id'], $v['item_id']);
        }
        return $cart_data;
    }

    /**
     * 清楚掉空的集合
     */
    private function clear_empty() {
        foreach ($this->items as $key => &$value) {
            if ($this->items[$key]['num'] <= 0 || !$this->items[$key]['price'] || !$key) {
                unset($this->items[$key]);
            }
        }
    }

}
