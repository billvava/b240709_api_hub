<?php

declare (strict_types = 1);

namespace app\common\model;

use think\facade\Config;
use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class User extends Model {

    protected $name = 'user';

    /*
     * 统一注册
     */

    public function reg($val, $type, $map = []) {
        if (!in_array($type, ['unionid', 'openid', 'tel'])) {
            return ['status' => 0, 'info' => '类型不对'];
        }
        if (!$val) {
            return ['status' => 0, 'info' => 'val不能为空'];
        }
        $reg_type = "update";
        $user_id = Db::name('user')->where(array($type => $val))->value('id');
        if (!$user_id) {
            //为防止获取微信返回过慢，导致两次注册，先注册基本信息
            $nickname = $map['nickname'] ? userTextEncode($map['nickname']) : '';
            if ($map['pid']) {
                $is_exit = $this->getUserInfo($map['pid']);
                if (!$is_exit) {
                    $map['pid'] = 0;
                }
            } else {
                $map['pid'] = 0;
            }
//            $i = rand(1, 300);
//            $headimgurl = 'http://xfchen.xinhu.wang/com/headimgurl2/' . $i . '.jpg';

            $u_add = array(
                'tel' => $type == 'tel' ? $val : '',
                'username' => $nickname ? $nickname : '',
                'nickname' => $nickname ? $nickname : '',
                'pwd' => $map['pwd'] ? xf_md5($map['pwd']) : '',
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'create_ip' => get_client_ip(),
                'update_time' => date('Y-m-d H:i:s'),
                'openid' => $type == 'openid' ? $val : ($map['openid'].''),
                'unionid' => $type == 'unionid' ? $val : '',
                'headimgurl' => $map['headimgurl'] ? $map['headimgurl'] : '',
                'pid' => $map['pid']
            );
            $user_id = Db::name('user')->insertGetId($u_add);
            $reg_type = "add";
            if ($user_id) {
                //三级分销
                Db::name('user_ext')->insert(array('user_id' => $user_id));
                $arr = array('user_id' => $user_id, 'pid1' => 0, 'pid2' => 0, 'pid3' => 0);
                if ($map['pid'] > 0) {
                    $pinfo = Db::name('user_parent')->where(array('user_id' => $map['pid']))->find();
                    Db::name('user_ext')->where(array('user_id' => $map['pid']))->inc('share_num')->update();
                    $arr['pid1'] = $map['pid'];
                    $arr['pid2'] = $pinfo['pid1'];
                    $arr['pid3'] = $pinfo['pid2'];
                }
                Db::name('user_parent')->insert($arr);
            }
        }
        return ['status' => 1, 'data' => ['user_id' => $user_id, 'type' => $reg_type]];
    }

    /**
     * 获取信息，会有缓存，请勿直接从此获取余额，积分数据
     * @param type $user_id
     * @return null
     */
    public function getUserInfo($user_id, $cache = false) {
        if (!$user_id)
            return null;
        $model = Db::name('user');
        if ($cache == false) {
            $where['a.id'] = $user_id;
            $info = $model->alias('a')
                    ->leftJoin('user_rank b', 'a.rank=b.id')
                    ->where($where)
                    ->field('a.*,b.name  as rank_name,b.discount')
                    ->find();
            $info['headimgurl'] = $info['headimgurl'] ? get_img_url($info['headimgurl']) : lang('empty_header');
            $info['user_id'] = $info['id'];
            return $info;
        }
        $name = 'getUserInfo' . $user_id;
        $info = cache($name);
        if (!$info) {
            $where['a.id'] = $user_id;
            $info = $model->alias('a')
                    ->leftJoin('user_rank b', 'a.rank=b.id')
                    ->where($where)
                    ->field('a.*,b.name as rank_name,b.discount')
                    ->find();
            if (!$info) {
                return null;
            }
            $info['headimgurl'] = $info['headimgurl'] ? get_img_url($info['headimgurl']) : lang('empty_header');
            $info['user_id'] = $info['id'];
            unset($info['dot']);
            unset($info['money']);
            unset($info['bro']);
            cache($name, $info, 3600);
        }
        return $info;
    }

    //获取角色
    public function rank_all() {
        $name = 'rank_all';
        $data = cache($name);
        if (!$data) {
            $data = Db::name('user_rank')->select()->toArray();
            $data = array_reduce($data, function ($v, $w) {
                $v[$w["id"]] = $w;
                return $v;
            });
            cache($name, $data);
        }
        return $data;
    }

    /**
     * 获取扩展信息
     * @param type $user_id
     */
    public function getExt($user_id, $cache = true) {
        if (!$user_id) {
            return null;
        }
        $cache_name = "getExt_{$user_id}";
        if ($cache && ($data = cache($cache_name))) {
            return $data;
        }
        $data = Db::name('user_ext')->where(array('user_id' => $user_id))->find();
        cache($cache_name, $data);
        return $data;
    }

    public function getExtField($user_id, $field) {
        return Db::name('user_ext')->where(array('user_id' => $user_id))->value($field);
    }

    public function setExtField($user_id, $field, $val) {
        return Db::name('user_ext')->where(array('user_id' => $user_id))->save([$field => $val]);
    }

    /**
     * 清除getUserInfo的缓存
     * @param type $user_id
     */
    public function clearCache($user_id) {
        if (!$user_id)
            return null;
        $name = 'getUserInfo' . $user_id;
        cache($name, null);
        $name = 'getExt_' . $user_id;
        cache($name, null);
        return true;
    }

//获取openid
    public function getOpenid($user_id) {
        return $this->where(array('id' => $user_id))->cache(true)->value('openid');
    }

//获取金融数据
    public function getFinance($user_id) {
        $info = $this->removeOption()->where(array('id' => $user_id))->find();
        return $info ? $info : [];
    }

    /**
     * 获取余额,佣金，积分日志
     * 在前台请勿直接传入I()作为参数
     * @param type $in
     * @return type
     */
    public function getUserLog($table, $in = array(), $page_type = 'home') {
//bro bro money
        $model = Db::name("user_{$table}");
        $where = array();
//时间查询
        if ($in['start_datetime'] && $in['end_datetime']) {
            $where[] = array('a.time', 'between', array(($in['start_datetime']), ($in['end_datetime'])));
        } elseif ($in['start_datetime']) {
            $where[] = array('a.time', '>', ($in['start_datetime']));
        } elseif ($in['end_datetime']) {
            $where[] = array('a.time', '<', ($in['end_datetime']));
        }


//第几页
        if ($in['p']) {
            $_GET['p'] = $in['p'];
        }
//用户名查询
        //用户名查询
        if ($in['user_id']) {
            $where[] = ['a.user_id', '=', $in['user_id']];
        }

        if ($in['ordernum']) {
            $where[] = ['a.ordernum', '=', $in['ordernum']];
        }

        if ($in['type']) {
            $where[] = ['a.type', '=', $in['type']];
        }

        if ($in['cate']) {
            $where[] = ['a.cate', '=', $in['cate']];
        }
       // dump($where);exit;
        $count = $model->alias('a')->where($where)->count();
//分页数
        $num = $in['num'] ? $in['num'] : C('data_page_count');
        if (!$num) {
            $num = 10;
        }
        $start = 0;

        if ($page_type == 'admin') {
            tool()->classs('PageForAdmin');
            $Page = new \PageForAdmin($count, $num);
            $start = $Page->firstRow;
        } elseif ($page_type == 'home') {
            tool()->classs('Page');
            $Page = new \Page($count, $num);
            $start = $Page->firstRow;
        } else {
            $start = ($in['page'] - 1) * $num;
        }
        $data['page'] = $Page->show();
        $data['list'] = $model->alias('a')
                        ->leftJoin('user b', 'a.user_id=b.id')
                        ->field("a.*,b.username")
                        ->where($where)
                        ->order('a.id desc')
                        ->limit($start, $num)
                        ->select()->toArray();
        return $data;
    }

    /**
     * 处理余额，佣金，积分
     */
    public function handleUser($table, $user_id, $total, $type, $map = array()) {
        
        
         if (!in_array($table, array('bro', 'dot', 'money','lvse_dot','daijinquan','duihuanquan','jinhuoquan','gongxianzhi'))) {
            return false;
        }
        $user_tb = "user_{$table}";
        $user = "user";
        if (!is_numeric($total)) {
            return false;
        }
        if (!in_array($type, array(1, 2))) {
            return false;
        }
        $r = null;
        $total = $total + 0;
        if ($type == 1) {


            if($map['cate'] <16){
               if($map['cate'] == 7){
                   $r = Db::name($user)->where(array('id' => $user_id))->inc('consumption', $total)->update();
               }else{
                   $r = Db::name($user)->where(array('id' => $user_id))->inc($table, $total)->update();
               }

            }



//            if(in_array($map['cate'] ,[5,6])){
//                $dj_time = C('dj_time');
//                Db::name('user_brodj')->insert([
//                    'user_id'=>$user_id,
//                    'total'=>$total,
//                    'expire'=>date('Y-m-d H:i:s',strtotime("+{$dj_time} day")),
//                    'cate'=>$map['cate']
//                ]);
//            }

        } else if ($type == 2) {
            if($map['cate'] == 8){
                $r = Db::name($user)->where(array('id' => $user_id))->dec('consumption', $total)->update();
            }else{
                $r = Db::name($user)->where(array('id' => $user_id))->dec($table, $total)->update();
            }
        }

        $sy = Db::name("user")->where(array('id' => $user_id))->value($table);
        
        $add = array(
            'time' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'type' => $type,
            'total' => $total,
            'current_total' => $sy,
            'ordernum' => isset($map['ordernum']) && $map['ordernum'] ? $map['ordernum'] : '',
            'cate' => isset($map['cate']) ? $map['cate'] : 0,
            'msg' => isset($map['msg']) ? $map['msg'] : '',
            'admin_id' => isset($map['admin_id']) ? $map['admin_id'] : '',
        );
        $addid = Db::name($user_tb)->insertGetId($add);
        if (($r || $r === 0) && $addid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取等级的名称
     * @param type $rank
     */
    function getRankName($rank, $is_echo = true) {
        if ($rank <= 0) {
            return false;
        }
        $cache_name = 'getRankName' . $rank;
        cache($cache_name);
        if (!($name = cache($cache_name))) {
            $name = Db::name('user_rank')->where('id=' . $rank)->value('name');
            cache($cache_name, $name);
        }
        if ($is_echo == true) {
            echo $name;
            return;
        }
        return $name;
    }

    /**
     * 获取名称
     * @param type $user_id
     * @param type $is_echo
     */
    function getNickname($user_id, $is_echo = true) {
        if ($user_id <= 0) {
            return false;
        }
        $cache_name = 'getNickname' . $user_id;
        cache($cache_name);
        if (!($name = cache($cache_name))) {
            $name = Db::name('user')->where('id=' . $user_id)->value('nickname');
            cache($cache_name, $name);
        }
        if ($is_echo == true) {
            echo $name;
            return;
        }
        return $name;
    }

    /**
     * 获取头像
     * @param type $user_id
     * @param type $is_echo
     */
    function getHeadimgurl($user_id, $is_echo = true) {
        if ($user_id <= 0) {
            return false;
        }
        $cache_name = 'getHeadimgurl' . $user_id;
        cache($cache_name);
        if (!($name = cache($cache_name))) {
            $name = Db::name('user')->where('id=' . $user_id)->value('headimgurl');
            cache($cache_name, $name);
        }
        if ($is_echo == true) {
            echo $name;
            return;
        }
        return $name;
    }

    /**
     * 用户充值
     * @param type $uid 用户ID
     * @param type $total 金额
     * @param type $msg 日志消息
     * @param type $ordernum 订单号或者流水号
     * @param type $is_rollback 是否回滚
     */
    public function addMoney($user_id, $total, $msg, $ordernum = '') {
        return $this->handleUser('money', $user_id, $total, 1, array('msg' => $msg, 'ordernum' => $ordernum));
    }

    /**
     * 用户扣除金额
     * @param type $uid 用户ID
     * @param type $total 金额
     * @param type $msg 日志消息
     * @param type $ordernum 订单号或者流水号
     */
    public function reduceMoney($user_id, $total, $msg, $ordernum = '') {
        return $this->handleUser('money', $user_id, $total, 2, array('msg' => $msg, 'ordernum' => $ordernum));
    }

    /**
     * 增加积分
     * @param type $uid 用户ID
     * @param type $int 金额
     * @param type $msg 日志消息
     * @param type $ordernum 订单号或者流水号
     * @param type $is_rollback 是否回滚
     */
    public function addInts($user_id, $total, $msg, $ordernum = '') {
        return $this->handleUser('dot', $user_id, $total, 1, array('msg' => $msg, 'ordernum' => $ordernum));
    }

    /**
     * 减少积分 intlog2
     * @param type $uid 用户ID
     * @param type $int 金额
     * @param type $msg 日志消息
     * @param type $ordernum 订单号或者流水号
     * @param type $is_rollback 是否回滚
     */
    public function reduceInts($user_id, $total, $msg, $ordernum = '') {
        return $this->handleUser('dot', $user_id, $total, 2, array('msg' => $msg, 'ordernum' => $ordernum));
    }

    /**
     * 增加佣金
     * @param type $uid 用户ID
     * @param type $total 金额
     * @param type $msg 日志消息
     * @param type $ordernum 订单号或者流水号
     * @param type $is_rollback 是否回滚
     */
    public function addBro($user_id, $total, $msg, $ordernum = '') {
        return $this->handleUser('bro', $user_id, $total, 1, array('msg' => $msg, 'ordernum' => $ordernum));
    }

    /**
     * 减少佣金
     * @param type $uid 用户ID
     * @param type $total 金额
     * @param type $msg 日志消息
     * @param type $ordernum 订单号或者流水号
     * @param type $is_rollback 是否回滚
     */
    public function reduceBro($user_id, $total, $msg, $ordernum = '') {
        return $this->handleUser('bro', $user_id, $total, 2, array('msg' => $msg, 'ordernum' => $ordernum));
    }

//累计所得佣金
    public function getHistoryBro($uid, $date = '') {


        $where = [
                ['user_id', '=', $uid],
                ['type', '=', 1]
        ];
        if ($date) {
            $where[] = array('time', '>', $date);
        }

        return Db::name('user_bro')->where($where)->sum('total') + 0;
    }

//获取上级
    public function getPinfo($user_id) {
        return Db::name('user_parent')
                        ->alias('a')
                        ->leftJoin('user b', 'a.pid1=b.id')
                        ->leftJoin('user c', 'a.pid2=c.id')
                        ->leftJoin('user d', 'a.pid3=d.id')
                        ->field("a.*,b.nickname as nickname1,c.nickname as nickname2,d.nickname as nickname3")
                        ->where(array('a.user_id' => $user_id))->find();
    }

//token验证
    public function token_check($token) {
        if (!$token) {
            return false;
        }
        $user_id = Db::name('user_token')->where(array(
                        ['token', '=', $token],
                        ['expire', '>', date('Y-m-d H:i:s')],
                ))->value('user_id');
        if ($user_id) {
            return $user_id;
        } else {
            return false;
        }
    }

//添加token
    public function add_token($user_id) {
        $token = md5(uniqid() . $user_id . rand(10, 9999));
        Db::name('user_token')->insert(array(
            'user_id' => $user_id,
            'token' => $token,
            'expire' => date('Y-m-d H:i:s', strtotime("+30 day"))
        ));
        return $token;
    }

    public function getChildCount($pid) {
        return Db::name('user_parent')->where(array('pid1' => $pid))->count();
    }

    public function getRankInfo($id) {
        return Db::name('user_rank')->where(array('id' => $id))->find();
    }

    /**
     * 递归获取上级信息
     * @param type $uid
     * @return type
     */
    public function getPidInfo($uid) {
        $p_str = $this->getPid($uid);
        $p_arr = array_filter(explode(',', $p_str));
        foreach ($p_arr as &$v) {
            $v = Db::name('user')->where(array('id' => $v))->field("rank,id")->find();
        }
        return $p_arr;
    }

    /**
     * 递归获取
     */
    public function getPid($uid) {
        $model = Db::name('user_parent');
        $pid1 = $model->where(array('user_id' => $uid))->value('pid1');
        $pids = '';
        if ($pid1 != '') {
            $pids .= $pid1;
            $npids = $this->getPid($pid1);
            if ($npids) {
                $pids .= ',' . $npids;
            }
        }
        return $pids;
    }


    //统计业绩
    public function saveYeji($user_id,$money)
    {

        if(Db::name('mall_yeji') -> where('user_id',$money) -> count()){
            Db::name('mall_yeji') -> where('user_id',$money) -> inc('money',$money) -> update();
        }else{
            $data['user_id'] = $user_id;
            $data['money'] = $money;
            $data['create_time'] = date('Y-m-d H:i:s');
            Db::name('mall_yeji') -> save($data);
        }
        return true;
    }


    public function jicha($referee_path,$money)
    {
        $jicha = C('jicha');
        $pingji = C('pingji');

        $jicha_array = explode('|',$jicha);
        if(!$referee_path){
            return;
        }
        $referee_path = explode(',',$referee_path);
        $map = [];
        $map[] = ['id', 'in', $referee_path];

        $list = Db::name("user")->where($map)->  order('id asc') -> field('*') -> select();
      
        $cate = 5;
        foreach ($list as $key => $value){
            $rank = $value['rank'];
            $c_rank = $list[$key+1]['rank']??0;
            $bili = 0;
            if($rank > $c_rank){
                $bili_1 = $jicha_array[$rank - 2] ?? 0;
                $bili_2 = $jicha_array[$c_rank - 2] ?? 0;
                $bili = $bili_1 - $bili_2;
            }elseif ($rank == $c_rank && $rank >=5){
                $bili = $pingji;
                $cate = 6;
            }else{
                $bili = 0;
            }
            $bili = $bili / 100;

            $bonus = round($money*$bili,2) ;
            if($bonus > 0){
                $this->handleUser('daijinquan',$value['id'], $bonus, 1, array('cate' => $cate,'ordernum' => ''));
            }
        }
    }

    public function shengji($referee_path){
        $referee_path_array = [];
        if($referee_path){
            $referee_path_array = explode(',',$referee_path);
        }
        $map = [];
        $map[] = ['id', 'in', $referee_path_array];
        $order = 'id desc';
        $field = 'id,referee_path';
        $list = $this ->where($map)-> orderRaw($order) -> field($field) -> select();
        foreach ($list as $key => $value){
            $this -> updateRank($value['id'],$value['referee_path']);
        }
    }

    //更新级别
    public function updateRank($uid,$referee_path){
        $user =  Db::name('user')->where('id',$uid)->find();

        $shengji1 = C('shengji1');
        $shengji2= C('shengji2');

        $shengji1_array = explode('|',$shengji1); //业绩
        $shengji2_array = explode('|',$shengji2); //等级人数
        if($user['rank'] <1){
            $update_data['rank'] = 1;
        }
        $update_data['is_pay'] = 1;
        Db::name('user')-> where('id',$uid) -> update($update_data);
        $referee_path_array = [];
        if($referee_path){
            $referee_path_array = explode(',',$referee_path);
        }


        $map = [];
        $map[] = ['id', 'in', $referee_path_array];
        if(!$referee_path_array){
            return true;
        }
        $list = Db::name("user")->where($map)->  order('id desc') -> select();
        foreach ($list as $value){
            $map = [];
            $map[] = ['referee_path', 'like', '%' . "," . $value['id'] . "," . '%'];
            $user_ids = $this -> where($map)-> column('id');
            $rank = $value['rank'];
            $total_sum = Db::name('mall_yeji') -> where('user_id','in',$user_ids) -> sum('money');//团队总业绩
            $re_num =  $this -> where('pid',$value['id']) -> where('is_pay',1) -> count(); //有效直推人
            if($rank <= 1){ //升级v1
                if($total_sum >= $shengji1_array[0] && $re_num >=$shengji2_array[0]){
                    $new_rank = 2;
                }
            }elseif($rank <= 2){ //升级v2
                if($total_sum >= $shengji1_array[1] && $re_num >=$shengji2_array[1]){
                    $new_rank = 3;
                }
            }elseif($rank <= 3){ //升级v3
                if($total_sum >= $shengji1_array[2] && $re_num >=$shengji2_array[2]){
                    $new_rank = 4;
                }
            }elseif($rank <= 4){ //升级v4
                $num = $this -> validateLevel($value['id'],4);//三个V3且不在同一条线
                if($num >=$shengji2_array[3]){
                    $new_rank = 5;
                }
            }elseif($rank <= 5){ //升级v5
                $num = $this -> validateLevel($value['id'],5);//三个V4且不在同一条线
                if($num >=$shengji2_array[4]){
                    $new_rank = 6;
                }
            }elseif($rank <= 6){ //升级v6
                $num = $this -> validateLevel($value['id'],6);//三个V5且不在同一条线
                if($num >=$shengji2_array[5]){
                    $new_rank = 7;
                }
            }elseif($rank <= 7){ //升级v7
                $num = $this -> validateLevel($value['id'],7);//三个V6且不在同一条线
                if($num >=$shengji2_array[6]){
                    $new_rank = 8;
                }
            }

            if($new_rank>$rank ){
                $this -> where('id',$value['id']) ->update(['rank' => $new_rank]);
            }
            return true;
        }





        return true;

    }


    //判断是否符合升级条件
    public function validateLevel($uid,$type=4){

        $list = Db::name('user') ->where('pid',$uid) -> select();
        if(!$list){
            return false;
        }
        $num = 0;
        foreach ($list as $key => $value){
            $res = $this -> getTeamNum($value['id'],$type);
            if($res){
                $num+= 1;
            }
            if($value['rank'] >= $type){
                $num+= 1;
            }
        }
        return $num;

    }


    public function getTeamNum($uid,$type)
    {
        $map = [];
        $map[] = ['referee_path', 'like', '%' . "," . $uid . "," . '%'];
        $map[] = ['rank', '>=', $type];
        $order = 'id desc';
        $count = Db::name('user') ->where($map)-> orderRaw($order) -> count();
        if($count > 0){
            return 1;
        }else{
            return 0;
        }
    }


    //直推奖
    public function zhituijiang($money,$uid,$pid){
       $is_pay =  $this->where(array('id' => $pid))->value('is_pay');
       $lvse_dot = C('lvse_dot');
       $total_dot = $lvse_dot * $money;
       $zhitui = C('zhitui');
       $daijinquan = C('daijinquan');

       $daijinquan_num = $money * $daijinquan;
       $user_lvse_dot = $daijinquan_num * $zhitui/100;//改为拿代金券的

//       if($is_pay){
           if($total_dot > 0 && $user_lvse_dot > 0){

               $this->handleUser('lvse_dot', $uid, $total_dot, 1, array('cate' => 8,'ordernum' => ''));

               $this->handleUser('lvse_dot', $pid, $user_lvse_dot, 1, array('cate' => 1,'ordernum' => ''));
               $this->handleUser('daijinquan', $uid, $daijinquan_num, 1, array('cate' => 1,'ordernum' => ''));

               $this -> jiantuijiang($daijinquan_num,$pid);

           }

//       }

        return true;
    }


    //间推奖
    public function jiantuijiang($total_dot,$uid){
        $pid =  Db::name("user")->where(array('id' => $uid))->value('pid');
        if(!$pid){
            return ;
        }
        $jiantui = C('jiantui');
        $bonus = $total_dot * $jiantui/100;
        if($bonus > 0){
            $this->handleUser('lvse_dot', $pid, $bonus, 1, array('cate' => 2,'ordernum' => ''));
        }
        return true;

    }

    public function kaijiang(){

        $file = 'example.txt'; // 要锁定的文件
        $lock = fopen($file, 'w+'); // 创建一个可写的文件句柄

        if (flock($lock, LOCK_EX)) { // 获取独占锁
            $winning_numbers = rand(1,12);
            $num = 12*24;
            $day_start_time = strtotime("today");

            for ($i=1;$i<= $num;$i++){
                $strat_time = $day_start_time + $i*300;
                $end_time = $day_start_time +($i+1) * 300;
                if($strat_time <= time() && time() < $end_time){
                    $data['strat_time'] = $strat_time;
                    $data['end_time'] = $end_time;
                }
            }
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['winning_numbers'] = $winning_numbers;
            $activity_data = Db::name('activity') -> order('id desc') -> find();
            if(!$activity_data || $activity_data['end_time'] < time()){
                if(iseet($data['strat_time']) && $data['strat_time'] ){
                    Db::name('activity') -> save($data);
                }

            }

            $map = [];
            $map [] = ['end_time','<=',time()];
            $map [] = ['status','=',0];

            $list = Db::name('activity_jingcai') -> where($map) -> select();
            foreach ($list as $key => $value){
                $num_json_array = json_decode($value['num_json'],true);
                $bonus = 0;
                $winning_numbers = $value['winning_numbers'];
                if(in_array($winning_numbers,$num_json_array)){
                    $bonus = $value['beishu'] * 100;
                    $this->handleUser('daijinquan', $value['user_id'], $bonus, 1, array('cate' => 4,'ordernum' => ''));
                }
                $update_data = [];
                $update_data['status'] = 1;
                $update_data['bonus'] = $bonus;
                Db::name('activity_jingcai') -> where('id',$value['id']) -> update($update_data);

            }
            if($activity_data['end_time'] < time()){
                Db::name('activity') -> where('id',$activity_data['id']) -> update(['status' => 1]);
                return;
            }

            flock($lock, LOCK_UN); // 释放锁
        } else {
            echo "无法获取文件锁";
        }

        fclose($lock); // 关闭文件句柄



    }


}
