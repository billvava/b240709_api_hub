<?php

namespace app\gii\controller;

use Think\Controller;
use think\App;
use think\facade\View;

class CreateApp extends Common {

    public function index() {
        $this->title = "应用生成";
        return $this->display();
    }

    /**
     * CRUD生成
     */
    public function run() {
        $in = request()->param();
        if (!$in['module']) {
            die_echo("模块名必填");
        }
        if (!preg_match("/^[a-zA-Z\s]+$/", $in['module'])) {
            die_echo("模块名必须为英文");
        }
        $module = strtolower($in['module']);
        if (!$in['module_name']) {
            die_echo("中文标示必填");
        }
        $gii_path = app_path();
        $dir = $gii_path . 'assets/app_file/';
        $app_path = APP_PATH;
        $create_path = $app_path . "{$module}/";
        tool()->classs('FileUtil');
        $fil_util = new \FileUtil();
        $fil_util->copyDir($dir, $create_path);
        $ds = $fil_util->getDirList($create_path);
        foreach ($ds as $v) {
            $fs = $fil_util->getOnleFileList("{$create_path}{$v}");
            if ($fs) {
                foreach ($fs as $fv) {
                    $fvs = "{$create_path}{$v}/{$fv}";
                    $str = file_get_contents($fvs);
                    $str = str_replace(array('#module#'), array($module), $str);
                    $res = writeFile($fvs, $str);
                    arr_echo($res, 1);
                }
            }
        }
        $root = __ROOT__;
        success_echo("执行完成：<a target='_blank' href='{$root}/{$module}/index/index'>点击打开</a>");
    }

}
