<?php

namespace app\admin\controller;

use app\admin\model\AdminUser;
use think\App;
use app\admin\model\AdminNav;
use think\facade\Db;
use think\facade\View;

class Plug extends Common {

    public $model;

    public function __construct(App $app) {
        parent::__construct($app);
        $this->model = new \app\admin\model\Plug();
    }

    /**
     * 应用商店
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
        $logs = $this->model->column('token');
        View::assign('title', '安装扩展');
        View::assign('logs', $logs);
        return $this->display();
    }

    /**
     * 开始安装
     */
    public function install() {
        set_time_limit(120);
        $in = input();
        //检测版本号
        $system_versions = C('system_versions');
        if ($in['minversions'] > 0 && $system_versions < $in['minversions']) {
            show_msg("该安装程序最低版本要求{$in['minversions']}，您当前系统版本号{$system_versions}过低", 0);
            die;
        }
        if ($in['maxVersions'] > 0 && $system_versions > $in['maxVersions']) {
            show_msg("该安装程序最大版本要求{$in['minversions']}，您当前系统版本号{$system_versions}过高", 0);
            die;
        }
        if ($in['rely']) {
            $re = array_filter(explode(',', $in['rely']));
            foreach ($re as $v) {
                $is = $this->model->removeOption()->where([
                                ['token', '=', $v]
                        ])->value('id');
                if (!$is) {
                    show_msg("缺少依赖{$in['rely']}", 0);
                    die;
                }
            }
        }
        //安装Thinkphp模块
        if ($in['installtype'] == 'module') {
//            $in['installdirectory'] = ucfirst($in['installdirectory']);
            $mkdir = app()->getBasePath() . "{$in['installdirectory']}";
            //检查目录
            if (is_dir($mkdir)) {
                show_msg("目录{$mkdir}已经存在，你可能已经安装过了，请先确认后再重新安装", 0);
                die;
            }
            //下载安装文件
            $res = $this->download($in['downloadurl']);
            if ($res['status'] == 0) {
                show_msg("{$res['msg']}", 0);
                die;
            }
            show_msg("{$res['msg']}", 1);
            //解压安装文件
            $unzipRes = $this->unzipFile($res['file'], app()->getBasePath());
            unlink($res['file']);
            show_msg("解压操作执行完毕，删除已经下载的安装包{$res['file']}");
            if ($unzipRes['status'] == 0) {
                show_msg("{$unzipRes['msg']}", 0);
                die;
            }
            $show_url = $in['show_url'];
            show_msg("<a href='{$show_url}'>访问地址:{$show_url}</a>", 1);
        }
        //独立扩展
        elseif ($in['installtype'] == 'extend') {
            $mkdir = "./{$in['installdirectory']}";
            //检查目录
            if (is_dir($mkdir)) {
                show_msg("目录{$mkdir}已经存在，你可能已经安装过了，请先确认后再重新安装", 0);
                die;
            }
            //下载安装文件
            $res = $this->download($in['downloadurl']);
            if ($res['status'] == 0) {
                show_msg("{$res['msg']}", 0);
                die;
            }
            show_msg("{$res['msg']}", 1);
            //解压安装文件
            $unzipRes = $this->unzipFile($res['file'], $mkdir);
            unlink($res['file']);
            show_msg("解压操作执行完毕，删除已经下载的安装包{$res['file']}");
            if ($unzipRes['status'] == 0) {
                show_msg("{$unzipRes['msg']}", 0);
                die;
            }
            show_msg("{$unzipRes['msg']}", 1);
            $show_url = $in['show_url'];
            show_msg("<a href='{$show_url}'>访问地址:{$show_url}</a>", 1);
        }
        //直接覆盖，插件
        elseif ($in['installtype'] == 'com') {
            //下载安装文件
            $res = $this->download($in['downloadurl']);
            if ($res['status'] == 0) {
                show_msg("{$res['msg']}", 0);
                die;
            }
            show_msg("{$res['msg']}", 1);
            //解压安装文件
            $unzipRes = $this->unzipFile($res['file'], '../');
            unlink($res['file']);
            show_msg("解压操作执行完毕，删除已经下载的安装包{$res['file']}");
            if ($unzipRes['status'] == 0) {
                show_msg("{$unzipRes['msg']}", 0);
                die;
            }
            show_msg("{$unzipRes['msg']}", 1);
            if ($in['install_sql']) {
                tool()->func('install');
                execute_sql("../{$in['install_sql']}", 1);
            }
            $show_url = $in['show_url'];
            show_msg("<a href='{$show_url}'>访问地址:{$show_url}</a>", 1);
        }
        if (isset($in['token'])) {
            $this->model->removeOption()->insert([
                'name' => $in['name'],
                'token' => $in['token'],
                'time' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($in['installtype'] == 'module' && $in['show_url']) {
            header("location:{$in['show_url']}");
        }
    }

    /**
     * 下载安装文件
     * @param type $url
     */
    private function download($url, $putDir = './') {
        if (!$url) {
            return array('status' => 0, 'msg' => "下载安装文件失败，文件不存在！");
        }
        $newfname = $putDir . basename($url);
        if (file_exists($newfname)) {
            return array('status' => 0, 'msg' => "下载安装文件失败，文件{$newfname}已经存在，为防止覆盖，请重新检查再安装！");
        }
        $file = fopen($url, "rb");
        if ($file) {
            $newf = fopen($newfname, "wb");
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
        if (file_exists($newfname)) {
            return array('status' => 1, 'msg' => "安装文件已经下载成功，准备解压", 'file' => $newfname);
        } else {
            return array('status' => 0, 'msg' => "下载失败");
        }
    }

    /**
     * 解压到目录
     * @param type $filename
     * @param type $path
     */
    private function unzipFile($filename, $path) {
        //先判断待解压的文件是否存在
        if (!file_exists($filename)) {
            return array('status' => 0, 'msg' => "安装文件解压失败，文件{$filename}不存在！");
        }
        $starttime = explode(' ', microtime()); //解压开始的时间
        //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
        $filename = iconv("utf-8", "gb2312", $filename);
        $path = iconv("utf-8", "gb2312", $path);
        //打开压缩包
        $resource = zip_open($filename);
        $i = 1;
        //遍历读取压缩包里面的一个个文件
        while ($dir_resource = zip_read($resource)) {
            //如果能打开则继续
            if (zip_entry_open($resource, $dir_resource)) {
                //获取当前项目的名称,即压缩包里面当前对应的文件名
                $file_name = $path . zip_entry_name($dir_resource);

                if (file_exists($file_name) && !is_dir($file_name)) {
                    // return array('status' => 0, 'msg' => "文件解压失败，文件{$file_name}已经存在，为防止覆盖，请重新检查再安装！");
                }
                //以最后一个“/”分割,再用字符串截取出路径部分
                $file_path = substr($file_name, 0, strrpos($file_name, "/"));

                //如果路径不存在，则创建一个目录，true表示可以创建多级目录
                if (!is_dir($file_path)) {
                    mkdir($file_path, 0777, true);
                }
                //如果不是目录，则写入文件
                if (!is_dir($file_name)) {
                    //读取这个文件
                    $file_size = zip_entry_filesize($dir_resource);
                    //最大读取6M，如果文件过大，跳过解压，继续下一个
                    if ($file_size < (1024 * 1024 * 6)) {
                        $file_content = zip_entry_read($dir_resource, $file_size);
                        file_put_contents($file_name, $file_content);
                    } else {
                        show_msg($i++ . " 此文件已被跳过，原因：文件过大， -> " . iconv("gb2312", "utf-8", $file_name), 'blue');
                    }
                }
                //关闭当前
                zip_entry_close($dir_resource);
            }
        }
        //关闭压缩包
        zip_close($resource);
        $endtime = explode(' ', microtime()); //解压结束的时间
        $thistime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);
        $thistime = round($thistime, 3); //保留3为小数
        return array('status' => 1, 'msg' => "安装文件解压完毕！，本次解压花费：{$thistime} 秒。");
    }

}
