<?php

namespace app\mall\controller;

use app\common\lib\Lib;
use app\common\lib\Plug;
use app\common\Lib\Util;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 用户统计
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-people_fill
 */
class UserReport extends Common {

    public $model;
    public $time_field;
    public $time_where;
    public $name;
    public $cate_field;
    public $cate_where;
    public $cate_lan;
    public $total_field;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->time_field = "create_time";
        $this->cate_field = "rank";
        $this->time_where = "";
        $this->cate_where = "";
        $this->cate_lan = "1@哈哈";
        $this->total_field = "";
        $this->name = "注册用户";
        View::assign('time_field', $this->time_field);
        View::assign('name', $this->name);
        View::assign('cate_field', $this->cate_field);
        View::assign('total_field', $this->total_field);
        if ($this->cate_lan) {
            $h1 = explode(PHP_EOL, $this->cate_lan);
            $this->cate_lan = array();
            foreach ($h1 as $v) {
                $h2 = explode('@', $v);
                $this->cate_lan[$h2[0]] = $h2[1];
            }
        } else {
            $this->cate_lan = array();
        }
        View::assign('cate_lan', $this->cate_lan);
    }

   
    /**
     * 按分类
     * @auto true
     * @auth true
     * @menu true
     */
    public function cate() {
        $where = array();
        if ($this->cate_where) {
            $where[] = $this->cate_where;
        }
        $in = $this->in;
        if ($in['create_time_min'] && $in['create_time_max']) {
            $where[] = array('create_time','between', array($in['create_time_min'], "{$in['create_time_max']}"));
        } else if ($in['create_time_max']) {
            $where[] = array('create_time','<=', "{$in['mall_new_time_max']}");
        } else if ($in['create_time_min']) {
            $where[] = array('create_time','>=', $in['create_time_min']);
        }
        $data = Db::name('user')->field(array("count(*) as count", "{$this->cate_field}"))->where($where)->group($this->cate_field)->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 按年
     * @auto true
     * @auth true
     * @menu true
     */
    public function year() {
        $where = array();
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field}, '%Y') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $data = Db::name('user')->where($where)
                ->field($field)
                ->group("date_format({$this->time_field}, '%Y')")
                ->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 按月
     * @auto true
     * @auth true
     * @menu true
     */
    public function month() {
        $time = $this->in['time'] ? $this->in['time'] : date('Y');
        $start = "{$time}-01-01 00:00:00";
        $end = "{$time}-12-31 23:59:59";
        $where = array( array($this->time_field ,'between', array($start, $end)));
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field},'%Y-%m') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $res = Db::name('user')
                ->where($where)
                ->field($field)
                ->group("date_format({$this->time_field}, '%Y-%m')")
                ->select()->toArray();
        $temp = array_reduce($res, function ($v,$w){
            $v[$w["month"]]=$w["count"];return $v;
        });
        if ($this->total_field) {
            $totals = array_reduce($res, function ($v,$w){
                $v[$w["month"]]=$w["total"];return $v;
            });
            View::assign('totals', $totals);
        }
        $data = array();
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $i = "0{$i}";
            }
            $k = "{$time}-{$i}";
            $data[$k] = $temp[$k] ? $temp[$k] : 0;
        }
        View::assign('res', $res);
        View::assign('time', $time);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 按天
     * @auto true
     * @auth true
     * @menu true
     */
    public function day() {
        $time = $this->in['time'] ? $this->in['time'] : date('Y-m');
        $yy = explode('-', $time);
        $max = getMonthLastDay($yy[1], $yy[0]);
        $start = "{$time}-01 00:00:00";
        $end = "{$time}-{$max} 23:59:59";
        $where = array( array($this->time_field,'between', array($start, $end)));
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field}, '%d') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $res = Db::name('user')
                ->where($where)
                ->field($field)
                ->group("date_format({$this->time_field}, '%Y-%m-%d')")
                ->select()->toArray();
        $temp = array_reduce($res, function ($v,$w){
            $v[$w["month"]]=$w["count"];return $v;
        });
        if ($this->total_field) {
            $totals = array_reduce($res, function ($v,$w){
                $v[$w["month"]]=$w["total"];return $v;
            });
            View::assign('totals', $totals);
        }
        $data = array();
        for ($i = 1; $i <= $max; $i++) {
            if ($i < 10) {
                $i = "0{$i}";
            }
            $k = "{$i}";
            $data[$k] = $temp[$k] ? $temp[$k] : 0;
        }
        View::assign('res', $res);
        View::assign('time', $time);
        View::assign('data', $data);
        return $this->display();
    }

}
