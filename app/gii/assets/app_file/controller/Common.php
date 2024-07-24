<?php

namespace app\#module#\controller;

use app\common\controller\Api as BCOM;
use think\App;
use think\facade\View;

class Common extends BCOM {

    public function __construct(App $app) {
        parent::__construct($app);

        $appname = strtolower(app('http')->getName());
        $cname = ucfirst(request()->controller());
        $action_name = request()->action();
        //未登录判断
        if (!$this->uinfo['id'] && !in_array($cname, ['Index'])) {
            json_msg(-1);
        }
        p($this->uinfo,0);
        p($this->in,0);
        
        //映射到logic
        $className = "\app\\$appname\logic\\$cname";
        $this->logic = new $className();
        $this->logic->config(array(
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data,
             'request' => $this->request,
        ));

        $logic_methods = get_class_methods(new $className());
        $methods = get_class_methods(__CLASS__);
        if (!in_array($action_name, $methods) && in_array($action_name, $logic_methods)) {
            $res = $this->logic->$action_name();
            header('content-type:application/json');
            echo json_encode($res);
            die();
        }
    }

}
