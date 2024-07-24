<?php

namespace app\comapi\logic;

use think\App;
use think\facade\Db;
use app\home\model\O;

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
        $this->oModel = new O();
        $this->model = new \app\user\model\User();
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

    public function item()
    {   
        $cate_id = Db::name('help_cate') -> where('token',$this->in['id']) -> value('id');
        $this->data['info'] =  Db::name('help_item') -> where('cate_id',$cate_id) -> find();
        $this->data['info']['content'] = contentHtml($this->data['info']['content']);
        return ['status' => 1, 'data' => $this->data];
    }
    public function pwd_login()
    {
        if (!$this->in['username'] || !$this->in['pwd']) {
            return ['status' => 0, 'info' => '缺少参数'];
        }
        $uinfo = $this->model->login($this->in['username'], $this->in['pwd']);
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
        $data['uinfo'] = $this->model->getUserInfo($uinfo['id']);
        $data = array();
        $data['token'] = $token;
        return array('status' => 1, 'info' => '登录成功', 'data' => $data);
    }

    /**
     * 小程序登陆页面显示
     * @return type
     */
    /**
     * showdoc
     * @catalog 用户
     * @title 小程序登录信息
     * @description 登录页
     * @method post
     * @url {{host}}/comapi/Login/load_index
     * @return {"status":1,"data":{"cart_num":0,"site_status":"0","logo":"","xcx_check_tel":"0","xcx_bind_tel":null,"wapurl":"","step":1,"openid":"","unionid":""}}
     * @remark 初始化登录页
     * @number 99
     */
    public function load_index()
    {
        $this->data['logo'] = C('xcx_logo');
        $this->data['xcx_check_tel'] = C('xcx_check_tel');
        $this->data['xcx_bind_tel'] = C('xcx_bind_tel');
        $this->data['wapurl'] = C('wapurl');
        $this->data['step'] = 3;
        if ($this->xcx_bind_type == 1) {
            $this->data['step'] = 1;
        }
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

    //为了以前的路由
    public function index()
    {
        return $this->wxid_login();
    }

    /**
     * openid ,unionid 登录注册
     * @return type
     */
    /**
     * showdoc
     * @catalog 用户
     * @title 登录注册
     * @description 登录注册
     * @method post
     * @param code 必选 string 登录code
     * @param reginfo 必选 array 微信授权信息
     * @param pid 必选 int 推荐人id
     * @url {{host}}/comapi/Login/index
     * @return {"status":1,"data":{"token":"xxxxx"}}
     * @return_param token string 用户登录 token
     * @remark
     * @number 99
     */
    public function wxid_login()
    {
        if (!in_array($this->xcx_bind_type, [2, 3])) {
            return ['status' => 0, 'info' => '未开启该功能'];
        }
        $code = $this->request['code'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->apppwd}&js_code={$code}&grant_type=authorization_code";
        $data = file_get_contents($url);
        $data = json_decode($data, TRUE);
        if (!$data['openid']) {
            return array('status' => 0, "info" => "登陆失败：" . $data['errmsg']);
        }
        cache("seesion_{$data['openid']}", $data['session_key']);
        $this->in['reginfo']['unionid'] = isset($data['unionId']) ? $data['unionId'] : '';
        $this->in['reginfo']['headimgurl'] = $this->in['reginfo']['avatarUrl'];
        $this->in['reginfo']['nickname'] = $this->in['reginfo']['nickName'];
        $this->in['reginfo']['openid'] = $data['openid'];
        $pid = $this->in['pid'] ? $this->in['pid'] : 0;
        $this->in['reginfo']['pid'] = $pid;
        $reg_id = $data['openid'];
        $reg_type = 'openid';
        if ($this->xcx_bind_type == 3) {
            $reg_id = $data['unionId'];
            $reg_type = 'unionid';
        }

        $res = $this->model->reg($reg_id, $reg_type, $this->in['reginfo']);
        if ($res['status'] != 1) {
            return array('status' => 0, "info" => $res['info']);
        }

        $data['uinfo'] = $this->model->getUserInfo($res['data']['user_id']);
        $token = $this->model->add_token($res['data']['user_id']);
        $data['token'] = $token;
        //验证手机号码
        $data['step'] = 0;
        if (C('xcx_check_tel') == 1) {
            if (!$data['uinfo']['tel']) {
                $data['step'] = 4;
            }
        }
        return array('status' => 1, 'data' => $data);
    }

    /**
     * 公众号授权登录
     * @return array
     */
    public function login_h5()
    {

        $appid = C('appid');
        $apppwd = C('apppwd');
        if (!class_exists('weixin')) {
            include INCLUDE_PATH . 'weixin/weixin.class.php';
        }
        $wx = new \weixin($appid, $apppwd);

        $data = $wx->get_base_access_token($this->in['code']);
        if (!$data['openid']) {
            return array('status' => 0, 'data' => '获取微信信息失败');
        }
        $weixinInfo = $wx->getUserInfo($data['access_token'], $data['openid']);
        $pid = $this->in['pid'] ? $this->in['pid'] : 0;

        $weixinInfo['openid'] = $pid;
        $res = $this->model->reg($data['openid'], 'openid', $weixinInfo);
        $user_id = $res['data']['user_id'];
        if (!$user_id) {
            return array('status' => 0, "info" => $res['info']);
        }
        $data['uinfo'] = $this->model->getUserInfo($user_id);
        $token = $this->model->add_token($user_id);
        $data['token'] = $token;
        return array('status' => 1, 'data' => $data);
    }

    //静默登陆

    /**
     * showdoc
     * @catalog 用户
     * @title 静默登陆
     * @description 静默登陆
     * @method post
     * @param code 必选 string 登录code
     * @param pid 必选 int 推荐人id
     * @url {{host}}/comapi/Login/def_login
     * @return {"status":1,"data":{"token":"xxxxx"}}
     * @return_param token string 用户登录 token
     * @remark
     * @number 99
     */
    public function def_login()
    {
        $code = $this->request['code'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->apppwd}&js_code={$code}&grant_type=authorization_code";
        $data = file_get_contents($url);
        $data = json_decode($data, TRUE);
        if (!$data['openid']) {
            return array('status' => 0, "info" => $data['errmsg']);
        }
        cache("seesion_{$data['openid']}",$data['session_key']);
        $pid = $this->in['pid'] ? $this->in['pid'] : 0;
        $reg_id = $data['openid'];
        $reg_type = 'openid';
        if ($this->xcx_bind_type == 3) {
            $reg_id = $data['unionId'];
            $reg_type = 'unionid';
        }
        $res = $this->model->reg($reg_id, $reg_type, ['pid' => $pid]);
        if ($res['status'] != 1) {
            return array('status' => 0, "info" => $res['info']);
        }
        $user_id = $res['data']['user_id'];
        $data['uinfo'] = $this->model->getUserInfo($user_id);
        $token = $this->model->add_token($user_id);
        $data['token'] = $token;
        $this -> updateUserReferee($user_id,$pid);
        return array('status' => 1, 'data' => $data);
    }


    //更新用户推荐路径
    public function updateUserReferee($user_id,$pid){
        //推荐人信息
        $field = 'id,referee_path';
        $user = $this->model->where('id',$pid)->field($field)->find();
        $update_data = [];
        $referee_path = $user['referee_path'].$user['id'].',';
        $update_data['referee_path'] = $referee_path;
        $this->model-> where('id',$user_id) -> update($update_data);
    }

    /**
     * showdoc
     * @catalog 用户
     * @title 微信手机登录注册
     * @description 微信手机登录注册
     * @method post
     * @param encryptedData 必选 string encryptedData
     * @param iv 必选 string iv
     * @param openid 必选 string openid
     * @param pid 否 int 推荐人id
     * @url {{host}}/comapi/Login/wx_tel
     * @return {"status":1,"data":{"token":"xxxxx"}}
     * @return_param token string 用户登录 token
     * @remark  先调用获取session 接口
     * @number 99
     */
    public function wx_tel()
    {
        if ($this->xcx_bind_type != 1) {
            return ['status' => 0, 'info' => '未开启该功能'];
        }
        include_once INCLUDE_PATH . 'weixin/wxBizDataCrypt.php';
        $session_key = cache("seesion_{$this->request['openid']}");
        if (!$session_key) {
            return ['status' => 0, 'info' => '登录失败，请退出小程序再重试'];
        }
        $pc = new \WXBizDataCrypt($this->appid, $session_key);
        $errCode = $pc->decryptData($this->request['encryptedData'], $this->request['iv'], $data2);
        if ($errCode == 0) {
            $data2 = json_decode($data2, true);
            if (!$data2['phoneNumber']) {
                return ['status' => 0, 'info' => '获取失败'];
            }
            $tel = $data2['phoneNumber'];
            $pid = $this->in['pid'] + 0;
            $res = $this->model->reg($tel, 'tel', ['pid' => $pid, 'nickname' => '', 'openid' => $this->request['openid']]);
            if ($res['status'] != 1) {
                return ['status' => 0, 'info' => $res['info']];
            }
            $token = $this->model->add_token($res['data']['user_id']);
            $uinfo = $this->model->getUserInfo($res['data']['user_id']);
            $data = array();
            $data['step'] = 0;
            if ($uinfo['nickname'] == '') {
                $data['step'] = 2;
            }
            $data['token'] = $token;
            return array('status' => 1, 'info' => lang('s'), 'data' => $data);
        } else {
            return array('status' => 0, "info" => "获取失败，请稍后");
        }
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


    /**
     * showdoc
     * @catalog 用户
     * @title 小程序获取手机号
     * @description 小程序获取手机号
     * @method post
     * @param encryptedData 必选 string encryptedData
     * @param iv 必选 string iv
     * @url {{host}}/comapi/Login/get_tel
     * @return {"status":1,"data":{"tel":"xxxxx"}}
     * @return_param tel string 手机号码
     * @remark
     * @number 99
     */
    public function get_tel()
    {
        include_once INCLUDE_PATH . 'weixin/wxBizDataCrypt.php';
        $session_key = cache("seesion_{$this->uinfo['openid']}");
        if (!$session_key) {
            return ['status' => 0, 'info' => '登录失败，请退出小程序再重试'];
        }
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



    /**
     * showdoc
     * @catalog 用户
     * @title 获取session
     * @description 获取session
     * @method post
     * @param code 必选 string code
     * @url {{host}}/comapi/Login/get_seesion
     * @return {"status":1,"data":{"token":"xxxxx"}}
     * @return_param session_key string session_key
     * @return_param openid string openid
     * @remark
     * @number 99
     */

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

    //验发送证码
    public function send_verify()
    {


        $time_limit_cache = "send_verify_time_{$this->in['tel']}";
        $time_num_cache = "send_verify_num_{$this->in['tel']}";
        $reg_cache = "send_verify_{$this->in['tel']}";
        $is = cache($time_limit_cache);
//
//测试
//        cache($time_limit_cache, 1, 60);
//        cache($time_num_cache, 10, 60 * 30);
//        cache($reg_cache, 1234, 60 * 30);
//        return array('status' => 1, 'info' => '验证码已发送');

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
            ['username', '=', $this->in['tel']],
        ])->value('id');
        if (!in_array($this->in['type'], ['forget', 'reg'])) {
            return ['status' => 0, 'info' => 'type不对'];
        }
        if ($this->in['type'] == 'forget') {
            if (!$user_id) {
                return array('status' => 0, 'info' => '该用户不存在');
            }
        }
        if ($this->in['type'] == 'reg') {
            if ($user_id) {
                return array('status' => 0, 'info' => '该用户已注册');
            }
        }
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
            ['username', '=', $this->in['tel']],
            ['status', '=', 1],
        ])->value('id');
        //不存在就注册1个
        if (!$user_id) {
            $res = $this->model->reg($this->in['tel'], 'tel', [
                'pwd' => $this->in['pwd'],
                'pid' => $this->in['pid'] + 0,
            ]);
            if ($res['status'] != 1) {
                return ['status' => 0, 'info' => $res['info']];
            }
            $user_id = $res['data']['user_id'];
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
        $Common = new \app\comapi\controller\Rule(app());
        $Common->check('forget');
        if ($this->in['pwd'] != $this->in['repwd']) {
            return ['status' => 0, 'info' => '确认密码不一致'];
        }
        $user_id = $this->model->where([
            ['username', '=', $this->in['tel']],
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
        Db::name('user_token')->where(array('user_id' => $this->uinfo['id']))->delete();
        return array('status' => 1, 'info' => lang('s'));
    }


    public function getShop(){
        $map = [];
        $map[] = ['type','=',4];
        $map[] = ['state','=',1];
        $list = Db::name('shop') -> where($map) -> select();
        $oh=new O();
        $data['city_json']=(new O())->get_json();
        $data['list'] = $list;
        return array('status' => 1, 'data' => $data);
    }

    public function save_info(){
        $Common = new \app\comapi\controller\Rule(app());
        $Common->check('save_info');
        $this->in['info_status'] = 1;

        $this->model->where(array('id' => $this->uinfo['id']))->field('headimgurl,nickname,tel,info_status,province,city,country,shop_id')->save($this->in);

        $this->model->clearCache($this->uinfo['id']);
        return array('status' => 1, 'info' => lang('s'));
    }

}
