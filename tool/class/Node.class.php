<?php

class Node {

    /**
     * 驼峰转下划线规则
     * @param string $name
     * @return string
     */
    public function nameTolower($name) {
        $dots = [];
        foreach (explode('.', strtr($name, '/', '.')) as $dot) {
            $dots[] = trim(preg_replace("/[A-Z]/", "_\\0", $dot), '_');
        }
        return strtolower(join('.', $dots));
    }

    /**
     * 下划线转驼峰规则
     * @param string $name
     * @return string
     */
    public function xiahuaxianTotop($name) {
        $name = str_replace('_controller', '', $name);
        $ns = explode('/', $name);
        $to = "";
        foreach ($ns as $k => $v) {
            if ($k == 0) {
                $to .= "{$v}";
                $to .= "/";
                continue;
            }
            if ($k > 1) {
                $to .= "{$v}";
                break;
            }
            $nv = explode('_', $v);
            foreach ($nv as $vv) {
                $to .= ucfirst($vv);
            }
            $to .= "/";
        }
        return $to;
    }

    /**
     * 获取当前节点内容
     * @param string $type
     * @return string
     */
    public function getCurrent($type = '') {
        $prefix = $Namespace;
        $middle = '\\' . $this->nameTolower($controller);
        $suffix = ($type === 'controller') ? '' : ('\\' . $action);
        return strtr(substr($prefix, stripos($prefix, '\\') + 1) . $middle . $suffix, '\\', '/');
    }

    /**
     * 检查并完整节点内容
     * @param string $node
     * @return string
     */
    public function fullnode($node) {
        if (empty($node))
            return $this->getCurrent();
        if (count($attrs = explode('/', $node)) === 1) {
            return $this->getCurrent('controller') . '/' . strtolower($node);
        } else {
            $attrs[1] = $this->nameTolower($attrs[1]);
            return strtolower(join('/', $attrs));
        }
    }

    /**
     * 获取应用列表
     * @param array $data
     * @return array
     */
    public function getModules($data = []) {
        $path = app()->getRootPath();
        foreach (scandir($path) as $item)
            if ($item[0] !== '.') {
                if (is_dir(realpath($path . $item)))
                    $data[] = $item;
            }
        return $data;
    }

    /**
     * 获取所有控制器入口
     * @param boolean $force
     * @return array
     * @throws \ReflectionException
     */
    public function getMethods($path = '') {
        if (!$path) {
             $path = APP_PATH;
        }
        $ignores = array('__construct', '__set', '__get', '__isset', '__call', '__destruct');
        foreach ($this->scanDirectory($path) as $file) {
            if (preg_match("|/(\w+)/(\w+)/controller/(.+)\.php$|i", $file, $matches)) {
                $xie_count = substr_count($matches[0], '/');
                if ($xie_count != 4) {
                    continue;
                }
                if (strpos($matches[0], '/app') !== 0) {
                    continue;
                }
                $appname = $matches[2];
                $appname = strtolower($appname);
                 if(in_array($appname, array('common','home')) || strpos($appname, 'api')!==false){
                     continue;
                }
                $classname = $matches[3];

                $app_map=config('app.app_map');

                foreach ($app_map as $k => $v){
                    if($v==$appname){
                        $appname2=$k;
                    }else{
                        $appname2=$appname;
                    }
                }
                $prefix = strtr("{$appname2}/{$this->nameTolower($classname)}", '\\', '/');
                $test_clas = (strtr("{$matches[1]}/{$appname}/controller/{$classname}", '/', '\\'));
                $reflection = new \ReflectionClass($test_clas);
                $getDocComment = $reflection->getDocComment();

//                if (strpos($prefix, 'controller') === false) {
//                    continue;
//                }

                $res = $this->_parseComment($getDocComment, $classname);

                if ($res['isauto'] == 1) {
                    $res['node'] = $prefix;
                    $data[$prefix] = $res;
                }

                $ref_public = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

                foreach ($ref_public as $method) {
                    //排查父类
                    if ($method->class != $test_clas) {
                        continue;
                    }

                    if (in_array($metname = $method->getName(), $ignores)) {
                        continue;
                    }

                    $res = $this->_parseComment($method->getDocComment(), $metname);

                    $res['node'] = "{$prefix}/{$metname}";
                    if ($res['isauto'] == 1) {
                        if ($data["{$prefix}"]) {
                            $data["{$prefix}"]['list'][] = $res;
                        } else {
                            $data["{$prefix}/{$metname}"] = $res;
                        }
                    }
                }
            }
        }
        $data = array_change_key_case($data, CASE_LOWER);
        return $data;
    }

    /**
     * 解析硬节点属性
     * @param string $comment 备注内容
     * @param string $default 默认标题
     * @return array
     */
    private function _parseComment($comment, $default = '') {
        $text = strtr($comment, "\n", ' ');
        $title = preg_replace('/^\/\*\s*\*\s*\*\s*(.*?)\s*\*.*?$/', '$1', $text);
        if (!$title) {
            
        }
        if (in_array(substr($title, 0, 5), ['@auth', '@menu', '@auto']))
            $title = $default;




        preg_match("/@icon\s*([A-Za-z_-]+)/i", $text, $m);
        return [
            'name' => $title ?: $default,
            'isauto' => intval(preg_match('/auto\s*true/i', $text)),
            'isauth' => intval(preg_match('/@auth\s*true/i', $text)),
            'ismenu' => intval(preg_match('/@menu\s*true/i', $text)),
            'icon' => isset($m[1])?$m[1]:'' . "",
        ];
    }

    /**
     * 获取所有PHP文件列表
     * @param string $path 扫描目录
     * @param array $data 额外数据
     * @param string $ext 文件后缀
     * @return array
     */
    public function scanDirectory($path, $data = [], $ext = 'php') {
        if (file_exists($path)) {
            if (is_file($path)) {
                $data[] = strtr($path, '\\', '/');
            } elseif (is_dir($path)) {
                foreach (scandir($path) as $item)
                    if ($item[0] !== '.') {
                        $real = rtrim($path, '\\/') . DIRECTORY_SEPARATOR . $item;
                        if (is_readable($real))
                            if (is_dir($real)) {
                                $data = $this->scanDirectory($real, $data, $ext);
                            } elseif (is_file($real) && (is_null($ext) || pathinfo($real, 4) === $ext)) {
                                $data[] = strtr($real, '\\', '/');
                            }
                    }
            }
        }
        return $data;
    }

}
