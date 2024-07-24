<?php

namespace app\mall\logic;

use app\mall\model\GoodsCategory;
use app\mall\model\GoodsSpec;
use think\facade\Db;

Class Goods
{


    public function __construct()
    {
//        C('sql', 1);
    }

    public function getCateList($pid = 0)
    {
        return Db::name('mall_goods_category')->where(array('pid' => $pid, 'is_show' => 1))->cache(true)->select()->toArray();
    }

    //获取栏目信息 缓存
    public function getCateInfoCache($category_id)
    {
        $name = "GoodsLogicgetCateInfoCache{$category_id}";
        if (!$info = cache($name)) {
            $info = $this->getCateInfo($category_id);
            cache($name, $info);
        }
        return $info;
    }

    //获取栏目信息
    public function getCateInfo($category_id)
    {
        if (!$category_id) {
            return null;
        }
        $info = Db::name('mall_goods_category')->where(array('category_id' => $category_id))->find();
        return $info;
    }

    //获取商品列表，缓存
    public function getGoodsListCache($param = array())
    {
        $cacheNmae = md5(serialize($param) . $_GET['p']);
        $data = cache($cacheNmae);
        if (!$data) {
            $where = array();
            $where[] = ['a.status', '=', 1];
            if ($param['category_id']) {
                $allCates = (new GoodsCategory())->getChildIdsCache($param['category_id']);

                if (!$allCates) {
                    return null;
                }
                $where[] = ['b.category_id', 'in', $allCates];
            }

            $order_data = lang('order_data');
            $order = $order_data[$param['order']]['order'] ? $order_data[$param['order']]['order'] : $order_data[$param['order']]['default'];
            if ($param['name']) {
                $where[] = ['a.name', 'like', "%{$param['name']}%"];
            }
            $data = $this->getGoodsList($where, $order);
            cache($cacheNmae, $data);
        }
        return $data;
    }

    //获取商品列表
    public function getGoodsList($where = array(), $order = 'a.sort asc,a.goods_id desc', $current_page = NULL, $pageNum = 10)
    {
        $count = Db::name('mall_goods')->alias('a')
            ->leftJoin('mall_goods_category_relation b', 'a.goods_id=b.goods_id')
            ->where($where)
            ->count("DISTINCT a.goods_id");
        tool()->classs('Page');
        $Page = new \Page($count, $pageNum);
        if ($current_page > 0) {
            $Page->firstRow = ($current_page - 1) * $pageNum;
        }
        $data['count'] = $count;
        $data['page_count'] = $Page->totalPages;
        $order = $order ? $order : 'a.sort asc,a.goods_id desc';
        $data['list'] = Db::name('mall_goods')->alias('a')
            ->leftJoin('mall_goods_category_relation b', 'a.goods_id=b.goods_id')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->field(array('DISTINCT a.goods_id', 'a.*'))
            ->order($order)
            ->select()->toArray();
        foreach ($data['list'] as &$v) {
            $v['url'] = url('mall/Goods/item', array('goods_id' => $v['goods_id']));
        }
        $data['page'] = $Page->show();
        return $data;
    }

    //获取商品信息
    public function getInfo($goods_id)
    {
        if ($goods_id <= 0) {
            return null;
        }
        $cachaName = "goods_logic_info{$goods_id}";
        if (!$info = cache($cachaName)) {
            $m = new \app\mall\model\Goods();
            $info = $m->getInfo($goods_id, true);
            if ($info) {
                $info['comment_count'] = $m->getCommentCount($goods_id);
                $info['images'] = array_filter(json_decode($info['images'], true));
                $info['content'] = htmlspecialchars_decode($info['content']);
            }
            cache($cachaName, $info, 3600);
        }
        return $info;
    }

    //获取商品缓存
    public function clearInfo($goods_id)
    {
        $cachaName = "goods_logic_info{$goods_id}";
        cache($cachaName, null);
    }

    //判断是否收藏
    public function isAtn($goods_id,$user_id)
    {
        if (!$goods_id) {
            return false;
        }
        if (!$user_id) {
            return false;
        }
        $is = Db::name('mall_goods_atn')->where(array('user_id' => $user_id, 'goods_id' => $goods_id))->value('id');
        return $is ? true : false;
    }

    public function isAtn2($goods_id,$user_id)
    {
        if (!$goods_id) {
            return false;
        }
        if (!$user_id) {
            return false;
        }
        $is = Db::name('mall_goods_atn')->where(array('master_id' => $user_id, 'goods_id' => $goods_id))->value('id');
        return $is ? true : false;
    }
    public function delAtn2($goods_id,$user_id)
    {
        if (!$goods_id) {
            return false;
        }
        Db::name('mall_goods_atn')->where(array('master_id' => $user_id, 'goods_id' => $goods_id))->delete();
    }

    public function addAtn2($goods_id,$user_id)
    {
        if (!$goods_id) {
            return false;
        }
        Db::name('mall_goods_atn')->insertGetId(array('master_id' => $user_id, 'goods_id' => $goods_id, 'time' => date('Y-m-d H:i:s')));
    }
    public function delAtn($goods_id,$user_id)
    {
        if (!$goods_id) {
            return false;
        }
        Db::name('mall_goods_atn')->where(array('user_id' => $user_id, 'goods_id' => $goods_id))->delete();
    }

    public function addAtn($goods_id,$user_id)
    {
        if (!$goods_id) {
            return false;
        }
        Db::name('mall_goods_atn')->insertGetId(array('user_id' => $user_id, 'goods_id' => $goods_id, 'time' => date('Y-m-d H:i:s')));
    }

    /**
     * 获取商品评论
     * @param type $cid
     * @param type $param
     * @return type
     */
    public function getCommentList($goods_id, $param = array())
    {
        $model = Db::name('mall_goods_comment');
        $num = $param['pagenum'] ? $param['pagenum'] : 20;
        $where = array('a.goods_id' => $goods_id, 'a.status' => 1);
        if ($param['page']) {
            $_GET['p'] = $param['page'];
        }
        if ($param['rank']) {
            $where['a.rank'] = $param['rank'];
            $where[] = ['a.rank', '=', $param['rank']];
        }
        if ($param['img']) {
            $where[] = ['a.images', '!=', ''];
        }
        $count = $model->alias('a')
            ->where($where)
            ->count();
        tool()->classs('Page');
        $Page = new \Page($count, $num);
        $data['count'] = $count;
        $data['pagenum'] = $num;
        $data['list'] = $model->alias('a')
            ->leftJoin('user b', 'a.user_id=b.id')
            ->where($where)
            ->field("a.*,b.nickname as bnickname,b.headimgurl")
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select()->toArray();
        return $data;
    }

    /**
     * 新版获取商品规格
     *
     * @param   [type]  $goods_id  [$goods_id description]
     *
     * @return  [type]             [return description]
     */
    public function goods_spec ($goods_id) {
        // 查询规格项
        $goodsSpec = Db::name('mall_goods_spec')->where(['goods_id' => $goods_id])->select()->toArray();
        if ($goodsSpec) {
            foreach ($goodsSpec as $k => $v) {
                $goodsSpec[$k]['value'] = Db::name('mall_goods_spec_value')->where(['spec_id' => $v['id'], 'goods_id' => $goods_id])->select()->toArray();
            }
        }
        return $goodsSpec;
    }

    /**
     * 获取商品规格价格
     *
     * @param   [type]  $goods_id        [$goods_id description]
     * @param   [type]  $spec_value_ids  [$spec_value_ids description]
     *
     * @return  [type]                   [return description]
     */
    public function goods_spec_item ($goods_id, $spec_value_ids) {
        // 兼容旧系统购物车以下划线拼接
        $spec_value_ids = str_replace('_', ',', $spec_value_ids);
        $data = Db::name('mall_goods_item')->where([
            'goods_id' => $goods_id,
            'spec_value_ids' => $spec_value_ids
        ])->find();
        if ($data['image']) {
            $data['image'] = get_img_url($data['image']);
        }

        if ($data['spec_value_str']) {
            $data['spec_value_arr'] = explode(',', $data['spec_value_str']);
        }

        // 为了防止出现未知错误 兼容旧系统字段
        if ($data) {
            $data['num'] = $data['stock'];
            $data['cost'] = $data['cost_price'];
            $data['spec_str'] = str_replace(',', ' ', $data['spec_value_str']);
            $data['gids'] = $spec_value_ids;
        }
        return $data;
    }

    /**
     * 获取商品总的库存
     */
    public function get_goods_nums($goods_id){
        return Db::name('mall_goods_item')->where(array('goods_id'=>$goods_id))->sum('stock')+0;
    }

}
