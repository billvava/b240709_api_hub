<?php

namespace app\common\logic;

use think\App;
use think\facade\Db;

/**
 * 下单活动的工具类
 */
class Discount {

    public $in;
    public $uinfo;
    public $data;
    public $model;
    public $request;

    public function __construct() {
        
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    //遍历执行
    public function handle($type, $method = 'get') {
        if (strpos($type, 'discount_') === false) {
            $type = "discount_{$type}";
        }
        if (!in_array($method, ['get', 'sub', 'pay_success'])) {
            return false;
        }
        tool()->classs('FileUtil');
        $FileUtil = new \FileUtil();
        $path = APP_PATH . "common/{$type}/";
        $files = [];
        if (is_dir($path)) {
            $files = $FileUtil->getOnleFileList($path);
        }
        if (is_array($files)) {
            $objs = [];
            //扫描
            foreach ($files as $v) {
                if (strpos($v, '.php') !== false) {
                    $class_name = trim($v, '.php');
                    $className = "\app\common\\$type\\$class_name";
                    $logic = new $className();
                    $objs[] = [
                        'sort' => $logic->sort,
                        'class' => $className,
                    ];
                    unset($logic);
                }
            }
            //排序
            $objs = $this->sort($objs);
            //执行
            if ($objs) {
                $res = ['status' => 1, 'data' => $this->data];
                $clear_field = [];
                foreach ($objs as $v) {
                    $class = new $v['class']();
                    $class->config([
                        'in' => $this->in,
                        'uinfo' => $this->uinfo,
                        'data' => $this->data,
                    ]);

                    $methods = get_class_methods($class);
                    if (in_array($method, $methods)) {
                        $res = $class->$method();
                        if ($res['status'] != 1) {
                            return $res;
                        }
                        if ($res['clear_field']) {
                            $clear_field = array_merge($clear_field, $res['clear_field']);
                        }
                        $this->data = $res['data'];
                    }
                }
                $res['clear_field'] = $clear_field;
                return $res;
            }else {
                $res = ['status' => 1, 'data' => $this->data];
                return $res;

            }
        }
    }

    //单个执行
    public function single_handle($class_name, $method = 'get') {
        if (mb_substr($class_name, 0, 1) != '\\') {
            $class_name = '\\' . $class_name;
        }
        $class = new $class_name();
        $class->config([
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data,
        ]);
        $methods = get_class_methods($class);
        if (in_array($method, $methods)) {
            $res = $class->$method();
            return $res;
        }
        return ['status' => 0, 'info' => 'class not exist'];
    }

    public function sort($array) {
        $order = array_column($array, 'sort');
        array_multisort($order, SORT_ASC, SORT_REGULAR, $array);
        return $array;
    }

}
