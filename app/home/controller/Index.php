<?php

namespace app\home\controller;

use app\BaseController;
use app\user\model\User;
use think\App;
use think\facade\Env;
use think\facade\Db;
use think\facade\View;

class Index extends Common {

    public function __construct(App $app) {
        parent::__construct($app);
    }

    /**
     * 上线后：
     * robots.txt删除掉第二行，
     * 在index.php关闭debug
     * 后台修改网址配置成上线后的网址
     * 后台一些用不上的菜单自己隐藏
     * 开启301跳转 配置参数：set_host_on
     */
    public function index() {
        $this->create_seo();
        return $this->display();
    }

    /**
     * 生成二维码
     */
    public function qr() {
        $url = $this->in['url'];
        if (!$url) {
            $this->error('url不存在');
        }
        if (is_contain_http($url) == false) {
//            $this->error('url不包含http');
        }
        $url = urldecode($url);
        tool()->classs('phpqrcode');
        \QRcode::png($url, false, 'L', 10, 2);
    }

}
