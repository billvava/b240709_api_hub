<?php

namespace app\common\service\storage\engine;

use think\Request;
use think\Exception;

/**
 * 存储引擎抽象类
 * Class server
 */
abstract class Server
{
    /* @var $file \think\File */
    protected $error;
    protected $fileName;
    protected $filePath;

    protected $fileInfo;
    protected $fileAllowExt;
    protected $type;

    protected $fileBody;


    /**
     * 构造函数
     * Server constructor.
     */
    protected function __construct()
    {
        $storage = C('storage');
        $this->fileAllowExt =$storage['fileAllowExt'];
    }

    /**
     * 设置上传的文件信息
     * @param string $name
     * @throws Exception
     */
    public function setUpload($name = '')
    {
        // 接收上传的文件
        if (!$_FILES) {
            $this->error = '未找到上传文件的信息';
            return false;
        }
        if (!$name) {
            $this->fileInfo = reset($_FILES);
            $name = array_keys($_FILES)[0];
        } else {
            $this->fileInfo = $_FILES[$name];
        }
//        $this->file = request()->file($name);
        if (empty($this->fileInfo)) {
            $this->error = '未找到上传文件的信息';
            return false;
        }

        try {
            // 获取上传的文件，如果有上传错误，会抛出异常
            $file = \think\facade\Request::file($name);
            // 如果上传的文件为null，手动抛出一个异常，统一处理异常
            if (null === $file) {
                // 异常代码使用UPLOAD_ERR_NO_FILE常量，方便需要进一步处理异常时使用
                $this->error = '未找到上传文件的信息~';
                return false;
            }
            $storage = C('storage');
            $max_file_size = $storage['max_file_size'];
            // 使用验证器验证上传的文件
            validate(['file' => [
                // 限制文件大小(单位b)，这里限制为4M
                'fileSize' => $max_file_size * 1024 * 1024,
                // 限制文件后缀，多个后缀以英文逗号分割
                'fileExt' => $this->getALLExt(),
            ]])->check(['file' => $file]);

        } catch (\Exception $e) {
            // 如果上传时有异常，会执行这里的代码，可以在这里处理异常
//            throw new Exception($e->getMessage());
            $this->error = $e->getMessage();
            return false;
        }
        // 生成保存文件名
        $this->buildSaveName();
    }

    public function getALLExt()
    {
        $tmp = [];
        foreach ($this->fileAllowExt as $v) {
            if ($v) {
                $tmp = array_merge($tmp, $v);
            }
        }
        return implode(',', $tmp);
    }


    public function setBase64($body = '')
    {
        if (!$body) {
            $this->error = '文件内容不能为空';
            return false;
        }
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $body, $result)) {
            //验证一下
            $ext_arr = $this->fileAllowExt['images'];
            $ext_type = 'images';
            $is_safetype = substr($body, 0, 30);
            $flag = 0;
            foreach ($ext_arr as $v) {
                if (stripos($is_safetype, $v) >= 0) {
                    $flag = 1;
                }
            }
            if ($flag == 0) {
                $this->error = '该只能上传图片';
                return false;
            }
            // 自动生成文件名
            $ext = $result[2];
            $ext = strtolower($ext);
            if(!in_array($ext,$ext_arr)){
                $this->error('该文件不允许!');
                return false;
            }

            $this->fileName = date('YmdHis') . substr(md5(uniqid()), 0, 5)
                . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . ".{$ext}";
            $this->filePath = "{$ext_type}/" . date('Y-m') . "/" . date('d') . "/";
            $this->fileBody = base64_decode(str_replace($result[1], '', $body));
            return true;
        }
        $this->error = '文件内容不合规';
        return false;
    }

    /**
     * 文件上传
     * @return mixed
     */
    abstract protected function upload();

    abstract protected function put();

    abstract protected function getSts();

    /**
     * 文件删除
     * @param $fileName
     * @return mixed
     */
    abstract protected function delete($fileName);


    abstract protected function getFielUrl($obj, $map);


    /**
     * 返回上传后文件路径
     * @return mixed
     */
    abstract public function getRes();

    /**
     * 返回文件信息
     * @return mixed
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    protected function getRealPath()
    {
        return $this->getFileInfo()['tmp_name'];
    }


    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 生成保存文件名
     */
    private function buildSaveName()
    {
        // 要上传图片的本地路径
        $realPath = $this->getRealPath();
        // 扩展名
        $ext = pathinfo($this->getFileInfo()['name'], PATHINFO_EXTENSION);

        $ext_type = '';
        foreach ($this->fileAllowExt as $k => $v) {
            if (in_array($ext, $v)) {
                $ext_type = $k;
                break;
            }
        }
        if (!$ext_type) {
            throw new Exception('文件后缀不合法');
        }
        $this->type = $ext_type;
        // 自动生成文件名
        $this->fileName = date('YmdHis') . substr(md5($realPath), 0, 5)
            . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . ".{$ext}";
        $this->filePath = "{$ext_type}/" . date('Y-m') . "/" . date('d') . "/";

    }

}
