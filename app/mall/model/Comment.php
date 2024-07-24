<?php

namespace app\mall\model;

use think\facade\Db;
use think\Model;

class Comment extends Model {

    protected $name = 'mall_goods_comment';
    public $order;

    public function __construct(array $data = array()) {
        parent::__construct($data);

        $this->order = Db::name('mall_order');
    }

    public function handle($v) {
        $v['thumb'] = get_img_url($v['thumb']);
        $v['is_comment_txt'] = $v['is_comment'] == 1 ? '已评论' : '待评论';
        return $v;
    }

    public function handle_tp($v) {
        if ($v) {
            $userModel = new \app\common\model\User();
            $img = json_decode($v['images'], true);
            $tmp = [];
            for ($i = 1; $i <= $v['star']; $i++) {
                $tmp[] = $i;
            }
             $v['stars'] = $tmp;
            $comment_rank = lang('comment_rank');
            $v['images'] = $img ? array_map('get_img_url', $img) : [];
            $uu = $userModel->getUserInfo($v['user_id']);
            $v['headimgurl'] = $uu['headimgurl'];
            $v['rank_str'] = $comment_rank[$v['rank']];
        }

        return $v;
    }

    public function get_list($where, $page = 1, $get_goods = 0) {
        $page = $page + 0;
        $data = $this->removeOption()
                ->where($where)
                ->page(($page ?: 1), 10)
                ->order('id desc')
                ->select();
        if ($data) {
            $data = $data->toArray();
        } else {
            $data = [];
        }
        foreach ($data as &$v) {
            $v = $this->handle_tp($v);
            if ($get_goods == 1) {
                $info = Db::name('mall_order_goods')->where([
                                ['id', '=', $v['order_goods_id']]
                        ])->cache(true)->find();
                $info['thumb'] = get_img_url($info['thumb']);
                $v['ginfo'] = $info;
            }
        }
        return $data;
    }

    public function get_wait_comment_list($where, $page = 1) {
        $page = $page + 0;
        $list = $this->order->alias('a')
                        ->join('mall_order_goods b', 'a.order_id = b.order_id')
                        ->where($where)
                        ->field('b.thumb,b.name,b.spec_str,b.id,b.goods_id,item_id,b.unit_price,b.old_unit_price,num,is_comment,a.ordernum')
                        ->page($page, 10)
                        ->select()->toArray();
        foreach ($list as &$v) {
              $v['stars'] = [];
            $v = $this->handle($v);
        }
        return $list;
    }

    public function get_info($where) {
        $v = $this->order->alias('a')
                ->join('mall_order_goods b', 'a.order_id = b.order_id')
                ->where($where)
                ->field('b.thumb,b.name,b.spec_str,b.id,b.goods_id,item_id,b.unit_price,num,is_comment')
                ->find();
        $v = $this->handle($v);
        return $v;
    }

}
