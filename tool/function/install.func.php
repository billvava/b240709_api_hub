<?php

/**
 * 检测安装
 */
if (!function_exists('ck_installed')) {

    function ck_installed() {
        $r = checkinstall();
        if ($r == false) {
            js(url('Install/step1'));
        }
    }

}

/**
 * 检测是否已经安装
 * @return boolean
 */
if (!function_exists('checkinstall')) {

    function checkinstall() {
        $install_file = app()->getAppPath() . 'Install/install.lock';
        if (!file_exists($install_file)) {
            return false;
        } else {
            return true;
        }
    }

}

/*
 * 写入安装文件
 */
if (!function_exists('create_install')) {

    function create_install() {
        if (!is_dir(app()->getAppPath() . "Install")) {
            mkdir(app()->getAppPath() . "Install", 0777, true);
        }
        $install_file = app()->getAppPath() . 'Install/install.lock';
        file_put_contents($install_file, "Do not delete");
    }

}

//创建菜单
function create_nav($nav_data) {
    $AdminNav = new \app\admin\model\AdminNav();
    $nav_ids = array();
    if ($nav_data['name']) {
        $child = $nav_data['child'];
        unset($nav_data['child']);
        $pid = $AdminNav->insertGetId($nav_data);
        $nav_ids[] = $pid;
        if ($child) {
            foreach ($child as $vv) {
                $vv['pid'] = $pid;
                $temp = $vv;
                unset($vv['child']);
                $tid1 = $AdminNav->insertGetId($vv);
                $nav_ids[] = $tid1;
                if ($temp['child']) {
                    foreach ($temp['child'] as $vvv) {
                        $vvv['pid'] = $tid1;
                        $tid = $AdminNav->insertGetId($vvv);
                        $nav_ids[] = $tid;
                    }
                }
            }
        }
    } else {
        foreach ($nav_data as $v) {
            $child = $v['child'];
            unset($v['child']);
            $pid = $AdminNav->insertGetId($v);
            $nav_ids[] = $pid;
            if ($child) {
                foreach ($child as $vv) {
                    $vv['pid'] = $pid;
                    $temp = $vv;
                    unset($vv['child']);
                    $tid1 = $AdminNav->insertGetId($vv);
                    $nav_ids[] = $tid1;
                    if ($temp['child']) {
                        foreach ($temp['child'] as $vvv) {
                            $vvv['pid'] = $tid1;
                            $tid = $AdminNav->insertGetId($vvv);
                            $nav_ids[] = $tid;
                        }
                    }
                }
            }
        }
    }
    $AdminRole = new \app\admin\model\AdminRole();
    $AdminRole->insertNav(1, $nav_ids);
}


//新的创建菜单
function create_new_nav($data) {
    if (is_string($data)) {
        $data = json_decode($data, true);
    }
    $add = $data;
    unset($add['id']);
    unset($add['children']);
    $admin_nav = \think\facade\Db::name('admin_nav');
    $pid = $admin_nav->insertGetId($add);
    foreach ($data['children'] as $k => $v) {
        unset($v['id']);
        $v['pid'] = $pid;
        $two_pid = $admin_nav->insertGetId($v);
        if($v['children']){
            foreach ($v['children'] as $kk => $vv) {
                unset($vv['id']);
                $vv['pid'] = $two_pid;
                $admin_nav->insertGetId($vv);
            }
        }
    }
}

//创建配置
function create_config($data) {
    $system_config_cate = \think\facade\Db::name('system_config_cate');
    $system_config = \think\facade\Db::name('system_config');
    $add = $data;
    unset($add['children']);
    $pid = $system_config_cate->insertGetId($add);
    foreach ($data['children'] as $k => $v) {
        $two_pid = $system_config_cate->insertGetId(array(
            'name' => $v['name'],
            'pid' => $pid,
            'sort' => $k
        ));
        foreach ($v['children'] as $kk => $vv) {
            $vv['cate_id'] = $two_pid;
            $vv['sort'] = $kk;
            $vv['type'] = app('http')->getName();
            $system_config->insertGetId($vv);
        }
    }
}



//创建配置
function create_plug($data) {
    $system_plug = \think\facade\Db::name('system_plug');
    $temp = array();
    foreach ($data as $v) {
        $system_plug->where(array('plug' => $v['plug'], 'key' => $v['key']))->delete();
        $v['is_show'] = isset($v['is_show']) ? $v['is_show'] : 1;
        $temp[] = $v;
    }
    $system_plug->insertAll($temp);
}

//创建钩子
function create_hook($data) {
    $system_plug = \think\facade\Db::name('system_hook');
    $system_plug->where(array('module' => config('config.plug')))->delete();
    if ($data) {
        $system_plug->insertAll($data);
    }
}

//创建广告
function create_ad($data) {
    $model = \think\facade\Db::name('system_ad');
    $system_plug = \think\facade\Db::name('system_plug');
    $system_ad_img = \think\facade\Db::name('system_ad_img');
    $plug = C('plug');
    foreach ($data as $k => $v) {
//        $model->where(array('title' => $v['title']))->delete();
        $ad_id = $model->insertGetId(array(
            'name' => $v['title'],
            'remark' => $v['msg'],
        ));
        $options = json_decode($v['option'], true);
        foreach ($options as $ov) {
            $system_ad_img->insertGetId(array(
                'big' => $ov['big'],
                'ad_id' => $ad_id,
                'start' => date('Y-m-d H:i:s'),
                'end' => date('Y-m-d H:i:s', strtotime("+10 year")),
            ));
        }
        $system_plug->where(array('plug' => $plug, 'key' => $k))->save(['val' => $ad_id]);
    }
}

/**
 * 用户字段
 */
function create_tb_ext($data) {
    tool()->func('sql');
    tool()->classs('Mysql_query');
    $Mysqli = new \Mysql_query();
    $pre = DB_PREFIX;
    foreach ($data as $v) {
        $v['table'] = str_replace("{{pre}}", $pre, $v['table']);
        $sql = add_field_ext($v);
        $Mysqli->query($sql);
    }
}

/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env() {
    $items = array(
        'os' => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
        'php' => array('PHP版本', '7.1', '7.1+', PHP_VERSION, 'success'),
//        'mysql'   => array('MYSQL版本', '5.0', '5.0+', '未知', 'success'), //PHP5.5不支持mysql版本检测
        'upload' => array('附件上传', '不限制', '2M+', '未知', 'success'),
        'gd' => array('GD库', '2.0', '2.0+', '未知', 'success'),
        'disk' => array('磁盘空间', '5M', '不限制', '未知', 'success'),
    );

    //PHP环境检测
    if ($items['php'][3] < $items['php'][1]) {
        $items['php'][4] = 'danger';
        session('error', true);
    }

    //数据库检测
//     if(function_exists('mysql_get_server_info')){
//     	$items['mysql'][3] = mysql_get_server_info();
//     	if($items['mysql'][3] < $items['mysql'][1]){
//     		$items['mysql'][4] = 'error';
//     		session('error', true);
//     	}
//     }
    //附件上传检测
    if (@ini_get('file_uploads'))
        $items['upload'][3] = ini_get('upload_max_filesize');

    //GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if (empty($tmp['GD Version'])) {
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'error';
        session('error', true);
    } else {
        $items['gd'][3] = $tmp['GD Version'];
    }
    unset($tmp);

    //磁盘空间检测
    if (function_exists('disk_free_space')) {
        $items['disk'][3] = floor(disk_free_space(app()->getBasePath()) / (1024 * 1024)) . 'M';
    }

    return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile() {
    $items = array(
        array('dir', '可写', 'success', './uploads'),
    );
    foreach ($items as &$val) {
        $path = $val[3];
        if ('dir' == $val[0]) {
            if (!is_writable($path)) {
                if (is_dir($path)) {
                    $val[1] = '不可写';
                    $val[2] = 'danger';
                    session('error', true);
                } else {
                    $val[1] = '不存在';
                    $val[2] = 'danger';
                    session('error', true);
                }
            }
        } else {
            if (file_exists($path)) {
                if (!is_writable($path)) {
                    $val[1] = '不可写';
                    $val[2] = 'danger';
                    session('error', true);
                }
            } else {
                if (!is_writable(dirname($path))) {
                    $val[1] = '不存在';
                    $val[2] = 'danger';
                    session('error', true);
                }
            }
        }
    }
    return $items;
}

/**
 * 检测表是否存在
 * @return boolean
 */
function check_table($tables) {
    $DB_PREFIX = DB_PREFIX;
    $DB_NAME = C('DB_NAME');
    $items = $tables;
    foreach ($items as $key => &$value) {
        $sql = "select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA='{$DB_NAME}' and TABLE_NAME='{$DB_PREFIX}{$key}' ;";
        $data = \think\facade\Db::query($sql);

        if ($data) {
            $value[2] = '存在重复表名';
            $value[3] = 'danger';
            session('error', true);
        }
    }
    return $items;
}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func() {
    $items = array(
        array('file_get_contents', '支持', 'success'),
    );

    foreach ($items as &$val) {
        if (!function_exists($val[0])) {
            $val[1] = '不支持';
            $val[2] = 'danger';
            $val[3] = '开启';
            session('error', true);
        }
    }

    return $items;
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($prefix = '') {

    //读取SQL文件
    $sql = file_get_contents(app()->getAppPath() . 'Install/install.sql');
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    //替换表前缀
    $sql = str_replace(" `{{pre}}", " `{$prefix}", $sql);
    //开始安装
    ;
    show_msg('开始安装数据库...');

    foreach ($sql as $value) {
        $value = trim($value);

        if (empty($value))
            continue;


        if (substr($value, 0, 12) == 'CREATE TABLE') {

            if(strstr($value,'CREATE TABLE IF NOT EXISTS')){
                $name = preg_replace("/^CREATE TABLE IF NOT EXISTS `(\w+)` .*/s", "\\1", $value);
            }else{
                $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            }



            $msg = "创建数据表{$name}";

            if (false !== \think\facade\Db::execute($value)) {
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！');
            }
        } else {
            \think\facade\Db::execute($value);
        }
    }
}

//监测模块
function check_module($module) {
    $items = $module;
    foreach ($items as &$val) {
        if (!is_dir(app()->getBasePath() . $val[0])) {
            $val[1] = '不存在';
            $val[2] = 'danger';
            $val[3] = '安装';
            session('error', true);
        }
    }
    return $items;
}

/**
 * 安装sql
 * @param  resource $db 数据库连接资源
 */
function execute_sql($file, $is_del = 0) {

    //读取SQL文件
    if (file_exists($file)) {
        $sql = file_get_contents($file);
        $sql = str_replace("\r", "\n", $sql);
        $sql = explode(";\n", $sql);
        //替换表前缀
        $prefix = env('PREFIX');
        $sql = str_replace(" `{{pre}}", " `{$prefix}", $sql);
        //开始安装
        show_msg('开始安装数据库...');
        foreach ($sql as $value) {
            $value = trim($value);
            if (empty($value)) {
                continue;
            }
            if (substr($value, 0, 12) == 'CREATE TABLE') {
                $name = preg_replace("/^CREATE TABLE IF NOT EXISTS `(\w+)` .*/s", "\\1", $value);
                $msg = "创建数据表{$name}";
                if (false !== \think\facade\Db::execute($value)) {
                    show_msg($msg . '...成功');
                } else {
                    show_msg($msg . '...失败！');
                }
            } else {
                \think\facade\Db::execute($value);
            }
        }
        if ($is_del == 1) {
            unlink($file);
        }
    }
}
