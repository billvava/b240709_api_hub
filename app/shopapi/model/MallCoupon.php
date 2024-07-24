<?php
namespace app\shopapi\model;

use think\facade\Db;
use think\Model;
/**
*代码自动生成所需参数
*moudleName:模块名称
*tableName:表名
*modelName:模型名称
**/
class MallCoupon extends Model {

    protected $name = 'mall_coupon';


    public function send_tpl($master_id, $tpl_id, $num = 1, $source = 1) {
        if ($num <= 0) {
            return array('status' => 0, 'info' => '数量太少了');
        }
        $MallCouponTpl = new MallCouponTpl();
        $info = $MallCouponTpl->getInfo($tpl_id);
        if (!$info) {
            return array('status' => 0, 'info' => '模板不存在');
        }
        if ($info['end_type'] == 1) {
            $end = $info['end'];
        } else if ($info['end_type'] == 2) {
            $end = date('Y-m-d H:i:s', strtotime("+{$info['day']} day"));
        }
        $add = array();
        for ($i = 1; $i <= $num; $i++) {
            $add[] = array(
                'name' => $info['name'],
                'base_money' => $info['base_money'],
                'money' => $info['money'],
                'end' => $end,
                'time' => date('Y-m-d H:i:s'),
                'master_id' => $master_id,
                'range' => $info['range'],
                'goods_id' => $info['goods_id'],
                'category_id' => $info['category_id'],
                'source' => $source,
                'remark' => $info['remark'],
                'tpl_id' => $tpl_id,
            );
        }
        $this->insertAll($add);
        return array('status' => 1, 'num' => count($add),'tplinfo'=>$info);
    }

    public function handle($v) {
        $v['status_str'] = '去使用';
        $v['class'] = 'useable';
        $v['is_can'] = 1;
        $coupon_range = lang('coupon_range');
        if ($v['status'] == 1) {
            $v['is_can'] = 2;
            $v['status_str'] = '已使用';$v['class'] = 'used';
        } else if (strtotime($v['end']) < time()) {
            $v['is_can'] = 3;
            $v['status_str'] = '已过期';$v['class'] = 'expired';
        }
        if ($v['base_money'] == floor($v['base_money'])) {
            $v['base_money'] = floor($v['base_money']);
        }
        if ($v['money'] == floor($v['money'])) {
            $v['money'] = floor($v['money']);
        }
        $v['msg'] = "消费满￥{$v['base_money']}可用";
        $v['end_msg'] = "截止至：{$v['end']}";
        $v['range_str'] = $coupon_range[$v['range']];
        $v['type_msg'] = '代金卷';
        return $v;
    }

    public function getList($where = array(), $page = 1, $num = 15, $order = "") {
        $order = $order ? $order : "status asc,end desc";
        $data = $this->where($where)->page(($page ?: 1), $num)->order($order)->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    //可用数量
    public function getCanCount($master_id) {
        $where = array(
            ['master_id','=',$master_id],
            ['status','=',0],
            ['end','>',date('Y-m-d H:i:s')],
        );
        return $this->where($where)->count();
    }

    public function send($map = array()) {
        /*
          name  base_money money start end time master_id  status
         *          */
        return $this->insertGetId(array(
            'name' => $map['name'] ? $map['name'] : '',
            'base_money' => $map['base_money'] ? $map['base_money'] : 0,
            'money' => $map['money'] ? $map['money'] : 0,
            'start' => $map['start'] ? $map['start'] : date('Y-m-d H:i:s'),
            'end' => $map['end'] ? $map['end'] : date('Y-m-d H:i:s', strtotime('+365 day')),
            'time' => date('Y-m-d H:i:s'),
            'master_id' => $map['master_id'] ? $map['master_id'] : 0,
            'status' => 0,
            'goods_id' => $map['goods_id'] ? $map['goods_id'] : 0,
            'get_id' => $map['get_id'] ? $map['get_id'] : 0,
        ));
    }

}
