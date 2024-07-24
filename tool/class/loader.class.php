<?php

//用于加载
function tool() {
    static $loader;
    if (empty($loader)) {
        $loader = new \Loader();
    }
    return $loader;
}

class Loader {

    private $cache = array();

    function func($name) {
        if (isset($this->cache['func'][$name])) {
            return true;
        }
        $file = INCLUDE_PATH . 'function/' . $name . '.func.php';
        if (file_exists($file)) {
            include $file;
            $this->cache['func'][$name] = true;
            return true;
        } else {
            return false;
        }
    }

    function model($name) {
        if (isset($this->cache['model'][$name])) {
            return true;
        }
        $file = INCLUDE_PATH . 'model/' . $name . '.mod.php';
        if (file_exists($file)) {
            include $file;
            $this->cache['model'][$name] = true;
            return true;
        } else {
            return false;
        }
    }

    function classs($name) {
        if (isset($this->cache['class'][$name])) {
            return true;
        }
        $file = INCLUDE_PATH . 'class/' . $name . '.class.php';
        if (file_exists($file)) {
            include $file;
            $this->cache['class'][$name] = true;
            return true;
        } else {
            return false;
        }
    }

}
