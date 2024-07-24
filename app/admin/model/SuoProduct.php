<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SuoProduct extends Model {


    protected $name='suo_product';

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

        $v['thumb_url'] = get_img_url($v['thumb']);

        return $v;
    }
    //最新的获取项目列表的方法
    public function getMapList($map = []){
        $res['count'] = 0;
        $page_num=  C('page_num');
        $page_num  = intval(isset($map['page_num'])  ? ($map['page_num']?:$page_num) : $page_num);
        $list = $this->alias('a')
            ->when(isset($map['id']) && $map['id'], function ($query) use ($map) {
                $query->where('id',$map['id']);
            })
            ->when(isset($map['page']) && $map['page'], function ($query) use ($map,$page_num) {
                $query->page(intval($map['page']),$page_num);
            })
            ->when(isset($map['cate_id']) && $map['cate_id'], function ($query) use ($map,$page_num) {
                $query->where('cate_id',$map['cate_id']);
            })
            ->when(isset($map['name']) && $map['name'], function ($query) use ($map,$page_num) {
                $query->where('name','like',"%{$map['name']}%");
            })
            ->when(isset($map['status']) && $map['status']!='', function ($query) use ($map,$page_num) {
                $query->where('status',$map['status']);
            })
            ;
        if(isset($map['page']) && $map['page']==1){
            $res['count'] = $list->count();
            $res['page_num'] = $page_num;

        }
        $obj = $list->order("sort asc,id desc")
            ->select();
        if ($obj) {
            $list = $obj->toArray();
            if ($list) {
                foreach ($list as &$v) {
                    $v = $this->handle($v);

                    if(isset($map['type']) &&  $map['type']){
                        $v['price'] = $v['price'. $map['type']];
                    }
                }
            }
        } else {
            $list = [];
        }
        $res['list'] = $list;
        return $res;
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
       $name = "suo_product_1675933249_{$pre}";
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
        $name = "suo_product_info_1675933249_{$pk}";
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
        $lans = array('status' => array('1'=>'正常','0'=>'下架',),);
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
        $name = "suo_product_1675933249";
        cache($name,null);
        if ($pk) {
            $name = "suo_product_info_1675933249_{$pk}";
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
'name'=>'',
'status'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'name'=>'名称',
//'thumb'=>'展示图',
'price1'=>'开锁价格',
'price2'=>'换锁价格',
'price3'=>'安装锁价格',
'price4'=>'维修锁价格',
'price5'=>'配钥匙价格',
'status'=>'状态',
//'sort'=>'排序',
//'cate_id'=>'分类',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'name|名称'=>["max"=>255,],
'thumb|展示图'=>["max"=>255,],
'price|价格'=>["float",],
'status|状态'=>["integer",],
'sort|排序'=>["integer",],
'cate_id|分类'=>["integer",],

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
'thumb'=>'',
'price1'=>'',
'price2'=>'',
'price3'=>'',
'price4'=>'',
'price5'=>'',
'status'=>'1',
'sort'=>'10',
'cate_id'=>'',
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
