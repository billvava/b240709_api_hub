<?php

namespace app\mallapi\logic;

use think\App;
use think\facade\Db;

class Login {

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;
    public $appid;
    public $apppwd;

    public function __construct() {
        $this->model = new \app\common\model\User();
        $this->appid = C('appid');
        $this->apppwd = C('apppwd');
        $this->userModel = new \app\user\model\User();
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    /**
     * 使用密码登陆
     * @return type
     */
//    public function pwd_login() {
//        if (!$this->in['username'] || !$this->in['pwd']) {
//            json_msg(0, '缺少参数');
//        }
//        $uinfo = $this->model->login($this->in['username'], $this->in['pwd']);
//        if (!$uinfo) {
//            return array('status' => 0, "info" => "账户或密码错误");
//        }
//        $token = $this->model->add_token($uinfo['id']);
//        $data = array();
//        $data['token'] = $token;
//        return array('status' => 1, 'info' => '登录成功', 'data' => $data);
//    }

    /**
     * 使用密码登陆
     * @return type
     */
    public function pwd_login() {
        if (!$this->in['username'] || !$this->in['pwd']) {
            json_msg(0, '缺少参数');
        }
        $uinfo = $this->userModel->login($this->in['username'], $this->in['pwd']);
        if (!$uinfo) {
            return array('status' => 0, "info" => "用户名或密码错误");
        }
        $token = $this->userModel->add_token($uinfo['id']);
        $data = array();
        $data['token'] = $token;
        return array('status' => 1, 'info' => '登录成功', 'data' => $data);
    }

    /**
     * @return mixed
     */
    public function getClearData()
    {
        return Db::name('activity_match') -> update(['num'=> 0]);
    }

    public function register(){

        $tel = trim($this->in['tel']);


        $pwd = trim($this->in['pwd']);
        $pwd2 = trim($this->in['pwd2']);
        $username = trim($this->in['username']);

        if($username == '' || !$username){
            return array('status' => 0, "info" =>'用户名不能为空');
        }

        if(!validateAlphaNum($username)){
            return array('status' => 0, "info" =>'用户名只能是数字或英文字母');
        }
        if($this->model->where('username',$username) -> count()){
            return array('status' => 0, "info" =>'该用户名已存在');
        }
        if(strlen($pwd) < 6){
            return array('status' => 0, "info" =>'一级密码不能少于六位数');
        }
        if(strlen($pwd2) < 6){
            return array('status' => 0, "info" =>'二级密码不能少于六位数');
        }

        if (!$tel) {
            return array('status' => 0, "info" =>'手机号不能为空');
        }

        if (!$this->in['pwd']) {
            return array('status' => 0, "info" =>'一级密码不能为空');
        }
        if($this->in['pwd'] != $this->in['confirm_pwd']){
            return array('status' => 0, "info" =>'两次输入一级密码不一致');
        }

        if (!$this->in['pwd2']) {
            return array('status' => 0, "info" =>'二级级密码不能为空');
        }
        if($this->in['pwd2'] != $this->in['confirm_pwd2']){
            return array('status' => 0, "info" =>'两次输入二级密码不一致');
        }




        $tel_count = $this->model->where('tel',$this->in['tel']) -> count();
        if($tel_count >= 5){
            return array('status' => 0, "info" =>'一个手机号码只能注册5个账号');
        }

        $user = [];
        if($this->in['invitation_code']){
            $user = $this->model->where('invitation_code',$this->in['invitation_code']) -> field('id,referee_path') -> find();
            if(!$user){
                return array('status' => 0, "info" =>'推荐人不存在');
            }
        }

        $pwd =$this->in['pwd'];
        $pwd2 =$this->in['pwd2'];
        $this->in['username'] = $username;
        $this->in['pwd'] = xf_md5($pwd);
        $this->in['pwd2'] = xf_md5($pwd2);
        $this->in['create_time'] =  date('Y-m-d H:i:s');
        $this->in['update_time'] = date('Y-m-d H:i:s');
        $this->in['create_ip'] = get_client_ip();
        $this->in['pid'] = $user['id']??0;


        unset($this->in['invitation_code']);
        $data = $this->in;
//        $data['is_commissioner'] = $this->in['rank'] > 1?1:0;
        if($user){
            $data['referee_id'] = $user['id']??'';
            $referee_path = $user['referee_path'].$user['id'].',';
        }else{
            $data['referee_id'] = 0;
            $data['pid'] = 0;
            $referee_path =',';
        }

        $data['referee_path'] = $referee_path;

        $data['info_status'] = 1;


        do {
            $invitation_code = rand('100000',999999);
            $num = $this->model->where('invitation_code',$invitation_code) -> count();

        } while ($num >=1);

        $data['invitation_code'] = $invitation_code;

        if($this->model->save($data)){
            $uinfo = $this->userModel->login($username,$pwd);

            $token = $this->model->add_token($uinfo['id']);
            $this->userModel-> where('id',$uinfo['id']) -> update(['login_time' => date('Y-m-d H:i:s')]);
            $data = array();
            $data['token'] = $token;
            return array('status' => 1, 'info' => '注册成功', 'data' => $data);

        }else{
            return array('status' => 0, 'info' => '注册失败');
        }



    }
    /**
     * 登陆页面显示
     * @return type
     */
    public function load_index() {
        $this->data['logo'] = C('xcx_logo');
        return array('status' => 1, 'data' => $this->data);
    }

    /**
     * 登陆表单提交
     * @return type
     */
    public function index() {
        $code = $this->request['code'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->apppwd}&js_code={$code}&grant_type=authorization_code";
        $data = file_get_contents($url);
        $data = json_decode($data, TRUE);
        if (!$data['openid']) {
            return array('status' => 0, "info" => "登陆失败：" . $data['errmsg']);
        }
        $is_unionid = C('is_unionid');
        if ($is_unionid == 1) {
            include_once INCLUDE_PATH . 'weixin/wxBizDataCrypt.php';
            $pc = new \WXBizDataCrypt($xcx_appid, $data['session_key']);
            $errCode = $pc->decryptData($this->in['encryptedData'], $this->in['iv'], $data2);
            if ($errCode == 0) {
                $data2 = json_decode($data2, true);
                $this->in['reginfo']['unionid'] = $data2['unionId'];
            }
            if (!$this->in['reginfo']['unionid']) {
                return array('status' => 0, "info" => "获取unionid失败");
            }
        }
        $this->in['reginfo']['headimgurl'] = $this->in['reginfo']['avatarUrl'];
        $this->in['reginfo']['nickname'] = $this->in['reginfo']['nickName'];
        $this->in['reginfo']['sex'] = $this->in['reginfo']['gender'];
        $this->in['reginfo']['openid'] = $data['openid'];
        $pid = $this->in['pid'] ? $this->in['pid'] : 0;
        $verfiy_id = $is_unionid == 1 ? $this->in['reginfo']['unionid'] : $data['openid'];
        $wx = new \app\common\model\Weixin();
        $res = $wx->reg($verfiy_id, $pid, $this->in['reginfo']);
        if (!$res['user_id']) {
            return array('status' => 0, "info" => "登录失败：" . $data['errmsg']);
        }

        $data['uinfo'] = $this->model->getUserInfo($res['user_id']);
        $token = $this->model->add_token($res['user_id']);
        $data['token'] = $token;
        //验证手机号码
        if (C('xcx_check_tel') == 1) {
            if (!$data['uinfo']['tel']) {
                $data['is_tel'] = 1;
            }
        }
        return array('status' => 1, 'data' => $data);
    }

    //静默登陆
    public function def_login() {
        $code = $this->request['code'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->apppwd}&js_code={$code}&grant_type=authorization_code";
        $data = file_get_contents($url);
        $data = json_decode($data, TRUE);
        if (!$data['openid']) {
            return array('status' => 0, "info" => $data['errmsg']);
        }
        $pid = $this->in['pid'] ? $this->in['pid'] : 0;
        $wx = new \app\common\model\Weixin();
        $res = $wx->reg($data['openid'], $pid, array());
        if (!$res['user_id']) {
            return array('status' => 0, "info" => "登录失败");
        }
        $data['uinfo'] = $this->model->getUserInfo($res['user_id']);
        $token = $this->model->add_token($res['user_id']);
        $data['token'] = $token;
        return array('status' => 1, 'data' => $data);
    }

    //获取手机号
    public function get_tel() {
        include_once INCLUDE_PATH . 'weixin/wxBizDataCrypt.php';
        $session_key = cache("seesion_{$this->uinfo['openid']}");
        $pc = new \WXBizDataCrypt($this->appid, $session_key);
        $errCode = $pc->decryptData($this->request['encryptedData'], $this->request['iv'], $data2);
        if ($errCode == 0) {
            $data2 = json_decode($data2, true);
            if ($this->uinfo['id'] && $this->in['flag'] == 'up') {
                $this->model->where([
                        ['id', '<>', $this->uinfo['id']],
                        ['tel', '=', $data2['phoneNumber']],
                ])->update(array('tel' => ''));
                $this->model->where(array('id' => $this->uinfo['id']))->update(array('tel' => $data2['phoneNumber']));
                $this->model->clearCache($this->uinfo['id']);
            }
            $this->data['tel'] = $data2['phoneNumber'];
            return array('status' => 1, 'data' => $this->data);
        } else {
            return array('status' => 0, "info" => "获取失败，请稍后");
        }
    }

    //获取session
    public function get_seesion() {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->apppwd}&js_code={$this->request['code']}&grant_type=authorization_code";
        $data = file_get_contents($url);
        $data = json_decode($data, TRUE);
        if (!$data['openid']) {
            json_msg(0, lang('e'));
        } else {
            cache("seesion_{$data['openid']}", $data['session_key'], 7000);
            $ac = $this->in['ac'];
            if ($ac) {
                $userLogic = new app\mallapi\logic\User();

                $userLogic->config(array(
                    'data' => $this->data,
                    'request' => $this->request,
                    'in' => $this->in,
                    'uinfo' => $this->uinfo,
                ));
                $res = $userLogic->$ac();
                $this->ajaxReturn($res);
            }
            return array('status' => 1, 'data' => $data);
        }
    }

    /*
     * 退出
     */
    public function logout() {
        Db::name('user_token')->where(array('user_id' => $this->uinfo['id']))->delete();
        return array('status' => 1, 'info' => lang('s'));
    }

    public function jingcaikaijiang(){
        $this->model -> kaijiang();
    }

}
