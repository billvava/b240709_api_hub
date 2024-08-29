<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class Tihuo extends Model {


    protected $name='tihuo';

    public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
    }



    public function handle($v) {

        if ($v['content']) {
            $v['content']=contentHtml($v['content']);
        }
        
        //自动生成语言包
        $lans  = $this->getLan();
        foreach($lans as $type=>$arr){
            $v["{$type}_str"] = $arr[$v[$type]];
        }

        return $v;
    }
    
    public  function getList($where, $page=1 ,$num = 10) {
        $page = $page+0;

        $order = "id desc";
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
        $order = "id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow , $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public  function getAll($where = array(),$cache=true){
       $pre =  md5(json_encode($where));
       $name = "tihuo_1724937753_{$pre}";
       $data = cache($name);
       if(!$data  || !$cache){
           $order = "id desc";
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
        $name = "tihuo_info_1724937753_{$pk}";
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
        $lans = array('status' => array('0'=>'待发货','1'=>'已发货',),);
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
        $name = "tihuo_1724937753";
        cache($name,null);
        if ($pk) {
            $name = "tihuo_info_1724937753_{$pk}";
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
'user_id'=>'',
'user_id'=>'',
'num'=>'',
'address'=>'',
'tel'=>'',
'create_time'=>'',
'status'=>'',
'confirm_time'=>'',
'real_name'=>'',
'product_name'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'id',
'user_id'=>'用户id',
'num'=>'数量',
'address'=>'收货地址',
'tel'=>'手机号',
'create_time'=>'时间',
'status'=>'状态',
'confirm_time'=>'处理时间',
'real_name'=>'姓名',
'stock'=>'剩余库存',
'product_id'=>'商品id',
'price'=>'单价',
'total_price'=>'总额',
'product_name'=>'商品',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'user_id|用户id'=>["integer",],
'num|数量'=>["integer",],
'address|收货地址'=>["max"=>255,],
'tel|手机号'=>["max"=>15,],
'create_time|时间'=>[],
'status|状态'=>["integer",],
'confirm_time|处理时间'=>[],
'real_name|姓名'=>["max"=>20,],
'stock|剩余库存'=>["integer",],
'product_id|商品id'=>["integer",],
'price|单价'=>["float",],
'total_price|总额'=>["float",],
'product_name|商品'=>["max"=>255,],

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
'user_id'=>'',
'num'=>'',
'address'=>'',
'tel'=>'',
'create_time'=>'',
'status'=>'0',
'confirm_time'=>'',
'real_name'=>'',
'stock'=>'0',
'product_id'=>'',
'price'=>'0.00',
'total_price'=>'0.00',
'product_name'=>'',
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
        return ['confirm_time',];
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
