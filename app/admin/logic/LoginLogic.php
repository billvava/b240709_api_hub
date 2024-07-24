<?php
declare (strict_types = 1);

namespace app\admin\logic;
use app\admin\model\AdminNav;
use app\admin\model\AdminRole;
use app\admin\model\AdminUser;
use app\admin\model\Config;
use think\facade\Db;


/**
 * @mixin think\Model
 */
class LoginLogic
{
    private $model;

    const LOGIN_AUTO_TOKEN = 'login_auto_token';
    const LOGIN_INFO = 'admin';
    const LOGIN_DEV = 'admin_developer';

    public function __construct() {
        $this->model = new AdminUser;
    }

    //关闭后台登录
    public function loginLock() {
        $login_error_sys_count = C('my.login_error_sys_count');
        $loginNum = $this->model->getLoginErrorCount();
        $config = new Config();
        if ($login_error_sys_count <= $loginNum) {
            $config::update(['val'=>0], ['field' => 'login_status']);
            $config->clear();
        }
    }

    //记住自动登录
    public function addAutoLogin($admin_id) {
        if ($admin_id <= 0) {
            return false;
        }

        $auto_login = app()->request->param('auto_login');
        if ($auto_login == 1) {

            $token = md5(uniqid(strval(rand()), TRUE));
            $login_auto_day = C('login_auto_day');
            cookie(self::LOGIN_AUTO_TOKEN, $token, 3600 * 24 * $login_auto_day);
            $add = array(
                'token' => $token,
                'timeout' => date('Y-m-d H:i:s', strtotime("+{$login_auto_day} day")),
                'admin_id' => $admin_id,
                'create_time' => date('Y-m-d H:i:s')
            );
            Db::name('admin_login_token')->insert($add);
        } else {
            $login_auto_token = cookie(self::LOGIN_AUTO_TOKEN);
            if ($login_auto_token) {
                Db::name('admin_login_token')->where(array('token' => $login_auto_token))->delete();
            }
            cookie(self::LOGIN_AUTO_TOKEN, null);
        }
    }

    //自动登录
    public function autoLogin() {
        if ($this->isLogin()) {
            return true;
        }
        $login_auto_token = cookie(self::LOGIN_AUTO_TOKEN);
        if ($login_auto_token) {
            $where[]=['token','=',$login_auto_token];
            $where[]=['timeout','>',date('Y-m-d H:i:s')];
            $info = Db::name('admin_login_token')
                ->where($where)->order('id desc')->find();

            if ($info) {
                return $this->login($info['admin_id']);
            }
        }
        return false;
    }

    //记住登录信息
    public function login($admin_id) {
        $info = $this->model->getInfo($admin_id);
        $this->model->where(array('id' => $admin_id))->save(['login_error_num'=>0]);
        if (!$info) {
            return false;
        }
        session(self::LOGIN_INFO, $info);
        $authInfo = AdminRole::getAuth($info['role_id']);
        session(self::LOGIN_INFO . ".auth", $authInfo);
        return true;
    }

    //设为开发者
    public function setDev() {
        session(self::LOGIN_DEV, 1);
    }

    //登录
    public function logout() {
        session(self::LOGIN_DEV, null);
        session(self::LOGIN_INFO, null);
        cookie(self::LOGIN_AUTO_TOKEN, null);
    }

    //判断是否登录
    public function isLogin() {
        $info = session(self::LOGIN_INFO);
        return $info ? true : false;
    }

    //判断是否开发者
    public function isDev() {
        $info = session(self::LOGIN_DEV);
        return $info ? true : false;
    }

    //检查权限
//    public function checkAuth() {
//        if ($this->isDev()) {
//            return true;
//        }
//        $authInfo = session(self::LOGIN_INFO . ".auth");
//        $module_name = strtolower(C('my.admin_app'));
//        $controller_name = strtolower(request()->controller());
//        $action_name = strtolower(request()->action());
//        if (in_array($module_name, array_keys($authInfo))) {
//            if (in_array($controller_name, array_keys($authInfo[$module_name]))) {
//                if (in_array($action_name, $authInfo[$module_name][$controller_name])) {
//                    return true;
//                }
//            }
//        }
//        return false;
//    }


    //检查权限
    public function checkAuth() {
        //排除开发者
        if ($this->isDev()) {
            return true;
        }

        $appname=App('http')->getName();
        if($appname=='admin'){
            $module_name=strtolower(C('my.admin_app'));
        }else{
            $module_name=strtolower($appname);
        }
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());
        //当前节点
        $index = $module_name . '/' . $controller_name . '/' . $action_name;
        //获取所有需要判断的权限
        $AdminNav = new AdminNav();
        $AllAuthNode = $AdminNav->getAllAuthNode();
        foreach ($AllAuthNode as &$v){
            $v=strtolower($v);
        }
        if (!in_array($index, $AllAuthNode)) {
            return true;
        }
        //判断当前角色的权限
        $authInfo = session(self::LOGIN_INFO . ".auth");
        foreach ($authInfo as &$a){
            $a=strtolower($a);
        }
        if (in_array($index, $authInfo)) {
            return true;
        }
        return false;
    }


    public function getInfo() {
        return session(self::LOGIN_INFO);
    }

    public function getId() {
        $info = $this->getInfo();
        return $info['id'];
    }

}
