<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SuoMeansContent extends Model {


    protected $name='suo_means_content';

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
        if(is_string($v['tag_id'])){
            $v['tag_id_arr'] = explode(',',$v['tag_id']);
            $op =  (new SuoMeansTag())->getOption();

            if(    $v['tag_id_arr']){
                $t = [];
                foreach( $v['tag_id_arr'] as $av){
                    $t[] = $op[$av];
                }
                $v['tag_id_arr_str'] = $t;
            }

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
       $name = "suo_means_content_1677119586_{$pre}";
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
        $name = "suo_means_content_info_1677119586_{$pk}";
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
        $lans = array('status' => array('1'=>'显示','0'=>'隐藏',),
            'cate_id' => (new SuoMeansCate())->getOption(),

            );
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
        $name = "suo_means_content_1677119586";
        cache($name,null);
        if ($pk) {
            $name = "suo_means_content_info_1677119586_{$pk}";
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
            'name'=>'',
'status'=>'',
'create_time'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'name'=>'标题',
'content'=>'内容',
'status'=>'状态',
'sort'=>'排序',
'cate_id'=>'分类',
'create_time'=>'发布时间',
'thumb'=>'缩略图',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'name|标题'=>["max"=>255,],
'content|内容'=>[],
'status|状态'=>["integer",],
'sort|排序'=>["integer",],
'cate_id|分类'=>["integer",],
'create_time|发布时间'=>[],
'thumb|缩略图'=>["max"=>255,],

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
'name'=>'',
'content'=>'',
'status'=>'1',
'sort'=>'10',
'cate_id'=>'',
'create_time'=>'',
'thumb'=>'',
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
        return ['create_time',];
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
