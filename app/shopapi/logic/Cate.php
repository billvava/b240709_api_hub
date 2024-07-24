<?php

namespace app\shopapi\logic;

use think\App;
use think\facade\Db;

class Cate
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $GoodsCategory;

    public function __construct()
    {
        $this->GoodsCategory = new \app\shopapi\model\GoodsCategory();
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
        $this->data['list'] = $this->GoodsCategory->getTreeCache(array('is_show' => 1, 'is_nav' => 1));
        return array('status' => 1, 'data' => $this->data);
    }

    public function cate_list2()
    {
        $category_list = Db::name('mall_goods_category')
            ->where(array('is_show' => 1, 'pid' => 0))->order("sort desc")
            ->cache(true)->field('category_id,name,thumb')->select()->toArray();
        foreach ($category_list as $k => &$v) {
            $v['thumb'] = get_img_url($v['thumb']);
            $v['id'] = "cate_{$v['category_id']}";

            $where = [['is_show', '=', 1], ['pid', '=', $v['category_id']]];
            if($this->in['name']){
                $where[] = ['name','like',"%{$this->in['name']}%"];
            }
            $v['children'] = Db::name('mall_goods_category')
                ->where($where)->order("sort desc")->cache(true)->field('category_id,name,thumb')->select()->toArray();
            if ($v['children']) {
                foreach ($v['children'] as &$vv) {
                    $vv['thumb'] = get_img_url($vv['thumb']);
                    $vv['id'] = "cate_{$vv['category_id']}";
                }
            }
        }
        $this->data['category_list'] = $category_list;
        return array('status' => 1, 'data' => $this->data);


    }

    public function cate_list()
    {

        $category_list = Db::name('mall_goods_category')
            ->where(array('is_show' => 1, 'pid' => 0))->order("sort desc")->cache(true)->field('category_id,name')->select()->toArray();

        array_unshift($category_list, array('category_id' => 0, 'name' => '全部'));
        $tabbar = [];

        foreach ($category_list as $k => $v) {

            if ($v['category_id'] == 0) {
                $where = [['is_show', '=', 1], ['pid', '<>', 0]];
            } else {
                $where = [['is_show', '=', 1], ['pid', '=', $v['category_id']]];
            }
            $foods = Db::name('mall_goods_category')
                ->where($where)->order("sort desc")->cache(true)->field('thumb,category_id,name')->select()->toArray();

            foreach ($foods as &$v2) {
                $v2['thumb'] = get_img_url($v2['thumb']);
            }
            unset($v2);
            $tabbar[$k] = [
                'name' => $v['name'],
                'foods' => $foods,
                'category_id' => $v['category_id'],

            ];
        }
        $this->data['tabbar'] = $tabbar;

        return array('status' => 1, 'data' => $this->data);
    }

}
