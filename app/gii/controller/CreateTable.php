<?php

namespace app\gii\controller;

use Think\Controller;
use think\App;
use think\facade\View;

class CreateTable extends Common {

    public function index() {
        $this->title = "表参考";
        return $this->display();
    }


}
