<?php
/**
 *
 */

namespace app\common\middleware;

use think\facade\Config;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Validate;

class ConfigLoad
{
	
    public function handle($request, \Closure $next)
    {
//        \think\Facade\Lang::setLangSet("zh-cn");
        error_reporting(E_ALL & ~E_NOTICE);
        // URL常量
        define('__SELF__', strip_tags($_SERVER['REQUEST_URI']));
        define('ADMIN_MODULE', 'admin');
        define('ADMIN_URL', 'xf');
        define('ADMIN_APP', config('my.admin_app'));
        define('INCLUDE_PATH', app()->getRootPath().'tool/');
        define('APP_PATH', '../app/');
        define('DB_PREFIX', config('database.connections.mysql.prefix'));
        define('__ROOT__', '');
        defined('HTML_PATH')    or define('HTML_PATH',      app()->getRuntimePath().'Html/');  // 应用静态目录

        if (!class_exists('Loader')) {
            include INCLUDE_PATH . 'class/loader.class.php';
        }

        //写入配置
        (new \app\common\lib\Util())->load_config();


        return $next($request);
    }



}