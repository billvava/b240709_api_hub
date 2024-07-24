<?php

namespace app\#moudleName#\controller;

use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * #name#
 * @auto true
 * @auth true
 * @menu true
 */
class #controllerName# extends Common {

    public $model;
    public $time_field;
    public $time_where;
    public $name;
    public $cate_field;
    public $cate_where;
    public $cate_lan;
    public $total_field;

    public function __construct() {
        parent::__construct(app());
        $this->model = Db::name("#table_name#");
        $this->time_field = "#time_field#";
        $this->cate_field = "#cate_field#";
        $this->time_where = "#time_where#";
        $this->cate_where = "#cate_where#";
        $this->cate_lan = "#cate_lan#";
        $this->total_field = "#total_field#";
        $this->name = "#name#";
        View::assign('time_field', $this->time_field);
        View::assign('name', $this->name);
        View::assign('cate_field', $this->cate_field);
        View::assign('total_field', $this->total_field);
        $this->cate_lan = array(#cate_lan#);
        View::assign('cate_lan', $this->cate_lan);
    }

    public function index() {
        return $this->day();
    }

    public function cate() {
        $where = array();
        if ($this->cate_where) {
            $where[] = $this->cate_where;
        }
        $data = $this->model->field(array("count(*) as count", "{$this->cate_field}"))->where($where)->group($this->cate_field)->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    public function year() {
        $where = array();
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field}, '%Y') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $data = $this->model->where($where)
                ->field($field)
                ->group("date_format({$this->time_field}, '%Y')")
                ->select()->toArray();
        View::assign('data', $data);
       return $this->display();
    }

    public function month() {
        $time = $this->in['time'] ? $this->in['time'] : date('Y');
        $start = "{$time}-01-01 00:00:00";
        $end = "{$time}-12-31 23:59:59";
        
         $where = [];
        $where[] = [
            $this->time_field,'between',[$start, $end]
        ];       
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field},'%Y-%m') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $res = $this->model
                ->where($where)
                ->field($field)
                ->group("date_format({$this->time_field}, '%Y-%m')")
                ->select()->toArray();
        $temp = array_reduce($res, function($v,$w){ 
            $v[$w[ 'month']]=$w['count'];return $v; });        
            
            
        if ($this->total_field) {
            $totals =  array_reduce($res, function($v,$w){ 
            $v[$w[ 'month']]=$w['total'];return $v; });      
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

    public function day() {
        $time = $this->in['time'] ? $this->in['time'] : date('Y-m');
        $yy = explode('-', $time);
        $max = getMonthLastDay($yy[1], $yy[0]);
        $start = "{$time}-01 00:00:00";
        $end = "{$time}-{$max} 23:59:59";
        $where = [];
        $where[] = [
            $this->time_field,'between',[$start, $end]
        ];
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field}, '%d') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $res = $this->model
                ->where($where)
                ->field($field)
                ->group("date_format({$this->time_field}, '%Y-%m-%d')")
                ->select()->toArray();
        
          $temp = array_reduce($res, function($v,$w){ 
            $v[$w[ 'month']]=$w['count'];return $v; });        
            
            
        if ($this->total_field) {
             $totals =  array_reduce($res, function($v,$w){ 
            $v[$w[ 'month']]=$w['total'];return $v; });     
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
        return $this->display('day');
    }

}
