<?php

namespace app\masterapi\logic;

use think\App;
use think\facade\Db;

class Login
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;
    public $appid;
    public $apppwd;
    public $xcx_bind_type;

    public function __construct()
    {   

        $this->model = new \app\common\model\SuoMaster();
        $this->appid = C('appid');
        $this->apppwd = C('apppwd');
        //注册验证方式
        $this->xcx_bind_type = C('xcx_bind_type');

    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }


    public function load_index()
    {
        
        $this->data['openid'] = '';
        $this->data['unionid'] = '';
        if ($this->request['code']) {
            $res = $this->get_seesion();
            if ($res['status'] == 1) {
                $this->data['openid'] = $res['data']['openid'];
                $this->data['unionid'] = $res['data']['unionid'];
            }
        }
        return array('status' => 1, 'data' => $this->data);
    }

    public function get_seesion()
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->apppwd}&js_code={$this->request['code']}&grant_type=authorization_code";
        $data = file_get_contents($url);
        $data = json_decode($data, TRUE);
        if (!$data['openid']) {
            return ['status' => 0, 'info' => lang('e')];
        } else {
            cache("seesion_{$data['openid']}", $data['session_key'], 7000);
            return array('status' => 1, 'data' => $data);
        }
    }
    
    /**
     * 使用密码登陆
     * @return type
     */

    /**
     * showdoc
     * @catalog 用户
     * @title 账号密码登录
     * @description 登录页
     * @method post
     * @url {{host}}/comapi/Login/load_index
     * @return {"status":1,"data":{"cart_num":0,"site_status":"0","logo":"","xcx_check_tel":"0","xcx_bind_tel":null,"wapurl":"","step":1,"openid":"","unionid":""}}
     * @remark 初始化登录页
     * @number 99
     */

    public function pwd_login()
    {
//        echo 1;
        if (!$this->in['tel'] || !$this->in['pwd']) {
            return ['status' => 0, 'info' => '缺少参数'];
        }
//        sqlListen();
        $type = $this->in['type']?$this->in['type']:0;
//        sqlListen();
        $uinfo = $this->model->login($this->in['tel'], $this->in['pwd'],$type);
        if (!$uinfo) {
            return array('status' => 0, "info" => "账户或密码错误");
        }
        if ($this->in['openid'] && !$uinfo['openid']) {
            $this->model->removeOption()->where([
                ['openid', '=', $this->in['openid']]
            ])->save(['openid' => '']);
            $this->model->removeOption()->where([
                ['id', '=', $uinfo['id']]
            ])->save(['openid' => $this->in['openid']]);
            $this->model->clearCache($uinfo['id']);
        }
        $token = $this->model->add_token($uinfo['id']);
        $data['uinfo'] = $this->model->getUserInfo($uinfo['id'],false);
        $data = array();
        $data['token'] = $token;
        return array('status' => 1, 'info' => '登录成功', 'data' => $data);
    }

    


    //更新头像,昵称
    public function upinfo()
    {
        $save['headimgurl'] = $this->in['reginfo']['avatarUrl'];
        $save['nickname'] = $this->in['reginfo']['nickName'];
        $save['username'] = $save['nickname'];
        $this->model->where(['id' => $this->uinfo['id']])->save($save);
        $this->model->clearCache($this->uinfo['id']);
        return ['status' => 1, 'info' => lang('s')];
    }


   

    //验发送证码
    public function send_verify()
    {

        $time_limit_cache = "send_verify_time_{$this->in['tel']}";
        $time_num_cache = "send_verify_num_{$this->in['tel']}";
        $reg_cache = "send_verify_{$this->in['tel']}";
        $is = cache($time_limit_cache);

      //测试
       cache($time_limit_cache, 1, 60);
       cache($time_num_cache, 10, 60 * 30);
       cache($reg_cache, 1234, 60 * 30);
       $data['code'] = 1234;
       return array('status' => 1, 'data' => $data);

        if ($is) {
            return array('status' => 0, "info" => "一分钟内只能发一次");
        }
        if (!$this->in['tel']) {
            return array('status' => 0, "info" => "请输入手机号");
        }
        if (!preg_match("/^1[0-9]{10}$/", $this->in['tel'])) {
            return array('status' => 0, "info" => "手机号码格式有误");
        }
        $user_id = $this->model->where([
            ['tel', '=', $this->in['tel']],
        ])->value('id');
       
       
        
        $res = (new \app\common\lib\Util())->sms($this->in['tel']);
        if ($res['status'] == 1) {
            cache($time_limit_cache, 1, 60);
            cache($time_num_cache, 10, 60 * 30);
            cache($reg_cache, $res['code'], 60 * 30);
            return array('status' => 1, 'info' => '验证码已发送');
        } else {
            return array('status' => 0, 'info' => $res['info']);
        }
    }

    //验证验证码
    private function check_verify()
    {
        $reg_cache = "send_verify_{$this->in['tel']}";
        $is = cache($reg_cache);
      
        $time_num_cache = "send_verify_num_{$this->in['tel']}";
        $num = cache($time_num_cache);
        $num = $num - 1;
        cache($time_num_cache, $num, 60 * 30);
        if (!$is || $is != $this->in['verify'] || !$this->in['tel']) {
            return array('status' => 0, "info" => "验证码错误");
        }
        if ($num <= 0) {
            return array('status' => 0, "info" => "验证码已失效");
        }
        return ['status' => 1];
    }

    //销毁验证码
    private function bad_verify()
    {
        $reg_cache = "send_verify_{$this->in['tel']}";
        cache($reg_cache, null);
    }

    //验证码登录
    public function verify_login()
    {
        $r = $this->check_verify();
        if ($r['status'] == 0) {
            return $r;
        }
        $user_id = $this->model->removeOption()->where([
            ['tel', '=', $this->in['tel']],
            ['status', '=', 1],
        ])->value('id');
        
        if (!$user_id) {
             return ['status' => 0, 'info' => '用户不存在'];
        }
        $token = $this->model->add_token($user_id);
        $data = array();
        $data['token'] = $token;
        $this->bad_verify();
        return array('status' => 1, 'info' => '登录成功', 'data' => $data);
    }


    //APP端的微信登录，未绑定的手机的会跳到手机注册页面
    public function app_wxlogin()
    {   
        $unionid = $this->in['unionid'];
        if (!$unionid) {
            return ['status' => 0, 'info' => 'unionid不存在'];
        }
        if ($this->in['is_bind'] == 1 && $this->uinfo['id']) {
            $this->model->where(array('unionid' => $unionid))->update(['openid' => '', 'unionid' => '']);
            $save = ['headimgurl' => $this->in['headimgurl'],
                'nickname' => $this->in['nickname'],
                'sex' => $this->in['sex'],
                'unionid' => $this->in['unionid']
            ];
            $this->model->where('id' ,$this->uinfo['id'])->update($save);
            $this->model->clearCache($this->uinfo['id']);
            return ['status' => 1, 'info' => lang('s')];
        }
        $user_id = $this->model->where(array('unionid' => $unionid))->value('id');
        if ($user_id) {
            $token = $this->model->add_token($user_id);
            return ['status' => 1, 'data' => [
                'is_reg' => 1,
                'token' => $token
            ]];
        } else {
            return ['status' => 1, 'data' => [
                'is_reg' => 0
            ]];
        }
    }

    //APP端的微信登录，未绑定的手机的会跳到手机注册页面
    public function app_wxlogin_old()
    {
        $openid = $this->in['openid'];
        if (!$openid) {
            return ['status' => 0, 'info' => 'openid不存在'];
        }
        if ($this->in['is_bind'] == 1 && $this->uinfo['id']) {
            $this->model->where(array('openid' => $openid))->save(array('openid' => '', 'unionid' => ''));
            $save = array('openid' => $openid,
                'headimgurl' => $this->in['headimgurl'],
                'nickname' => $this->in['nickname'],
                'sex' => $this->in['sex'],
                'unionid' => $this->in['unionid']
            );
            $this->model->where(array('id' => $this->uinfo['id']))->save($save);
            $this->model->clearCache($this->uinfo['id']);
            return ['status' => 1, 'info' => lang('s')];
        }
        $user_id = $this->model->where(array('openid' => $openid))->value('id');
        if ($user_id) {
            $token = $this->model->add_token($user_id);
            return ['status' => 1, 'data' => [
                'is_reg' => 1,
                'token' => $token
            ]];
        } else {
            return ['status' => 1, 'data' => [
                'is_reg' => 0
            ]];
        }
    }

    //手机注册
    public function tel_reg()
    {
        $r = $this->check_verify();
        if ($r['status'] == 0) {
            return $r;
        }
        $Common = new \app\comapi\controller\Rule(app());
        $Common->check('tel_reg');
        if ($this->in['pwd'] != $this->in['repwd']) {
            return ['status' => 0, 'info' => '确认密码不一致'];
        }
        $user_id = $this->model->where([
            ['username', '=', $this->in['tel']],
        ])->value('id');
        if ($user_id) {
            return array('status' => 0, 'info' => '该用户已注册');
        }
        $pid = 0;
        if ($this->in['code']) {
            $code = $this->in['code'];
            $pid = $this->model->where(['id' => $code])->value('id');
            if (!$pid) {
                return ['status' => 0, 'info' => '该邀请码填写错误'];
            }
        }
        $res = $this->model->reg($this->in['tel'], 'tel', ['pwd' => $this->in['pwd'], 'pid' => $pid, 'openid' => $this->in['openid'], 'nickname' => $this->in['tel']]);
        if ($res['status'] != 1) {
            return ['status' => 0, 'info' => $res['info']];
        }
        $token = $this->model->add_token($res['data']['user_id']);
        $data = array();
        $data['token'] = $token;
        $this->bad_verify();
        return array('status' => 1, 'info' => '注册成功', 'data' => $data);
    }



    //忘记密码
    public function forget()
    {
        $r = $this->check_verify();
        if ($r['status'] == 0) {
            return $r;
        }
        $Common = new \app\masterapi\controller\Rule(app());
        $Common->check('forget');
        if ($this->in['pwd'] != $this->in['repwd']) {
            return ['status' => 0, 'info' => '确认密码不一致'];
        }
        $type = intval($this->in['type']);
        if(!$type){
            return ['status' => 0, 'info' => '账号类型有误'];
        }
        $user_id = $this->model->where([
            ['tel', '=', $this->in['tel']],
            ['type', '=', $type],
        ])->value('id');
        if (!$user_id) {
            return array('status' => 0, 'info' => '该用户不存在');
        }
        $this->model->where(['id' => $user_id])->save(['pwd' => xf_md5($this->in['pwd'])]);
        return array('status' => 1, 'info' => lang('s'));
    }


    //APP解绑微信
    public function app_wxunbind()
    {
        $this->model->where(array('id' => $this->uinfo['id']))->save(array('openid' => '', 'unionid' => ''));
        $this->model->clearCache($this->uinfo['id']);
        return array('status' => 1, 'info' => lang('s'));
    }


    /**
     * showdoc
     * @catalog 用户
     * @title 退出登录
     * @description 退出登录
     * @method post
     * @url /comapi/Login/logout
     * @return {"status":1,"info":'成功'}
     * @remark
     * @number 99
     */

    public function logout()
    {
        Db::name('suo_master_token')->where(array('user_id' => $this->uinfo['id']))->delete();
        return array('status' => 1, 'info' => lang('s'));
    }


    public function save_info(){
        $Common = new \app\comapi\controller\Rule(app());
        $Common->check('save_info');
        $this->in['info_status'] = 1;
        $this->model->where(array('id' => $this->uinfo['id']))->field('headimgurl,nickname,tel,info_status')->save($this->in);
        $this->model->clearCache($this->uinfo['id']);
        return array('status' => 1, 'info' => lang('s'));
    }

}
