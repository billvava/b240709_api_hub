<?php

namespace app\gii\controller;

use Think\Controller;
use think\App;
use think\facade\View;

class CreateReport extends Common {

    public $model;
    public $table_name;
    public $c_name;
    public $view_name;
    public $comment;

    public function __construct() {
        parent::__construct(app());

        $this->model = new \app\gii\model\O();
        $this->in['time_where'] = htmlspecialchars_decode($this->in['time_where'], ENT_QUOTES);
        $this->in['cate_where'] = htmlspecialchars_decode($this->in['cate_where'], ENT_QUOTES);
    }

    public function index() {
        $this->title = "统计图";
        return $this->display();
    }

    /**
     * CRUD生成
     */
    public function run() {
        $this->input_check();
        $this->check_file_exits();
        $this->create_file();

        if ($this->in['module'] == ADMIN_MODULE) {
            $this->in['module'] = ADMIN_URL;
        }
        $url = url("{$this->in['module']}/{$this->in['c_name']}/index");
        success_echo("<a href='{$url}' target='_blank'>点击链接打开</a>", 1);
    }

    public function input_check() {
        $is_die = 0;
        if ($this->in['time_is'] == 1) {
            if (!$this->in['time_field']) {
                error_echo('时间字段不存在');
                $is_die = 1;
            }
        }
        if ($this->in['cate_is'] == 1) {
            if (!$this->in['cate_field']) {
                error_echo('分类字段不存在');
                $is_die = 1;
            }
        }



        if (!$this->in['c_name']) {
            error_echo('c_name字段不存在');
            $is_die = 1;
        }
        if (!$this->in['view_name']) {
            error_echo('view_name字段不存在');
            $is_die = 1;
        }
        if (!$this->in['comment']) {
            error_echo('comment字段不存在');
            $is_die = 1;
        }
        $info = $this->model->getTableName($this->in['table_name']);
        if (!$info) {
            error_echo('该表不存在');
            $is_die = 1;
        }

        $dir = APP_PATH . $this->in['module'];
        if (!is_dir($dir)) {
            error_echo('该应用不存在');
            $is_die = 1;
        }
        $is_die && die;
    }

    /**
     * 生成文件
     */
    public function create_file() {
        $gii_path = app_path();
        $dir = $gii_path . 'assets/report/';
        $app_path = APP_PATH;
        $module = $this->in['module'];
        $create_path = $app_path . "{$module}/";
        tool()->classs('FileUtil');
        $fil_util = new \FileUtil();
        $fil_util->copyDir($dir, $create_path);
        $ds = $fil_util->getDirList($create_path);
        $temp_report_55 = $create_path . 'view/temp_report_55/';
        $new_view_dir = $create_path . "view/{$this->in['view_name']}/";
        if (!is_dir($new_view_dir)) {
            mkdir($new_view_dir, 0775, true);
        }

        $fil_util->copyDir($temp_report_55, $new_view_dir);
        $fil_util->unlinkDir($temp_report_55);

        //替换视图变量
        $fs = $fil_util->getOnleFileList($new_view_dir);
        if ($fs) {
            foreach ($fs as $fv) {
                $fvs = "{$new_view_dir}{$fv}";
                $str = file_get_contents($fvs);
                $str = str_replace(array('#view_name#'), array($this->in['view_name']), $str);
                $res = writeFile($fvs, $str);
                arr_echo($res, 1);
            }
        }

        //替换控制器
        $c_file = $create_path . 'controller/temp_report_55.php';
        $controllerStr = file_get_contents($c_file);
        $controllerStr = str_replace(
                array('#moudleName#', '#table_name#', '#controllerName#', '#name#'), array($this->in['module'], $this->in['table_name'], $this->in['c_name'], $this->in['comment']), $controllerStr
        );
        $controllerStr = str_replace(
                array('#time_field#', '#time_where#', '#total_field#'), array($this->in['time_field'], $this->in['time_where'], $this->in['total_field']), $controllerStr
        );
        tool()->func('str');
        $ac_str = '';
        if ($this->in['cate_comment']) {
            $ac = str_to_arr($this->in['cate_comment']);
            if ($ac) {
                foreach ($ac as $k => $v) {
                    $ac_str.="'{$k}'=>'{$v}',";
                }
            }
        }

        $controllerStr = str_replace(
                array('#cate_field#', '#cate_where#', '#cate_lan#'), array($this->in['cate_field'], $this->in['cate_where'], $ac_str), $controllerStr
        );
        $res = writeFile($this->controllerFile, $controllerStr);
        arr_echo($res, 1);

        unlink($c_file);
    }

    /**
     * 检查文件是否存在
     */
    public function check_file_exits() {
        $this->controllerDir = APP_PATH . "{$this->in['module']}/controller/";
        $this->viewDir = APP_PATH . "{$this->in['module']}/view/{$this->in['view_name']}/";
        if (!is_dir($this->controllerDir)) {
            mkdir($this->controllerDir, 0775, true);
        }
        if (!is_dir($this->viewDir)) {
            mkdir($this->viewDir, 0775, true);
        }


        $this->controllerFile = $this->controllerDir . "{$this->in['c_name']}.php";

        //检测是否存在同样的文件
        if ($this->in['is_cover'] == 0) {
            if (file_exists($this->controllerFile)) {
                die_echo("控制器文件{$this->controllerFile }已存在，为避免覆盖错误，请手动检查处理");
            }
            $a = array('year', 'month', 'day', 'cate', 'index', 'nav');
            foreach ($a as $v) {
                $t = $this->viewDir . $v . ".php";
                if (file_exists($t)) {
                    die_echo("模板文件{$t}已存在，为避免覆盖错误，请手动检查处理");
                }
            }
        }
    }

    /**
     * 验证
     */
    public function check_tb() {
        $DB_PREFIX = env('DATABASE.PREFIX');
        if (!$this->in['table_name']) {
            return;
        }
        $info = $this->model->getTableName($this->in['table_name']);
        if (!$info) {
            $this->error('该表不存在');
        }
        $data['file_name'] = tableNameToModelName(str_replace($DB_PREFIX, '', $info[0]['TABLE_NAME'])) . "Report";
        $table_info = $this->model->showTables($this->in['table_name']);
        $table_fs = $this->model->getTableInfoArray($this->in['table_name']);

        $data['time_field'] = "";
        $data['total_field'] = "";
        $data['cate_field'] = "";
        $data['cate_comment'] = "";
        foreach ($table_fs as $v) {
            if ($v['COLUMN_TYPE'] == 'datetime') {
                $data['time_field'] = $v['COLUMN_NAME'];
            }

            if (strpos($v['COLUMN_TYPE'], 'float') !== false || strpos($v['COLUMN_TYPE'], 'decimal') !== false) {
                $data['total_field'] = $v['COLUMN_NAME'];
            }
            if (strpos($v['COLUMN_COMMENT'], '|') !== false) {
                $data['cate_field'] = $v['COLUMN_NAME'];
                $tt = explode('|', $v['COLUMN_COMMENT']);
                $data['cate_comment'] = $tt[1];
            }
        }
        $data['view_name'] = uncamelize($data['file_name']);
        $data['table_name'] = $table_info['table_comment'] ? $table_info['table_comment'] . '统计' : $data['table_name'];
        json_msg(1, null, null, $data);
    }

}
