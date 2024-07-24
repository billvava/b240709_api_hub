<?php
declare (strict_types = 1);

namespace app\admin\logic;
use app\admin\model\AdminRole;
use app\admin\model\AdminUser;
use app\admin\model\Config;
use think\facade\Db;


/**
 * @mixin think\Model
 */
class Node
{

    /**
     * 自动生成actions
     */
    public function fastInsertNode($module, $controller = '') {
        if (!$module) {
            return false;
        }

        $t = array();

        if ($controller == '') {
            $cs = $this->get_controller($module);
            foreach ($cs as $v) {
                $action_option = $this->get_action($module, $v);
                $t[$v] = $action_option;
            }
        } else {
            $action_option = $this->get_action($module, $controller);
            $t[$controller] = $action_option;
        }

        $admin_node = Db::name('admin_node');
        $module = strtolower($module);
        $lev1 = $admin_node->where(array('name' => $module, 'pid' => 0))->find();
        $pid = $lev1['id'];
        if (!$lev1) {
            $pid = $admin_node->save(
                array('name' => $module, 'pid' => 0, 'level' => 1,
                ));
        }
        //录入
        foreach ($t as $key => $value) {

            $lev2 = $admin_node->where(array('name' => $key, 'pid' => $pid))->find();
            $pid2 = $lev2['id'];
            if (!$lev2) {
                $pid2 = $admin_node->save(array(
                    'name' => strtolower($key),
                    'pid' => $pid,
                    'level' => 2
                ));
            }
            foreach ($value as $v) {
                $lev3 = $admin_node->where(array('name' => $v, 'pid' => $pid2))->find();
                if (!$lev3) {
                    $pid3 = $admin_node->save(array(
                        'name' => strtolower($v),
                        'pid' => $pid2,
                        'level' => 3
                    ));
                }
            }
        }
    }

    /**
     * 获取控制器
     */
    public function get_controller($module) {
        if (empty($module))
            return null;
        $module=strtolower($module);
        $module_path = app()->getBasePath() . $module . '/controller/';  //控制器路径
        if (!is_dir($module_path))
            return null;
        $module_path .= '/*.php';
        $ary_files = glob($module_path);
        foreach ($ary_files as $file) {
            if (is_dir($file)) {
                continue;
            } else {
                $files[] = basename($file, config('route.default_controller') . '.php');
            }
        }
        return $files;
    }

    /**
     * 获取方法名
     * @param type $module
     * @param type $controller
     * @return null
     */
    public function get_action($module, $controller) {
        if (empty($controller))
            return null;
        $module=strtolower($module);
        $content = file_get_contents(app()->getBasePath()  . $module . '/controller/' . $controller . '.php');
        preg_match_all("/.*?public.*?function(.*?)\(.*?\)/i", $content, $matches);
        $functions = $matches[1];
        //排除部分方法
        $inherents_functions = array('_before_index', '_after_index', '_initialize', '__construct', 'getActionName', 'isAjax', 'display', 'fetch', 'buildHtml', 'assign', '__set', 'get', '__get', '__isset', '__call', 'error', 'success', 'ajaxReturn', 'redirect', '__destruct', '_empty');
        foreach ($functions as $func) {
            $func = trim($func);
            if (!in_array($func, $inherents_functions)) {
                if (strlen($func) > 0)
                    $customer_functions[] = $func;
            }
        }
        return $customer_functions;
    }
}