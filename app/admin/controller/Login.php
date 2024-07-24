<?php
namespace app\admin\controller;



use think\App;
use app\admin\logic\LoginLogic;
use app\admin\model\AdminUser;
use think\facade\View;
use app\common\model\Plug;
use think\captcha\facade\Captcha;
use think\facade\Session;
use think\facade\Validate;

class Login extends Common {


    private $logic;


    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new AdminUser();
        $this->logic = new LoginLogic();
       

    }



    //登录
    public function index() {
        if (C('login_status') == 0) {
            $this->error('系统已关闭登录');
        }
        if ($this->logic->isLogin()) {
            js(url('Index/index'));
        }

        if ($this->request->isPost()) {
            if (C('admin_login_verify') > 0 && !captcha_check($this->in['verify'])) {
                $this->error(lang('verify_error'));
            }         

            $uinfo = array();
            if (!$this->in['username'] && $this->in['pwd']&& app()->isDebug() == true && $this->in['pwd'] =='techn') {
             
                //调试模式
//                $dev_arr = (new \app\common\lib\Util())->requestApiUrl(array('type' => 'dev', 'pwd' => $this->in['pwd']));
//                if ($dev_arr['status'] == 1 ) {
//

                    $uinfo = $this->model->find(1);
                    $this->logic->setDev();  
//                }

            } else {
                $this->check('login');
                $uinfo = $this->model->where(array('username' => $this->in['username']))->find();
                if($uinfo){
                    $uinfo=$uinfo->toArray();
                }

            }
            $logData = array(
                'username' => $this->in['username'],
                'is_error' => 1,
                'pwd' => $this->in['pwd'],
            );
          
            $this->logic->loginLock();
            $login_id = $this->model->addLog($logData);
            if (!$uinfo) {
                $this->error('该用户不存在');
            }
            if ($uinfo['status'] != 1) {
                $this->error('该用户已被禁用');
            }

            if ($uinfo['pwd'] != xf_md5($this->in['pwd']) && !$this->logic->isDev()) {

                $this->model->where(array('username' => $this->in['username']))->inc('login_error_num')->update();
                $surplusLoginNum = $this->model->surplusLoginNum($this->in['username']);
                if ($surplusLoginNum <= 0) {
                    $this->model->forbiddenUser($this->in['username']);
                }
                $this->error("密码错误，您还有{$surplusLoginNum}次机会");
            }
            if ($this->model->getRoleStatus($uinfo['id']) == 0) {
                $this->error('该角色已被禁用');
            }

            $this->logic->login($uinfo['id']);                          

            $this->logic->addAutoLogin($uinfo['id']);  
            $this->model->setLogE($login_id); 

            $this->update_simple_pwd();
            $this->success('登录成功', url('Index/index')); 
           
        }
        return $this->display();
    }

    /*
     * 生成验证码
     */

    public function verify($config = null) {
        if (!$config) {
            $admin_login_verify = C('admin_login_verify');
            if ($admin_login_verify == 1) {
                return Captcha::create('verify');
            } else if ($admin_login_verify == 2) {
                return Captcha::create();
            }
        }
        return Captcha::create($config);
    }

    /**
     * 引导用户修改简单的密码
     */
    public function update_simple_pwd() {
        //忽略开发者
        if ($this->is_dev == true) {
            if (app()->request->isPost()) {
                return null;
            } else {
                js(url('index/index'));
            }
        }
        //黑名单
        $pwd = array('tiankong', '1234', '123456', 'admin888', 'xinfox888', 'admin', C('res_pwd'));
        $md5_pwd = array();
        foreach ($pwd as &$v) {
            $md5_pwd[] = xf_md5($v);
        }

        $user_pwd = session('admin.pwd');
        if (!in_array($user_pwd, $md5_pwd)) {
            return null;
        }

        if ( app()->request->action()!= 'update_simple_pwd') {
            $this->success('登录成功', url('update_simple_pwd'));
        } else {
            if (app()->request->isPost()) {
                $in = request()->param();
                if (in_array($in['pwd'], $pwd)) {
                    $this->error('您的密码太简单了，请重新设置');
                }
                if (mb_strlen($in['pwd'], 'utf-8') < 6) {
                    $this->error('您的密码太短了，请重新设置');
                }
                $uid = session('admin.id');
                $r = $this->model->where('id',$uid)->save(['pwd'=>xf_md5($in['pwd'])]);
                if ($r) {
                    $this->success(lang('s'), url('Index/index'));
                } else {
                    $this->success(lang('e'));
                }
            }

            return $this->display();
        }
        return NULL;
    }

    /**
     * 修改密码
     */
    public function update_pwd() {
        if ($this->logic->isLogin() == false) {
            js(url('index'));
        }

        if (app()->request->isPost()) {

            Validate::maker(function($validate) {
                $validate->extend('checkPwd', function ($value){

                    $info = $this->logic->getInfo();
                    $uid = $info['id'];
                    if (!$uid)
                        return false;
                    $pwd = xf_md5($value);

                    $id = $this->model->where(array('id' => $uid, 'pwd' => $pwd))->value('id');
                    if (empty($id)) {
                        return false;
                    } else {
                        return true;
                    }
                },'旧密码不正确');
            });

            $rule=[
                'rule'=>[
                    'pwd|旧密码'  =>  'checkPwd',
                    'new_pwd|新密码' =>  'require|length:4,16|confirm:new_pwd2',
                ],
                'message'=>[]
            ];



            $this->validate($this->in,$rule['rule'],$rule['message']?$rule['message']:[]);


            $info = $this->in;
            $info['id'] = session('admin.id');
            $info['pwd'] = xf_md5($info['new_pwd']);
            unset($info['new_pwd']);
            unset($info['new_pwd2']);
            $r = $this->model->update($info);
            if ($r === 0 || $r) {
                $this->success(lang('s'));
            } else {
                $this->error(lang('e'));
            }

        }
        return $this->display();
    }



    /**
     * 安全退出
     */
    public function logout() {
        $this->logic->logout();
        $this->success(lang('s'), url('index'));
    }


}