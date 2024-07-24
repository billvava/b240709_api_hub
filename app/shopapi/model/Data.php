<?php
declare (strict_types = 1);

namespace app\shopapi\model;

use think\facade\Db;
use think\Model;

class Data {
    
    

    public function getBuy($in) {
        $where = array();
        if ($in['mall_new_time_min'] && $in['mall_new_time_max']) {
            $where[] = array('mall_new_time','between', array($in['mall_new_time_min'], "{$in['mall_new_time_max']} 23:59:59"));
        } else if ($in['mall_new_time_max']) {
            $where[] = array('mall_new_time','<=', "{$in['mall_new_time_max']} 23:59:59");
        } else if ($in['mall_new_time_min']) {
            $where[] = array('mall_new_time','>=', $in['mall_new_time_min']);
        }
        $arr = array(
            array(
                'field' => 'mall_order_num',
                'min' => 'mall_order_num_min',
                'max' => 'mall_order_num_max',
            ),
            array(
                'field' => 'mall_total',
                'min' => 'mall_total_min',
                'max' => 'mall_total_max',
            ),
            array(
                'field' => 'mall_order_avg',
                'min' => 'mall_order_avg_min',
                'max' => 'mall_order_avg_max',
            ),
        );
        foreach ($arr as $v) {
            if ($in[$v['min']] && $in[$v['max']]) {
                $where[] = array($v['field'],'between', array($in[$v['min']], $in[$v['max']]));
            } else if ($in[$v['max']]) {
                $where[] = array($v['field'],'<=', $in[$v['max']]);
            } else if ($in[$v['min']]) {
                $where[] = array($v['field'],'>=', $in[$v['min']]);
            }
        }
        if ($in['rank']) {
            $where[] =["b.rank",'=','rank'];
        }

        $page_num = $in['page_num'] ? $in['page_num'] : 20;
        $count = Db::name('user_ext')->where($where)->alias('a')->join('user b','a.master_id=b.id')->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, $page_num);
        $start = $Page->firstRow;

        $data['list'] = Db::name('user_ext')->alias('a')->join('user b','a.master_id=b.id')->field("a.*,b.username,b.rank")->where($where)->limit($start ,$page_num+0)->select()->toArray();
        $data['page'] = $Page->show();
        $data['count'] = $count;
        $data['page_num'] = $page_num;
        return $data;
    }

    public function user_rank($in) {
        $where=[
            ['pay_status','=',1],
        ];
        if ($in['create_time_min'] && $in['create_time_max']) {
            $where[] = array('create_time','between', array($in['create_time_min'], "{$in['create_time_max']}"));
        } else if ($in['create_time_max']) {
            $where[] = array('create_time','<=', "{$in['mall_new_time_max']}");
        } else if ($in['create_time_min']) {
            $where[] = array('create_time','>=', $in['create_time_min']);
        }

        $start = 0;
        $page_num = $in['page_num'] ? $in['page_num'] : 20;
        if ($in['page_type'] == 'admin') {
            $d = Db::name('mall_order')->where($where)
                    ->group("master_id")
                    ->field("master_id")
                    ->select()->toArray();
            $count = count($d);
            tool()->classs('PageForAdmin');
            $Page = new \PageForAdmin($count, $page_num);
            $start = $Page->firstRow;
            $data['page'] = $Page->show();
        }
        $data['list'] = Db::name('mall_order')->where($where)
                ->field(array('master_id', 'username', "count(*) as count", 'sum(goods_total) as total'))
                ->limit($start, $page_num)
                ->group("master_id")
                ->order("total desc")
                ->select()->toArray();

        $data['count'] = $count;
        $data['current_page'] = $in['p'] ? $in['p'] : 1;
        $data['page_num'] = $page_num;
        return $data;
    }

    public function goods_rank($in) {
        $where=[
           ['pay_status','=',1],
        ];
        if ($in['create_time_min'] && $in['create_time_max']) {
            $where[] = array('create_time','between', array($in['create_time_min'], "{$in['create_time_max']}"));
        } else if ($in['create_time_max']) {
            $where[] = array('create_time','<=', "{$in['mall_new_time_max']}");
        } else if ($in['create_time_min']) {
            $where[] = array('create_time','>=', $in['create_time_min']);
        }

        $data = Db::name('mall_order_goods')->where($where)->alias('a')
                ->join('mall_order b','a.order_id=b.order_id')
                ->field(array("a.goods_id", 'a.name', 'sum(a.total_price) as total', 'sum(a.num) as num'))
                ->limit(100)
                ->group("goods_id")
                ->order("total desc")
                ->select()->toArray();
        return $data;
    }

    public function cate_rank($in) {
        $where=[
              ['pay_status','=',1],
        ];
        if ($in['create_time_min'] && $in['create_time_max']) {
            $where[] = array('create_time','between', array($in['create_time_min'], "{$in['create_time_max']}"));
        } else if ($in['create_time_max']) {
            $where[] = array('create_time','<=', "{$in['mall_new_time_max']}");
        } else if ($in['create_time_min']) {
            $where[] = array('create_time','>=', $in['create_time_min']);
        }

        $data['list'] = Db::name('mall_order_goods')->where($where)->alias('a')
                ->join('mall_order b','a.order_id=b.order_id')
                ->field(array("a.category_id", 'sum(a.total_price) as total', 'sum(a.num) as num'))
                ->limit(100)
                ->group("category_id")
                ->order("total desc")
                ->select()->toArray();
        $data['cate'] = (new GoodsCategory())->getOption();
        return $data;
    }

   //最近七天
    public function order_7() {
        $d = date('Y-m-d', strtotime("-6 day"));
        $start = "{$d} 00:00:00";
        $where = array( array('create_time','>=', $start));
        $field = array('count(*) as count', "date_format(create_time, '%d') as month", "sum(goods_total) as total");
        $res = Db::name('mall_order')
                ->where($where)
                ->field($field)
                ->group("date_format(create_time, '%Y-%m-%d')")
                ->cache(3600)
                ->select()->toArray();
        $counts = array_reduce($res, function ($v,$w){
            $v[$w["month"]]=$w["count"];return $v;
        });
        $totals = array_reduce($res, function ($v,$w){
            $v[$w["month"]]=$w["total"];return $v;
        });
        for ($i = 1; $i <= 7; $i++) {
            $k = date('Y-m-d', strtotime($d) + (3600 * 24 * $i));
            
            $k2 = date('d', strtotime($d) + (3600 * 24 * $i));
            $names[] = "'" . date('m-d', strtotime($k)) . "'";
            $t[] = $totals[$k2] ? $totals[$k2] : 0;
            $c[] = $counts[$k2] ? $counts[$k2] :0;
        }
        return array(
            't' => $t,
            'names' => $names,
            'c' => $c,
        );
    }

    //最近七天
    public function user_7() {
        $d = date('Y-m-d', strtotime("-6 day"));
        $start = "{$d} 00:00:00";
        $where = array( array('create_time','>=', $start));
        $field = array('count(*) as count', "date_format(create_time, '%d') as month");
        $res = Db::name('user')
                ->where($where)
                ->field($field)
                ->group("date_format(create_time, '%Y-%m-%d')")
                ->cache(3600)
                ->select()->toArray();
        $counts = array_reduce($res, function ($v,$w){
            $v[$w["month"]]=$w["count"];return $v;
        });
        for ($i = 1; $i <= 7; $i++) {
            $k = date('Y-m-d', strtotime($d) + (3600 * 24 * $i));
            $k2 = date('d', strtotime($d) + (3600 * 24 * $i));
            $names[] = "'" . date('m-d', strtotime($k)) . "'";
            $c[] = $counts[$k2] ? $counts[$k2] :0;
        }
        return array(
            'names' => $names,
            'c' => $c,
        );
    }
}
