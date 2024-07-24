<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SystemConfig extends Model
{

    protected $name='system_config';

    public function dbName()
    {
       return $this->name;
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

    public function getList($where, $page=1 ,$num = 10,$cache=false) {
        $order = "sort asc,id desc";
        $data = Db::name($this->name)->where($where)->page($page,$num)->order($order)->cache($cache)->select()->toArray();
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

    public function getAll($where = array()){
        $pre =  md5(serialize($where));
        $name = "system_config_1609145446_{$pre}";
        $data = cache($name);
        if(!$data){
            $order = "sort asc,id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            cache($name,$data);
        }
        return $data;
    }

    public function getInfo($pk,$cache=true) {
        if (!$pk) {
            return null;
        }
        $name = "system_config_info_1609145446_{$pk}";
        $data = cache($name);
        if (!$data || !$cache) {
            $mypk = $this->getPk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            if($data){
                $data = $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }

    //获取字典
    public function getLan($field=''){
        $lans = array('is_del' => array('1'=>'可以删除','0'=>'不可以',),'is_show' => array('1'=>'是','0'=>'否',),);
        if($field==''){
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


    public function clear($pk = ''){
        $name = "system_config_1609145446";
        cache($name,null);
        if ($pk) {
            $name = "system_config_info_1609145446_{$pk}";
            cache($name, null);
        }
    }


    public function setVal($id,$key,$val){
        $pk = $this->getPk();
        if($pk){
            return Db::name($this->name)->where(array($pk=>$id))->save([$key=>$val]);
        }

    }


    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'id'=>'编号',
            'field'=>'字段名',
            'name'=>'中文名称',
            'msg'=>'提示',
            'val'=>'值',
            'type'=>'类型',
            'is_del'=>'可删除|1=可以删除,0=不可以',
            'input_type'=>'input_type',
            'type_group'=>'type_group',
            'option'=>'option',
            'option_text'=>'option_text',
            'is_show'=>'显示状态',
            'unit'=>'单位',
            'cate_id'=>'分组ID',
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
                'field|字段名' =>  'require|length:0,25',
                'name|中文名称' =>  'require|length:0,25',
                'input_type|类型' =>  'require|length:0,25',
//                'type_group|type_group' =>  'require|integer',
                'unit|单位' =>  'max:25',
                'cate_id|分组ID' =>  'require|integer',
                'sort|排序' =>  'require|integer',
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
            'is_del'=>'1',
            'input_type'=>'',
            'type_group'=>'',
            'option'=>'',
            'option_text'=>'',
            'is_show'=>'1',
            'cate_id'=>'0',
            'sort'=>'10',

        );
    }
    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr(){
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
        if ($str==='') {
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
        if ($str==='') {
            return false;
        }
        if(!is_numeric($str)){
            return false;
        }
        if($str<0){
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
        if (!$str && $str!=='0') {
            return false;
        }
        if(!is_numeric($str)){
            return false;
        }
        if($str<0){
            return false;
        }
        if(is_int($str)){
            return true;
        }
    }
    /**
     * 检测是否为整数
     * @param type $str
     * @return boolean
     */
    function checkIsInt($str) {
        if (!$str && $str!=='0') {
            return false;
        }
        if(!is_numeric($str)){
            return false;
        }
        if(is_int($str)){
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