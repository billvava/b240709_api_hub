<?php

namespace app\com\controller;

use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;

class Common extends BCOM {


    public function __construct(App $app) {
        parent::__construct($app);
    }

}
