<?php
namespace app\admin\controller;
use think\App;
use think\facade\Db;
use think\facade\View;


class Tpl extends Common {
    protected $_theme = 'wap';

    /**
     * 文件操作类实例对象
     * @var object
     */
    protected $_dir = '';

    /**
     * 主题目录
     * @var unknown_type
     */
    protected $_themePath = '';

    /**
     * @name网站模板初始化数据
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        tool()->classs('dir');
        $this->title = '模板管理';
        $in = input('');
        if ($in['theme']) {
            $this->_theme = $in['theme'];
        }
        $this->_themePath = app()->getRootPath();
        $this->_themePath = str_replace('\\', "/", $this->_themePath);
        View::assign('themePath', $this->_themePath);
        $in['folder'] = urldecode($in['folder']);
        if ($in['folder']) {
            View::assign('folder', $in['folder']);
            View::assign('theme', $this->_theme);
            tool()->classs('dir');
            $this->_dir = new \Dir($this->_themePath . $in['folder']);
            //上一级
            if (false !== strpos($in['folder'], '/')) {
                $folders = explode('/', $in['folder']);
                array_pop($folders);
                View::assign('pfolder', implode('/', $folders));
            }
        } else {
            View::assign('theme', $this->_theme);
            $this->_dir = new \Dir($this->_themePath);
        }
        define('TPLNOTE_PATH', ALL_CACHE_PATH . 'tplnote/');
    }

    /**
     * @name模板管理
     */
    public function manage() {
        $in = input('');
        $scanPath = $this->_themePath . $in['folder'];
        if (!xf_validate_file($in['folder'])) {
            $this->error('参数错误！');
        }
        if (file_exists($scanPath)) {
            $fileLists = $this->_dir->toArray();
            if (is_array($fileLists) && !empty($fileLists)) {
                foreach ($fileLists as $k => $v) {
                    if (!$v['isDir']) {
                        $fileLists[$k]['note'] = F($v['filename'] . '_' . md5($v['pathname']), '', TPLNOTE_PATH);
                    }
                }
            }
            usort($fileLists, array($this, 'dirSort'));
            View::assign('data', $fileLists);
            $this->display();
        } else {
            $this->error('目录不存在');
        }
    }

    /**
     * @name模板编辑
     */
    public function edit() {
        $in = input();
        if ($in['ajax'])
            $this->_ajax_edit();
        $in['filename'] = urldecode($in['filename']);
        tool()->classs('NOOP_Translations');
        tool()->func('editor');
        $config_path = array(
            APP_PATH . 'Common/Lang/zh-cn.php',
            APP_PATH . 'Common/Conf/config.php',
            APP_PATH . 'H/Conf/config.php',
            APP_PATH . ADMIN_MODULE . '/Conf/config.php',
        );
        //保存模板
        if (IS_POST) {
            $file = $in['info']['path'] . '/' . $in['info']['filename'];

            if (in_array($in['filename'], $config_path)) {
                $file = $in['filename'];
            } elseif (!xf_validate_file($in['info']['filename'])) {
                $this->error('参数错误！');
            }

            $file = realpath($file);
            $newcontent = wp_unslash($_POST['info']['content']);
            if (is_writeable($file)) {
                $f = fopen($file, 'w+');
                if ($f !== false) {
                    fwrite($f, $newcontent);
                    fclose($f);
                }
            }
            $this->success('模板保存成功');
            die;
        }

        $file = realpath($this->_themePath . $in['filename']);
        View::assign('filename', $in['filename']);
        if (filetype($file) == 'file' || in_array($in['filename'], $config_path)) {

            if (in_array($in['filename'], $config_path)) {
                $file = $in['filename'];
            } elseif (!xf_validate_file($in['filename'])) {
                $this->error('参数错误！');
            }

            //获取文件内容
            $f = fopen($file, 'r');
            $content = fread($f, filesize($file));
            $content = esc_textarea($content);

            View::assign('content', $content);
            $data['filename'] = basename($file);
            $data['path'] = dirname($file);
            $data['info'] = pathinfo($file);
            View::assign('data', $data);
            $this->title = $data['path'] . '/' . $data['filename'];
            $this->display();
        } else {
            $this->error('文件不存在！');
        }
    }

    /**
     * @name处理编辑模板时候的ajax请求
     */
    protected function _ajax_edit() {
        $in = input('');
        switch ($in['ajax']) {

            case 'getcontent':
                $file = realpath($this->_themePath . $in['filename']);
                if (!xf_validate_file($in['filename'])) {
                    $this->error('参数错误！');
                }
                $content = file_get_contents($file);
                echo $content;
                break;
        }
        exit();
    }

    /**
     * @name删除模板
     */
    public function delete() {
        $in = input('');
        $file = realpath($this->_themePath . $in['filename']);
        if (!xf_validate_file($in['filename'])) {
            $this->error('参数错误！');
        }
        if (file_exists($file)) {
            unlink($file);
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * @name清除模板缓存
     */
    public function cache() {
        tool()->classs('FileUtil');
        $FileUtil = new \FileUtil();
        $FileUtil->unlinkDir(RUNTIME_PATH . 'Cache');
        $this->success('模板缓存清除成功');
    }

    /**
     * @name标签生成向导
     */
    public function create_tag() {
        $in = input('');

        $this->display();
    }

    /**
     * @name对模板文件进行排序
     *
     * @param unknown_type $a
     * @param unknown_type $b
     * @return unknown
     */
    protected function dirSort($a, $b) {
        if ($a['type'] == 'dir' && $b['type'] == 'file') {
            return false;
        } else if ($a['type'] == 'file' && $b['type'] == 'dir') {
            return true;
        } else {
            return strcmp($a["filename"], $b["filename"]);
        }
    }

}
