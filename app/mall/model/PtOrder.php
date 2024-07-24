<?php

namespace app\mall\model;

use app\common\model\User;
use think\facade\Db;
use think\Model;

/**
 *代码自动生成所需参数
 *moudleName:模块名称
 *tableName:表名
 *modelName:模型名称
 **/
class PtOrder extends Model
{
    public function getTeamList($group_id, $user_id, $ordernum = '')
    {
        $list = [];
        $userModel = new User();

        if ($ordernum) {
            $data = $this->removeOption()->where([
                ['p_ordernum', '=', $ordernum]
            ])->field('user_id,group_num,type')->order('type asc')->select()->toArray();
            if ($data) {
                $pt_num = $data[0]['group_num'];
                if($user_id){
                    $data[] = [
                        'user_id'=>$user_id,
                        'group_num'=>$pt_num,
                        'type'=>2
                    ];
                }
                for ($i = 0; $i < $pt_num; $i++) {
                    if ($data[$i]) {
                        $item = $data[$i];
                        $uinfo = $userModel->getUserInfo($item['user_id']);
                        $list[] = [
                            'nickname' => $uinfo['nickname'],
                            'headimgurl' => get_img_url($uinfo['headimgurl']),
                            'user_id' => $item['user_id'],
                            'type' => $item['type'],
                            'lev_class'=> $item['type'] == 1 ? 'leader' : 'member',
                            'lev_name' => $item['type'] == 1 ? '团长' : '参团'
                        ];
                    } else {
                        $list[] = [
                            'user_id' => 0,
                            'type' => 0,
                            'lev_name' => ''
                        ];
                    }

                }
            }


        } else {
            $pt_num = Db::name('pt_goods')->where([
                ['id', '=', $group_id]
            ])->value('pt_num');
            for ($i = 1; $i <= $pt_num; $i++) {
                $uinfo = $userModel->getUserInfo($user_id);
                if ($i == 1) {
                    $list[] = [
                        'nickname' => $uinfo['nickname'],
                        'headimgurl' => get_img_url($uinfo['headimgurl']),
                        'user_id' => $user_id,
                        'type' => 1,
                        'lev_class'=> 'leader',
                        'lev_name' => '团长'
                    ];
                } else {
                    $list[] = [
                        'user_id' => 0,
                        'type' => 0,
                        'lev_name' => ''
                    ];
                }
            }
        }
        return $list;
    }

    //获取商品的拼团
    public function getCurrentList($group_id,$p_ordernum='')
    {
        $where = [
            ['status', '=', 0],
            ['group_id', '=', $group_id],
            ['type', '=', 1],
            ['end_time', '>', date('Y-m-d H:i:s')],
        ];
        if($p_ordernum){
            $where[] = ['p_ordernum','=',$p_ordernum];
        }
        $data = $this->where($where)->select()->toArray();
        $userModel = new User();
        foreach ($data as &$v) {
            $uinfo = $userModel->getUserInfo($v['user_id']);
            $v['nickname'] = $uinfo['nickname'];
            $v['headimgurl'] = $uinfo['headimgurl'];
            $v['cha'] = $v['group_num'] - $v['num'];
            $sy_time = strtotime($v['end_time']) - time();
            $v['sy_time'] = $sy_time;
            $v['sy_time_str'] = '';
            $v['team_list'] = $this->getTeamList($group_id,0, $v['p_ordernum']);
        }
        return $data;
    }

    //获取用户的拼团
    public function getPageList($where = array(), $page = 1)
    {
        $data = Db::name('pt_order')->where($where)->page(($page ?: 1), 10)->order("id desc")->select()->toArray();
        $userModel = new User();
        $pt_status = lang('pt_status');
        $pt_type = lang('pt_type');
        foreach ($data as &$v) {
            $uinfo = $userModel->getUserInfo($v['user_id']);
            $v['nickname'] = $uinfo['nickname'];
            $v['headimgurl'] = $uinfo['headimgurl'];
            $v['cha'] = $v['need_num'] - $v['num'];
            $sy_time = strtotime($v['end_time']) - time();
            $v['sy_time'] = $sy_time;
            $v['status_str'] = $pt_status[$v['status']];
            $v['type_str'] = $pt_type[$v['type']];

        }
        return $data;
    }

    public function get_data($in = array())
    {
        $where = array();
        $like_arr = array('goods_name', 'ordernum');
        $status_arr = array('user_id', 'shop_id', 'goods_id', 'type', 'status');
        foreach ($in as $k => $v) {
            if (in_array($k, $like_arr) && $v) {
                $where[] = ['a.' . $k, 'like', "%{$v}%"];
            }
            if (in_array($k, $status_arr) && $v !== '') {
                $where[] = ['a.' . $k, '=', $v];
            }
        }

        if ($in['mul_time']) {
            $mul_times = explode(' - ', $in['mul_time']);
            if (strtotime($mul_times[0]) > 0 && strtotime($mul_times[1]) > 0) {
                $where[] = array('time', 'between', array($mul_times[0], "{$mul_times[1]} 23:59:59"));
            }
        }

        if ($in['order']) {
            $order = $in['order'];
        } else {
            $order = "a.id desc";
        }
        //是否需要分页
        $page_num = $in['page_num'] ? $in['page_num'] : 10;
        if ($in['page_type']) {
            $count = Db::name('pt_order')->alias('a')->where($where)->count();
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
            $page = $in['page'] ? $in['page'] : 1;
            $start = ($page - 1) * $page_num;
        }
        $field = "a.*";

        $data['list'] = Db::name('pt_order')
            ->alias('a')
            ->field($field)
            ->where($where)
            ->limit($start, $page_num)
            ->order($order)
            ->select()->toArray();
        $pt_type = lang('pt_type');
        $pt_status = lang('pt_status');
        foreach ($data['list'] as &$v) {
            $v['type_str'] = $pt_type[$v['type']];
            $v['status_str'] = $pt_status[$v['status']];
        }

        return $data;
    }

}
