<?php
declare (strict_types=1);

namespace app\user\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class UserCashoutChannel extends Model
{


    protected $name = 'user_cashout_channel';

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


        //自动生成语言包
        $lans = $this->getLan();
        foreach ($lans as $type => $arr) {
            $v["{$type}_str"] = $arr[$v[$type]];
        }
        $v['name'] .= '';
        $v['num'] .= '';
        $v['realname'] .= '';

        $v['text'] = '【'.$v['cate_str'].'】' . ' ****' . mb_substr($v['num'], -4, 4, 'utf-8');
        return $v;
    }

    public function getList($where, $page = 1, $num = 10)
    {
        $page = $page + 0;

        $order = "id desc";
        $data = Db::name($this->name)->where($where)->page($page, $num)->order($order)->select()->toArray();
        $bg = ['#3b64ff;', '#ff5248;', '#2dbd3e;'];
        $i = 0;
        foreach ($data as &$v) {
            $v = $this->handle($v);
            $v['bg'] = $bg[$i];
            $i++;
            if ($i > 2) {
                $i = 0;
            }
        }
        return $data;
    }

    public function get_data($where = array(), $num = 10)
    {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow, $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array(), $cache = true)
    {
        $pre = md5(json_encode($where));
        $name = "user_cashout_channel_1646034710_{$pre}";
        $data = cache($name);
        if (!$data || !$cache) {
            $order = "id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            foreach ($data as &$v) {
                $v = $this->handle($v);
            }
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

    public function getInfo($pk, $cache = true)
    {
        if (!$pk) {
            return null;
        }
        $name = "user_cashout_channel_info_1646034710_{$pk}";
        $data = cache($name);
        if (!$data || !$cache) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            if ($data) {
                $data = $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field = '')
    {
        $lans = array('cate' => array('bank' => '银行卡',
         // 'alipay' => '支付宝',
           'weixin' => '微信',
            ),);
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
        $name = "user_cashout_channel_1646034710";
        cache($name, null);
        if ($pk) {
            $name = "user_cashout_channel_info_1646034710_{$pk}";
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
            'id' => '',
            'num' => '',
            'user_id' => '',
            'user_id' => '',
            'realname' => '',
            'tel' => '',
            'cate' => '',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels()
    {
        return ['id' => '编号',
            'name' => '银行名称',
            'address' => '开户点',
            'num' => '账号',
            'user_id' => '用户',
            'realname' => '姓名',
            'tel' => '手机',
            'cate' => '渠道分类',
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
                'name|银行名称' => ["max" => 255,],
                'address|开户点' => ["max" => 255,],
                'num|账号' => ["max" => 255,],
                'user_id|用户' => ["integer",],
                'realname|姓名' => ["max" => 255,],
                'tel|手机' => ["max" => 255,],
                'cate|渠道分类' => ["max" => 25,],

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
            'name' => '',
            'address' => '',
            'num' => '',
            'user_id' => '',
            'realname' => '',
            'tel' => '',
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
     * 要转成日期的字段
     * @return type
     */
    public function dateAttr()
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
