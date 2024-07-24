<?php

declare (strict_types = 1);

namespace app\common\model;

use think\facade\Config;
use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete; 
/**
 * @mixin think\Model
 */
class SuoMaster extends Model {
    use SoftDelete;
    protected $type = [
      'shop_imgs' => 'json'
    ];

    /**
     * 登录验证
     * @param type $username
     * @param type $pwd
     * @return type
     */
    public function login($username, $pwd,$type=null) {
        $where[] =['pwd','=',xf_md5($pwd)] ;
        $where[] =['status','=',1] ;
        if($type ){
            $where[] =['level','=',$type] ;
        }

        $where[]=function ($query) use($username){
            $w[] =['tel','=',$username] ;
            $w[] =['username','=',$username] ;
            $query->whereOr($w);
        };

        return $this->where($where)->find();
    }

     //获取用户信息
    public function getUserInfo($user_id, $cache = true) {
      if (!$user_id)
        return null;

       return  (new \app\admin\model\SuoMaster())->getInfo($user_id,$cache);
//      $map = [];
//      if ($cache == false) {
//        $map[] = ['id','=',$user_id];
//        $info = $this->where($map)->field('*')->find();
//
//        $info['master_shop_name'] = $this -> where('id',$info['shop_id']) -> value('shop_name');
//
//        $info['headimgurl'] = $info['headimgurl'] ? get_img_url($info['headimgurl']) : lang('empty_header');
//        return $info;
//      }
//      $name = 'getUserInfomaster' . $user_id;
//      $info = cache($name);
//      if (!$info) {
//          $map[] = ['id','=',$user_id];
//          $info = $this->where($map)->field('*')->find();
//          if (!$info) {
//            return null;
//          }
//          $info['headimgurl'] = $info['headimgurl'] ? get_img_url($info['headimgurl']) : lang('empty_header');
//           $info['master_shop_name'] = $this -> where('id',$info['shop_id']) -> value('shop_name');
//          cache($name, $info, 3600);
//      }
//      return $info;
    }

    // 更新用户数据
    public function updateUserData($uid,$update_data=[]){
      $map = [];
      $map[] = ['id','=',$uid];
      if(isset($update_data['yyzz']) && is_array($update_data['yyzz'])){
          $update_data['yyzz'] = json_encode($update_data['yyzz']);
      }
      if(isset($update_data['shop_imgs'])){
        return $this -> where($map) -> json(['shop_imgs']) -> update($update_data);
      }else{
        return $this -> where($map) -> update($update_data);
      }
      
    }

    //token验证
    public function token_check($token) {

      if (!$token) {
        return false;
      }
      $user_id = Db::name('suo_master_token')->where(array(
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
        Db::name('suo_master_token')->insert(array(
            'user_id' => $user_id,
            'token' => $token,
            'expire' => date('Y-m-d H:i:s', strtotime("+30 day"))
        ));
        return $token;
    }

    //获取金融数据
    public function getFinance($user_id) {
        $info = $this->removeOption()->where(array('id' => $user_id))->field('balance')->find();
        return $info ? $info : [];
    }


    /**
     * 处理余额，佣金，积分
     */
    public function handleUser($table, $user_id, $total, $type, $map = array()) {
         if (!in_array($table, array('bro', 'balance', 'money'))) {
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

            $r = $this -> where(array('id' => $user_id))->inc('balance', $total)->update();
        } else if ($type == 2) {
            $r = $this ->where(array('id' => $user_id))->dec('balance', $total)->update();
        }

        $sy = Db::name("user")->where(array('id' => $user_id))->value($table);
        
        $add = array(
            'time' => date('Y-m-d H:i:s'),
            'master_id' => $user_id,
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
        $data = Db::name('user_ext')->where(array('master_id' => $user_id))->find();
        cache($cache_name, $data);
        return $data;
    }

    public function getExtField($user_id, $field) {
        return Db::name('user_ext')->where(array('master_id' => $user_id))->value($field);
    }

    public function setExtField($user_id, $field, $val) {
        return Db::name('user_ext')->where(array('master_id' => $user_id))->save([$field => $val]);
    }
}
