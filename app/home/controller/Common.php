<?php
namespace app\home\controller;


use app\common\controller\Home;
use think\App;
use think\facade\Config;
use think\facade\Env;
use think\facade\Db;
use think\facade\View;

class Common extends Home
{

    public function __construct(App $app)
    {
        parent::__construct($app);


        $the_host = $this->lib->set_host();
        if ($the_host) {
            $config=[
                // 模板目录名
                'view_dir_name' => 'view/'.$the_host,
                'layout_on'     =>  true,
                'layout_name'   =>  'layout',

            ];
            Config::set($config,'view');

        }
        /* 验证自动登陆 */
        if (cookie('user_token') && !session('user.id')) {
            $token = cookie('user_token');
            $base = D('Common/User');
            $user_id = $base->token_check($token);
            if ($user_id) {
                $uinfo = $base->getUserInfo($user_id);
                session('user', $uinfo);
                session('user_id', $uinfo['id']);
                echo "<script>document.location.reload();</script>";
                die;
            }
        }

    }



}
