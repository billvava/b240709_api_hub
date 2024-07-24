<?php

namespace app\suoapi\controller;

use app\common\controller\Api as BCOM;
use think\App;
use think\facade\View;

class Common extends BCOM {

    /**
     * showdoc
     * @catalog 重要说明
     * @title 重要说明
     * @description 主要说明
     * @method post
     * @param token 可选 string token
     * @url http://xxx.wzxinhu.cn/
     * @return {"status":1,"data":{"token":"xxxxx"}}
     * @return_param status string 状态1=成功 0=失败 -1=未登录
     * @return_param data string 返回数据
     * @return_param info string 说明
     * @remark 除特定接口外其他接口都需要token
     * @number 1
     */
    public function __construct(App $app) {
        parent::__construct($app);
        $appname = strtolower(app('http')->getName());
        $cname = ucfirst(request()->controller());
        $action_name = request()->action();
        $className = "\app\\$appname\logic\\$cname";
        $this->logic = new $className();
        $this->logic->config(array(
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data,
            'request' => $this->request,
        ));
        if(!$this->uinfo && $cname!='Index'){
            echo json_encode(['status'=>'-1']);
            die();
        }

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
