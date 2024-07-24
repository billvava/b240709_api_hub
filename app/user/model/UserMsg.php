<?php
declare (strict_types = 1);

namespace app\user\model;

use app\common\model\O;
use think\facade\Config;
use think\facade\Db;
use think\Model;


/**
 * @mixin think\Model
 */
class UserMsg extends Model{
    protected $name='user_msg';

    public function addLog($master_id,$type){
        $this->insert([
            'master_id'=>$master_id,
            'type'=>$type,
            'time'=>date('Y-m-d H:i:s')
        ]);
    }

    public function handle($v){

        if($v){
            if(!$v['title']){
                $v['title'] = '系统通知';
            }
            if($v['type']){
                $types = [
                    1=>'您好，您有一个新的工单需要处理，请前往查看接单。'
                ];
                $types2 = [
                    1=>'/pages/work-order/work-order'
                ];

                $v['content'] = $types[$v['type']];
                $v['link'] = $types2[$v['type']];
            }

        }
        return $v;
    }

    public function getList($where, $page = 1, $num = 10, $cache = false) {
        $order = "is_read asc,id desc";
        $data = $this->where($where)->page($page, $num)->order($order)->cache($cache)->select()->toArray();
        foreach ($data as &$v) {
                $v = $this->handle($v);
        }
        return $data;
    }


    public function getAll($where = array()) {
        $pre = md5(serialize($where));
        $name = "user_msg_1557020533_{$pre}";
        $data = cache($name);
        if (!$data) {
            $order = "id desc";
            $data = $this->where($where)->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "user_msg_info_1557020533_{$pk}";
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
        $this->f=$name;
        $names = array_reduce($as, function ($v,$w){
            $v[$w[$this->getPk()]]=$w[$this->f];
            return $v;
        });
        return $names;
    }

    public function clear($pk = '') {
        $name = "user_msg_1557020533";
        cache($name, null);
        if ($pk) {
            $name = "user_msg_info_1557020533_{$pk}";
            cache($name, null);
        }
    }

    public function setVal($id, $key, $val) {
        $pk = $this->getPk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key=> $val]);
        }
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'sender' => 'sender',
            'title' => 'title',
            'content' => 'content',
            'time' => 'time',
            'is_read' => 'is_read',
            'is_del' => 'is_del',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return [
            'rule'=>[

                'title|标题' =>  'require|length:0,25',
                'content|内容' =>  'require',
            ],
            'message'=>[]
        ];
    }

    /**
     * 规则
     * @return type
     */
//    public function rules() {
//        return array(
//            array('sender', 'require', '发送者不能是空！', 0),
//            array('sender', '0,25', '发送者长度最大25个字符', 0, 'length'),
//            array('title', 'require', '标题不能是空！', 0),
//            array('title', '0,55', '标题长度最大55个字符', 0, 'length'),
//            array('content', 'require', '内容不能是空！', 0),
//            array('time', 'require', '发送时间不能是空！', 0),
//            array('time', 'checkIsInt', '发送时间必须是整数', 0, 'callback'),
//            array('is_read', 'require', '1=未读，2=已读不能是空！', 0),
//            array('is_read', 'checkIsInt', '1=未读，2=已读必须是整数', 0, 'callback'),
//            array('is_del', 'require', '0=不删除，1=删除不能是空！', 0),
//            array('is_del', 'checkIsInt', '0=不删除，1=删除必须是整数', 0, 'callback'),
//        );
//    }

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
            'user_id' => '',
            'content' => '',
            'time' => '',
            'is_read' => '1',
            'is_del' => '0',
        );
    }

    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr() {
        return array(
        );
    }

    /**
     * 字段类型
     * @return type
     */
    public function fieldType() {
        return array(
            #fieldType#
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

    /**
     * 验证邮箱
     * @param type $str
     * @return boolean
     */
    function checkEmail($str) {
        if (!$str) {
            return false;
        }
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($str, '@') !== false && strpos($str, '.') !== false) {
            if (preg_match($chars, $str)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 检查手机号码格式
     * @param type $str
     * @return boolean
     */
    function checkTel($str) {
        if (!$str) {
            return false;
        }
        if (preg_match("/^1\d{10}$/", $str)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检测数组，例如 id[]
     * @param type $i
     * @return boolean
     */
    public function checkArr($i) {
        if (count($i) <= 0) {
            return false;
        }
        return true;
    }

    /**
     * 检测生日
     */
    function checkIsDate($str) {
        if (strtotime($str) == 0) {
            return false;
        } else {
            return true;
        }
    }

}