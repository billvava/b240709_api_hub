<?php

namespace app\mall\model;

use think\facade\Db;
use think\Model;

class Goods extends Model {

    protected $name = 'mall_goods';
    protected $pk = 'goods_id';

    /**
     * 减少库存
     * @param $goods_id
     * @param string $gids
     * @param $num
     * @return bool
     */
    public function reduceStore($goods_id, $item_id, $num = 1) {
        $where['goods_id'] = $goods_id;
        $where['id'] = $item_id;
        $r = Db::name('mall_goods_item')->where($where)->dec('stock', $num)->update();
        return $r;
    }

    /**
     * 增加库存
     * @param $goods_id
     * @param string $gids
     * @param $num
     * @return bool
     */
    public function addStore($goods_id, $item_id, $num = 1) {
        $where['goods_id'] = $goods_id;
        $where['id'] = $item_id;
        $r = Db::name('mall_goods_item')->where($where)->inc('stock', $num)->update();
        ;
        return $r;
    }

    //获取商品列表
    public static function get_data($in = [],$map=[]) {
        $where = array();
        $cache_name = "goods_data_" . md5(serialize($in));
        if ($in['cache'] == true) {
            $data = cache($cache_name);
            if ($data) {
                return $data;
            }
        }
        $is = array('goods_id', 'is_new', 'is_recommend', 'brand_id', 'shop_id', 'user_id','master_id');
        foreach ($is as $v) {
            if ($in[$v] != '') {
                $where[] = [$v, '=', $in[$v]];
            }
        }
        if ($in['ngoods_id']) {
            $where[] = array('goods_id', '<>', $in['ngoods_id']);
        }

        $like = array('name', 'wares_no');
        foreach ($like as $v) {
            if ($in[$v] != '') {
                $where[] = array($v, 'like', "%{$in[$v]}%");
            }
        }


        if ($in['category_id']) {
            $cs = (new GoodsCategory())->getChildIdsCache($in['category_id']);
            $where[] = ['a.category_id', 'in', $cs];
        }
        if ($in['brand_id']) {
            $where[] = ['a.brand_id', '=', $in['brand_id']];
        }

        if ($in['status'] != '') {
            $where[] = ['a.status', '=', $in['status']];
        } else {
            $where[] = ['a.status', 'in', [1, 0]];
        }
        $order_type = array(
            0 => 'a.sort asc,a.goods_id desc',
            1 => 'a.sale_num desc,a.sort asc',
            2 => 'a.sale_num asc,a.sort asc',
            3 => 'a.min_price asc,a.goods_id desc',
            4 => 'a.min_price desc,a.goods_id desc',
            5 => 'a.goods_id desc',
            6 => 'is_recommend desc,sort asc',
            7 => 'is_new desc,sort asc',
            8 => 'is_xinji desc,sort asc',
        );
        $order_type = array(
            '0' => 'a.sort asc,a.goods_id desc',
            'sale_num' => 'a.sale_num desc,a.sort asc',
            'price1' => 'a.min_price asc,a.goods_id desc',
            'price2' => 'a.min_price desc,a.goods_id desc',
            'is_new' => 'is_new desc,sort asc',
        );
        $ot = $in['order_type'] ? $in['order_type'] : 0;
        $order = $order_type[$ot];

        //是否需要分页
        $page_num = $in['page_num'] ? $in['page_num'] : 10;
        if ($in['page_type']) {
            $count = Db::name('mall_goods')->alias('a')->where($where) -> where($map) ->count();
            if ($in['page_type'] == 'admin') {
                tool()->classs('PageForAdmin');
                $Page = new \PageForAdmin($count, $page_num);
            } else {
                tool()->classs('Page');
                $Page = new \Page($count, $page_num);
            }
            $data['page'] = $Page->show();
            $data['total'] = $count;
            $start = $Page->firstRow;
        } else {
            $page = $in['page'] ? $in['page'] : 1;
            $start = ($page - 1) * $page_num;
        }


        $data['list'] = Db::name('mall_goods')
                        ->alias('a')
                        ->field('a.*')
                        ->where($where)
                        -> where($map)
                        ->limit($start, $page_num)
                        ->order($order)
                        ->select()->toArray();
        foreach ($data['list'] as &$v) {
            $v['thumb'] = get_img_url($v['thumb']);
            $v['min_price'] = $v['min_price'] + 0;
            $v['tags'] = array('火爆');
        }
        $data['num'] = count($data['list']);
        if ($in['cache'] == true) {
            cache($cache_name, $data);
        }
        return $data;
    }

    /**
     * 永久删除
     */
    public function foreverDel($goods_id) {

        if (is_array($goods_id)) {
            $where[] = ['goods_id', 'in', $goods_id];
        } else {
            $where = array('goods_id' => $goods_id);
        }

        Db::name('mall_goods_atn')->where($where)->delete();
        Db::name('mall_goods_data')->where($where)->delete();
        Db::name('mall_goods_ext')->where($where)->delete();
        Db::name('mall_goods')->where($where)->delete();
        Db::name('mall_goods_attr_record')->where($where)->delete();
        Db::name('mall_goods_field_relation')->where($where)->delete();
        Db::name('mall_goods_spec')->where($where)->delete();
        Db::name('mall_goods_item')->where($where)->delete();
        Db::name('mall_goods_spec_value')->where($where)->delete();
    }

    //获取评论数量
    public function getCommentCount($goods_id) {
        return Db::name('mall_goods_comment')->where(array('goods_id' => $goods_id, 'status' => 1))->count();
    }

    //获取商品信息
    public function getInfo($goods_id, $get_ext = 0, $is_cache = false) {
        if (!$goods_id) {
            return NULL;
        }
        if ($is_cache == true) {
            $cacheName = "mall_goods_model_{$goods_id}_{$get_ext}";
            $goodsInfo = cache($cacheName);
            if ($goodsInfo) {
                return $goodsInfo;
            }
        }

        $goodsInfo = null;
        if ($get_ext == 1) {
            $goodsInfo = Db::name('mall_goods')->alias('a')->
                            join("mall_goods_ext b", "a.goods_id=b.goods_id")
                            ->where('a.goods_id', $goods_id)->find();
            $goodsInfo['attr_id'] = $this->getAttrId($goods_id);
            $goodsInfo['images_file'] = json_decode($goodsInfo['images'], true);
            $images = [];
            if (is_array($goodsInfo['images_file'])) {
                foreach ($goodsInfo['images_file'] as $v) {
                    $suffix =  strrchr($v, '.');
                    $type = 'img';
                    if($suffix == '.mp4'){
                        $type = 'video';
                    }
                    $images[] = [
                        'type'=>$type,
                        'url'=>get_img_url($v),
                    ];
                }
            }
            $goodsInfo['images'] = $images;

            $goodsInfo['content_html'] =    contentHtml($goodsInfo['content']) .'<font style="opacity: 0">.</font>';

        } else {
            $goodsInfo = Db::name('mall_goods')->where(array('goods_id' => $goods_id))->find();
        }

//        $goodsInfo['url'] = url('Mall/Goods/item', array('goods_id' => $goods_id));
        $goodsInfo['thumb_file'] = $goodsInfo['thumb'];
        $goodsInfo['thumb'] = get_img_url($goodsInfo['thumb']);

        if ($is_cache == true) {
            cache($cacheName, $goodsInfo);
        }

        return $goodsInfo;
    }

    //清除缓存
    public function clear($goods_id) {
        $cacheName = "mall_goods_model_{$goods_id}";
        cache($cacheName, null);
        $cacheName = "mall_goods_model_{$goods_id}_0";
        cache($cacheName, null);
        $cacheName = "mall_goods_model_{$goods_id}_1";
        cache($cacheName, null);
    }

    //获取属性ID
    public function getAttrId($goods_id) {
        $model = Db::name('mall_goods_attr_record');
        $attr_id = $model->where(array('goods_id' => $goods_id))->value('attr_id');
        return ($attr_id);
    }

    //获取商品属性组
    public function get_attr($goods_id, $attr_id) {
        if (!$goods_id || !$attr_id) {
            return false;
        }
        $cache_name = "get_attr_{$goods_id}_{$attr_id}";
        $data = cache($cache_name);
        if(!$data){
            $as = (new GoodsAttr())->getAllField($attr_id);
            $rs = Db::name('mall_goods_attr_record')->where(array(
                'a.goods_id' => $goods_id,
                'a.attr_id' => $attr_id,
            ))->alias('a')->
            leftJoin('mall_goods_attr_field b', 'a.field_id=b.field_id')
                ->field("a.*,b.type")
                ->select()->toArray();
            $as = array_reduce($as, function($v,$w){ $v[$w['field_id']]=$w['name'];return $v; });
            $data = array();
            foreach ($rs as $v) {
                if($v['val'] != ''){
                    $data[] = [
                        'name'=>$as[$v['field_id']],
                        'val'=>$v['val']
                    ];
                }
            }
            cache($cache_name,$data);
        }

        return $data;
    }

    //获取相关的商品ID,根据品牌
    public function getGoodsIdByBrandId($BrandId) {
        $mall_goods_category_relatio = Db::name('mall_goods_brand_relation');
        $cs = $mall_goods_category_relatio->where(array('brand_id' => $BrandId))->column('goods_id');
        return array_unique($cs);
    }

    //获取相关的商品ID,根据栏目
    public function getGoodsIdByCategoryId($CateogryId) {
        $allcs = (new GoodsCategory())->getChildIdsCache($CateogryId);
        $allcs = $allcs ? $allcs : array(0);
        $mall_goods_category_relatio = Db::name('mall_goods_category_relation');
        $cs = $mall_goods_category_relatio->where(array('category_id' => array('in', $allcs)))->column('goods_id');
        return array_unique($cs);
    }

//得到规格
    public function getSpec($goods_id) {
        if (!$goods_id) {
            return NULL;
        }
        $goods_spec = Db::name('mall_goods_spec')->where(array('goods_id' => $goods_id))->field('*')->select()->toArray();
        foreach ($goods_spec as &$v) {
            $v['spec_value'] = Db::name('mall_goods_spec_value')->where(array('goods_id' => $goods_id, 'spec_id' => $v['id']))->field('*')->select()->toArray();
            foreach ($v['spec_value'] as $kk => &$vv) {
                $vv['check'] = 0;
                if ($kk == 0) {
                    $vv['check'] = 1;
                }
            }
        }
        return $goods_spec;
    }

    //得到规格详情
    public function getItems($goods_id, $thumb = '') {
        if (!$goods_id) {
            return NULL;
        }

        $res = Db::name('mall_goods_item')->where(array('goods_id' => $goods_id))->order("id asc")->select()->toArray();
        foreach ($res as &$v) {
            if (!$v['image']) {
                $v['image'] = $thumb;
            }
            $v['image'] = get_img_url($v['image']);
        }
        return $res;
    }

    //得到单条的sku
    public function getItemInfo($goods_id, $item_id) {
        $where = array('goods_id' => $goods_id, 'id' => $item_id);
        if(!$item_id){
            $spec_type = Db::name('mall_goods')->where([
                        ['goods_id', '=', $goods_id],
            ])->value('spec_type');
            if($spec_type == 1){
                unset($where['id']);
            }
        }

        $info = Db::name('mall_goods_item')->where($where)->find();
        if ($info) {
            $info['num'] = $info['stock'];
            $info['item_id'] = $info['id'];

        }
        return $info;
    }

    //获取推荐商品
    public function getRecommendGoods($limit = 20) {
        $where[] = ['is_recommend', '=', 1];
        $where[] = ['status', '=', 1];
        return $this->where($where)->order("sort asc")->limit($limit)->cache(true)->select()->toArray();
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return;
    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField() {
        return "goods_id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue() {
        return array(
            'goods_id' => '',
            'wares_no' => '',
            'status' => '1',
            'create_time' => '',
            'min_price' => '0.00',
            'is_spec' => '0',
        );
    }

}
