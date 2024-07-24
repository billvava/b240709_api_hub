<?php

namespace app\admin\controller;

use app\admin\model\Field;
use app\common\service\storage\Driver;
use think\App;
use think\Db;
use think\facade\View;

/**
 * 上传设置
 * @auto true
 * @auth true
 * @menu false
 */
class Upload extends Common
{

    private $userid;

    public function __construct(App $app)
    {
        parent::__construct($app);


        tool()->classs('Uploadfile');
        tool()->classs('Image');
        $admin_id = session('admin.id');
        $this->userid = empty($admin_id) ? request()->isGet('userid') : $admin_id;
    }

    /**
     * 上传文件
     * @auto true
     * @auth true
     * @menu false
     */
    public function upload_file()
    {
        //验证字段参数
        $catid = request()->isGet('catid');
        $ext = "";
        $type_params = [
            'create_thumb' => 0,
        ];
        if ($catid) {
//            $res = (new Field())->get_field_par();
//            $res = $res ? $res['0'] : json_msg('0', '参数错误');
//            $type_params = json_decode($res['type_params'], true);
        } else {
            $fexts = '.jpg|.gif|.png|.bmp|.jpeg';
            $ext = "image";
        }

        if (input('field')) {
            $fexts = '.zip|.rar|.doc|.docx|.xls|.ppt|.txt';
            $ext = "file";
        }

        //临时处理
        $fexts = '.zip|.rar|.doc|.docx|.xls|.xlsx|.ppt|.pptx|.txt|.jpg|.gif|.png|.bmp|.jpeg|.mp4|.mp3|.pdf';
//        if ($res['type'] == 'thumb') {
//            $fexts = '.jpg|.gif|.png|.bmp|.jpeg';
//            $ext = "image";
//        } elseif ($res['type'] == 'file') {
//            $fexts = $type_params['file_type'];
//            $ext = "file";
//        }

        $type_params['create_thumb'] = $type_params['create_thumb'] ? $type_params['create_thumb'] : 0;
        $this->uploadFile();
    }

    /**
     * 上传音乐
     * @auto true
     * @auth true
     * @menu false
     */
    public function upload_music()
    {
        $fexts = '.mp3';
        $ext = "music";
        $type_params = array('file_max' => 1);
        $this->uploadFile();
    }

    /**
     * 上传图片
     * @auto true
     * @auth true
     * @menu false
     */
    public function upload_image()
    {
        $fexts = '.jpg|.gif|.png|.bmp|.jpeg';
        $ext = "image";
        $create_water = C('water') > 0 ? 1 : 0;
        $type_params = array('file_max' => 1,
            'create_water' => $create_water,
            'water_type' => C('water'),
            'water_text' => C('waterText'),
            'water_img' => C('waterImage'),
            'create_thumb' => 0,
            'thumb_width' => '',
            'thumb_height' => '',
        );
        $this->uploadFile();
    }

    /**
     * 上传
     * @param type $fexts 可以上传的后缀 .jpg|.gif|.png|.bmp|.jpeg
     * @param type $ext 文件类型 image
     * @param type $type_params 参数
     */
    private function uploadFile()
    {

        $store = new Driver();
        $store->setUpload();
        $store->upload();
        if ($error = $store->getError()) {
            json_msg('0', $error);
        }
        $res = $store->getRes();
        json_msg('1', null, null, array(
            'file' => $res['file'],
            'url' =>  $res['file_url'],
            'source' => $res['source'],
            'type' => $res['type'],

        ));

    }

    /**
     * 上传图片
     * @auto true
     * @auth true
     * @menu false
     */
    public function local_upload_img()
    {

        return $this->display();
    }

    /**
     * 多图片上传
     * @auto true
     * @auth true
     * @menu false
     */
    public function upload_img()
    {
        $type_params['create_thumn'] = false;
        $type_params['thumb_width'] = 0;
        $type_params['thumb_height'] = 0;
        if (input('get.flag') != 1 && $this->in['catid'] > 0) {
            $res = (new Field())->get_field_par();
            $res = $res ? $res['0'] : json_msg('0', '参数错误');
            $type_params = json_decode($res['type_params'], true);
        }

        $store = new Driver();
        $store->setUpload();
        $store->upload();
        if ($error = $store->getError()) {
            json_msg('0', $error);
        }
        $res = $store->getRes();
        json_msg('1', null, null, array(
            'file' => $res['file'],
            'url' =>  $res['file_url'],
            'source' => $res['source'],
            'type' => $res['type'],

        ));

    }

}
