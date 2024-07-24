<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SystemConfigCate extends Model
{

    protected $name='system_config_cate';

    public function dbName()
    {
       return $this->name;
    }

    public function getTree($where = array(), $type = 1) {
        $order = "sort asc,id desc";
        $data = Db::name($this->name)->alias('a')
            ->field('a.*')
            ->where($where)
            ->order($order)
            ->select()->toArray();
        if ($type == 1) {
            $data = $this->_getTree($data);
        }
        if ($type == 2) {
            $data = $this->_getTree2($data);
        }
        return $data;
    }

    /**
     * 递归生成分类树
     * @param $item 分类数据
     * @param $pid  父级id
     * @param $sub 生成子分类键名
     * @param $level 当前层级
     * @return array
     */
    public function _getTree2($list, $pid = 0, $level = 0) {
        // static 表示声明一个静态变量, 静态变量在函数中会一直保存它的值
        static $tree = array();
        foreach ($list as $row) {
            if ($row['pid'] == $pid) {
                // 这个level是原来数组没有的，用于表示缩进的次数
                $row['level'] = $level;
                $row['name_pre'] = str_repeat('----', $level);
                $tree[] = $row;
                // 递归操作，重新把当前id传入函数中，获取当前id对应的子分类
                $this->_getTree2($list, $row['id'], $level + 1);
            }
        }
        return $tree;
    }

    public function _getTree($data, $parent_username = '0') {
        $arr = [];
        foreach ($data as $key => $val) {
            if ($val['pid'] == $parent_username) {
                $val['children'] = $this->_getTree($data, $val['id']);
                $arr[] = $val;
            }
        }
        return $arr;
    }

    public function handle($v) {

        if ($v['content']) {
            $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
            $v['html'] = htmlspecialchars_decode($v['content'], ENT_QUOTES);
            preg_match_all($preg, $v['html'], $allImg);
            foreach ($allImg[1] as $k => $av) {
                $new_str = "<img src='" . get_img_url($av) . "' style='width:100%; display:block;float:left;height:auto;' />";
                $v['html'] = str_replace($allImg[0][$k], $new_str, $v['html']);
            }
        }

        return $v;
    }

    public function getList($where, $page = 1, $num = 10, $cache = false) {
        $order = "sort asc,id desc";
        $data = Db::name($this->name)->where($where)->page($page, $num)->order($order)->cache($cache)->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function getData($where = array(), $num = 10) {
        $count = $this->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, $num);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow , $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array()) {
        $pre = md5(serialize($where));
        $name = "system_config_cate_1608795807_{$pre}";
        $data = cache($name);
        if (!$data) {
            $order = "sort asc,id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    public function getInfo($pk, $cache = true) {
        if (!$pk) {
            return null;
        }
        $name = "system_config_cate_info_1608795807_{$pk}";
        $data = cache($name);
        if (!$data || !$cache) {
            $mypk = $this->getPk();
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
    public function getLan($field = '') {
        $lans = array('status' => array('1' => '是', '0' => '否',),);
        if ($field == '') {
            return $lans;
        }
        return $lans[$field];
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
        $name = "system_config_cate_1608795807";
        cache($name, null);
        if ($pk) {
            $name = "system_config_cate_info_1608795807_{$pk}";
            cache($name, null);
        }
    }

    public function setVal($id, $key, $val) {
        $pk = $this->getPk();
        if ($pk) {
            return Db::name($this->name)->where(array($pk => $id))->save([$key=>$val]);
        }
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'id' => '变化',
            'name' => '中文名称',
            'pid' => '上级',
            'sort' => '排序',
            'status' => '状态',
        );
    }


    /**
     * 规则
     * @return type
     */
    public function rules() {
        return [
            'rule'=>[
                'name|中文名称' =>  'require|length:0,25',

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
            'id' => '',
            'pid' => '0',
            'sort' => '10',
            'status' => '1',
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