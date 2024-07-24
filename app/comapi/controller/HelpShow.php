<?php

namespace app\comapi\controller;

use think\App;
use think\facade\View;

class HelpShow extends \app\BaseController {

    public function index() {
        $Content = new \app\com\model\HelpItem();
        $data = $Content->getInfo( input('id'));
        View::assign('data', $data);
        return $this->display();
    }

}
