<?php

namespace app\admin\controller;

use app\common\service\storage\Driver;
use think\App;
use think\facade\Db;
use think\facade\View;
use function app\com\controller\gmt_iso8601;

define('IS_WIN', strstr(PHP_OS, 'WIN') ? 1 : 0);
defined('OUT_ENCODE') or define('OUT_ENCODE', IS_WIN ? 'gbk' : 'utf-8');

class Files extends Common
{

    private $system_imgs;

    public function __construct(App $app)
    {
        parent::__construct($app);
        tool()->classs('dir');
        $this->system_imgs = Db::name('system_imgs');
    }

    /**
     * 新版站内图片选择
     * 自定义回调：get参数callback会调用父级callback(field,src,file);
     * @return  [type]  [return description]
     */
    public function new_imgs()
    {
        View::assign('show_top', 0);
        $model = $this->system_imgs;
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($model->count(), C('data_page_count'));
        $show = $Page->show();
        $images = Db::name('system_file')
            ->limit($Page->firstRow, $Page->listRows)
            ->order('id desc')
            ->select()->toArray();
        View::assign([
            'images' => $images,
            'show' => $show,
            'name' => $this->in['name'],
            'field' => $this->in['field'],
            'selectNum' => $this->in['selectNum'] ? $this->in['selectNum'] : 1
        ]);
        return $this->display();
    }

    /**
     * 新站内图片保存
     * 通过前台调用upload方法拿到返回值后提交到该方法保存
     * @return  [type]  [return description]
     */
    public function new_imgs_save()
    {
        $postData = input();
        $type =  $postData['type'] ? : '';
        if(!$type){
            $ext = pathinfo($postData['file'], PATHINFO_EXTENSION);
            $storage = C('storage');
            foreach ($storage['fileAllowExt'] as $k => $v) {
                if (in_array($ext, $v)) {
                    $type = $k;
                    break;
                }
            }
        }
        $id = Db::name('system_file')->insertGetId([
            'file' => $postData['file'],
            'create_time' => date('Y-m-d H:i:s'),
            'name' => $postData['name'] ? $postData['name'] : md5(uniqid()),
            'source' => $postData['source'] ? $postData['source'] : '',
            'type' =>$type,
        ]);
        json_msg(1, null, null, ['id' => $id]);
    }

    /**
     * 新站内图片删除
     *
     * @return  [type]  [return description]
     */
    public function new_imgs_del()
    {
        $images = input('images');
        if ($images) {
            $model = $this->system_imgs;
            // oss图片
            $accessKeyId = C('aliyun_access_key_id');
            $accessKeySecret = C('aliyun_access_key_secret');
            $endpoint = C('aliyun_endpoint');

            foreach ($images as $v) {
                // 待删除oss/本地图片
                $check = Db::name('system_file')->where(['id' => $v])->find();
                switch ($check['source']) {
                    case 'local':
                        // 删除本地图片
                        @unlink('.' . $check['img']);
                        break;
                    case 'oss':
                        $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
                        $ossClient->deleteObject(C('aliyun_oss_bucket'), $check['img']);
                        break;
                }
                // 删除数据库图片
                Db::name('system_file')->where(['id' => $v])->delete();
            }
            $this->success('删除成功');
        }
        $this->error('请选择图片');
    }

    /**
     * @name模板库
     */
    public function tpl()
    {
        $in = input('');
        empty($in ['path']) && $in ['path'] = '/';
        $path = &$in ['path']; //当前浏览路径
        if (!xf_validate_file($path)) {
            $this->error('参数错误！');
        }
        //当前物理路径
        $tpl_dir = realpath(app()->getBasePath() . 'home/View/');

        //可以显示的模板文件类型
        $images_ext = array(
            'html', 'htm'
        );
        $now_dir = realpath($tpl_dir . $path);
        if (!file_exists($now_dir)) {
            $this->error('参数错误！');
        }
        $dir = new \Dir($now_dir);
        $data = $dir->toArray();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['path'] = rtrim(str_replace($tpl_dir, '', $v['pathname']), '/\\');
                $data[$k]['path'] = ltrim(str_replace($tpl_dir, '', $v['pathname']), '/\\');
                if (strpos($data[$k]['path'], '\\'))
                    $data[$k]['path'] = str_replace('\\', '/', $data[$k]['path']);
            }
        }
        //当前目录，上级目录
        $pathArr = explode('/', $path);
        array_pop($pathArr);
        if (empty($pathArr)) {
            $parent_path = '/';
        } else {
            $parent_path = '/' . implode('/', $pathArr);
        }
        usort($data, array($this, 'tplFileSort'));
        View::assign('data', $data);
        View::assign('parent_path', $parent_path);
        View::assign('now_path', $path);
        View::assign('opener_id', $in['opener_id']);
        return $this->display();
    }

    /**
     * @name 对选择模板排序
     * @param unknown_type $a
     * @param unknown_type $b
     */
    protected function tplFileSort($a, $b)
    {
        if ($a['isDir'] == true && $b['isDir'] == false) {
            return false;
        } else if ($a['isDir'] == false && $b['isDir'] == true) {
            return true;
        } else {
            return strcmp($a["filename"], $b["filename"]);
        }
    }

    /**
     * 删除目录及下面的文件夹
     */
    protected function deletedir()
    {
        $in = input('');
        if (!xf_validate_file($in['dir'])) {
            $this->error('参数错误！');
        }
        tool()->classs('FileUtil');
        $FileUtil = new \FileUtil();
        $path = realpath(FANGFACMS_ROOT . 'public/uploads' . $in['dir']);
        $FileUtil->unlinkDir($path);
    }


    public function get_sts()
    {
        $store = new Driver();
        $store->getSts();
        json_msg('0', $store->getError());
    }

}
