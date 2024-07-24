<?php

namespace app\home\controller;

use app\BaseController;
use app\common\service\storage\Driver;
use app\home\model\O;
use think\App;
use think\facade\Env;
use think\facade\Db;
use think\facade\View;

class Ajax extends BaseController
{

    public $lib;

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    //省市联动
    function ajax_address()
    {
        $in = input();

        $d_city = (new O())->get_quyu($in['id']);
        $html = '<option value="">请选择</option>';
        foreach ($d_city as $v) {
            $html .= "<option value='{$v["id"]}'>{$v['name']}</option>";
        }
        json_msg('1', '', '', array('html' => $html));
    }

    /**
     * 上传图片
     */
    public function upload_img()
    {
        $val = input('val', '', 'trim');

        $store = new Driver();
        $store->setBase64($val);
        $store->put();
        if ($error = $store->getError()) {
            json_msg('0', $error);
        }
        $res = $store->getRes();
        json_msg('1', null, null, array(
            'file' => $res['file'],
            'url' => $res['file_url'],
            'source' => $res['source'],
        ));
    }

    /**
     * 图片上传(ajax)
     * @return \think\Response|void
     * @throws \Exception
     */
    public function upload_images()
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
            'url' => $res['file_url'],
            'source' => $res['source'],
        ));
    }

}
