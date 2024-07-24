<?php

namespace app\common\service\storage\engine;

use DateTime;

/**
 * 阿里云存储引擎 (OSS)
 */
class Oss extends Server
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
        $this->client = new \OSS\OssClient($config['access_key_id'], $config['access_key_secret'], $config['endpoint']);
    }

    /**
     * 执行上传
     * @return bool|mixed
     */
    public function upload()
    {
        if(!$this->fileInfo){
            $this->error = "未设置文件";
            return false;
        }
        $object = "{$this->config['dir']}{$this->filePath}{$this->fileName}";
        $object = str_replace('//','/',trim($object,'/'));
        $object = str_replace('//','/',$object);
        $this->client->uploadFile($this->config['bucket'],$object,$this->fileInfo['tmp_name']);
        $this->res = [
            'file' => $object,
            'file_url' => $this->config['cdn_domain'].'/'.$object,
            'source'=>substr(strrchr( strtolower(__CLASS__), "\\"), 1),
            'type'=>$this->type,
        ];
        return true;
    }

    public function put(){
        if(!$this->fileBody){
            $this->error = "未设置文件";
            return false;
        }
        $object = "{$this->config['dir']}{$this->filePath}{$this->fileName}";
        $object = str_replace('//','/',trim($object,'/'));
        $object = str_replace('//','/',$object);
        try {
            $this->client->putObject($this->config['bucket'],$object,$this->fileBody);
            $this->res = [
                'file' => $object,
                'file_url' => $this->config['cdn_domain'].'/'.$object,
                'source'=>substr(strrchr( strtolower(__CLASS__), "\\"), 1),
                'type'=>$this->type,
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
        $this->client->deleteObject($this->config['bucket'], $fileName);
        return true;
    }

    public function getFielUrl($obj,$map=[]){
        if (!$obj) {
            return '';
        }
        if(isset($map['type']) ){
            if($map['type'] == 'resize' && $map['width'] && strpos($obj,'image/resize')===false ) {
                $pre = '&';
                if(strpos($obj,'?') === false){
                    $pre = '?';
                }
                $obj = $obj . "{$pre}x-oss-process=image/resize,w_{$map['width']},limit_0";
            }
        }
        if (!is_contain_http($obj)) {
            $wapurl =$this->config['cdn_domain'] . '/';
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
        function gmt_iso8601($time)
        {
            $dtStr = date("c", $time);
            $mydatetime = new \DateTime($dtStr);
            $expiration = $mydatetime->format(DateTime::ISO8601);
            $pos = strpos($expiration, '+');
            $expiration = substr($expiration, 0, $pos);
            return $expiration . "Z";
        }

        $id = $this->config['access_key_id'];
        $key = $this->config['access_key_secret'];
        $host =$this->config['bucket_domain'];
        $now = time();
        $expire = 10; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = gmt_iso8601($end);

        //文件大小范围.用户可以自己设置
        $condition = array(0 => 'content-length-range', 1 => 0, 2 => 1048576000 * 50);
//设置用户上传指定的前缀
        $dir = "{$this->config['dir']}/{$this->type}/" . date('Y-m') . "/" . date('d');
        $dir = trim($dir,'/').'/';
//用户上传数据的位置匹配,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0 => 'starts-with', 1 => '$key', 2 => $dir);

//设置bucket
//        $bucket = array(0 => 'eq', 1 => '$bucket', 2 => 'xfcommon');

        $conditions = array(0 => $condition, 1 => $start);

        $arr = array('expiration' => $expiration, 'conditions' => $conditions);
//echo json_encode($arr);
//return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $signature = base64_encode(hash_hmac('sha1', $base64_policy, $key, true));
        $response = array(
            'accessid' => $id,
            'host' => $host,
            'policy' => $base64_policy,
            'signature' => $signature,
            'expire' => $end,
            'dir' => $dir
        );

        echo json_encode($response);
        die;
    }

}
