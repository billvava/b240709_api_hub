<?php

namespace app\mall\controller;

use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
 * 商城插件
 * @auto true
 * @auth true
 * @menu false
 */
class Com extends Common {

    /**
     * 选择商品
     * @auto true
     * @auth true
     * @menu false
     */
    public function select_goods() {
        //get 参数： 'callback'=>'select_goods_callback','field'=>'goods_id'
        //接收：select_goods_callback(field,goods_id,items)
        $this->in['page_type'] = 'admin';
        $Goods = new \app\mall\model\Goods();
        $data = $Goods->get_data($this->in);
        foreach ($data['list'] as &$v) {
            $v['goods_items'] = ($Goods->getItems($v['goods_id']));
        }
        $category_list = (new \app\mall\model\GoodsCategory())->categoryTreeeTree();
        View::assign('category_list', $category_list);
        View::assign('show_top', 0);
        View::assign('data', $data);
        return $this->display();
    }

    public function get_item() {
        $Goods = new \app\mall\model\Goods();
        $info = $Goods->getInfo($this->in['goods_id']);
        $items = $Goods->getItems($this->in['goods_id']);
        return json(array('status' => 1, 'data' => array(
                'goods_id' => $this->in['goods_id'],
                'items' => $items,
                'info' => $info,
        )));
    }

}
