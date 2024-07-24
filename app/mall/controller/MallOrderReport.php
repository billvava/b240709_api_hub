<?php
namespace app\mall\controller;
use app\admin\model\SystemAreas;
use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
 * 订单时间统计
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-integral_fill
 */
class MallOrderReport extends Common {

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
        $this->cate_field = "is_new";
        $this->time_where = [['pay_status','=',1]];
        $this->cate_where = [['pay_status','=',1]];
        $this->cate_lan = "0@老客户
1@新客户";
        $this->total_field = "goods_total";
        $this->name = "有效订单统计";
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
        View::assign('order_source', lang('order_source'));
    }


    /**
     * 按分类
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
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
        $data = Db::name('mall_order')->field(array("sum(goods_total) as count", "{$this->cate_field}"))->where($where)->group($this->cate_field)->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 按来源
     * @auto true
     * @auth true
     * @menu true
     */
    public function source() {
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
        $data = Db::name('mall_order')->field(array("sum(goods_total) as count", "source"))->where($where)->group("source")->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 按金额区间
     * @auto true
     * @auth true
     * @menu true
     */
    public function total_sca() {
        $a = array(
            array(
                'name' => "0-50元",
                'min' => 0,
                'max' => 50,
            ),
            array(
                'name' => "51-100元",
                'min' => 51,
                'max' => 100,
            ),
            array(
                'name' => "101-200元",
                'min' => 101,
                'max' => 200,
            ),
            array(
                'name' => "201-500元",
                'min' => 201,
                'max' => 500,
            ),
            array(
                'name' => "501-1000元",
                'min' => 501,
                'max' => 1000,
            ),
            array(
                'name' => "1001-5000元",
                'min' => 1001,
                'max' => 5000,
            ),
            array(
                'name' => "5001-10000元",
                'min' => 5001,
                'max' => 10000,
            ),
            array(
                'name' => "10000元以上",
                'min' => 10000,
            ),
        );

        $field = array();
        $data['names'] = array();
        foreach ($a as $k => $v) {
            $and = '';
            if (isset($v['max'])) {
                $and = "and goods_total<={$v['max']}";
            }
            $data['names'][] = "'{$v['name']}'";
            $field[] = "count( (goods_total>={$v['min']} {$and}) or null ) as c{$k}";
        }
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
//        C('sql',1);
        $data['count'] = Db::name('mall_order')->where($where)->field($field)->find();
//        p($data);
//        select count( (goods_total>=0 and goods_total<=50) or null ), count( (goods_total>=100 and goods_total<=200) or null ) from xf_mall_order
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
        $data = Db::name('mall_order')->where($where)
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
        $res = Db::name('mall_order')
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
        $where = array( array($this->time_field ,'between', array($start, $end)));
        if ($this->time_where) {
            $where[] = $this->time_where;
        }
        $field = array('count(*) as count', "date_format({$this->time_field}, '%d') as month");
        if ($this->total_field) {
            $field[] = "sum({$this->total_field}) as total";
        }
        $res = Db::name('mall_order')
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
