<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SuoOrderComment extends Model {


    protected $name='suo_order_comment';

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

        if($v['images']){
            $v['images_array']=json_decode($v['images'],true);
        }
        $v['realname_str'] = nicknameEncryption($v['realname']);
        $v['tel_str'] = yc_phone($v['tel']);
        //自动生成语言包
        $lans  = $this->getLan();
        foreach($lans as $type=>$arr){
            $v["{$type}_str"] = $arr[$v[$type]];
        }

        return $v;
    }
    
    public  function getList($where, $page=1 ,$num = 10) {
        $page = $page+0;

        $order = "a.sort asc,a.id desc";
        $field = 'a.*,u.realname,u.headimgurl,u.address,u.tel';
        $data = Db::name($this->name)->where($where) -> field($field) -> alias('a') -> join('user u','a.user_id = u.id')->page($page,$num)->order($order)->select()->toArray();
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
       $name = "suo_order_comment_1678169565_{$pre}";
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
        $name = "suo_order_comment_info_1678169565_{$pk}";
        $data = cache($name);
        if (!$data || !$cache) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $field = 'a.*,u.realname,u.headimgurl,u.address,u.tel';
            $data = Db::name($this->name) -> alias('a') -> join('user u','a.user_id = u.id')-> field($field)->find($pk);
            if($data){
                 $data= $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field=''){
        $lans = array('status' => array('1'=>'是','0'=>'否',),'is_anonymous' => array('1'=>'是','0'=>'否',),'is_img' => array('1'=>'是','0'=>'否',),);
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
        $name = "suo_order_comment_1678169565";
        cache($name,null);
        if ($pk) {
            $name = "suo_order_comment_info_1678169565_{$pk}";
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
'time'=>'',
'user_id'=>'',
'user_id'=>'',
'status'=>'',
'is_anonymous'=>'',
'is_img'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'id',
'order_id'=>'订单ID',
'images'=>'图片',
'rank'=>'等级',
'content'=>'内容',
'time'=>'时间',
'user_id'=>'用户编号',
'status'=>'状态',
'star'=>'星级',
'type'=>'1=正常，2=虚拟',
'is_anonymous'=>'是否匿名',
'reply'=>'商家回复',
'sort'=>'排序',
'is_img'=>'是否带图',
'master_id'=>'师傅',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'order_id|订单ID'=>["require","integer",],
'images|图片'=>[],
'rank|等级'=>["integer",],
'content|内容'=>["require","max"=>255,],
'time|时间'=>["require",],
'user_id|用户编号'=>["require","integer",],
'status|状态'=>["require","integer",],
'star|星级'=>["integer",],
'type|1=正常，2=虚拟'=>["integer",],
'is_anonymous|是否匿名'=>["integer",],
'reply|商家回复'=>["max"=>255,],
'sort|排序'=>["integer",],
'is_img|是否带图'=>["integer",],
'master_id|师傅'=>["integer",],

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
'order_id'=>'',
'images'=>'',
'rank'=>'1',
'time'=>'',
'user_id'=>'',
'status'=>'1',
'star'=>'5',
'type'=>'1',
'is_anonymous'=>'1',
'sort'=>'99',
'is_img'=>'0',
'master_id'=>'',
];
    }
     /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr(){
        return ['images',];
    }
    /**
     * 要转成日期的字段
     * @return type
     */
    public function dateAttr(){
        return ['time',];
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
