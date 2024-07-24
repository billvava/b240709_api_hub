<?php
declare (strict_types = 1);

namespace app\mall\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class MallCouponTpl extends Model {


    protected $name='mall_coupon_tpl';

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
        $order = "id desc";
        $data = Db::name($this->name)->where($where)->page(($page ?: 1),$num)->order($order)->select()->toArray();
        foreach ($data as &$v) {

        }
        return $data;
    }

     public  function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public  function getAll($where = array()){
       $pre =  md5(json_encode($where));
       $name = "mall_coupon_tpl_1616146797_{$pre}";
       $data = cache($name);
       if(!$data || 1){
           $order = "id desc";
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
        $name = "mall_coupon_tpl_info_1616146797_{$pk}";
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
        $name = "mall_coupon_tpl_1616146797";
        cache($name,null);
        if ($pk) {
            $name = "mall_coupon_tpl_info_1616146797_{$pk}";
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
'name'=>'1',
'base_money'=>'1',
'money'=>'1',
'time'=>'1',
'range'=>'1',
'goods_id'=>'1',
'category_id'=>'1',
'type'=>'1',
'end_type'=>'1',
'end'=>'1',
'day'=>'1',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'系统编号',
'name'=>'名称',
'base_money'=>'起用金额',
'money'=>'面值',
'time'=>'创建时间',
'range'=>'1=全场,2=商品,3=分类',
'goods_id'=>'限定商品',
'category_id'=>'限定栏目',
'type'=>'1=模板,2=注册赠送,3=购物,4=全场',
'end_type'=>'1=固定日期,2=固定天数',
'end'=>'截止时间',
'day'=>'固定天数',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'name|名称'=>["max"=>55,],
'base_money|起用金额'=>["float",],
'money|面值'=>["float",],
'time|创建时间'=>[],
'range|1=全场,2=商品,3=分类'=>["integer",],
'goods_id|限定商品'=>["max"=>2500,],
'category_id|限定栏目'=>["max"=>2500,],
'type|1=模板,2=注册赠送,3=购物,4=全场'=>["integer",],
'end_type|1=固定日期,2=固定天数'=>["integer",],
'end|截止时间'=>[],
'day|固定天数'=>["number",],

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
'base_money'=>'',
'money'=>'',
'time'=>'',
'range'=>'1',
'type'=>'1',
'end_type'=>'1',
'end'=>'',
'day'=>'',
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
