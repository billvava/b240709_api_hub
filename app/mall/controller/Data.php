<?php
namespace app\mall\controller;
use app\common\controller\Admin as BCOM;
use app\common\Lib\Util;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
* 数据统计
* @auto true
* @auth true
* @menu true
* @icon icon-supply
*/
class Data extends Common {

    public $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\Data();
    }
    
    /**
    * 订单数统计
    * @auto true
    * @auth true
    * @menu true
    */
    public function index() {
        $order = new \app\mall\model\Order();
        $data['data_any'] = array(
            array(
                'title' => '订单数',
                'class' => 'layui-bg-blue',
                'count' => Db::name('mall_order')->where(array( array('create_time','>', date('Y-m-d'))))->count(),
                'name' => '今日订单总数',
                'pre' => '',
                'alias' => '天'
            ),
            array(
                'title' => '销售额',
                'class' => 'layui-bg-cyan',
                'count' => Db::name('mall_order')->where(array( array('pay_status','=',1),array('create_time','>', date('Y-m-d'))))->sum('total')+0,
                'name' => '今日销售总额',
                'pre' => '￥',
                 'alias' => '天'
            ),
            array(
                'title' => '销售额',
                'class' => 'layui-bg-green',
                'count' => Db::name('mall_order')->where(array(array('pay_status','=',1),array('create_time','between', array(date('Y-m-d', strtotime("-1 day")), date('Y-m-d')))))->sum('total')+0,
                'name' => '昨日销售总额',
                'pre' => '￥',
                 'alias' => '天'
            ),
            array(
                'title' => '销售额',
                'class' => 'layui-bg-orange',
                'count' => Db::name('mall_order')->where(array(array('pay_status','=',1),  array('create_time','>', date('Y-m-d', strtotime("-6 day")))))->sum('total')+0,
                'name' => '近7天销售总额',
                'pre' => '￥',
                 'alias' => '周'
            ),
        );

        $kuc = Db::name('mall_goods_item')->where(array(array('stock','<', 10)))->field(array('DISTINCT goods_id as goods_id'))->select()->toArray();
        $data['goods_overview'] = array(
            array(
                'count' => Db::name('mall_goods')->where(array('status' => 0))->count(),
                'name' => '已下架',
            ),
            array(
                'count' => Db::name('mall_goods')->where(array('status' => 1))->count(),
                'name' => '已上架',
            ),
            array(
                'count' => count($kuc),
                'name' => '库存紧张',
            ),
            array(
                'count' => Db::name('mall_goods')->where(array( array('status','in', array(0, 1))))->count(),
                'name' => '全部商品',
            ),
        );
        $user = Db::name('user');
        $yue = date('Y-m-01');
        $data['user_overview'] = array(
            array(
                'count' => Db::name('user')->where(array( array('create_time','>', date('Y-m-d'))))->count(),
                'name' => '今日新增',
            ),
            array(
                'count' => Db::name('user')->where(array(array('create_time','between', array(date('Y-m-d', strtotime("-1 day")), date('Y-m-d')))))->count(),
                'name' => '昨日新增',
            ),
            array(
                'count' => Db::name('user')->where(array( array('create_time','>', $yue)))->count(),
                'name' => '本月新增',
            ),
            array(
                'count' => Db::name('user')->count(),
                'name' => '会员总数',
            ),
        );
        

        $data['census'] = $order->census();
      
        $data['oqi'] = $this->model->order_7();
        $data['uqi'] = $this->model->user_7();
        View::assign('data', $data);
        return $this->display();
    }


    /**
    * 购买力筛选
    * @auto true
    * @auth true
    * @menu true
    */
    public function buy() {
        $this->in['page_num'] > 100 && $this->error('最大100');
        $data = $this->model->getBuy($this->in);
        View::assign('data', $data);
        $this->create_seo('购买力筛选');
        return $this->display();
    }


    /**
    * 会员排行
    * @auto true
    * @auth true
    * @menu true
    */
    public function user_rank() {
        if ($this->in['xls'] == 1) {
            $this->in['page_num'] = 1000;
        } else {
            $this->in['page_type'] = 'admin';
        }
        $data = $this->model->user_rank($this->in);
        if ($this->in['xls'] == 1) {
            (new Util())->xls("会员排行", array('用户ID', '用户名', '订单数', '消费金额',), $data['list']);
            die;
        }
        View::assign('data', $data);
        $this->create_seo('会员排行');
        return $this->display();
    }


    /**
    * 单品销量排行
    * @auto true
    * @auth true
    * @menu true
    */
    public function goods_rank() {
        $data = $this->model->goods_rank($this->in);
        if ($this->in['xls'] == 1) {
            (new Util())->xls("商品销量排行", array('商品编号', '商品','金额', '数量', ), $data);
            die;
        }
        View::assign('data', $data);
        $this->create_seo('商品销量排行');
        return $this->display();
    }
    /**
    * 分类销量排行
    * @auto true
    * @auth true
    * @menu true
    */
    public function cate_rank() {
        $data = $this->model->cate_rank($this->in);
        if ($this->in['xls'] == 1) {
            foreach ($data['list'] as &$v) {
                $v['name'] = $data['cate'][$v['category_id']] ? $data['cate'][$v['category_id']] : '未归类';
            }
            (new Util())->xls("分类销量排行", array('分类编号', '金额', '数量', '名称'), $data['list']);
            die;
        }
        View::assign('data', $data); 
        $this->create_seo('分类销量排行');
        return $this->display();
    }

}
