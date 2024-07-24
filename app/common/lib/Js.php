<?php
namespace app\common\lib;

use think\exception\HttpResponseException;
use think\facade\View;

class Js
{
    private $path;


    public function __construct() {
        $this->path=app()->getBasePath().'common/view/Js/';

    }

    /**
     * 上传各种文件，请先引入plupload
     * 使用方法：<?php echo W('js/upload',array('upload',url('upload/upload_image'),'jpg,gif,png'));  ?>
     * @param type $field
     * @param type $server_url
     * @param type $ext
     */
    public function upload($pram) {

        $field=$pram[0];
        $server_url=$pram[1];
        $ext=$pram[2]??'*';
        View::assign('field', $field);
        View::assign('ext', $ext);
        View::assign('server_url', $server_url);

        return View::fetch($this->path.'upload.php');

    }


    /**
     * 日期插件
     * {:W('js/jedate')}
     * .jeDateTime
     */
    public function jedate() {
        return View::fetch($this->path.'jedate.php');
    }


    /**
     *
     * {:W('js/select2')}
     *
     */
    public function select2() {
        return View::fetch($this->path.'select2.php');
    }



}