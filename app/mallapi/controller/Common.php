<?php

namespace app\mallapi\controller;

use app\user\model\User;
use think\App;
use app\common\controller\Api as BCOM;
use think\facade\Config;
use app\compai\logic;

class Common extends BCOM
{

    public function __construct()
    {
        parent::__construct(app());

        $appname = strtolower(app('http')->getName());
        $cname = ucfirst(request()->controller());
        $action_name = request()->action();


        if ($this->uinfo['id'] <= 0) {

            if (!in_array($cname, array('Index', 'Goods', 'Login', 'Cate', 'Ms'))) {
                if (!in_array("{$cname}/{$action_name}", lang('auth_login'))) {
                    json_msg(-1, '请先登录');
                }
            }
        }



        if($this->uinfo['id']){
            $cart = new \app\mallapi\logic\Cart();
            $cart->config(array(
                'in' => $this->in,
                'uinfo' => $this->uinfo,
                'data' => $this->data,
                'request' => $this->request,
            ));
            $this->data['cart_num'] = $cart->get_total_num();
        }

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
//            return json($res);
        }
    }

}
