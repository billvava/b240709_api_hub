<?php
namespace app\admin\controller;

use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;


class Common extends BCOM{

    public $in;
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->in=$app->request->param();

        if( in_array($app->request->controller(), array('Index','Login')) ){
            View::assign('show_top', false);
        }



    }







}