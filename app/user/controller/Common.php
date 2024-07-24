<?php

namespace app\user\controller;



use app\user\model\User;
use think\App;

use app\common\controller\Admin as BCOM;
use think\facade\Config;

class Common extends BCOM{
    public function __construct(App $app)
    {
        parent::__construct($app);
        tool()->func('install');
        ck_installed();
        $this->model = new User();
    }
}