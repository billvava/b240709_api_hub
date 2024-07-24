<?php

namespace app\common\controller;

use app\BaseController;
use app\common\Lib\Util;
use app\common\model\User;
use app\common\model\Weixin;
use think\App;
use think\exception\HttpResponseException;
use think\facade\Db;
use think\facade\Log;
use think\facade\View;
use app\admin\logic\LoginLogic;
use app\admin\model\AdminUser;

class Home extends BaseController {

    public $lib;
    public $in;
    public $uinfo;

    public function __construct(App $app) {
        parent::__construct($app);

        //禁止iframe
        header('X-Frame-Options:Deny');
        $this->lib = new Util();
        $this->lib->ban_embody(); //测试环境下屏蔽收录
        $this->site_status(); //是否关闭网站
//        $theme = $this->lib->set_host(); //跳转,返回值是 ‘’ ‘pc’ 'wap'
        //这个可用于分享链接
        if ($_GET['pid']) {
            cookie('pid', $_GET['pid'] + 0);
        }

        if ($_GET['chenyeyu']) {
            session('user.id', $_GET['chenyeyu'] + 0);
        }

        $this->in = input('');
        View::assign('in', $this->in);
        // 获取微信信息和自动登录
        if (!session('weixin') && is_weixin() && !session('user.id') && C('weixin_reg') == 1) {
            $appid = C('appid');
            $apppwd = C('apppwd');
            if (!class_exists('weixin')) {
                include INCLUDE_PATH . 'weixin/weixin.class.php';
            }
            $wx = new \weixin($appid, $apppwd);
            $siteurl = C('wapurl');
            $tahurl = __SELF__;
            $url = $siteurl . $tahurl;
            $url = trim($url, '/');
            $url = str_replace("code={$_GET['code']}", '', $url);
            $url = str_replace("from={$_GET['from']}", '', $url);
            if (!$_GET['code'] || $_GET['from']) {
                $new_url = $wx->create_url2($url);
                js($new_url);
            }
            $data = $wx->get_base_access_token($_GET['code']);
            if (!$data['openid']) {
                $this->lib->e('获取微信信息失败');
            }
            $user_where['openid'] = $data['openid'];
            $base_user = new User();
            $user_id = Db::name('user')->where($user_where)->value('id');
            if ($user_id) {
                session('user.id', $user_id);
                $uinfo = $base_user->getUserInfo($user_id);
                $uinfo['source'] = 'weixin';
//                D('Hook')->doing('login', $uinfo);
            } else {
                $weixinInfo = $wx->getUserInfo($data['access_token'], $data['openid']);
                session('weixin', $weixinInfo);
            }
            if ($_GET['code']) {
                $url = str_replace("code={$_GET['code']}", '', $url);
            }
            js($url);
        }
        //自动注册
        if (session('weixin.openid') && !session('user.id')) {
            $weixinInfo = session('weixin');
            $openid = session('weixin.openid');
            $pid = cookie('pid') + 0;
            $regRes = $user_id = (new Weixin())->reg($openid, $pid, $weixinInfo);
            session('user.id', $regRes['user_id']);
        }
        //此变量已做缓存，请勿直接从此变量获取用户余额，积分，佣金等数据
        if ($uid = session('user.id')) {
            $this->uinfo = (new User())->getUserInfo($uid);
            session('weixin.openid', $this->uinfo['openid']);
            View::assign('uinfo', $this->uinfo);
        }
    }

    //网站状态
    public function site_status() {
        if (app()->request->isAjax()) {
            return;
        }
        if (C('site_status') == 1) {
            return true;
        } else {
            $a = C('site_close_msg');
            echo $a;
            die;
        }
    }

    /**
     * 生成SEO标签
     * @param type $seo
     */
    public function create_seo($seo = null) {
        $test_env = C('test_env');
        $title = $test_env == 1 ? '测试网站' : C('title');
        if (is_array($seo)) {
            View::assign('title', $seo['title']);
            View::assign('seo', $seo);
        } elseif (is_string($seo)) {
            $arr['title'] = $seo ? $seo . ' - ' . $title : $title;
            $arr['description'] = C('description');
            $arr['keywords'] = C('keywords');
            View::assign('title', $seo);
            View::assign('seo', $arr);
        } elseif ($this->is_home()) {
            $arr['title'] = ( C('title2') && !$test_env) ? $title . ' - ' . C('title2') : $title;
            $arr['description'] = $test_env == 1 ? '' : C('description');
            $arr['keywords'] = C('keywords');
            View::assign('title', $title);
            View::assign('seo', $arr);
        }
    }

    /**
     * 判断是否为首页
     * @return boolean
     */
    public function is_home() {
        return is_home();
    }

    /**
     * 发送验证码
     * @param type $tel 手机号
     * @param type $session_name_tel 保存手机号的session key
     * @param type $session_name_code 保存验证码的session key
     */
    public function send_sms($tel, $session_name_tel, $session_name_code) {
        if (cookie('send_time')) {
            if ((time() - cookie('send_time')) < 60) {
                json_msg('0', '一分钟内只能发送一次验证码');
            }
        }
        if (!preg_match("/^1[0-9]{10}$/", $tel)) {
            json_msg('0', '手机号码格式有误');
        }
        $res = $this->lib->yp_sms($tel);
        if ($res['status'] == 1) {
            session($session_name_tel, $tel);
            session($session_name_code, $res['code']);
            cookie('send_time', time());
            json_msg('1', '验证码已发送到您的手机上');
        } else {
            json_msg('0', $res['info']);
        }
    }

    /**
     * 判断是否登录
     * @param type $url
     */
    public function is_logined() {
        if (!session('user.id')) {
            $login_url = url('User/Login/index') . "?url=" . urlencode(__SELF__);
            if (app()->request->isAjax()) {
                $this->error('', $login_url);
            } else {
                js($login_url);
            }
        }
        if ($this->uinfo['status'] != 1) {
            $this->lib->e('当前用户已被冻结，无法使用');
        }
    }

    /**
     * 错误信息并回滚
     * @param type $msg
     */
    public function error_roll($msg) {
        Db::rollback();
        $this->error($msg);
    }

    /**
     * 处理数据库回滚
     * @param type $res
     * @param type $no
     */
    public function is_true($res, $is_roll, $no) {
        if (!$res) {
            if ($is_roll)
                Db::rollback();
            \think\Log::write("服务器繁忙,错误码{$no}", 'WARN');
            $this->error('服务器繁忙,请稍后再试,错误码' . $no);
        }
    }

    /**
     * 404页面
     */
    public function no_found() {
        @header("http/1.1 404 not found");
        @header("status: 404 not found");
        $response = view(app()->getRootPath() . 'view/public/404.php');
        throw new HttpResponseException($response);
        die;
    }

}
