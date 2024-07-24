<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SuoOrderJia extends Model {


    protected $name='suo_order_jia';
    protected $autoWriteTimestamp = 'datetime';

    public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
    }

    public function pay_success($info){
        $save = [
            'pay_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'pay_status' => 1,
            'status' => 1,
            'pay_type'=>$info['pay_type']
        ];

        if ($info['trade_no']) {
            $save['trade_no'] = $info['trade_no'];
        }
        Db::name('suo_order_jia')->where(['id' => $info['id']])->save($save);
        (new SuoOrder())->where('id',$info['order_id'])->update([
            'jia_total'		=>	Db::raw('jia_total+'.$info['total']),
            'jia_num'		=>	Db::raw('jia_num+1'),
        ]);
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
       $name = "suo_order_jia_1676455191_{$pre}";
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
        $name = "suo_order_jia_info_1676455191_{$pk}";
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
        $lans = array('status' => array('0'=>'待支付','1'=>'已支付','2'=>'已退款',),'pay_status' => array('0'=>'未支付','1'=>'已支付',),'fund_status' => array('0'=>'无','1'=>'已退款',),'pay_type' => array('0'=>'无','1'=>'微信','2'=>'余额',),);
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
        $name = "suo_order_jia_1676455191";
        cache($name,null);
        if ($pk) {
            $name = "suo_order_jia_info_1676455191_{$pk}";
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
'create_time'=>'',
'status'=>'',
'pay_status'=>'',
'ordernum'=>'',
'fund_status'=>'',
'pay_type'=>'',
'pay_time'=>'',
'update_time'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
'user_id'=>'用户',
'create_time'=>'创建时间',
//'status'=>'状态',
'pay_status'=>'支付状态',
'ordernum'=>'订单号',
'fund_status'=>'退款状态',
'fund_money'=>'退款金额',
//'trade_no'=>'第三方交易号',
'pay_type'=>'支付类型',
'pay_time'=>'支付时间',
'total'=>'订单金额',
//'update_time'=>'更新时间',
'order_id'=>'订单ID',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'user_id|用户'=>["integer",],
'create_time|创建时间'=>[],
'status|状态'=>["integer",],
'pay_status|支付状态'=>["integer",],
'ordernum|订单号'=>["max"=>55,],
'fund_status|退款状态'=>["integer",],
'fund_money|退款金额'=>["float",],
'trade_no|第三方交易号'=>["max"=>55,],
'pay_type|支付类型'=>["integer",],
'pay_time|支付时间'=>[],
'total|订单金额'=>["float",],
'update_time|更新时间'=>[],
'order_id|订单ID'=>["integer",],

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
'create_time'=>'',
'status'=>'0',
'pay_status'=>'0',
'ordernum'=>'',
'fund_status'=>'',
'fund_money'=>'0.00',
'trade_no'=>'',
'pay_type'=>'0',
'pay_time'=>'',
'total'=>'0.00',
'update_time'=>'',
'order_id'=>'',
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
        return ['pay_time','update_time',];
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
