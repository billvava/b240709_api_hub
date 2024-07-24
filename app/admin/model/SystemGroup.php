<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SystemGroup extends Model {


    protected $name='system_group';

    public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
    }

    public function getCache($key){
        $cache = "system_group_{$key}";
        $data = cache($cache);
        if(!$data){
           $group_id =  $this->where('group_key',$key)->value('id');
           $res =  (new SystemGroupData())->getAll(['status'=>1,'group_id'=>$group_id],false);
           if($res){
               $data = [];
               foreach($res as $v){
                   $data[] = $v['value'];
               }
               cache($cache,$data);
           }
        }
        return $data;
    }

    public function getCacheOne($key){
        $data = $this->getCache($key);
        if($data){
            return $data[0];
        }
        return null;
    }

    public function clearCache($id){
        $key = $this->where('id',$id)->value('group_key');
        $cache = "system_group_{$key}";
        cache($cache,null);
    }

    public function handle($v) {

        if ($v['content']) {
            $v['content']=contentHtml($v['content']);
        }


        $v['fields'] =   json_decode(  $v['fields'],true);
        
        //自动生成语言包
        $lans  = $this->getLan();
        foreach($lans as $type=>$arr){
            $v["{$type}_str"] = $arr[$v[$type]];
        }




        return $v;
    }
    
    public  function getList($where, $page=1 ,$num = 10) {
        $page = $page+0;

        $order = "sort asc,id desc";
        $data = Db::name($this->name)->where($where)->page($page,$num)->order($order)->select()->toArray();
        foreach ($data as &$v) {
            $v= $this->handle($v);
        }
        return $data;
    }

     public  function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow , $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public  function getAll($where = array(),$cache=true){
       $pre =  md5(json_encode($where));
       $name = "system_group_1669708159_{$pre}";
       $data = cache($name);
       if(!$data  || !$cache){
           $order = "sort asc,id desc";
          $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
          foreach( $data as &$v){
             $v= $this->handle($v);
          }
          cache($name,$data);
       }
       return $data;
    }


    public  function itemAll(){
        $data=$this->getAll();
        $list=array();
        $pk = $this->get_pk();
        foreach ($data as $v){
            $list[]=array(
                'val'=>$v[$pk],'name'=>$v['name']
            ) ;
        }
        return $list;
    }

    public  function getInfo($pk,$cache=false) {
        if (!$pk) {
            return null;
        }
        $name = "system_group_info_1669708159_{$pk}";
        $data = cache($name);
        if (!$data || !$cache) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            if($data){
                 $data= $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field=''){
        $lans = array();
        if($field==''){
            return $lans;
        }
        return $lans[$field];
    }

    public  function getOption($name = 'name') {
        $as = $this->getAll();
        $this->open_name=$name;
        $names = array_reduce($as, function($v,$w){ $v[$w[ $this->get_pk()]]=$w[$this->open_name ];return $v; });
        return $names;
    }


    public  function clear($pk = ''){
        $name = "system_group_1669708159";
        cache($name,null);
        if ($pk) {
            $name = "system_group_info_1669708159_{$pk}";
            cache($name, null);
        }
    }


    public  function setVal($id,$key,$val){
        $pk = $this->get_pk();
        if($pk){
           return $this->where(array($pk=>$id))->save([$key=>$val]);
        }

    }

    public  function getVal($id,$key,$cache=true){
        $pk = $this->get_pk();
        if($pk){
            return $this->where(array($pk=>$id))->cache($cache)->value($key);
        }
    }




    /**
    * 搜索框
    * @return type
    */
    public  function searchArr() {
        return [
            'id'=>'',
'group_name'=>'',
'create_time'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'group_name'=>'数据组名称',
'group_info'=>'数据提示',
'group_key'=>'数据字段',
'fields'=>'数据组字段以及类型（json数据）',
'sort'=>'排序',
'create_time'=>'创建时间',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'group_name|数据组名称'=>["require","max"=>50,],
'group_key|数据字段'=>["require","max"=>50,],
'fields|数据组字段以及类型（json数据）'=>[],
'sort|排序'=>["number",],
'create_time|创建时间'=>[],

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
        return ['id'=>'',
'group_name'=>'',
'group_info'=>'',
'group_key'=>'',
'fields'=>'',
'sort'=>'0',
'create_time'=>'',
];
    }
     /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr(){
        return [];
    }
    /**
     * 要转成日期的字段
     * @return type
     */
    public function dateAttr(){
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
