<?php

namespace app\mall\controller;


use think\App;

class Index extends Common {
    public function __construct(App $app)
    {
        parent::__construct($app);

    }

    public function index(){
        $nav_data = file_get_contents(app()->getAppPath() . 'Install/nav.php') ;
        create_new_nav($nav_data);
    }
}