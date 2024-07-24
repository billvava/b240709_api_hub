<?php

namespace app\mallapi\logic;

use think\App;
use think\facade\Db;

class Comment {

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $order;
    public $model;
    public $order_goods;

    public function __construct() {
        $this->model = new \app\mall\model\Comment();
        $this->order = Db::name('mall_order');
        $this->order_goods = Db::name('mall_order_goods');
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function new_index() {
        $this->data['nav'] = [
                [
                'name' => '待评价',
                'is_comment' => 0,
            ],
                [
                'name' => '已评价',
                'is_comment' => 1,
            ]
        ];
        $this->data['comment_dot'] = C('comment_dot');
        //22

        return [
            'status' => 1, 'data' => $this->data
        ];
    }

    //已评价的列表
    public function commented_list() {
        $where = [
                ['user_id', '=', $this->uinfo['id']],
        ];
        $get_goods = 1;
        $this->data['list'] = $this->model->get_list($where, $this->in['page'], $get_goods);
        return [
            'status' => 1, 'data' => $this->data
        ];
    }

    /**
     * 已经评论的
     * @return type
     */
    public function index() {
        $arr = array(
            "count(*) as all_count",
            "count( star>3  or null) as good_count",
            "count( star=3  or null) as medium_count",
            "count( star<3  or null) as bad_count",
            "count( is_img=1  or null) as image_count",
        );
        $where = [
                ['user_id', '=', $this->uinfo['id']],
                ['status', '=', 1],
        ];
        $info = $this->model->where($where)->field($arr)->find();
        $this->data['nav'] = [
                [
                'name' => '全部',
                'id' => 0,
                'count' => $info['all_count']
            ],
                [
                'name' => '晒图',
                'id' => 1,
                'count' => $info['image_count'],
                'where' => ['is_img', '=', 1],
            ],
                [
                'name' => '好评',
                'id' => 2,
                'count' => $info['good_count'],
                'where' => ['star', '>', 3],
            ],
                [
                'name' => '中评',
                'id' => 3,
                'count' => $info['medium_count'],
                'where' => ['star', '=', 3],
            ],
                [
                'name' => '差评',
                'id' => 4,
                'count' => $info['bad_count'],
                'where' => ['star', '<', 3],
            ]
        ];
        if ($this->in['id']) {
            $w = $this->data['nav'][$this->in['id']]['where'];

            if ($w) {
                $where[] = $w;
            }
        }
        $this->data['list'] = $this->model->get_list($where);
        $this->data['count'] = count($this->data['list']);
        return [
            'status' => 1, 'data' => $this->data
        ];
    }

    /**
     *  获取未评论或已评论的订单商品列表
     */
    public function wait_comment() {
        $where = [];
        $where[] = ['a.status', '=', 3];
        $where[] = ['a.is_u_del', '=', 0];
        $where[] = ['a.user_id', '=', $this->uinfo['id']];

        if (isset($this->in['is_comment']) && $this->in['is_comment'] != '') {
            $where[] = ['b.is_comment', '=', $this->in['is_comment']];
        } else {
            $where[] = ['b.is_comment', '=', 0];
        }
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $this->data['list'] = $this->model->get_wait_comment_list($where, $page);
        if ($this->in['is_comment'] == 1) {
            foreach ($this->data['list'] as &$v) {
                $v['comment'] = $this->model->cache(true)->where([
                                ['order_goods_id', '=', $v['id']]
                        ])->find();
                $v['comment'] = $this->model->handle_tp($v['comment']);
            }
        }

        $this->data['count'] = count($this->data['list']);
        return [
            'status' => 1, 'data' => $this->data
        ];
    }

    /**
     * 获取评论的商品信息
     */
    public function get_comment_info() {
        $where = [];
        $where[] = ['a.status', '=', 3];
        $where[] = ['b.id', '=', $this->in['id']];
        $where[] = ['a.is_u_del', '=', 0];
        $where[] = ['a.user_id', '=', $this->uinfo['id']];
        $this->data['info'] = $this->model->get_info($where);
        return [
            'status' => 1, 'data' => $this->data
        ];
    }

    /**
     * 提交评论
     */
    public function sub_comment_info() {
        $data = $this->get_comment_info();
        $info = $data['data']['info'];
        if (!$info) {
            return ['status' => 1, 'info' => '不存在'];
        }
        if ($info['is_comment'] == 1) {
            return ['status' => 1, 'info' => '已评论了'];
        }
        $time = date('Y-m-d H:i:s');
        $images = '';
        if ($this->in['images']) {
            if (is_array($this->in['images'])) {
                $images = json_encode($this->in['images']);
            } else {
                $is = array_filter(explode(',', $this->in['images']));
                $images = $is ? json_encode($is) : '';
            }
        }
        $temp = array(
            'headimgurl' => $this->uinfo['headimgurl'],
            'order_id' => $info['order_id'],
            'order_goods_id' => $this->in['id'],
            'goods_id' => $info['goods_id'],
            'images' => $images,
            'content' => $this->in['content'] . '',
            'user_id' => $this->uinfo['id'],
            'nickname' => $this->in['is_anonymous'] ? '****' : $this->uinfo['nickname'],
            'star' => $this->in['star'] ? $this->in['star'] : 5,
            'is_anonymous' => $this->in['is_anonymous'],
            'time' => $time,
            'is_img' => $images ? 1 : 0
        );
        $this->model->insert($temp);
        $this->order_goods->where(['id' => $this->in['id']])->save(['is_comment' => 1]);

        $count = Db::name('mall_order_goods')->where(['order_id' => $info['order_id'], 'is_comment' => 0])->count();
        if ($count == 0) {
            Db::name('mall_order')->where(['order_id' => $info['order_id']])->save(['comment_status' => 1]);
        }
        return [
            'status' => 1, 'info' => '评论成功'
        ];
    }

}
