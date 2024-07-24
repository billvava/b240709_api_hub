<?php

declare (strict_types = 1);

namespace app\com\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class RechargeItem extends Model {

    protected $name = 'recharge_item';

    public function dbName() {
        return $this->name;
    }

    public function get_pk() {
        return "id";
    }

    public function handle($v) {
        $money = $v['money'];
        if (ceil($v['money']) == $v['money']) {
            $money = ceil($money);
        }
        $give = $v['give'];
        if (ceil($v['give']) == $v['give']) {
            $give = ceil($give);
        }
        $v['txt'] = "充{$money}" . ($give > 0 ? "赠{$give}" : '');
        return $v;
    }

    public function getList($where, $page = 1, $num = 10) {
        $order = "sort asc,id desc";
        $data = Db::name($this->name)->where($where)->page(($page ?: 1), $num)->order($order)->cache(true)->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow, $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array()) {
        $pre = md5(serialize($where));
        $name = "recharge_item_1616730795_{$pre}";
        $data = cache($name);
        if (!$data) {
            $order = "sort asc,id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    public function itemAll() {
        $data = $this->getAll();
        $list = array();
        $pk = $this->get_pk();
        foreach ($data as $v) {
            $list[] = array(
                'val' => $v[$pk], 'name' => $v['name']
            );
        }
        return $list;
    }

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "recharge_item_info_1616730795_{$pk}";
        $data = cache($name);
        if (!$data) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            cache($name, $data);
        }
        return $data;
    }

    //获取字典
    public function getLan($field = '') {
        $lans = array('status' => array('1' => '是', '0' => '否',),);
        if ($field == '') {
            return $lans;
        }
        return $lans[$field];
    }

    public function getOption($name = 'name') {
        $as = $this->getAll();
        $this->open_name = $name;
        $names = array_reduce($as, function($v, $w) {
            $v[$w[$this->get_pk()]] = $w[$this->open_name];
            return $v;
        });
        return $names;
    }

    public function clear($pk = '') {
        $name = "recharge_item_1616730795";
        cache($name, null);
        if ($pk) {
            $name = "recharge_item_info_1616730795_{$pk}";
            cache($name, null);
        }
    }

    public function setVal($id, $key, $val) {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key => $val]);
        }
    }

    public function getVal($id, $key, $cache = true) {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->cache($cache)->value($key);
        }
    }

    /**
     * 搜索框
     * @return type
     */
    public function searchArr() {
        return [
            'id' => '1',
            'sort' => '1',
            'name' => '1',
            'money' => '1',
            'status' => '1',
        ];
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return ['id' => 'id',
            'sort' => '排序',
            'name' => '充值项',
            'money' => '充值面额',
            'status' => '状态 1上架 0下架',
        ];
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return [
            'rule' => [
                'sort|排序' => ["integer",],
                'name|充值项' => ["max" => 255,],
                'money|充值面额' => ["float",],
                'status|状态 1上架 0下架' => ["require", "integer",],
            ],
            'message' => []
        ];
    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField() {
        return "id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue() {
        return ['id' => '',
            'sort' => '99',
            'name' => '',
            'money' => '',
            'status' => '1',
        ];
    }

    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr() {
        return [];
    }

    /**
     * 字段类型
     * @return type
     * #fieldType#
     */
    public function fieldType() {
        return [];
    }

}
