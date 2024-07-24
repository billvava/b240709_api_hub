<?php

namespace app\common\controller;

use app\BaseController;
use app\common\Lib\Util;
use think\App;
use think\exception\HttpResponseException;
use think\facade\View;
use app\admin\logic\LoginLogic;
use app\admin\model\AdminUser;

class Admin extends BaseController {

    public $model;
    public $admin;
    public $adminInfo;
    public $is_dev;
    public $in;
    public $rule;
    public $data;
    public $CommonUtil;

    public function __construct(App $app) {
        parent::__construct($app);
        $this->CommonUtil = new Util();
        $login = new LoginLogic();
        $login->autoLogin();

        if ($this->request->controller() != 'Login' && $login->isLogin() == false) {
            js(url(ADMIN_URL . '/Login/index'));
            die;
        }
        $this->admin = new AdminUser();
        $this->adminInfo = $login->getInfo();
        //是否判断权限
        if (C('admin_auth') == 1) {
            //超管是否判断权限
            if (C('judge_superadmin_auth') == 0 && $this->adminInfo['is_superadmin'] == 1) {
                
            } else {
                if ($this->request->controller() != 'Login' && $login->checkAuth() == false && C('admin_auth') == 1) {
                    if ($this->request->isAjax()) {
                        $this->error('您无权操作该功能');
                    }
                    $this->msg('您无权操作该功能');
                }
            }
        }

        $adminInfo = session('admin');
        View::assign('adminInfo', $adminInfo);

        $this->adminId = $login->getId();
        $this->is_dev = $login->isDev();
        $this->in = request()->param();
        View::assign('adminInfo', $this->adminInfo);
        View::assign('adminId', $this->adminId);
        View::assign('is_dev', $this->is_dev);
        View::assign('in', $this->in);
    }

    /**
     * 生成SEO标签
     * @param type $seo
     */
    public function create_seo($seo = null) {
        if (is_string($seo)) {
            View::assign('name', $seo);
        }
    }

    /**
     * 信息提示
     * @param type $msg
     */
    public function msg($msg) {
        View::assign('msg', $msg);
        $response = view(app()->getBasePath() . 'admin/view/public/msg.php');
        throw new HttpResponseException($response);
        die();
    }

    /**
     * 检查权限
     */
    public function check_auth() {

        if ($flag == false) {
            if (app()->request->isAjax()) {
                $this->error('您无权操作该功能');
            }
            $this->msg('抱歉，您无权操作该功能');
        }
    }

}
