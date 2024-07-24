<?php

namespace app\mallapi\controller;

use think\App;
use app\BaseController;

class Pay extends BaseController {

    public function __construct() {
        parent::__construct(app());

        $appname = strtolower(app('http')->getName());
        $cname = ucfirst(request()->controller());
        $action_name = request()->action();


        $className = "\app\\$appname\logic\\$cname";
        $this->logic = new $className();


        $logic_methods = get_class_methods(new $className());
        $methods = get_class_methods(__CLASS__);
        if (!in_array($action_name, $methods) && in_array($action_name, $logic_methods)) {
            $res = $this->logic->$action_name();
            die();
//            return json($res);
        }
    }

}
