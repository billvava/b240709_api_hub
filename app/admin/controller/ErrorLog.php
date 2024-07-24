<?php
/**
    *代码自动生成所需参数
    *moudleName:模块名称
    *tableTitle:表注释
    *modelName:模型名称
    **/
namespace app\admin\controller;
use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;
use app\admin\model\ErrorLog as model;

/**
 * 错误日志
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-document_fill
 */
class ErrorLog extends BCOM{

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->name = '错误日志';
        $this->create_seo($this->name);
    }

    /**
     * 错误日志
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $page = $this->in['p'] ? $this->in['p'] : 1;
        $pagenum = 10;
        $path = ERRLOG_PATH;
        $array = array();
        if (is_dir($path)) {
            $handle = opendir($path);
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                if (xf_validate_file($file)) {
                    $filename = $path . $file;
                    $info = pathinfo($filename);
                    $array[] = array(
                        'filename' => $file,
                        'file' => $info['filename'],
                        'filemtime' => date('Y-m-d H:i:s', filemtime($filename)),
                        'filesize' => byte_format(filesize($filename)),
                    );
                    $i++;
                }
            }
        }
        $count = count($array);
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, $pagenum);
        $data['page'] = $Page->show();
        $data['list'] = $array;
        View::assign('page', $page);
        View::assign('pagenum', $pagenum);
        View::assign('i', $i);

        View::assign('data', $data);
        View::assign('title', '列表');
        return $this->display();
    }

    /**
     * 下载
     * @auto true
     * @auth true
     * @menu false
     */
    public function download() {
        $filename = ERRLOG_PATH . $this->in['file'] . '.log';
        return download($filename);
    }

    /**
     * 查看
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
        $path = ERRLOG_PATH;
        $file = $this->in['file'] . '.log';
        if (xf_validate_file($file)) {
            $content = file_get_contents($path . $file);
            View::assign('content', $content);
            return $this->display();
        }
    }

    /**
     * 全部删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function delall() {
        $path = ERRLOG_PATH;
        tool()->classs('FileUtil');
        $FileUtil = new \FileUtil();
        $FileUtil->unlinkDir($path);
        $this->success(lang('s'));
    }

    /**
     * 单个删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $path = ERRLOG_PATH;
        $file = $this->in['file'] . '.log';
        if (xf_validate_file($file)) {
            unlink($path . $file);
        }
        $this->success(lang('操作成功'));
    }

}
