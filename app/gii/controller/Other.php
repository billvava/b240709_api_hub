<?php

namespace app\gii\controller;

use think\facade\View;

class Other extends Common {

    public function index() {
        return View::fetch();
    }

    public function clear() {
        \think\facade\Cache::clear();
        $this->success(lang('s'));
    }

    public function delclear() {
        tool()->classs('FileUtil');
        $FileUtil = new \FileUtil();
        $FileUtil->unlinkDir('../runtime');
        $this->success(lang('s'));
    }

    public function up_all_node() {
        $AdminNav = new \app\admin\model\AdminNav();
        $AdminNav->up_all_node();
        \think\facade\Cache::clear();
        $this->success(lang('s'));
    }

}
