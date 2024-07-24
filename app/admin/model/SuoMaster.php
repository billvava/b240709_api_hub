<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete; 
/**
 * @mixin think\Model
 */
class SuoMaster extends Model {

    use SoftDelete;
    protected $name='suo_master';

    public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "id";
    }
    //更新接单数量
    public function updateJdnum($id){
//        0 1 9
        $star =   Db::name('suo_order')->where([
            ['master_id','=',$id],
             ['status','not in',[0,1,9]],

            ])->count() + 0;
        $this->where(['id'=>$id])->save([
            'jiedan_num'=>$star
        ]);

      $shop_id =   Db::name('suo_master')->where([
            ['id','=',$id],

        ])->value('shop_id');
      if($shop_id && $shop_id !=$id){
          $star =   Db::name('suo_order')->where([
                  ['shop_id','=',$shop_id],
                  ['status','not in',[0,1,9]],

              ])->count() + 0;
          $this->where(['id'=>$shop_id])->save([
              'jiedan_num'=>$star
          ]);
      }

    }
    //更新评分
    public function updateStar($id){
      $star =   Db::name('suo_order_comment')->where(['master_id'=>$id])->avg('star') + 0;
        if($star <= 0){
            $star = 5;
        }
        $this->where(['id'=>$id])->save([
            'star'=>$star
        ]);
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
            ->when(isset($map['type']) && $map['type']!='', function ($query) use ($map,$page_num) {
                $query->where('type',$map['type']);
            })
            ->when(isset($map['is_auth']) && $map['is_auth']!='', function ($query) use ($map,$page_num) {
                $query->where('is_auth',$map['is_auth']);
            })

            ->when(isset($map['level']) && $map['level']!='', function ($query) use ($map,$page_num) {
                $query->where('level',$map['level']);
            })

            ->when(isset($map['is_work']) && $map['is_work']!='', function ($query) use ($map,$page_num) {
                $query->where('is_work',$map['is_work']);
            })
            ->when(isset($map['tel']) && $map['tel']!='', function ($query) use ($map,$page_num) {
                $query->where('tel','like',"%{$map['tel']}%");
            })
            ->when(isset($map['status']) && $map['status']!='', function ($query) use ($map,$page_num) {
                $query->where('status',$map['status']);
            })
            ->when(isset($map['key']) && $map['key']!='', function ($query) use ($map,$page_num) {
                $query->where('key','like',"%{$map['key']}%");
            })

            ->when(isset($map['lat']) && $map['lat']!='', function ($query) use ($map) {
                $query->field(['*', "ROUND(6378.138*2*ASIN(SQRT(POW(SIN(({$map['lat']}*PI()/180-lat*PI()/180)/2),2)+COS({$map['lat']}*PI()/180)*COS(lat*PI()/180)*POW(SIN(({$map['lng']}*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance"])
                    ;
            });


        if(isset($map['page']) && $map['page']==1){
            $res['count'] = $list->count();
            $res['page_num'] = $page_num;
        }
        $order = 'id desc';
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
        if(isset($map['lat']) && $map['lat']!=''){
            $order = "distance asc";
        }
        $obj = $list->order($order)->select();
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
        $v['headimgurl']= get_img_url($v['headimgurl']);
        if (!$v['headimgurl']) {
            $v['headimgurl']=lang('empty_header');
        }
        if($v['tel']){
          $v['tel_text'] = substr_replace($v['tel'],'****',3,4);
        }
        //自动生成语言包
        $lans  = $this->getLan();
        foreach($lans as $type=>$arr){
            $v["{$type}_str"] = $arr[$v[$type]];
        }
        if(isset($v['distance']) && $v['distance']){
            if($v['distance'] > 1000){
                $v['distance_str']  =  (round($v['distance']/1000,1) ).'km';
            }else{
                $v['distance_str']  = "{$v['distance']}m";
            }
        }
        $v['shop_address'] = $v['address'];
//        $v['shop_name'] = '';

        $v['shop_business_hours']  = '';
        $v['shop_jiedan_num']  = 0;

        // 1=加盟,2=门店，3=个人
        $v['yy_type_arr'] = [
            1=>'加盟',
            2=>'门店',
            3=>'个人',
        ];
        $v['yy_type'] = 3;

        if($v['shop_id'] && $v['level'] == 1 && $v['shop_id']!=$v['id']){
            $info = (new SuoMaster())->getInfo($v['shop_id']);
            if($info){
                $v['yy_type'] = 1;
                $v['shop_address'] = $info['address'];
                $v['shop_name'] = $info['shop_name']? $info['shop_name']:'门店';
                $v['shop_class'] ='type-1';
                $v['shop_business_hours'] = $info['business_hours'];
                $v['shop_shop_imgs_arr'] = $info['shop_imgs_arr'];
                $v['shop_yyzz_arr'] = $info['yyzz_arr'];
                $v['shop_jiedan_num']  = $info['jiedan_num'];
            }
        }else if($v['level'] == 2){
            $v['shop_class'] ='type-1';
            $v['yy_type'] = 2;

        }else{
            $v['shop_name'] = '个人';
            $v['shop_class'] ='type-2';
        }
        $v['yy_type_str'] =   $v['yy_type_arr'][ $v['yy_type']];
        $v['shop_imgs_arr'] = [];
        if($v['shop_imgs'] && is_string($v['shop_imgs'])){

            $v['shop_imgs_arr'] =  json_decode($v['shop_imgs'],true);
            if( $v['shop_imgs_arr']){
                $v['shop_imgs_arr'] = array_map('get_img_url',$v['shop_imgs_arr']);
            }
        }
        $v['yyzz_arr'] = [];
        if($v['yyzz']){
            $v['yyzz_arr'] =   json_decode($v['yyzz'],true);
            if(  $v['yyzz_arr']){
                $v['yyzz_arr'] = array_map('get_img_url',$v['yyzz_arr']);
            }
        }

        foreach($v as $k=>$vv){
            if($v[$k] === null){
                $v[$k] = '';
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
       $name = "suo_master_1676534620_{$pre}";
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
        $name = "suo_master_info_1676534620_{$pk}";
        $data = cache($name);
        if (!$cache) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
           
            $data['master_shop_name'] = Db::name($this->name)-> where('id',$data['shop_id']) -> value('shop_name');

            unset($data['pwd']);
            if($data){
                $data= $this->handle($data);
            }
            cache($name, $data);
        }else{
            if(!$data){

                $mypk = $this->get_pk();
                if (!$mypk) {
                    return null;
                }
                $data = Db::name($this->name)->find($pk);

                $data['master_shop_name'] = Db::name($this->name)-> where('id',$data['shop_id']) -> value('shop_name');

                unset($data['pwd']);
                if($data){
                    $data= $this->handle($data);
                }
                cache($name, $data);
            }
        }
        return $data;
    }

    public function getShopList(){
        $list = [];
        $map = [];
        $map[] = ['status','=',1];
        $map[] = ['level','=',2];
        $list = $this -> where($map) -> column('shop_name','id');
        return $list;
    }

    //获取字典
    public function getLan($field=''){
        $lans = array('status' => array('1'=>'正常','0'=>'禁用',),'is_auth' => array('0'=>'未实名','1'=>'已实名',),'is_work' => array('0'=>'休息','1'=>'工作',),'type' => array('1'=>'师傅','2'=>'门店',),
            'level' => array('1'=>'锁匠','2'=>'管理员',),
            'sex' => array('1'=>'男','2'=>'女',),

        );
        if($field==''){
            return $lans;
        }
        return $lans[$field];
    }

    public  function getOption($name = 'realname',$w=[]) {
        $as = $this->getAll($w);
        $this->open_name=$name;
        $names = array_reduce($as, function($v,$w){ $v[$w[ $this->get_pk()]]=$w[$this->open_name ];return $v; });
        return $names;
    }


    public  function clear($pk = ''){
        $name = "suo_master_1676534620";
        cache($name,null);
        if ($pk) {
            $name = "suo_master_info_1676534620_{$pk}";
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
'realname'=>'',
'address'=>'',
'status'=>'',
'tel'=>'',
'linkman'=>'',
'remark'=>'',

        ];
    }


    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['id'=>'编号',
            'realname'=>'姓名',
            'headimgurl'=>'头像',

            'type'=>'类型',
            'shop_name'=>'门店',
            'sex'=>'性别',
            'is_auth'=>'是否实名',
            'is_work'=>'是否工作',
            'shop_id'=>'shop_id',

//'lat'=>'纬度',
//'lng'=>'经度',
            'address'=>'地址',
            'status'=>'状态',
//'sort'=>'排序',
            'tel'=>'电话',
            'linkman'=>'联系人',
            'remark'=>'擅长',
//'pwd'=>'密码',
        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels2() {
        return ['id'=>'编号',
'realname'=>'姓名',
'headimgurl'=>'头像',


'shop_name'=>'店名',

'is_auth'=>'是否实名',


//'lat'=>'纬度',
//'lng'=>'经度',
'address'=>'地址',
'status'=>'状态',
//'sort'=>'排序',
'tel'=>'电话',

//'pwd'=>'密码',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'realname|姓名'=>["max"=>255,],
'headimgurl|头像'=>["max"=>255,],
'lat|纬度'=>["max"=>55,],
'lng|经度'=>["max"=>55,],
'address|地址'=>["max"=>255,],
'status|状态'=>["integer",],
'sort|排序'=>["integer",],
'tel|电话'=>["max"=>55,],
'linkman|联系人'=>["max"=>55,],
'remark|擅长'=>["max"=>55,],
'shop_id|门店'=>["integer",],
'pwd|密码'=>["max"=>255,],

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
'realname'=>'',
'headimgurl'=>'',
'lat'=>'',
'lng'=>'',
'address'=>'',
'status'=>'1',
'sort'=>'10',
'tel'=>'',
'linkman'=>'',
'remark'=>'',
'shop_id'=>'',
'pwd'=>'',
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
