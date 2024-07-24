<?php

declare (strict_types = 1);

namespace app\gii\controller;

use think\App;
use think\facade\View;
use app\BaseController;

class Common extends BaseController {

    public function __construct(App $app) {
        parent::__construct($app);
        $this->lib = (new \app\common\lib\Util());
        if (!session('admin.id')) {
            $this->lib->e('该页面您无权访问');
        }
        $this->in = input();
        View::assign('in', $this->in);
      
    }

}
