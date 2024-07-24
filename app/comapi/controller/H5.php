<?php

namespace app\comapi\controller;

use app\common\model\User;
use app\common\model\Weixin;
use think\App;
use think\facade\Db;
use think\facade\View;


class H5 extends \app\BaseController
{

    public function get_openid()
    {
//        p($_REQUEST);
//        $str = "http%3A//localhost%3A8080/%23/pages/login_h5/login/login";
//        p(urldecode($str));

        $callback = C('wapurl') . "/h5/pages/login_h5/login/login";
        if ($_REQUEST['callback'] && is_contain_http($_REQUEST['callback'])) {
            $callback = urldecode($_REQUEST['callback']);
        }
        $pre = '?';
        if(strpos($callback,'?')!== false){
            $pre = '&';
        }

        $callback = "{$callback}{$pre}tophp=1";
        $appid = C('appid');
        if(!$appid){
            header("location:{$callback}");
            die;
        }
        $apppwd = C('apppwd');
        if (!class_exists('weixin')) {
            include INCLUDE_PATH . 'weixin/weixin.class.php';
        }
        $wx = new \weixin($appid, $apppwd);
        $siteurl = C('wapurl');
        $tahurl = __SELF__;
        $url = $siteurl . $tahurl;
        $url = str_replace("code={$_GET['code']}", '', $url);
        $url = str_replace("from={$_GET['from']}", '', $url);
        if (!$_GET['code'] || $_GET['from']) {
            $new_url = $wx->create_url2($url);
            header("location:{$new_url}");
            die;
        }
        $data = $wx->get_base_access_token($_GET['code']);
        if (!$data['openid']) {
            header("location:{$callback}");
            die;
//            $this->error('获取微信信息失败', '', '', -1);
        }
        $userModel = new User();
        $id = $userModel->removeOption()->where([
            ['openid', '=', $data['openid']]
        ])->value('id');

        if (!$id) {
            $callback .= "&openid={$data['openid']}";
        }else{
            $token = $userModel->add_token($id);
            $callback .= "&token={$token}";
        }
        header("location:{$callback}");
        die;

    }


}
