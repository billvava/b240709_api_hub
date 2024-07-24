<?php
declare (strict_types = 1);

namespace app\shopapi\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class Content extends Model {


    protected $name='mall_content';

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
        $v['content_str'] = strip_tags($v['html']);

        return $v;
    }
    
    public  function getList($where, $page=1 ,$num = 10) {
        $order = "sort asc,id desc";
        $data = Db::name($this->name)->where($where)->page(($page ?: 1),$num)->order($order)->cache(true)->select()->toArray();
        foreach ($data as &$v) {

        }
        return $data;
    }

     public  function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public  function getAll($where = array()){
       $pre =  md5(serialize($where));
       $name = "mall_content_1616143982_{$pre}";
       $data = cache($name);
       if(!$data){
           $order = "sort asc,id desc";
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
        $name = "mall_content_info_1616143982_{$pk}";
        $data = cache($name);
        if (!$data) {
            $where = array();
            if (is_numeric($pk)) {
                $where['id'] = $pk;
            } else {
                $where['key'] = $pk;
            }
            $data = $this->where($where)->find();
            if ($data) {
                $data = $this->handle($data);
            }
            
            
            
            
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field=''){
        $lans = array('status' => array('1'=>'是','0'=>'否',),);
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
        $name = "mall_content_1616143982";
        cache($name,null);
        if ($pk) {
            $name = "mall_content_info_1616143982_{$pk}";
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
            'id'=>'0',
'title'=>'1',
'type'=>'0',
'status'=>'1',
'sort'=>'1',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'title'=>'标题',
'content'=>'文字说明',
'type'=>'类型',
'status'=>'状态',
'sort'=>'排序',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'title|标题'=>["max"=>255,],
'content|文字说明'=>[],
'type|类型'=>["integer",],
'status|状态'=>["integer",],
'sort|排序'=>["integer",],

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
'title'=>'',
'content'=>'',
'type'=>'1',
'status'=>'1',
'sort'=>'9',
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
     * 字段类型
     * @return type
     * #fieldType#
     */
    public function fieldType() {
        return [];
    }


}
