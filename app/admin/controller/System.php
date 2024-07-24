<?php
namespace app\admin\controller;

use think\App;
use think\facade\Db;
use think\facade\View;


class System extends Common {

    /**
     * 系统信息
     * @auto true
     * @auth true
     * @menu true
     * @icon icon-keyboard
     */
    public function info() {
        $this->title = '系统信息';
        $sys_url = lang('sys_url');
        $sys_title = lang('sys_title');
        $info = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'ThinkPHP版本' => app()->version() . ' [ <a href="http://www.thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时区' =>date_default_timezone_get(),
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'register_globals' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
//            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
//            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO',
        );
        $tmp = function_exists('gd_info') ? gd_info() : array();
        if (empty($tmp['GD Version'])) {
            $info['GD库'] = '未安装';
        } else {
            $info['GD库'] = $tmp['GD Version'];
        }
        if (!is_writable(runtime_path())) {
            if (is_dir(runtime_path())) {
                $info['Runtime目录'] = '不可写';
            } else {
                $info['Runtime目录'] = '不存在';
            }
        } else {
            $info['Runtime目录'] = '可写';
        }
        $items = array(
            array('mysql_connect', '支持'),
            array('mysqli_connect', '支持'),
            array('file_get_contents', '支持'),
            array('mb_substr', '支持'),
        );

        foreach ($items as &$val) {
            if (!function_exists($val[0])) {
                $val[1] = '不支持';
            }
            $info[$val[0]] = $val[1];
        }
        View::assign('info', $info);
        return $this->display();
    }
}