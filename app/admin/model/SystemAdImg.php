<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SystemAdImg extends Model
{

    protected $name='system_ad_img';
    




    public function getAll() {
        $name = "system_ad_1541044818";
        $data = cache($name);
        if (!$data) {
            $order = "id desc";
            $data = $this->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "system_ad_info_1541044818_{$pk}";
        $data = cache($name);
        if (!$data) {
            $mypk = $this->getPk();
            if (!$mypk) {
                return null;
            }
            $data = $this->find($pk);
            cache($name, $data);
        }
        return $data;
    }

    public function getOption($name = 'name') {
        $as = $this->getAll();
        $names = array_reduce($as, create_function('$v,$w', '$v[$w["' . $this->getPk() . '"]]=$w["' . $name . '"];return $v;'));
        return $names;
    }

    public function clear($pk = '') {
        $name = "system_ad_1541044818";
        cache($name, null);
        if ($pk) {
            $name = "system_ad_info_1541044818_{$pk}";
            cache($name, null);
        }
    }

    public function setVal($id, $key, $val) {
        $pk = $this->getPk();
        if ($pk) {
            return $this->where(array($pk => $id))->setField($key, $val);
        }
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'id'=>'id',
            'ad_id'=>'上级',
            'name'=>'名称',
            'big'=>'大图',
            'small'=>'小图',
            'txt'=>'文字',
            'link'=>'链接',
            'sort'=>'排序',

        );
    }
    /**
     * 规则
     * @return type
     */
    public function rules() {
        return [
            'rule'=>[
                'name|名称' =>  'require|length:0,25',

            ],
            'message'=>[]
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
        return array(
            'id'=>'',
            'ad_id'=>'',
            'big'=>'',
            'small'=>'',
            'txt'=>'',
            'link'=>'',
            'sort'=>'99',

        );
    }


}