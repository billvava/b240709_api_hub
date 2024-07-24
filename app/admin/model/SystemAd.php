<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SystemAd extends Model
{

    protected $name='system_ad';
    
    /**
     * 获取广告图列表
     * @param type $id
     * @return type
     */
    public function get_ad($id) {
        if (!$id) {
            return false;
        }
        $name = "system_get_ad_1541044818_{$id}";
        $data = cache($name);
        if (!$data) {
            $date = date('Y-m-d H:i:s');
            if (!is_numeric($id)) {
                $id = $this->where(array('key' => $id))->value('id');
            }
            $where=[['ad_id','=',$id],['start','<',$date],['end','>',$date],['status','=',1]];

            $data = Db::name('system_ad_img')->where($where)->order("sort asc")->select()->toArray();
            foreach ($data as &$v) {
                $v['big'] = get_img_url($v['big']);
                //为了uview
                $v['image'] =  $v['big'];
                $v['img'] =  $v['big'];

                $v['small'] = get_img_url($v['small']);
            }
            cache($name, $data);
        }
        return $data;
    }

    public function clear_ad($id) {
        $name = "system_get_ad_1541044818_{$id}";
        cache($name, null);
    }



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
        $names = array_reduce($as,  function ($v,$w){
            $v[$w[$this->getPk()]]=$w['name'];
            return $v;
        } );
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
            'id' => '编号',
            'name' => '广告名称',
            'remark' => '提示信息',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {

        return [
            'rule'=>[
//                'id|编号'  =>  'require|integer',
                'name|广告名称' =>  'require|length:0,25',
                'remark|提示信息' =>  'length:0,100',
            ],
            'message'=>[]
        ];

//        return array(
//            array('id', 'require', '编号不能是空！', 0),
//            array('id', 'checkIsNotInt', '编号必须是正整数', 0, 'callback'),
//            array('name', 'require', '广告名称不能是空！', 0),
//            array('name', '0,25', '广告名称长度最大25个字符', 0, 'length'),
//            array('remark', '0,100', '提示信息长度最大100个字符', 0, 'length'),
//        );
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
            'id' => '',
            'remark' => '',
        );
    }

    /**
     * 检测是否为数字
     * @param type $str
     * @return boolean
     */
    function checkIsNumber($str) {
        if ($str === '') {
            return false;
        }
        return is_numeric($str);
    }

    /**
     * 检测是否为数字，并且符号是正
     * @param type $str
     * @return boolean
     */
    function checkIsZhengNumber($str) {
        if ($str === '') {
            return false;
        }
        if (!is_numeric($str)) {
            return false;
        }
        if ($str < 0) {
            return false;
        }
        return true;
    }

    /**
     * 检测是否为正整数
     * @param type $str
     * @return boolean
     */
    function checkIsNotInt($str) {
        if (!$str && $str !== '0') {
            return false;
        }
        if (!is_numeric($str)) {
            return false;
        }
        if ($str < 0) {
            return false;
        }
        if (is_int($str)) {
            return true;
        }
    }

    /**
     * 检测是否为整数
     * @param type $str
     * @return boolean
     */
    function checkIsInt($str) {
        if (!$str && $str !== '0') {
            return false;
        }
        if (!is_numeric($str)) {
            return false;
        }
        if (is_int($str)) {
            return true;
        }
    }


}