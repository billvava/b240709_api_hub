<?php

namespace app\masterapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoCate;
use app\admin\model\SuoOrder;
use app\admin\model\SuoOrderJia;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use app\admin\model\SuoMeansContent;
use app\admin\model\SuoActivities;
use app\common\model\SuoMaster;
use app\admin\model\SuoMaster as SuoMasterModel;
use app\admin\model\SuoQuickReply;
use app\admin\model\SuoOrderComment;

use app\com\model\HelpMsg;
use think\App;
use think\facade\Db;
use app\admin\model\SuoProfit;

class User
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {   

      $this->model = new SuoOrder();
      $this->SuoMaster = new SuoMaster();
      $this->SuoMasterModel = new SuoMasterModel();
      $this->SuoMeansContent = new SuoMeansContent();
      $this->SuoActivities = new SuoActivities();
      $this->SuoQuickReply = new SuoQuickReply();
      $this->SuoProfit = new SuoProfit();
      $this->SuoOrderComment = new SuoOrderComment();
      $this->SystemMessage = Db::name('system_message');
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }
    public function getMasterInfo(){
        $id = $this->in['id'] ? $this->in['id']:$this->uinfo['id'];
        $info = $this->SuoMaster->getUserInfo($id,false);
        if($info){
//            p($info);
            if($info['id'] != $this->uinfo['id'] && $info['shop_id'] !=  $this->uinfo['id']){
                return ['status'=>0,'info'=>'无权修改'];
            }
        }
       return ['status' => 1, 'data' => $info];
    }
    //获取用户信息
    public function getUserInfo(){
      $data = [];
      $uid = $this->uinfo['id'];
      $data = $this->SuoMaster->getUserInfo($uid,false);
      $data['level_text'] = $data['level'] == 1?'锁匠':'管理员';
      
      $field = $data['level'] == 1?'master_id':'shop_id';

      //今日收入
        $map = [];
        $map[] = ['type','=',$data['level']];
        $map[] = [$field,'=',$uid];

      $sum_day_money = $this->SuoProfit -> where($map) -> whereDay('create_time') -> sum('money');

      $data['sum_day_money'] = $sum_day_money;
      
      $nav_array1 = [
        

        [
          'url'=>'/pages/real-name/real-name',
          'img'=>'user-center/icon-service-5.png',
          'name' => '实名认证'
        ],

        [
          'url'=>'/pages/comments/comments',
          'img'=>'user-center/icon-service-7.png',
          'name' => '服务评价'
        ],
        [
          'url'=>'/pages/address/list',
          'img'=>'user-center/icon-service-6.png',
          'name' => '地址管理'
        ],

       
        [
          'url'=>'/pages/user/coupon/coupon',
          'img'=>'user-center/icon-service-4.png',
          'name' => '优惠券'
        ],
        [
          'url'=>'/pages/user/cashout_channel/index',
          'img'=>'user-center/icon-service-4.png',
          'name' => '银行卡管理'
        ],

        [
          'url'=>'/pages/user/atn/atn',
          'img'=>'user-center/icon-service-1.png',
          'name' => '我的收藏'
        ],
      ];

      $nav_array2 = [
        [
          'url'=>'/pages/store-info/store-info',
          'img'=>'user-center/icon-service-1.png',
          'name' => '门店资料'
        ],

        [
          'url'=>'/pages/locksmith-manage/list',
          'img'=>'user-center/icon-service-2.png',
          'name' => '锁匠管理'
        ],

        [
          'url'=>'/pages/profit/locksmith',
          'img'=>'user-center/icon-service-3.png',
          'name' => '锁匠贡献收益'
        ],

        [
          'url'=>'/pages/user/coupon/coupon',
          'img'=>'user-center/icon-service-4.png',
          'name' => '优惠券'
        ],

        [
          'url'=>'/pages/real-name/real-name',
          'img'=>'user-center/icon-service-5.png',
          'name' => '实名认证'
        ],

        [
          'url'=>'/pages/address/list',
          'img'=>'user-center/icon-service-6.png',
          'name' => '地址管理'
        ],

        [
          'url'=>'/pages/comments/comments',
          'img'=>'user-center/icon-service-7.png',
          'name' => '服务评价'
        ],
        [
          'url'=>'/pages/user/cashout_channel/index',
          'img'=>'user-center/icon-service-4.png',
          'name' => '银行卡管理'
        ],
          [
              'url'=>'/pages/user/atn/atn',
              'img'=>'user-center/icon-service-1.png',
              'name' => '我的收藏'
          ],

      ];

      $data['nav_list'] = $data['level'] == 1? $nav_array1: $nav_array2;



        $is = Db::name('user_msg')->where([
            ['master_id','=',$this->uinfo['id']],
            ['is_read','=',0],

        ])->find();
        $data['msg_has'] = $is?1:0;
        $data['companytel'] =C('companytel');

        return ['status' => 1, 'data' => $data];
    }

    //获取用户收益数据
    public function getUserProfitData(){
      $user_id = $this->uinfo['id'];
      $master_id = $this->in['master_id'];
      $time = '';

      $pege = 1;
      $is_devote = 1;
      //is_devote   1-工单收益（接单产生的收益），2-门店收益， 3-锁匠服务贡献收益(门店下的师傅产生的收益)
      if($master_id){
        $uid = $master_id;
        $is_devote = 3;
      }else{
        $uid = $user_id;
      }
      $user = $this->SuoMaster->getUserInfo($uid,false);
      $user['level_text'] = $user['level'] == 1?'锁匠':'管理员';

      $profit_data = $this->model -> getProfitStatistics($uid,$user['level'],$is_devote);
      $profit_list = $this->model -> getProfitDetail($uid,$user['level'],$time,$page,$is_devote);//收益列表
      $data = [];
      $data['user'] = $user;
      $data['profit_data'] = $profit_data;
      $data['profit_list'] = $profit_list;
      return ['status' => 1, 'data' => $data];
    }

    //获取用户收益明细记录
    public function getUserProfitDetail(){
      $user_id = $this->uinfo['id'];
      $level = $this->uinfo['level'];

      $pege = $this->in['pege'];
      $time = $this->in['time'];
      $master_id = $this->in['master_id'];

      $is_devote = 1;
      if($master_id){
        $uid = $master_id;
        $user = $this->SuoMaster->getUserInfo($uid,false);
        $level =$user['level'];
        $is_devote = 3;
      }else{
        $uid = $user_id;
      }
      $list = $this->model -> getProfitDetail($uid,$level,$time,$pege,$is_devote);//收益列表
      $data = [];
      $data['list'] = $list;
      return ['status' => 1, 'data' => $data];
    }


    //获取锁匠用户列表
    public function getMasterUserList(){
      $uid = $this->uinfo['id'];
      $level = $this->uinfo['level'];
      $map = [];
      $map[] = ['shop_id','=',$uid];
      $field = 'id,realname';
      $list = $this->SuoMaster->where($map) -> field($field) ->select();
      $data = [];
      $data['level'] = $level;
      if($list){
          $list = $list->toArray();
      }
      $data['list'] = $list;
        $data['list2'] = $list;

        $data['level'] = $level;

      array_unshift($data['list'],['realname'=>'全部锁匠','id'=>'']);
      return ['status' => 1, 'data' => $data];
    }

   //获取锁匠贡献收益统计列表
    public function getMasterProfitList(){
      $uid = $this->uinfo['id'];
      $level = $this->uinfo['level'];
      $pege = $this->in['pege'];
      $is_devote=3;
      $list = $this->model -> getProfitList($uid,$level,$is_devote);//收益列表
      $data = [];
      $data['list'] = $list;
      return ['status' => 1, 'data' => $data];
    }

    //更新工作状态
    public function updateWorkStastus(){
      $is_work = $this ->in['is_work'];
      $uid = $this->uinfo['id'];
      $update_data = [];
      $update_data['is_work'] = $is_work;
      $this->SuoMaster->updateUserData($uid,$update_data);
      return ['status' => 1, 'info' => '操作成功'];
    }

    //更新用户资料
    public function updateUserData(){
      $data = $this ->in;
      if(isset($data['pwd']) && $data['pwd']){
        $data['pwd'] = xf_md5($data['pwd']);
      }
      if(isset($data['uid']) && $data['uid']){
        $uid = $data['uid'];
      }else{
        $uid = $this->uinfo['id'];
      }
      
      $this->SuoMaster->updateUserData($uid,$data);
//      die;
      return ['status' => 1, 'info' => '操作成功'];
    }

  

    //数据统计
    public function getStatistics(){
      $uid = $this->uinfo['id'];
      $level = $this->uinfo['level'];
      $post_data = $this->in;
      $post_data['uid'] = $uid;
      $post_data['user_type'] = $level;
      $data = $this->model ->statistics($post_data); 
      return ['status' => 1, 'data' => $data];
    }

    //分类列表
    public function getCateList(){
      $list = Db::name('suo_means_cate') -> select()->toArray();
      $arr = [];
      $arr[0]['id'] = 0;
      $arr[0]['name'] = '资料分类';

      array_walk($arr,function($item) use (&$list) {
        array_unshift($list, $item);
      });
      $data = [];
      $data['list'] = $list;
      return ['status' => 1, 'data' =>$data];
    }

    //获取文章列表
    public function getArticleList(){
      $page = input('page',1);
      $keywords = input('keywords','');
      $cate_id = input('cate_id','');
      $sort = input('sort',1);

      // $cate_id = Db::name('suo_means_cate') -> where('type',$type) -> value('id');
      $map = [];
      $map[] = ['status','=',1];
      if($cate_id){
        $map[] = ['cate_id','=',$cate_id];
      }
      
      if($keywords !==''){
        $map[] = ['name|content','like','%'.$keywords.'%'];
      }
      $order = 'id desc';
      if($sort==2){
        $order = 'visits desc';
      }
      $field = '*';
      $page_size = 10;
//        $this->SuoMeansContent ->
      $list = $this->SuoMeansContent -> where($map) ->orderRaw($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
      foreach ($list as $key => $value) {
        $list[$key]['thumb_text'] = get_img_url($value['thumb']);
          $list[$key] =  $this->SuoMeansContent ->handle($value);
      }
      $data = [];
      $data['list'] = $list;
      return ['status' => 1, 'data' =>$data];
    }


     //详情
    public function getArticleDetail(){
      $id = input('id');
      $ip = get_client_ip();
      
      if(!cache('ip_'.$id)){
        $this->SuoMeansContent -> where('id',$id)  -> inc('visits') -> update();
      }
      cache('ip_'.$id,$ip);
      $form = $this->SuoMeansContent -> where('id',$id) -> find();
    
      $form['content'] = contentHtml($form['content']);
      $form['thumb'] = get_img_url($form['thumb']);
    
      $data['data'] = $form;
      return array('status' => 1, 'data' => $data);
    }


    //获取活动列表
    public function getActivitiesList(){
      $field = '*';
      $order = 'id desc';
      $page = input('page',1);
      $keywords = input('keywords','');
      $page_size = 10;
      $map = [];
      $map[] = ['status','=',1];
     
      if($keywords !==''){
        $map[] = ['title|content','like','%'.$keywords.'%'];
      }
      $list = $this->SuoActivities -> where($map) ->orderRaw($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
      foreach ($list as $key => $value) {
        $list[$key]['img_text'] = get_img_url($value['img']);

        if($value['start_time'] > date('Y-m-d H:i:s')){
          $list[$key]['state'] = 1;
          $list[$key]['state_text'] = '即将开始';
          $list[$key]['state_btn'] = '立即参与';
        }elseif($value['start_time'] <= date('Y-m-d H:i:s') && $value['end_time'] >= date('Y-m-d H:i:s')){
          $list[$key]['state'] = 2;
          $list[$key]['state_text'] = '进行中';
          $list[$key]['state_btn'] = '立即参与';
        }elseif($value['end_time'] < date('Y-m-d H:i:s')){
          $list[$key]['state'] = 3;
          $list[$key]['state_text'] = '已结束';
          $list[$key]['state_btn'] = '立即参与';
        }
      }

      $data = [];
      $data['list'] = $list;
      return ['status' => 1, 'data' =>$data];
    }

     //详情
    public function getActivitiesDetail(){
      $id = input('id');
      $form = $this->SuoActivities -> where('id',$id) -> find();
      $form['content'] = contentHtml($form['content']);
      $form['img_text'] = get_img_url($form['img']);
      if($form['start_time'] > date('Y-m-d H:i:s')){
          $form['state'] = 1;
          $form['state_text'] = '即将开始';
          
        }elseif($form['start_time'] <= date('Y-m-d H:i:s') && $form['end_time'] >= date('Y-m-d H:i:s')){
          $form['state'] = 2;
          $form['state_text'] = '进行中';
         
        }elseif($form['end_time'] < date('Y-m-d H:i:s')){
          $form['state'] = 3;
          $form['state_text'] = '已结束';
          
        }
      $data['data'] = $form;
      $data['tel'] = C('companytel');
      return array('status' => 1, 'data' => $data);
    }

    //参与活动
    public function userJoin(){
      $uid = $this->uinfo['id'];
      $realname = $this->uinfo['realname'];
      $tel = $this->uinfo['tel'];
      $id = input('id');//活动id
      $form = $this->SuoActivities -> where('id',$id) -> find();
      if($form['start_time'] > date('Y-m-d H:i:s')){
        return array('status' => 0, 'info' => '活动未开始');
      }

      if($form['end_time'] < date('Y-m-d H:i:s')){
        return array('status' => 0, 'info' => '活动已结束');
      }
      $map = [];
      $map[] = ['uid','=',$uid];
      $map[] = ['activity_id','=',$id];
      if(Db::name('suo_join') -> where($map) -> count()){
         return array('status' => 0, 'info' => '您已参加此活动，请勿重复提交');
      }
      $data = [];
      $data['uid'] = $uid;
      $data['tel'] = $tel;
      $data['realname'] = $realname;
      $data['activity_id'] = $id;
      $data['activity_name'] = $form['title'];
      $data['create_time'] = date('Y-m-d H:i:s');
      $this->SuoActivities -> where('id',$id) -> inc('num') -> update();
      Db::name('suo_join') -> save($data);
      return array('status' => 1, 'info' => '报名成功');
        
    } 

    //锁匠列表
    public function getUserListData(){
      $uid = $this->uinfo['id'];
      $field = '*';
      $order = 'id desc';
      $page = input('page',1);
      $page_size = 10;
      $type = $this ->in['type'];//类型|1=师傅,2=门店
      $map = [];
      if($type){
        $map[] = ['type','=',$type];
      }else{
        $map[] = ['shop_id','=',$uid];
      }
      
      $list = $this->SuoMaster -> where($map) -> orderRaw($order)->field($field)->paginate(['page' => $page, 'list_rows' => $page_size]);
      foreach ($list as $key => $value) {

        $value = $this->SuoMasterModel->handle($value);
        $list[$key] = $value;
      }

      $data = [];
      $data['list'] = $list;
      return array('status' => 1, 'data' => $data);

    }

    public function getUserDetail(){
      $uid = $this->in['uid']?$this->in['uid'] : $this->uinfo['id'];
      $form = $this->SuoMasterModel -> getInfo($uid);
      $data = [];
      unset($form['pwd']);
      $data['data'] = $form;
      return array('status' => 1, 'data' => $data);
    }

    //新增锁匠
    public function appendUser(){
      $uid = $this->uinfo['id'];
      $data = $this ->in;
      $data['pwd'] = xf_md5($data['pwd']);
      $data['type'] = 1;
      $data['shop_id'] = $uid;
      $this ->SuoMaster -> save($data);
      return array('status' => 1, 'info' =>'添加成功');
    }

    //删除锁匠
    public function deleteUser(){
      $id = $this ->in['id'];
      if(!$this ->SuoMasterModel ->find($id)){
        return array('status' => 0, 'info' =>'数据不存在');
      }
      try {
        $this ->SuoMasterModel -> destroy($id);
        return array('status' => 1, 'info' =>'删除成功');
      } catch (\Exception $e) {
        return array('status' => 0, 'info' =>$e -> getMessage());
      }

    }


    //快捷回复语列表
    public function getSuoQuickReply(){
      $list = $this->SuoQuickReply -> where('status',1) -> select();
      $data = [];
      $data['list'] = $list;
      return array('status' => 1, 'data' => $data);
    }


    //用户评价列表
    public function getServiceRateList(){
      $uid = $this->uinfo['id'];
      $page = input('page',1);
      $is_reply = input('status',0);

      $map = [];
      $map[] = ['master_id','=',$uid];
      if($is_reply !== ''){
        $map[] = ['is_reply','=',$is_reply];
      }
      $list = $this->SuoOrderComment ->  getList($map, $page);
      $data = [];
      $data['list'] = $list;
      return array('status' => 1, 'data' => $data);
    }

    public function replyDetail(){
      $id = $this ->in['id'];
      $form = $this->SuoOrderComment -> getInfo($id);
      $data = [];
      $data['data'] = $form;
      return array('status' => 1, 'data' => $data);
    }

    //回复评价
    public function reply(){
      $id = $this ->in['id'];
      $reply_content = $this ->in['reply_content'];
      $update_data['reply'] = $reply_content;
      $update_data['is_reply'] = 1;
      $update_data['reply_time'] = date('Y-m-d H:i:s');
      $this->SuoOrderComment -> where('id',$id) -> update($update_data);
      return array('status' => 1, 'info' => '提交成功');
    }

    //获取评价详情
    public function getRateDetail(){
      $id = $this ->in['id'];
      $form = $this->SuoOrderComment -> getInfo($id);
      $user = $this->SuoMaster->getUserInfo($form['master_id']);
      $order_id = $form['order_id'];
      $list = $this->SuoOrderComment ->  where('order_id',$order_id) -> select();
      $data = [];
      $data['count'] = count($list);
      $data['user'] = $user;
      $data['list'] = $list;
      $data['data'] = $form;
      return array('status' => 1, 'data' => $data);
    }

    public function appendMessage(){
      $uid = $this->uinfo['id'];
//      $tel = $this->uinfo['tel'];
//      $realname = $this->uinfo['realname'];
//      $data['realname'] = $realname;
        $data = [];
      $data['master_id'] = $uid;
//      $data['tel'] = $tel;
      $data['time'] = date('Y-m-d H:i:s');
        $data['imgs'] = json_encode($this->in['images']);
        $data['content'] = ($this->in['content']);
        Db::name('help_msg')->insert($data);
//      $this->SystemMessage-> json(['images']) -> save($data);
      return ['status' => 1, 'info' =>'提交成功'];
    }



}
