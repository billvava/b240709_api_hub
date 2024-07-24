<?php
declare (strict_types = 1);

namespace app\shopapi\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class Feedback extends Model {


    protected $name='mall_order_feedback';

        public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
    }



    //获取list
    public function get_data($in = array()) {
        $where = array();
        $like_arr = array('content', 'tel', 'name', 'remark');
        $status_arr = array('order_id', 'status','master_id');
        foreach ($in as $k => $v) {
            if (in_array($k, $like_arr) && $v) {
                $where[] = array('a.' . $k,'like', "%{$v}%");
            }
            if (in_array($k, $status_arr) && $v !== '') {
                $where[] =['a.' . $k,'=',$v] ;
            }
        }
        if ($in['mul_time']) {
            $mul_times = explode(' - ', $in['mul_time']);
            if (strtotime($mul_times[0]) > 0 && strtotime($mul_times[1]) > 0) {
                $where[] = array('create_time','between', array($mul_times[0], "{$mul_times[1]} 23:59:59"));
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
            $count = Db::name($this->name)->alias('a')->where($where)->count();
            if ($in['page_type'] == 'admin') {
                tool()->classs('PageForAdmin');
                $Page = new \PageForAdmin($count, $page_num);
            } else {
                tool()->classs('Page');
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
        if ($in['field']) {
            $field = $in['field'];
        }
        $data['list'] = Db::name($this->name)
            ->alias('a')
            ->field($field)
            ->where($where)
            ->limit($start, $page_num)
            ->order($order)
            ->select()->toArray();
        if($data['list']){
            foreach ($data['list'] as &$v) {

            }
        }

        return $data;
    }


}
