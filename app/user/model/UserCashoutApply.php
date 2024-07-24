<?php
declare (strict_types = 1);

namespace app\user\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class UserCashoutApply extends Model {


    protected $name='user_cashout_apply';

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
       $name = "user_cashout_apply_1646038600_{$pre}";
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

    public  function getInfo($pk,$cache=true) {
        if (!$pk) {
            return null;
        }
        $name = "user_cashout_apply_info_1646038600_{$pk}";
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
        $channel_model = new UserCashoutChannel();
       $cs = $channel_model->getLan('cate');
        $lans = array('status' => array('0'=>'未处理','1'=>'已发放现金','2'=>'已退回','3'=>'待核实',),
            'channel_cate'=>$cs
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
        $name = "user_cashout_apply_1646038600";
        cache($name,null);
        if ($pk) {
            $name = "user_cashout_apply_info_1646038600_{$pk}";
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
'time'=>'',
'update_time'=>'',
'status'=>'',
'order_num'=>'',
'name'=>'',
'address'=>'',
'num'=>'',
'realname'=>'',
'tel'=>'',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'id',
'user_id'=>'用户ID',
'money'=>'申请金额',
'time'=>'申请时间',
'update_time'=>'处理时间',
'status'=>'状态',
'order_num'=>'流水号',
'partner_trade_no'=>'微信退款号',
'real_total'=>'到账金额',
'plus_total'=>'手续费',
'cate'=>'金额分类',
'name'=>'银行名称',
'address'=>'开户点',
'num'=>'账号',
'realname'=>'姓名',
'tel'=>'手机',
'channel_cate'=>'渠道分类',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'user_id|用户ID'=>["require","integer",],
'money|申请金额'=>["require","float",],
'time|申请时间'=>[],
'update_time|处理时间'=>[],
'status|状态'=>["require","integer",],
'order_num|流水号'=>["require","max"=>20,],
'partner_trade_no|微信退款号'=>["max"=>255,],
'real_total|到账金额'=>["float",],
'plus_total|手续费'=>["float",],
'cate|金额分类'=>["max"=>15,],
'name|银行名称'=>["max"=>255,],
'address|开户点'=>["max"=>255,],
'num|账号'=>["max"=>255,],
'realname|姓名'=>["max"=>255,],
'tel|手机'=>["max"=>255,],
'channel_cate|渠道分类'=>["max"=>15,],

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
'money'=>'0.00',
'time'=>'',
'update_time'=>'',
'status'=>'0',
'partner_trade_no'=>'',
'real_total'=>'',
'plus_total'=>'',
'cate'=>'',
'name'=>'',
'address'=>'',
'num'=>'',
'realname'=>'',
'tel'=>'',
'channel_cate'=>'',
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
        return ['time','update_time',];
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
