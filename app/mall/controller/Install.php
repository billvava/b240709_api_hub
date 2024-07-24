<?php

namespace app\mall\controller;


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
        $module = check_module(include app()->getAppPath() . 'Install/module.php');
        View::assign('module', $module);
        $env = check_env();
        View::assign('env', $env);
        //目录文件读写检测
        $dirfile = check_dirfile();
        View::assign('dirfile', $dirfile);
       

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




        $nav_data = file_get_contents(app()->getAppPath() . 'Install/nav.php') ;
        create_new_nav($nav_data);



        $plug_data = include app()->getAppPath() . 'Install/config.php';
        create_config($plug_data);




        show_msg('配置已经生成成功');
        $ad_data = include app()->getAppPath() . 'Install/ad.php';
        create_ad($ad_data);
        show_msg('广告已经生成成功');

        $ext = include app()->getAppPath() . 'Install/user_ext.php';
        $ext && create_tb_ext($ext);


        tool()->classs('FileUtil');
        $fu = new \FileUtil();
        $fu->moveDir("../app/mall/mallapi/", "../app/mallapi/", false);
        show_msg('移动接口文件到app');
        
        $Config = new \app\admin\model\Config();
        $Config->where(['field'=>'admin_home_url'])->save(['val'=>'/mall/Data/index']);

        //创建配置文件
        create_install();
        session('step', 2);
        return $this->display('complete');
    }


}