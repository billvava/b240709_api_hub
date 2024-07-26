<?php

namespace app\mallapi\logic;

use app\mallapi\controller\Common;
use think\facade\Db;
use app\mall\logic\Goods;

use app\mall\model\{GoodsHistory, Order, MallCoupon, User as MallUser};
use app\user\model\{UserAddress, UserMsg, UserBrodj, UserBank};
use app\admin\model\Shop;

use app\common\model\{
    Weixin, User as CommonUser
};

use app\mallapi\model\Rule;

class User
{

    public $clear;
    public $uinfo;
    public $data;
    public $model;

    public function __construct()
    {
        $this->goodsLogicModel = new Goods;
        $this->orderModel = new Order;
        $this->model = new CommonUser;
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }



    public function index()
    {
        //站内信
        $is_new_msg = Db::name('user_msg')->where(array('user_id' => $this->uinfo['id'], 'is_read' => 0))->value('id');
        $this->data['is_new_msg'] = $is_new_msg ? 1 : 0;
        $this->data['coupon_num'] = (new MallCoupon)->getCanCount($this->uinfo['id']);
        $atn = new Atn();
        $atn->config([
            'data' => $this->data,
            'in' => $this->in,
            'uinfo' => $this->uinfo,
        ]);
        $this->data['atn_count'] = $atn->atn_count();

        $History = new GoodsHistory();
        $this->data['History_count'] = $History->getCount($this->uinfo['id']);

        $rank_array = get_user_level();
        $agent_array = ['主营账号','市级代理','县区级代理','店家'];

        $this->data['finfo'] = $this->model->getFinance($this->uinfo['id']);
        $this->data['uinfo'] = $this->uinfo;
        $this->data['uinfo']['rank_name'] = $rank_array[$this->data['uinfo']['rank']]??'临时会员';
        $this->data['uinfo']['agent_name'] = $agent_array[$this->data['uinfo']['role']-1]??'';
        $this->data['orderCountInfo'] = $this->orderModel->census(array('wait_pay_count', 'wait_send_count', 'wait_get_count',
            'after_count', 'wait_finish_count', 'wait_com_count'), array('user_id' => $this->uinfo['id'], 'is_u_del' => 0));

        $com_cdn = C('com_cdn');
        $this->data['order_nav'] = [
            [
                'name' => '待付款',
                'count' => $this->data['orderCountInfo']['wait_pay_count'],
                'url' => '/pages/order/index/index?flag=wait_pay',
                'ico' => "{$com_cdn}user/my_order_icon1.png",
            ],
            [
                'name' => '待发货',
                'count' => $this->data['orderCountInfo']['wait_send_count'],
                'url' => '/pages/order/index/index?flag=wait_send',
                'ico' => "{$com_cdn}user/my_order_icon2.png",
            ],
            [
                'name' => '待收货',
                'count' => $this->data['orderCountInfo']['wait_get_count'],
                'url' => '/pages/order/index/index?flag=wait_get',
                'ico' => "{$com_cdn}user/my_order_icon3.png",
            ],
            [
                'name' => '待评价',
                'count' => $this->data['orderCountInfo']['wait_com_count'],
                'url' => '/pages/order/index/index?flag=wait_com',
                'ico' => "{$com_cdn}user/my_order_icon4.png",
            ],
            [
                'name' => '售后',
                'count' => $this->data['orderCountInfo']['after_count'],
                'url' => '/pages/order/index/index?flag=after',
                'ico' => "{$com_cdn}user/my_order_icon5.png",
            ],
        ];

        $uid = $this->uinfo['id'];
//        $shop = Db::name('shop')-> where('uid',$uid) -> find();
        $url = "/pages/baoming/type4";
//        if($shop){
//            $url = "/pages/baoming/log/log";
//        }

        $this->data['navigates'] = [
            [
                'name' => '我的钱包',
                'url' => '/pages/user/log/log?flag=money',
                'icon' => "user-center/icon-nav-1.png",
            ],
            [
                'name' => '充值',
                'url' => '/pages/recharge/recharge',
                'icon' => "user-center/icon-nav-1.png",
            ],
            [
                'name' => '余额转账',
                'url' => '/pages/baoming/transfer',
                'icon' => "user-center/icon-nav-1.png",
            ],
            [
                'name' => '进货券转账',
                'url' => '/pages/baoming/jinhuoquantransfer',
                'icon' => "user-center/icon-nav-1.png",
            ],
            [
                'name' => '提现',
                'url' =>'/pages/user/cashout/cashout',
                'icon' => "user-center/icon-nav-4.png",
            ],
            [
                'name' => '合成',
                'url' =>'/pages/user/hecheng/hecheng',
                'icon' => "user-center/icon-nav-4.png",
            ],
            [
                'name' => '砸金蛋明细',
                'url' =>'/pages/smash-egg/log',
                'icon' => "user-center/icon-nav-4.png",
            ],
            [
                'name' => '我的粉丝',
                'url' => '/pages/user/team/myTeam',
                'icon' => "user-center/icon-nav-4.png",
            ],
            [
                'name' => '帮助与反馈',
                'url' => '/pages/ac/help/index',
                'icon' => "user-center/icon-nav-2.png",
            ],
            [
                'name' => '关于我们',
                'url' => '/pages/ac/help/item?id=about',
                'icon' => "user-center/icon-nav-3.png",
            ],
            [
                'name' => '我的银行卡',
                'url' => '/pages/user/cashout_channel/index',
                'icon' => "user-center/icon-nav-4.png",
            ],

        ];
        return array('status' => 1, 'data' => $this->data);
    }

    //设置
    public function set()
    {
        if ($this->in['flag'] == 'get') {
            $address_text = "";
            if ($this->uinfo['province']) {
                $o = new \app\home\model\O();
                $province_name = $o->getAreas($this->uinfo['province']);
                $address_text .= "$province_name";
                if ($this->uinfo['city']) {
                    $name = $o->getAreas($this->uinfo['city']);
                    $address_text .= "-{$name}";

                    if ($this->uinfo['country']) {
                        $name = $o->getAreas($this->uinfo['country']);
                        $address_text .= "-{$name}";

                    }
                }

            }


            $this->data['uinfo'] = get_arr_field($this->uinfo, array('headimgurl', 'nickname', 'rank_name', 'rank', 'tel', 'sex', 'birthday', 'realname'));
            $this->data['address_text'] = $address_text ? $address_text : '请选择';
            return array('status' => 1, 'data' => $this->data);
        }
        if ($this->in['flag'] == 'sub') {
            $field = "nickname,headimgurl,sex,birthday,tel,realname,pwd,pwd2";
            $add_model = new UserAddress();
            if ($this->in['province_name'] && $this->in['province_name'] <= 0) {
                $this->in['province'] = $add_model->getCityByName($this->in['province_name'], 1);
                $this->in['city'] = $add_model->getCityByName($this->in['city_name'], 2);
                $this->in['country'] = $add_model->getCityByName($this->in['country_name'], 3);
                $field .= ",province,city,country";
            }
            $user_id = $this->uinfo['id'];
            $pwd = $this->in['pwd'];
            $old_pwd = $this->in['old_pwd'];

            $pwd2 = $this->in['pwd'];
            $old_pwd2 = $this->in['old_pwd2'];

            if($pwd && $old_pwd){
                $old_pwd = xf_md5($this->in['old_pwd']);
                if(!$this->model->where('id',$user_id) -> where('pwd',$old_pwd) -> count()){
                    return array('status' => 0, 'info' => '旧的一级密码不对');
                }
                if(strlen($pwd) < 6){
                    return array('status' => 0, 'info' => '新一级密码不能少于6位数');
                }
                $this->in['pwd'] = xf_md5($pwd);
            }else{
                unset( $this->in['pwd'], $this->in['old_pwd']);
            }

            if($pwd2 && $old_pwd2){
                $old_pwd2 =  xf_md5($this->in['old_pwd2']);

                if(!$this->model->where('id',$user_id) -> where('pwd',$old_pwd2) -> count()){
                    return array('status' => 0, 'info' => '旧的二级密码不对');
                }
                if(strlen($pwd2) < 6){
                    return array('status' => 0, 'info' => '新二级密码不能少于6位数');
                }
                $this->in['pwd2'] = xf_md5($pwd2);
            }else{
                unset( $this->in['pwd2'], $this->in['old_pwd2']);
            }



            $this->model->where(array('id' => $this->uinfo['id']))->field($field)->update($this->in);
            $this->model->clearCache($this->uinfo['id']);
            return array('status' => 1, 'info' => lang('s'));
        }
    }

    //dot money bro日志
    public function f_log()
    {
        $a = array(
            'bro', 'money', 'dot','daijinquan','duihuanquan','jinhuoquan','lvse_dot','gongxianzhi'
        );
        $flag = $this->in['flag'];
        if (in_array($flag, $a)) {
            $arr = array(
                'bro' => array(
                    'cate' => 'user_bro_cate',
                    'name' => '礼包',
                ),
                'money' => array(
                    'cate' => 'user_money_cate',
                    'name' => '余额',
                ),
                'daijinquan' => array(
                    'cate' => 'user_daijinquan_cate',
                    'name' => '代金券',
                ),
                'duihuanquan' => array(
                    'cate' => 'user_duihuanquan_cate',
                    'name' => '兑换券',
                ),
                'jinhuoquan' => array(
                    'cate' => 'user_jinhuoquan_cate',
                    'name' => '进货券',
                ),
                'lvse_dot' => array(
                    'cate' => 'user_lvse_dot_cate',
                    'name' => '绿色积分',
                ),
                'gongxianzhi' => array(
                    'cate' => 'user_gongxianzhi_cate',
                    'name' => '贡献值',
                ),
                'dot' => array(
                    'cate' => 'user_dot_cate',
                    'name' => '积分',
                ),
            );
            $item = $arr[$flag];

            $finfo = $this->model->getFinance($this->uinfo['id']);
            $in = array('user_id' => $this->uinfo['id'], 'cate' => $this->in['cate'],'type'=>$this->in['type'],'date'=>$this->in['date']);
            $in['p'] = $this->in['page'] ? $this->in['page'] : 1;
            $res = $this->model->getUserLog($flag, $in);
            $cate = lang($item['cate']);
            foreach ($res['list'] as &$v) {
                $v['cate_str'] = $cate[$v['cate']];
            }
            $this->data['list'] = $res['list'];
            $this->data['can_total'] = $finfo[$flag];
            $this->data['count'] = count($res['list']);
            $this->data['name'] = $item['name'];
        }
        return array('status' => 1, 'data' => $this->data);
    }

    //微信手机
    public function get_tel()
    {
        include_once INCLUDE_PATH . 'weixin/wxBizDataCrypt.php';
        $pc = new \WXBizDataCrypt(C('appid'), $this->in['session_key']);
        $errCode = $pc->decryptData($this->in['encryptedData'], $this->in['iv'], $data2);
        if ($errCode == 0) {
            $data2 = json_decode($data2, true);
            $this->model->where(array('id' => $this->uinfo['id']))->update(array('tel' => $data2['phoneNumber']));
            $this->model->clearCache($this->uinfo['id']);
            $this->data['tel'] = $data2['phoneNumber'];
            return array('status' => 1, 'info' => lang('s'), 'data' => $this->data);
        } else {
            return array('status' => -1, 'info' => '请重新登陆');
        }
    }

    //站内信
    public function msg()
    {
        $UserMsg = new UserMsg;
        $where = array('user_id' => $this->uinfo['id'], 'is_del' => 0);
        if (isset($this->in['is_read'])) {
            $where['is_read'] = $this->in['is_read'];
        }
        if (isset($this->in['type']) && $this->in['type'] != '') {
            $where['type'] = $this->in['type'];
        }
        $this->data['list'] = $UserMsg->getList($where, $this->in['page']);
        $this->data['count'] = count($this->data['list']);
        foreach ($this->data['list'] as $v) {
            if ($v['is_read'] == 0) {
                $UserMsg->where(array('id' => $v['id']))->update(array('is_read' => 1));
            }
        }
        return array('status' => 1, 'data' => $this->data);
    }

    //删除站内信
    public function msg_del()
    {
        $UserMsg = new UserMsg;
        $where = array('user_id' => $this->uinfo['id'], 'id' => $this->in['id']);
        $UserMsg->where($where)->update(array('is_del' => 1));
        return array('status' => 1, 'info' => lang('s'));
    }

    public function password2(){
        $pwd2 = input('pwd2');
        $uid = $this->uinfo['id'];
        $map = [];
        $map[] = ['id','=',$uid];
        $map[] = ['pwd2','=',xf_md5($pwd2)];
        if(!$this->model -> where($map) -> count()){
            return array('status' => 0, 'info' => '密码错误');
        }else{
            return array('status' => 1, 'info' => '正在验证...');
        }
    }
    //转账
    public function transfer(){
        $uid = $this->uinfo['id'];
        $transfer_money = input('money');
        $mobile = input('mobile');
        $money = $this->model -> where('id',$uid) -> value('money');
        $transfer_uid = $this->model -> where('username',$mobile) -> value('id');//收款人的ID
        if(!$transfer_uid){
            return array('status' => 0, 'info' => '收款人不存在');
        }
        if($money < $transfer_money){
            return array('status' => 0, 'info' => '余额不足');
        }

        $this->model ->handleUser('money', $transfer_uid, $transfer_money, 1, array('cate' => 6,'ordernum' => ''));
        $this->model ->handleUser('money', $uid, $transfer_money, 2, array('cate' => 6,'ordernum' => ''));
        return array('status' => 1, 'info' => '转账成功');

    }


    //转账
    public function jinhuoquantransfer(){
        $uid = $this->uinfo['id'];
        $transfer_money = input('num');
        $mobile = input('mobile');
        $jinhuoquan = $this->model -> where('id',$uid) -> value('jinhuoquan');
        $transfer_uid = $this->model -> where('username',$mobile) -> value('id');//收款人的ID
        if(!$transfer_uid){
            return array('status' => 0, 'info' => '收款人不存在');
        }
        if($jinhuoquan < $transfer_money){
            return array('status' => 0, 'info' => '进货券不足');
        }

        $result_money = $transfer_money *0.9;
        $this->model ->handleUser('jinhuoquan', $transfer_uid, $transfer_money, 1, array('cate' => 2,'ordernum' => ''));
        $this->model ->handleUser('jinhuoquan', $uid, $result_money, 2, array('cate' =>3,'ordernum' => ''));
        return array('status' => 1, 'info' => '转账成功');

    }

    //修改密码
    public function change_pwd()
    {
        if ($this->in['flag'] == 'get') {
            $this->data['need_old_pwd'] = 1;
            if (!$this->uinfo['pwd']) {
                $this->data['need_old_pwd'] = 0;

            }
            return ['status' => 1, 'data' => $this->data];
        }
        if ($this->uinfo['pwd'] && !$this->in['old_pwd']) {
            return ['status' => 0, 'info' => '缺少旧密码'];
        }
        if (!$this->in['new_pwd']) {
            return ['status' => 0, 'info' => '缺少新密码'];
        }
        if (!$this->in['repwd']) {
            return ['status' => 0, 'info' => '缺少确认密码'];
        }
        if ($this->in['new_pwd'] != $this->in['repwd']) {
            return ['status' => 0, 'info' => '新密码跟确认密码不一致'];
        }
        if ($this->uinfo['pwd']) {
            if (xf_md5($this->in['old_pwd']) != $this->uinfo['pwd']) {
                return ['status' => 0, 'info' => '旧密码不正确'];
            }
        }
        $this->model->where(
            [
                ['id', '=', $this->uinfo['id']]
            ]
        )->save(
            [
                'pwd' => xf_md5($this->in['new_pwd'])
            ]
        );
        $this->model->clearCache($this->uinfo['id']);
        return ['status' => 1, 'info' => lang('s')];
    }


//    public function recharge()
//    {
//       $data =[];
//       $money = $this->in['money'];
//       if($money < 0 || !$money){
//         return ['status' => 0, 'info' => '请输入金额'];
//       }
//       $data['money'] = $money;
//       $data['create_time'] = date('Y-m-d H:i:s');
//       $data['user_id'] = $this->uinfo['id'];
//       $data['tel'] = $this->uinfo['tel'];
//       Db::name('recharge') -> save($data);
//
//       return ['status' => 1, 'info' => lang('s')];
//    }

    //充值
    public function recharge(){
        $data = input();
        $user_id = $this->uinfo['id'];
        $tel = $this->uinfo['tel'];
        $data['user_id'] = $user_id;
        $data['tel'] = $tel;
        $data['create_time'] = date('Y-m-d H:i:s');
        if ($this->in['flag'] == 'get') {
            $thumb = Db::name('help_item') -> where('token','shoukuanma') -> value('thumb');
            $this->uinfo['thumb'] = $thumb;
            return ['status' => 1, 'data' => $this->uinfo];
        }
        if ($this->in['flag'] == 'sub') {
            if($data['money'] < 0 || !$data['money']){
                return array('status' => 0, 'info' => '请输入充值金额');
            }
            if(!$data['img']){
                return array('status' => 0, 'info' => '请上传打款凭证');
            }
            if( Db::name('recharge') -> save($data)){
                return ['status' => 1, 'info' => lang('s')];
            }else{
                return array('status' => 0, 'info' => '提交失败');
            }
        }
    }

    public function getRechargeList(){
      $uid = $this->uinfo['id'];
      $map = [];
      $map[] = ['user_id','=',$uid];
      $field = '*';
      $page = input('page',1);
      $page_size = 10;
      $order = 'id desc';
      $list = Db::name('recharge') -> where($map) -> field($field) -> orderRaw($order) -> paginate(['page' => $page, 'list_rows' => $page_size]);
      $data = [];
      $data['list'] = $list;
       return ['status' => 1, 'data' => $data];
    }


    public function performance(){
        $uid = $this->uinfo['id'];
        $zhitui_ids = $this -> zhituiUserId($uid);
        $zhutui_total_money = Db::name('mall_order') -> where('user_id','in',$zhitui_ids) -> where('pay_status',1) -> sum('total');
        $jiantui_ids = $this -> jiantuiUserId($uid);
        $jiantui_total_money = Db::name('mall_order') -> where('user_id','in',$jiantui_ids) -> where('pay_status',1) -> sum('total');

        $data = [];
        $data['num'] = count($zhitui_ids);
        $data['jiantui_total_money'] = $jiantui_total_money;
        $data['zhutui_total_money'] = $zhutui_total_money;
        $data['jiantui_num'] = count($jiantui_ids);

        return ['status' => 1, 'data' => $data];
    }
    // 我的团队业绩
    public function performance_old(){
        $page = input('page',1);
        $page_size = input('page_size',10);
        $uid = $this->uinfo['id'];

        $map = [];
       
        $map[] = ['referee_path', 'like', '%' . "," . $uid . "," . '%'];
        $order = 'id desc';

        $list = $this->model ->where($map)-> orderRaw($order) -> paginate(['page' => $page, 'list_rows' => $page_size]);
        $array =  get_user_level();
        foreach ($list as $key => $value){

            $zhitui_ids = $this -> zhituiUserId($value['id']);
            $zhutui_total_money = Db::name('mall_order') -> where('user_id','in',$zhitui_ids) -> where('pay_status',1) -> sum('total');
            $jiantui_ids = $this -> jiantuiUserId($value['id']);
            $jiantui_total_money = Db::name('mall_order') -> where('user_id','in',$jiantui_ids) -> where('pay_status',1) -> sum('total');
            $list[$key]['rank_name_text'] = $array[$value['rank']]??'';
            $list[$key]['num'] = count($zhitui_ids);
            $list[$key]['jiantui_total_money'] = $jiantui_total_money;
            $list[$key]['zhutui_total_money'] = $zhutui_total_money;
            $list[$key]['jiantui_num'] = count($jiantui_ids);
        }
        $data = [];
        $data['list'] = $list;
        return ['status' => 1, 'data' => $data];
    }

    public function zhituiUserId($uid)
    {
        $user_ids = $this->model -> where('pid',$uid) -> column('id');
        return $user_ids;
    }

    public function jiantuiUserId($uid)
    {
        $map = [];
        $map[] = ['referee_path', 'like', '%' . "," . $uid . "," . '%'];
        $map[] = ['pid', '<>', $uid];
        $user_ids = $this->model -> where($map) -> column('id');
        return $user_ids;
    }

    //邀请
    public function invite(){
        $uid = $this->uinfo['id'];
        $invitation_code = $this->uinfo['invitation_code'];
        $domain = request() -> domain();
        $home_url = $domain.'/h5/#/pages/login/register?invitation_code='.$invitation_code;
        $img_url = create_qrcode($home_url,$uid);

        $data = [];
        $data['invitation_code'] = $invitation_code;
        $data['img_url'] = $domain.'/'.$img_url;
        $data['link'] = $home_url;
        return ['status' => 1, 'data' => $data];

    }


    //合成
    public function hecheng(){
        $data = input();
        $daijinquan = $this -> uinfo['daijinquan'];
        $lvse_dot = $this -> uinfo['lvse_dot'];
        $uid = $this -> uinfo['id'];
        $num = $data['num'];
        $gongxianzhi = C('gongxianzhi');
        $jinhuoquan = C('jinhuoquan');
        $duihuanquan = C('duihuanquan');

        if ($this->in['flag'] == 'get') {
            return array('status' => 1, 'data' => $this -> uinfo);
        }
        if ($this->in['flag'] == 'sub') {
            if ($num < 1) {
                return ['status' => 0, 'info' => '请输入数量'];
            }
            if ($daijinquan < $num) {
                return ['status' => 0, 'info' => '代金券数量不足'];
            }
            if ($lvse_dot < $num) {
                return ['status' => 0, 'info' => '绿色积分不足'];
            }

            $gongxianzhi_value = $gongxianzhi * $num / 100;
            $jinhuoquan_value = $jinhuoquan * $num / 100;
            $duihuanquan_value = $duihuanquan * $num / 100;
            $data['user_id'] = $uid;
            $data['tel'] = $this->uinfo['tel'];
            $data['create_time'] = date('Y-m-d H:i:s');

            if (Db::name('hecheng')->save($data)) {
//                Db::name('user') -> where('id',$uid) -> inc('gongxianzhi',$gongxianzhi_value) -> update();
                $this->model->handleUser('gongxianzhi', $uid, $gongxianzhi_value, 1, array('cate' => 1, 'ordernum' => ''));
                $this->model->handleUser('daijinquan', $uid, $num, 2, array('cate' => 2, 'ordernum' => ''));
                $this->model->handleUser('lvse_dot', $uid, $num, 2, array('cate' => 5, 'ordernum' => ''));
                $this->model->handleUser('jinhuoquan', $uid, $jinhuoquan_value, 1, array('cate' => 4, 'ordernum' => ''));
                $this->model->handleUser('duihuanquan', $uid, $duihuanquan_value, 1, array('cate' => 1, 'ordernum' => ''));
                return ['status' => 1, 'info' => lang('s')];
            } else {
                return array('status' => 0, 'info' => '提交失败');
            }
        }


    }


    public function getMatchList(){

        $user_id = $this -> uinfo['id'];
        $daijinquan = $this -> uinfo['daijinquan'];
        $activity_data = Db::name('activity') -> order('id desc') -> find();

        $map = [];
        $list = Db::name('activity_match') -> where($map) -> order('id asc') -> select();
        $data = [];
        foreach ($list as $key => $value){
            $value['hammerVisible'] = $value['hammerVisible']?true:false;
            $value['eggVisibel'] = $value['eggVisibel']?true:false;
            $value['step1Visibel'] = $value['step1Visibel']?true:false;
            $value['step2Visibel'] = $value['step2Visibel']?true:false;
            $value['step3Visibel'] = $value['step3Visibel']?true:false;
            $value['winLotter'] = $value['winLotter']?true:false;
            $value['ifOver'] = $value['ifOver']?true:false;
            unset($value['draw']);
            $list[$key] = $value;

        }
        $map = [];
        $map[] = ['activity_id','=',$activity_data['id']];
        $map[] = ['user_id','=',$user_id];
        $is_jingcai = 0;
        if(Db::name('activity_jingcai') -> where($map) -> count()){
            $is_jingcai = 1;
        }
        $activity_data['is_jingcai'] = $is_jingcai ;
        $data['list'] = $list;
        $data['daijinquan'] = $daijinquan;
        $data['remainingNum'] = 5;// 砸蛋次数
        $activity_data['diff_time'] = $activity_data['end_time'] - time();
        $data['activity_data'] = $activity_data;
        return ['status' => 1, 'data' => $data];
    }


    public function addMatch($activity_id,$user_id){

        $num = rand(0,8);
        for ($i = 0;$i <=8;$i++){
            $draw = 0;
            if($i == $num){
                $draw = 1;
            }
            $value = $i+1;
            $ifOver = false;
            $list[$i]['activity_id'] = $activity_id;
            $list[$i]['user_id'] = $user_id;
            $list[$i]['value'] = $value;
            $list[$i]['draw'] = $draw;
            $list[$i]['hammerVisible'] =  false; // 锤子显示
            $list[$i]['eggVisibel'] = true; // 蛋显示
            $list[$i]['step1Visibel'] = true;// 第一阶段显示
            $list[$i]['step2Visibel'] = true;// 第二阶段显示
            $list[$i]['step3Visibel'] = true;// 第三阶段显示
            $list[$i]['winLotter'] = false;// 是否中奖
            $list[$i]['ifOver'] = $ifOver;// 是否已砸开
        }
        if(!Db::name('activity_match') -> count()){
            Db::name('activity_match') -> insertAll($list);
        }



    }
    public function  lottery(){
        $user_id = $this -> uinfo['id'];
        $daijinquan = $this -> uinfo['daijinquan'];
        $tel = $this -> uinfo['tel'];
        $data = input();
        $activity_id = $data['activity_id'];
        $activity_data = Db::name('activity') -> where('id',$activity_id) -> find();
        if(!$activity_data){
            return array('status' => 0, 'info' => '非法操作');
        }
        if($activity_data['end_time'] < time()){
            return array('status' => 0, 'info' => '本期砸金蛋已结束');
        }
        $map = [];
        $map[] = ['activity_id','=',$activity_data['id']];
        $map[] = ['user_id','=',$user_id];
        if(Db::name('activity_jingcai') -> where($map) -> count()){
            return array('status' => 0, 'info' => '请勿重复参与');
        }
        $data['winning_numbers'] = $activity_data['winning_numbers'];
        $data['activity_id'] = $activity_data['id'];
        $data['end_time'] = $activity_data['end_time'];

        $data['user_id'] = $user_id;
        $data['tel'] = $tel;
        $beishu = $data['beishu'];


        $data['create_time'] = date('Y-m-d H:i:s');
        if(!isPositiveInteger($beishu)){
            return array('status' => 0, 'info' => '倍数必须是正整数');
        }

        $jingcai_num = count($data['checkboxValues']);
        if($jingcai_num > 5){
            return array('status' => 0, 'info' => '最多选择5个数字');
        }
        if($jingcai_num < 1){
            return array('status' => 0, 'info' => '最少选择1个数字');
        }
        $num_json = json_encode($data['checkboxValues']);
        $data['num_json'] = $num_json;

        $jingcai_djq = C('jingcai_djq');
        $total = $beishu * $jingcai_djq * $jingcai_num;
        if($daijinquan < $total){
            return array('status' => 0, 'info' => '代金券不足');
        }

        $update_data = [];
        foreach ($data['checkboxValues'] as $v){
            $update_data['number'.$v] = $beishu;
            Db::name('activity') -> where('id',$activity_data['id']) -> inc('number'.$v,$beishu) -> update();
        }

//        if($draw == 1){
//            $zhongjiang = $beishu * 100;
//            $data['num'] = $zhongjiang;
//            $this->model ->handleUser('daijinquan', $user_id, $zhongjiang, 1, array('cate' => 4,'ordernum' => ''));
//        }
        $data['daijinquan'] = $total;

        $this->model ->handleUser('daijinquan', $user_id, $total, 2, array('cate' =>3,'ordernum' => ''));
        Db::name('activity_jingcai') -> save($data);
//        Db::name('activity') -> where('id',$activity_data['id']) -> inc('numbers') -> update();
//        Db::name('activity') -> where('id',$activity_data['id']) -> inc('count_daijinquan',$total) -> update();
        $referee_path = $this->model ->where('id',$user_id) -> value('referee_path');
        $this->model ->jicha($referee_path,$total);
        return array('status' => 1, 'info' => '提交成功');

    }

    public function getJingcaiList()
    {
        $page = input('page',1);
        $user_id = $this -> uinfo['id'];
        $where['user_id'] = $user_id;
        $list = Db::name('activity_jingcai') ->where($where) ->page($page, 10)->order("id desc")->select()->toArray();
        foreach ($list as $key => $value){
            $value['status_text'] = $value['status']==1?'已开奖':'未开奖';
            $value['bonus'] = $value['status']==1?$value['bonus']:'未开奖';
            $canyu_text = json_decode($value['num_json'],true);
            $value['canyu_text'] = implode('||',$canyu_text);
            $list[$key] = $value;
        }
        $this->data['list'] = $list;
        $this->data['count'] = count($list);
        return array('status' => 1, 'data' => $this->data);
    }


}
