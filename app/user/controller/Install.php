<?php

namespace app\user\controller;


use app\admin\logic\Node;
use app\admin\model\AdminNav;
use think\App;
use think\facade\Db;
use think\facade\View;
use think\facade\Config;
use app\common\controller\Admin as BCOM;

class Install extends BCOM
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        tool()->func('install');
        if (checkinstall() == true) {
            $this->error("您已经安装过了");
        }
//        $config=[
//            // 模板目录名
//            'view_dir_name' => 'view/admin',
//            'layout_on'     =>  true,
//            'layout_name'   =>  'layout',
//
//        ];
//        Config::set($config,'view');

        View::assign('title', '安装');
    }

    //安装第一步，检测运行所需的环境设置
    public function step1() {
        session('error', false);
        $env = check_env();
        View::assign('env', $env);
        //目录文件读写检测
        $dirfile = check_dirfile();
        View::assign('dirfile', $dirfile);
        //函数检测
        $func = check_func();
        View::assign('func', $func);

        $install_table = include app()->getAppPath() . 'Install/table.php';
        $tab = check_table($install_table);
        View::assign('tab', $tab);

        if (session('error') == false) {
            session('step', 1);
        } else {
            session('step', null);
        }
        return $this->display();
    }

    //安装第二步，创建数据库
    public function step2() {
        if (session('step') != 1) {
            redirect('step1');
        }



        //创建数据表
        create_tables(DB_PREFIX);


//        $nav_data = include app()->getAppPath() . 'Install/nav.php';
//
//        create_nav($nav_data);
//        $Node=new Node();
//        $Node->fastInsertNode(ucfirst(config('config.plug')), 'admin');
//        show_msg('模块已经加入后台菜单');


        $top_id = Db::name('admin_nav')->insertGetId(array(
            'name' => '用户',
            'ismenu' => 1,
            'icon' => 'layui-icon layui-icon-user',
        ));
        (new AdminNav())->up_all_node(app()->getAppPath(), $top_id, 0);
        show_msg('菜单已经加入后台菜单');



        //创建配置文件
        create_install();
        session('step', 2);
        return $this->display('complete');
    }


}