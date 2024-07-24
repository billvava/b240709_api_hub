<?php
namespace app\common\lib;

use think\exception\HttpResponseException;
use think\facade\View;

class Admin
{

    private $path;


    public function __construct() {
        $this->path=app()->getBasePath().'common/view/Admin/';

    }
    public function header($pram) {
        return View::fetch($this->path.'header.php');

    }


    public function footer($pram) {
        return View::fetch($this->path.'footer.php');

    }
    public function top($pram) {
        return View::fetch($this->path.'top.php');

    }





}