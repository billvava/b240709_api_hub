<?php

namespace app\common\service\storage\engine;


use Qcloud\Cos\Client;

/**
 * 腾讯云存储引擎
 */
class Cos extends Server
{
    private $config;
    private $res;
    private $client;

    /**
     * 构造方法
     * Aliyun constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
        $this->client = new Client(array(
            'region' => $this->config['region'],
            'schema' => 'http', //协议头部，默认为http
            'credentials' => array(
                'secretId' => $this->config['secret_id'],
                'secretKey' => $this->config['secret_key'])
        ));
    }

    /**
     * 执行上传
     * @return bool|mixed
     */
    public function upload()
    {
        if (!$this->fileInfo) {
            $this->error = "未设置文件";
            return false;
        }
        $object = "{$this->config['dir']}//{$this->filePath}{$this->fileName}";
        $object = str_replace('//', '/', trim($object, '/'));
        $object = str_replace('//', '/', $object);
//        p($this->fileInfo);
        try {
            $res = $this->client->upload($this->config['bucket'], $object, file_get_contents($this->fileInfo['tmp_name']));
//            $GuzzleHttp = new GuzzleHtpp
//            p( $res);

            $this->res = [
                'file' => $object,
                'type'=>$this->type,

                'file_url' => $this->config['cdn_domain'] . '/' . $object,
                'source'=>substr(strrchr( strtolower(__CLASS__), "\\"), 1),
            ];
        } catch (\Exception $e) {

            $this->error = $e->getMessage();
            return false;

        }


        return true;
    }

    public function put(){
        if (!$this->fileBody) {
            $this->error = "未设置文件";
            return false;
        }
        $object = "{$this->config['dir']}//{$this->filePath}{$this->fileName}";
        $object = str_replace('//', '/', trim($object, '/'));
        $object = str_replace('//', '/', $object);
        try {
            $this->client->upload($this->config['bucket'], $object, $this->fileBody);
            $this->res = [
                'file' => $object,
                'file_url' => $this->config['cdn_domain'] . '/' . $object,
                'source'=>substr(strrchr( strtolower(__CLASS__), "\\"), 1),
            ];
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }


        return true;
    }
    /**
     * 删除文件
     * @param $fileName
     * @return bool|mixed
     */
    public function delete($fileName)
    {
        $this->client->deleteObject(array(
            'Bucket' => $this->config['bucket'],
            'Key' => $fileName,
        ));
        return true;
    }

    public function getFielUrl($obj,$map=[]){
        if (!$obj) {
            return '';
        }
        if(isset($map['type']) ){
            if($map['type'] == 'resize' && $map['width'] && strpos($obj,'imageView2/1/w')===false ) {
                $pre = '&';
                if(strpos($obj,'?') === false){
                    $pre = '?';
                }
                $obj = $obj . "{$pre}imageView2/1/w/{$map['width']}";
            }
        }
        if (!is_contain_http($obj)) {
            $wapurl = $this->config['cdn_domain'] . '/';
            $obj = $wapurl. trim($obj,'/');
        }else{
            $arr_url = parse_url($obj);
            $host = "{$arr_url['scheme']}://{$arr_url['host']}";
            if($host !=  $this->config['cdn_domain']){
                $obj = str_replace($host, $this->config['cdn_domain'],$obj);
            }
        }
        return $obj;


    }

    /**
     * 返回文件路径
     * @return mixed
     */
    public function getRes()
    {
        return $this->res;
    }

    public function getSts(){
        tool()->classs('qcloud_sts');
        $sts = new \qcloud_sts();
        $storage = C('storage');
        $cos_config = $storage['engine']['cos'];
        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            'proxy' => '',
            'secretId' =>  $this->config['secret_id'], // 固定密钥
            'secretKey' =>  $this->config['secret_key'], // 固定密钥
            'bucket' => $this->config['bucket'], // 换成你的 bucket
            'region' => $this->config['region'], // 换成 bucket 所在园区
            'durationSeconds' => 1800, // 密钥有效期
            // 允许操作（上传）的对象前缀，可以根据自己网站的用户登录态判断允许上传的目录，例子： user1/* 或者 * 或者a.jpg
            // 请注意当使用 * 时，可能存在安全风险，详情请参阅：https://cloud.tencent.com/document/product/436/40265
            'allowPrefix' => str_replace('//','/', $this->config['dir'].'/*'),
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array (
                // 所有 action 请看文档 https://cloud.tencent.com/document/product/436/31923
                // 简单上传
                'name/cos:PutObject',
                'name/cos:PostObject',
                // 分片上传
                'name/cos:InitiateMultipartUpload',
                'name/cos:ListMultipartUploads',
                'name/cos:ListParts',
                'name/cos:UploadPart',
                'name/cos:CompleteMultipartUpload'
            )
        );
        if($config['allowPrefix'] == '/*'){
            $config['allowPrefix']  = '*';
        }
// 获取临时密钥，计算签名
        $tempKeys = $sts->getTempKeys($config);
        // 返回数据给前端
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // 这里修改允许跨域访问的网站
        header('Access-Control-Allow-Headers: origin,accept,content-type');

        $tempKeys['bucket'] = $this->config['bucket'];
        $tempKeys['region'] = $this->config['region'];

        $object = "{$this->config['dir']}/{$this->type}/" . date('Y-m') . "/" . date('d') . "/";
        $object = str_replace('//', '/', trim($object, '/'));
        $object = str_replace('//', '/', $object);

        $tempKeys['uploadDir'] = $object;
        echo json_encode($tempKeys);
        die;
//        echo json_encode($tempKeys);
    }
}
