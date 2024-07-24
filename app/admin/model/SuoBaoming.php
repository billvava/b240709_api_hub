<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SuoBaoming extends Model {


    protected $name='suo_baoming';

    public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
    }


    public function getMapList($map = [])
    {
        $res['count'] = 0;
        $page_num = C('page_num');
        $page_num = intval(isset($map['page_num']) ? ($map['page_num'] ?: $page_num) : $page_num);
        $list = $this->alias('a')
            ->when(isset($map['id']) && $map['id'], function ($query) use ($map) {
                $query->where('id', $map['id']);
            })

            ->when(isset($map['page']) && $map['page'], function ($query) use ($map, $page_num) {
                $query->page(intval($map['page']), $page_num);
            })
            ->when(isset($map['status']) && $map['status'] !== '', function ($query) use ($map, $page_num) {
                $query->where('status', $map['status']);
            })
            ->when(isset($map['type']) && $map['type'] != '', function ($query) use ($map, $page_num) {
                $query->where('type', $map['type']);
            })
            ->when(isset($map['user_id']) && $map['user_id'] != '', function ($query) use ($map, $page_num) {
                $query->where('user_id', $map['user_id']);
            })
           ;
        if (isset($map['page']) && $map['page'] == 1) {
            $res['count'] = $list->count();
            $res['page_num'] = $page_num;
        }

        if (isset($map['find']) && $map['find'] == 1) {
            $obj = $list->find();
            if ($obj) {
                $info = $obj->toArray();
                if ($info) {
                    $info = $this->handle($info);
                }

            } else {
                $info = null;
            }
            return $info;
        }
        $obj = $list->order("id desc")
            ->select();
        if ($obj) {
            $list = $obj->toArray();
            if ($list) {
                foreach ($list as &$v) {
                    $v = $this->handle($v);

                }
            }
        } else {
            $list = [];
        }
        $res['list'] = $list;
        return $res;
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
       $name = "suo_baoming_1677570165_{$pre}";
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
        $name = "suo_baoming_info_1677570165_{$pk}";
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
        $lans = array('status' => array('0'=>'待处理','1'=>'已同意','2'=>'已拒绝'),'type' => array('1'=>'锁匠培训','2'=>'锁匠入驻','3'=>'渠道商入驻','4'=>'代理商入驻',),);
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
        $name = "suo_baoming_1677570165";
        cache($name,null);
        if ($pk) {
            $name = "suo_baoming_info_1677570165_{$pk}";
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
'create_time'=>'',
'user_id'=>'',
'user_id'=>'',
'realname'=>'',
'tel'=>'',
'status'=>'',
'up_time'=>'',
'type'=>'',
'company'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'create_time'=>'创建时间',
'user_id'=>'用户',
'realname'=>'姓名',
'tel'=>'手机',
'status'=>'状态',
'up_time'=>'更新时间',
'type'=>'类型',
'weixin'=>'微信',
'ruzhu_type'=>'入驻类型',
'city'=>'所在城市',
//'province'=>'省份',
//'country'=>'区域',
'company'=>'公司',
'daili_city'=>'期望代理城市',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'create_time|创建时间'=>[],
'user_id|用户'=>["integer",],
'realname|姓名'=>["max"=>255,],
'tel|手机'=>["max"=>255,],
'status|状态'=>["integer",],
'up_time|更新时间'=>[],
'type|类型'=>["integer",],
'weixin|微信'=>["max"=>255,],
'ruzhu_type|入驻类型'=>["max"=>255,],
'city|所在城市'=>["max"=>255,],
'province|省份'=>["max"=>255,],
'country|区域'=>["max"=>255,],
'company|公司'=>["max"=>255,],
'daili_city|期望代理城市'=>["max"=>255,],

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
'create_time'=>'',
'user_id'=>'',
'realname'=>'',
'tel'=>'',
'status'=>'0',
'up_time'=>'',
'type'=>'',
'weixin'=>'',
'ruzhu_type'=>'',
'city'=>'',
'province'=>'',
'country'=>'',
'company'=>'',
'daili_city'=>'',
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
        return ['up_time',];
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
