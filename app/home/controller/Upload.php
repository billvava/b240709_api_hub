<?php
namespace app\home\controller;

use app\BaseController;
use think\App;
use think\facade\Config;
use think\facade\Env;
use think\facade\Db;
use think\facade\View;

class Upload extends BaseController {


    public function __construct(App $app)
    {
        parent::__construct($app);
        View::assign('in', input());
        $config=[
            // 模板目录名
            'view_dir_name' => 'view/system',
            'layout_on'     =>  false,
            'layout_name'   =>  'layout',

        ];
        Config::set($config,'view');
    }

    /**
     * 裁剪
     */
    public function cut() {
        return $this->display();
    }

    //缩放上传
    public function zoom() {
        return $this->display();
    }

}
