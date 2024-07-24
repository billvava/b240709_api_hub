<?php

namespace app\mall\model;

use think\facade\Db;
use think\Model;

class User extends \app\common\model\User {

    //评论列表
    public function comment_list($map = array()) {
        $where = array();
        $style = $map['style'] == 'admin' ? 'admin' : 'front';
        if ($style == 'front') {
            $where[] = ['a.user_id', '=', $map['user_id']];
            $where[] = ['a.status', '=', 1];
        }
        if ($map['goods_id']) {
            $where[] = ['a.goods_id', '=', $map['goods_id']];
        }
        $model = M('mall_goods_comment');
        $count = Db::name('mall_goods_comment')->alias('a')->where($where)->count();
        $page_num = $map['page_num'] ? $map['page_num'] : 10;
        if ($map['page_type'] == 'admin') {
            tool()->classs('PageForAdmin');
            $Page = new \PageForAdmin($count, $page_num);
        } else {
            tool()->classs('Page');
            $Page = new \Page($count, $page_num);
        }
        $data['count'] = $count;
        $data['page'] = $Page->show();

        $data['list'] = $model->alias('a')
                        ->join("user b", "a.user_id=b.id")
                        ->join("mall_goods c", "a.goods_id=c.goods_id")
                        ->where($where)
                        ->field("a.*,b.nickname as bnickname,b.headimgurl,c.name as goods_name,c.thumb  as goods_thumb")
                        ->order('a.id desc')
                        ->limit($Page->firstRow, $Page->listRows)
                        ->select()->toArray();
        return $data ?: [];
    }

    //地址列表
    public function address($map = array()) {
        $where = array();
        $where['user_id'] = $map['user_id'];
        $model = Db::name('user_address');
        $count = $model->alias('a')->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 10);
        $data['count'] = $count;
        $data['page'] = $Page->show();
        $data['list'] = $model->alias('a')
                        ->join("user b", "a.user_id=b.id")
                        ->where($where)
                        ->field("a.*,b.nickname as bnickname,b.headimgurl")
                        ->order('a.id desc')
                        ->limit($Page->firstRow, $Page->listRows)
                        ->select()->toArray();
        return $data ?: [];
    }

    //是否显示评论
    public function changeComment($id, $map = array()) {
        if (!$id) {
            return false;
        }
        $where = array();
        $model = Db::name('mall_goods_comment');
        $where['id'] = $id;
        $where['user_id'] = $map['user_id'];
        if ($map['style'] == 'admin') {
            unset($where['user_id']);
        }
        $status = isset($map['status']) ? $map['status'] : 0;
        $model->where($where)->update(['status' => $status]);
    }

    //收藏列表
    public function atns($map = array()) {
        $where = array();
        $model = Db::name('mall_goods_atn');
        $where['user_id'] = $map['user_id'];
        if ($map['style'] == 'admin') {
            unset($where['user_id']);
        }
        $count = $model->alias('a')->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 10);
        $data['count'] = $count;
        $data['page'] = $Page->show();
        $data['list'] = $model->alias('a')
                        ->join("mall_goods b", "a.goods_id=b.goods_id")
                        ->where($where)
                        ->field("a.*,c.name,c.thumb,c.min_price")
                        ->order('a.id desc')
                        ->limit($Page->firstRow, $Page->listRows)
                        ->select()->toArray();
        return $data ?: [];
    }

    /*
     * 获取优惠劵列表
     * 在前台请勿直接传入I()作为参数
     * @param type $in
     * @return type
     */

    public function coupon($in = array(), $page_type = 'home') {
        $model = Db::name('mall_coupon');
        $where = array();
        //第几页
        if ($in['p']) {
            $_GET['p'] = $in['p'];
        }
        //时间查询
        if ($in['start_datetime'] && $in['end_datetime']) {

            $where[] = ['a.time', 'between', [strtotime($in['start_datetime']), strtotime($in['end_datetime'])]];
        } elseif ($in['start_datetime']) {
            $where[] = ['a.time', '>', strtotime($in['start_datetime'])];
        } elseif ($in['end_datetime']) {
            $where[] = ['a.time', '<', strtotime($in['end_datetime'])];
        }
        //用户名查询
        if ($in['user_id']) {
            $where['a.user_id'] = $in['user_id'];
        }
        if ($in['status'] === '0' || $in['status']) {
            $where['a.status'] = $in['status'];
        }
        //分页数
        $num = $in['num'] ? $in['num'] : C('data_page_count');
        $count = $model->alias('a')->where($where)->count();
        if (!$num) {
            $num = 10;
        }
        if ($page_type == 'admin') {
            tool()->classs('PageForAdmin');
            $Page = new \PageForAdmin($count, $num);
        } elseif ($page_type == 'home') {
            tool()->classs('Page');
            $Page = new \Page($count, $num);
        }
        $data['page'] = $Page->show();
        $data['list'] = $model->alias('a')
                        ->join("user b", "a.user_id=b.id")
                        ->field("a.*,b.username")
                        ->where($where)
                        ->order('a.id desc')
                        ->limit($Page->firstRow , $Page->listRows)
                        ->select()->toArray();
        return $data;
    }

    //佣金提现日志
    public function put_bro_log($user_id, $page = 1) {
        $user_money_put = Db::name('user_cashout_apply');
        $where['user_id'] = $user_id;
        return $user_money_put->where($where)->page($page, 10)->order("id desc")->select()->toArray();
    }

}
