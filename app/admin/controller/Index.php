<?php

namespace app\admin\controller;

use app\admin\model\AdminUser;
use think\App;
use app\admin\model\AdminNav;
use think\facade\Db;
use think\facade\View;

class Index extends Common {

    public function __construct(App $app) {
        parent::__construct($app);
    }

    public function index() {

        View::assign('data', $this->data);
        return $this->display();
    }

    public function get_menu_json() {
        $AdminNav = new AdminNav();
        $m_list = $AdminNav->get_all(3600, 1);
        $m_list = $AdminNav->getNavData($m_list, 0, 0);
        return json($m_list);
    }
    
    /**
     * 后台UI配置
     * @return type
     */
    public function get_json() {
        $title = C('title');
        $logo = "/static/admin/images/logo.png";
        $meun_url = url(ADMIN_URL . '/index/get_menu_json');
        $admin_home_url = str_replace('@', ADMIN_URL, C('admin_home_url'));
        $data = array(
            "logo" => array(
                "title" => "{$title}",
//                "image" => "{$logo}"
            ),
            "menu" => array(
                "data" => "{$meun_url}",
                "accordion" => "1",
                "method" => "GET",
                "control" => "1", //1=开启多组
                "select" => ""),
            "tab" => array(
                "muiltTab" => true,
                "keepState" => true,
                "enable" => true,
                "tabMax" => "30",
                "index" => array(
                    "id" => "0",
                    "href" => $admin_home_url,
                    "title" => "首页"
                )
            ),
            "theme" => array(
                "defaultColor" => "2",
                "defaultMenu" => "dark-theme",
                "allowCustom" => "1",
                "banner"=>false,
            ),
            "colors" => array(
                "0" => array("id" => "1", "color" => "#2d8cf0"),
                "1" => array("id" => "2", "color" => "#5FB878"),
                "2" => array("id" => "3", "color" => "#1E9FFF"),
                "3" => array("id" => "4", "color" => "#FFB800"),
                "4" => array("id" => "5", "color" => "darkgray")),

            "other" => array("keepLoad" => "800", "autoHead" => ""),
            "header" => array());
        return json($data);
//        $json = json_decode($json, true);
//        return $json;
    }

    // 默认信息
    public function systeminfo() {

        if (request()->isAjax()) {
            $in = input();
            if (isset($in['show_default_page'])) {
                $val = $in['show_default_page'] == 'true' ? 0 : 1;
                Db::name('admin_user')->where(['id' => $this->adminInfo['id']])->save(['show_default_page' => $val]);
                session('admin.show_default_page', $val);
            }
        }
        $path = ERRLOG_PATH;
        $err_size = 0;
        $err_str = '';
        if (is_dir($path)) {
            $handle = opendir($path);
            $array = array();
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                if (xf_validate_file($file)) {
                    $filename = $path . $file;
                    $err_size += filesize($filename);
                }
            }
            $err_str = byte_format($err_size);
        }
        view::assign('err_size', $err_size);
        view::assign('err_str', $err_str);

        $log_list = (new AdminUser())->getTopLog($this->adminInfo['username']);
        view::assign('log_list', $log_list);
        return $this->display();
    }

    /**
     * 选择连接
     */
    public function sel_link() {
        return $this->display();
    }

    /*
     * 选择经纬度
     * 请在页面设置函数sel_dot(lat,lng)接收
     */

    public function sel_dot() {
        return $this->display();
    }

    /*
     * wap编辑器
     * U示例：('wap_editor',array('func'=>'call_back'));
     */

    public function wap_editor() {
        return $this->display();
    }

}
