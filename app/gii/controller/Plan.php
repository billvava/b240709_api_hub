<?php

namespace app\gii\controller;

use think\facade\View;

/**
 * 2021/3/29 By MaxYeung
 */
class Plan extends Common {

    /**
     * 显示页面
     *
     * @return  [type]  [return description]
     */
    public function index() {
        return View::fetch();
    }

    /**
     * 生成excel
     *
     * @return  [type]  [return description]
     */
    public function excel() {
        if (empty($this->in['path'])) {
            $this->error('请输入效果图路径');
        }
        if (strpos($this->in['path'], '..') !== false) {
            $this->error('不能到其他目录');
        }
        $result = $this->_importImages($this->in['path']);    //获得的所有图片目录以及文件名，以多维数组形式返回，方便处理
        if (is_array($result)) {
            $this->xls('项目进度表' . date('Y-m-d'), ['功能页面', 'API接口', '前端对接'], $result);
            die;
        }
        $this->error('生成失败<br/><br/>' . $result);
    }

    /**
     * 处理图片生成excel
     *
     * @param   [type]  $dir  [$dir description]
     *
     * @return  [type]        [return description]
     */
    private function _importImages($dir) {
        try {
            $handle = opendir($dir); //读资源
            if (!$handle) {
                return false;
            }
            $result = [];

            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $curPath = $dir . '/' . $file;
                    if (is_dir($curPath)) { //判断是否为目录，递归读取文件
                        $result[$file] = $this->_importImages($curPath);
                    } else {
                        $explode = explode('.', $file);
                        $result[] = [
                            'name' => $explode[0],
                            'api' => '',
                            'page' => ''
                        ];
                    }
                }
            }
            closedir($handle);

            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 导出excel
     *
     * @param   [type]  $filename  [$filename description]
     * @param   [type]  $title     [$title description]
     * @param   [type]  $data      [$data description]
     *
     * @return  [type]             [return description]
     */
    private function xls($filename, $title, $data) {
        (new \app\common\lib\Util())->xls($filename, $title, $data);
        
    }

}
