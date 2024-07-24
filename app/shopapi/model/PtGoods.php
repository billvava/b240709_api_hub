<?php

namespace app\shopapi\model;

use think\facade\Db;
use think\Model;

/**
 *代码自动生成所需参数
 *moudleName:模块名称
 *tableName:表名
 *modelName:模型名称
 **/
class PtGoods extends Model
{

    protected $name = 'pt_goods';

    //获取商品列表
    public function get_data($in)
    {
        $where[] = array('status', '=', 1);
        $cache_name = "pt_goods_data_" . md5(serialize($in));
        if ($in['cache'] == true) {
            $data = cache($cache_name);
            if ($data) {
                return $data;
            }
        }
        if ($in['status'] != '') {
            $where[] = ['status', '=', $in['status']];
        }
        if ($in['goods_id']) {
            $where[] = ['goods_id', '=', $in['goods_id']];
        }
        if ($in['goods_ids']) {
            $where[] = ['goods_id', 'in', $in['goods_ids']];
        }

        if ($in['name']) {
            $where[] = array('a.name', 'like', "%{$in['name']}%");
        }
        if ($in['wares_no']) {
            $where[] = array('a.wares_no', 'like', "%{$in['wares_no']}%");
        }

        if ($in['end']) {
            $where[] = array('end', '>', date('Y-m-d H:i:s'));
            $where[] = array('start', '<', date('Y-m-d H:i:s'));
        }
        $orders = [
            'price_asc' => 'min_price asc',
            'price_desc' => 'min_price desc',

            'sale_num_asc' => 'sale_num asc',
            'sale_num_desc' => 'sale_num desc',

        ];

        if (in_array($in['order'], array_keys($orders))) {
            $order = $orders[$in['order']];
        } else {
            $order = "a.sort asc,a.goods_id desc";
        }
        //是否需要分页
        $page_num = $in['page_num'] ? $in['page_num'] : 10;
        if ($in['page_type']) {
            $count = Db::name($this->name)->alias('a')->where($where)->count();
            tool()->classs('PageForAdmin');
            if ($in['page_type'] == 'admin') {
                $Page = new \PageForAdmin($count, $page_num);
            } else {
                $Page = new \Page($count, $page_num);
            }
            $data['page'] = $Page->show();
            $data['total'] = $count;
            $start = $Page->firstRow;
        } else {
            $page = $in['page'] ? intval($in['page']) : 1;
            $start = ($page - 1) * $page_num;
        }
        $data['list'] = Db::name($this->name)
            ->alias('a')
            ->field('a.*')
            ->where($where)
            ->limit($start, $page_num)
            ->order($order)
            ->select()->toArray();
        $data['num'] = count($data['list']);
        if ($in['get_goods'] == 1) {
            $mall_goods = new Goods();
            foreach ($data['list'] as &$v) {
                $info = $mall_goods->getInfo($v['goods_id'], 0, 1);
                $v['thumb'] = $info['thumb'];
                $v['min_market_price'] = $info['min_market_price'];
                $v['name'] = $info['name'];
            }
        }

        if ($in['cache'] == true) {
            cache($cache_name, $data);
        }
        return $data;
    }

    public function getGoods($goods_id)
    {
        $info = Db::name($this->name)->where(array('goods_id' => $goods_id, 'status' => 1))->find();

        $cha = strtotime($info['end']) - time();

        if ($cha > 0) {
            $info['day'] = ceil($cha / (3600 * 24));

            $info['is_end'] = 0;
        } else {
            $info['day'] = 0;
            $info['is_end'] = 1;
        }
        return $info;
    }

    public function handle($v)
    {
        $mall_goods = new \app\mall\model\Goods();
        $info = $mall_goods->getInfo($v['goods_id'], 0, 1);
        $v['thumb'] = $info['thumb'];
        $v['min_market_price'] = $info['min_market_price'];
        $v['name'] = $info['name'];
        $v['sale_status'] = ($v['num'] <= $v['sale_num']) ? 0 : 1;
        $v['sale_bili'] = ($v['sale_num'] / $v['num']) * 100;
        $v['sale_bili'] = $v['sale_bili'] > 100 ? 100 : $v['sale_bili'];
        //倒计时
        $v['second'] = strtotime($v['end']) - time();

        return $v;
    }

    public function getInfo($pk)
    {
        if (!$pk) {
            return null;
        }
        $name = "pt_goods_info_1560333266_{$pk}";
        $data = cache($name);
        if (!$data || 1) {
            $data = Db::name($this->name)->where(['id' => $pk])->find();
            if ($data) {


                $items = json_decode($data['items'], true);
                $data['items_kv'] = $items;
                $data['items'] = (new Goods())->getItems($data['goods_id']);
                if ($data['items']) {
                    foreach ($data['items'] as &$v) {
                        $v['new_price'] = $items[$v['id']];
                        if ($items[$v['id']]) {
                            $v['price'] = $items[$v['id']];
                        }
                    }
                }
                $data = $this->handle($data);


            }
            cache($name, $data);
        }
        return $data;
    }


    public function clear($pk = '')
    {
        $name = "pt_goods_info_1560333266_";
        cache($name, null);
        if ($pk) {
            $name = "pt_goods_info_1560333266_{$pk}";
            cache($name, null);
        }
    }


}
