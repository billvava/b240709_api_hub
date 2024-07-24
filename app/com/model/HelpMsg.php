<?php
declare (strict_types = 1);

namespace app\com\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class HelpMsg extends Model {


    protected $name='help_msg';

        public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
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

        $v['imgs_arr'] = [];
        if($v['imgs']){
            $v['imgs_arr'] = json_decode($v['imgs'],true);

        }

        return $v;
    }
    
    public  function getList($where, $page=1 ,$num = 10) {
        $order = "status asc,id desc";
        $data = Db::name($this->name)->where($where)->page($page,$num)->order($order)->cache(true)->select()->toArray();
        foreach ($data as &$v) {
            $v= $this->handle($v);
        }
        return $data;
    }

     public  function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "status asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow , $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public  function getAll($where = array()){
       $pre =  md5(json_encode($where));
       $name = "help_msg_1626256782_{$pre}";
       $data = cache($name);
       if(!$data){
           $order = "status asc,id desc";
          $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
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

    public  function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "help_msg_info_1626256782_{$pk}";
        $data = cache($name);
        if (!$data) {
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
        $lans = array('status' => array('0'=>'待处理','1'=>'已处理',),);
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
        $name = "help_msg_1626256782";
        cache($name,null);
        if ($pk) {
            $name = "help_msg_info_1626256782_{$pk}";
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
            'id'=>'1',
'content'=>'1',
'imgs'=>'1',
'time'=>'1',
'up_time'=>'1',
'up_msg'=>'1',
'user_id'=>'1',
'status'=>'1',
'tel'=>'1',
'title'=>'1',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'content'=>'内容',
'imgs'=>'多图',
'time'=>'创建时间',
'up_time'=>'处理时间',
'up_msg'=>'处理备注',
'user_id'=>'用户',
'status'=>'状态|0=待处理,1=已处理',
'tel'=>'手机',
'title'=>'标题',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'content|内容'=>["max"=>255,],
'imgs|多图'=>[],
'time|创建时间'=>[],
'up_time|处理时间'=>[],
'up_msg|处理备注'=>["max"=>255,],
'user_id|用户'=>["integer",],
'status|状态|0=待处理,1=已处理'=>["integer",],
'tel|手机'=>["max"=>255,],
'title|标题'=>["max"=>255,],

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
'content'=>'',
'imgs'=>'',
'time'=>'',
'up_time'=>'',
'up_msg'=>'',
'user_id'=>'',
'status'=>'0',
'tel'=>'',
'title'=>'',
];
    }
     /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr(){
        return ['imgs',];
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
