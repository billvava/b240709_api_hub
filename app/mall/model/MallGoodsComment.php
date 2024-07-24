<?php
declare (strict_types = 1);

namespace app\mall\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class MallGoodsComment extends Model {


    protected $name='mall_goods_comment';

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
       $name = "mall_goods_comment_1616122457_{$pre}";
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
        $name = "mall_goods_comment_info_1616122457_{$pk}";
        $data = cache($name);
        if (!$data) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field=''){
        $lans = array('status' => array('1'=>'是','0'=>'否',),'is_anonymous' => array('1'=>'是','0'=>'否',),);
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
        $name = "mall_goods_comment_1616122457";
        cache($name,null);
        if ($pk) {
            $name = "mall_goods_comment_info_1616122457_{$pk}";
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
'order_id'=>'1',
'goods_id'=>'1',
'images'=>'1',
'rank'=>'1',
'content'=>'1',
'time'=>'1',
'user_id'=>'1',
'status'=>'1',
'nickname'=>'1',
'star'=>'1',
'type'=>'1',
'is_anonymous'=>'1',
'reply'=>'1',
'sort'=>'1',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'id',
'order_id'=>'订单ID',
'goods_id'=>'商品ID',
'images'=>'图片',
'rank'=>'等级',
'content'=>'内容',
'time'=>'时间',
'user_id'=>'用户编号',
'status'=>'状态',
'nickname'=>'后台录入的评论者昵称',
'star'=>'星级',
'type'=>'1=正常，2=虚拟',
'is_anonymous'=>'是否匿名',
'reply'=>'商家回复',
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
            'order_id|订单ID'=>["require","integer",],
'goods_id|商品ID'=>["require","integer",],
'images|图片'=>[],
'rank|等级'=>["integer",],
'content|内容'=>["require","max"=>255,],
'time|时间'=>["require",],
'user_id|用户编号'=>["require","integer",],
'status|状态'=>["require","integer",],
'nickname|后台录入的评论者昵称'=>["max"=>50,],
'star|星级'=>["integer",],
'type|1=正常，2=虚拟'=>["integer",],
'is_anonymous|是否匿名'=>["integer",],
'reply|商家回复'=>["max"=>255,],
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
'order_id'=>'',
'goods_id'=>'',
'images'=>'',
'rank'=>'1',
'time'=>'',
'user_id'=>'',
'status'=>'1',
'nickname'=>'',
'star'=>'5',
'type'=>'1',
'is_anonymous'=>'1',
'sort'=>'99',
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
     * 字段类型
     * @return type
     * #fieldType#
     */
    public function fieldType() {
        return [];
    }


}
