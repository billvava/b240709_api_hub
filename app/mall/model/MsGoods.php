<?php
declare (strict_types=1);

namespace app\mall\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class MsGoods extends Model
{


    protected $name = 'ms_goods';

    public function dbName()
    {
        return $this->name;
    }

    public function get_pk()
    {
        return "id";
    }


    public function handle($v)
    {
        $mall_goods = new \app\mall\model\Goods();
        $info = $mall_goods->getInfo($v['goods_id'], 0, 1);
        $v['thumb'] = $info['thumb'];
        $v['min_market_price'] = $info['min_market_price'];
        if (!$v['name']) {
            $v['name'] = $info['name'];
        }
        $v['sale_status'] = ($v['num'] <= $v['sale_num']) ? 0 : 1;
        $v['sale_bili'] = ($v['sale_num'] / $v['num']) * 100;
        $v['sale_bili'] = $v['sale_bili'] > 100 ? 100 : $v['sale_bili'];
        //倒计时
        $v['second'] = strtotime($v['end']) - time();


       $v['zong'] =  $v['num']+$v['sale_num'];

       $v['bili'] =  ($v['sale_num']/$v['zong']) * 100;

        return $v;
    }

    public function getList($where, $page = 1, $num = 10)
    {
        $order = "end asc,id desc";

        $data = Db::name($this->name)->where($where)->page(($page ?: 1), $num)->order($order)->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function get_data($where = array(), $num = 10)
    {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow, $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array())
    {
        $pre = md5(serialize($where));
        $name = "ms_goods_1616478764_{$pre}";
        $data = cache($name);
        if (!$data) {
            $order = "sort asc,id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }


    public function itemAll()
    {
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

    public function getInfo($pk)
    {
        if (!$pk) {
            return null;
        }
        $name = "ms_goods_info_1616478764_{$pk}";
        $data = cache($name);
        if (!$data || 1) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            if ($data) {

                $items = json_decode($data['items'], true);
                $data['items_kv'] = $items;
                $data['items'] = (new Goods())->getItems($data['goods_id']);
                if ($data['items']) {
                    foreach ($data['items'] as &$v) {
                        $v['new_price'] = $items[$v['id']];
                        if($items[$v['id']]){
                            $v['price'] = $items[$v['id']];
                        }
                    }
                }
                $data = $this->handle($data);
            }

            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field = '')
    {
        $lans = array('status' => array('1' => '是', '0' => '否',),);
        if ($field == '') {
            return $lans;
        }
        return $lans[$field];
    }

    public function getOption($name = 'name')
    {
        $as = $this->getAll();
        $this->open_name = $name;
        $names = array_reduce($as, function ($v, $w) {
            $v[$w[$this->get_pk()]] = $w[$this->open_name];
            return $v;
        });
        return $names;
    }


    public function clear($pk = '')
    {
        $name = "ms_goods_1616478764";
        cache($name, null);
        if ($pk) {
            $name = "ms_goods_info_1616478764_{$pk}";
            cache($name, null);
        }
    }


    public function setVal($id, $key, $val)
    {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key => $val]);
        }

    }

    public function getVal($id, $key, $cache = true)
    {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->cache($cache)->value($key);
        }
    }


    /**
     * 搜索框
     * @return type
     */
    public function searchArr()
    {
        return [
            'id' => '1',
            'goods_id' => '1',
            'num' => '1',
            'cate_id' => '1',
            'status' => '1',
            'sort' => '1',
            'sale_num' => '1',
            'items' => '1',
            'min_price' => '1',
            'end' => '1',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels()
    {
        return ['id' => '编号',
            'goods_id' => '商品',
            'num' => '库存数量',
            'cate_id' => '分类',
            'status' => '状态',
            'sort' => '排序',
            'sale_num' => '已售数量',
            'items' => '规格参数',
            'min_price' => '最低价格',
            'end' => '结束时间',
        ];
    }

    /**
     * 规则
     * @return type
     */
    public function rules()
    {
        return [
            'rule' => [
                'goods_id|商品' => ["integer",],
                'num|库存数量' => ["integer",],
                'cate_id|分类' => ["integer",],
                'status|状态' => ["integer",],
                'sort|排序' => ["integer",],
                'sale_num|已售数量' => ["require", "integer",],
                'items|规格参数' => ["max" => 2500,],
                'min_price|最低价格' => ["float",],
                'end|结束时间' => [],

            ],
            'message' => []
        ];

    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField()
    {
        return "id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue()
    {
        return ['id' => '',
            'goods_id' => '',
            'num' => '',
            'cate_id' => '',
            'status' => '1',
            'sort' => '99',
            'sale_num' => '0',
            'min_price' => '',
            'end' => '',
        ];
    }

    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr()
    {
        return [];
    }

    /**
     * 字段类型
     * @return type
     * #fieldType#
     */
    public function fieldType()
    {
        return [];
    }


}
