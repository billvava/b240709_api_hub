<?php

namespace app\common\service\storage\engine;

use think\Exception;

/**
 * 本地文件驱动
 * Class Local
 * @package app\common\library\storage\drivers
 */
class Local extends Server
{
    private $res;

    public function __construct()
    {
        parent::__construct();
        tool()->classs('Uploadfile');
        tool()->classs('Image');
    }

    /**
     * 上传图片文件
     * @return array|bool
     */
    public function upload()
    {
        if (!$this->fileInfo) {
            $this->error = "未设置文件";
            return false;
        }
        // 上传目录
        $path = './uploads/' . $this->filePath;
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
        $set = array(
            'savePath' => $path, //上传文件后保存的目录(要可写)
            'isThumb' => false, //生成缩略图
            'thumb' => array(array(100, 100)),
        );
        $up = new \Uploadfile();
        $up->setAttrib($set);
        if ($up->isUploaded()) {
            $up->upload($this->fileName);
            if ($up->hasError()) {
                $this->error = $up->error();
                return false;
            } else {
                $pic = $up->getUploadedFile();
                $file = trim($set['savePath'] . $pic, '.');
                $url = C('wapurl') . $file;
                $this->res = [
                    'file' => $file,
                    'file_url' => $url,
                    'source' => substr(strrchr(strtolower(__CLASS__), "\\"), 1),
                    'type'=>$this->type,
                ];
                return true;
            }
        } else {
            $this->error = '没有可上传的文件';
            return false;
        }
    }


    public function put()
    {
        if (!$this->fileBody) {
            $this->error = "未设置文件";
            return false;
        }
        // 上传目录
        $path = './uploads/' . $this->filePath;
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
        $new_file = "{$path}{$this->fileName}";
        $bool = file_put_contents($new_file, $this->fileBody);
        if (!$bool) {
            $this->error = '文件写入失败';
            return false;
        }
        $file = trim($new_file, '.');
        $url = C('wapurl') . $file;
        $this->res = [
            'file' => $file,
            'file_url' => $url,
            'source' => substr(strrchr(strtolower(__CLASS__), "\\"), 1),
            'type'=>$this->type,
        ];

        return true;
    }

    public function getFielUrl($obj, $map = [])
    {
        if (!$obj) {
            return '';
        }
//        if(isset($map['type']) ){
//            if($map['type'] == 'resize' && $map['width'] && strpos($obj,'image/resize')===false ) {
//                $obj = $obj . "?x-oss-process=image/resize,w_{$config['width']},limit_0";
//            }
//        }
        if (!is_contain_http($obj)) {
            $wapurl = C('wapurl');
            if (!$wapurl) {
                $wapurl = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/";
            }
            $obj = $wapurl . trim($obj, '/');
        }
        return $obj;


    }

    public function getSts(){
        $this->error = "本地储存没有此功能";
        return false;
    }
    /**
     * 删除文件
     * @param $fileName
     * @return bool|mixed
     */
    public function delete($file)
    {
        // 文件所在目录
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    public function getRes()
    {
        return $this->res;
    }


}
