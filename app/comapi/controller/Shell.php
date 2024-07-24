<?php

namespace app\comapi\controller;

use think\facade\Db;

class Shell extends \app\BaseController {

    public $fileclass;

    public function __construct(\think\App $app) {
        parent::__construct($app);
        tool()->classs('FileUtil');

        $this->fileclass = new \FileUtil();
    }

    //可以列出扩展包的文件目录
    public function show_file() {
        function scandirFolder($path) {
            $list = [];
            $temp_list = scandir($path);
            foreach ($temp_list as $file) {
                if ($file != ".." && $file != ".") {
                    if (is_dir($path . "/" . $file)) {
                        //子文件夹，进行递归
                        $list = array_merge($list, scandirFolder($path . "/" . $file));
                    } else {
                        //根目录下的文件
                        $list[] = $path . '/' . $file;
                    }
                }
            }
            return $list;
        }

        $list = scandirFolder("./test/");
        foreach ($list as $v) {
            $a = str_replace('./test/', '..', $v);
            echo "'" . $a . "'" . ',<br/>';
        }
    }
    
    //打包的主进程
    public function pack_path() {
        $this->com();
        $this->ball('mall');
        $this->ball('user');
    }

    //打包扩展
    public function com() {
        $data = [
            'cms' => include '../app/comapi/ext_data/cms.php',
        ];
        $tmp_path = "./tmp/";
        $this->fileclass->unlinkDir($tmp_path);
        tool()->func('str');
        foreach ($data as $name => $v) {
            if (!is_dir($tmp_path)) {
                mkdir($tmp_path, 0775, true);
            }
            $bakStr = '';
            //生成数据文件
            foreach ($v['table'] as $tb => $get_data) {
                $tb =  DB_PREFIX . $tb;
                $res = Db::query(" SHOW CREATE TABLE {$tb}");
                $bakStr .= $res[0]['Create Table'] . ';' . PHP_EOL;
                $j = 0;
                $fs = array();
                //分析表里的字段信息
                $DB_NAME = env('DATABASE');
                $nowtable =   $tb;
                $result = Db::query("select * from information_schema.columns where table_schema='" . $DB_NAME . "' and table_name='" . $nowtable . "'");
                $intable = "INSERT INTO `$nowtable` VALUES(";
                foreach ($result as $rv) {
                    $fs[$j] = $rv['COLUMN_NAME'];
                    $j++;
                }
                $fsd = $j - 1;
                if ($get_data == 1) {
                    $res = Db::query("SELECT * FROM `{$tb}` ");
                    foreach ($res as $row2) {
                        //正常情况
                        $line = $intable;
                        for ($j = 0; $j <= $fsd; $j++) {
                            if ($j < $fsd) {
                                $line .= "'" . RpLine(addslashes($row2[$fs[$j]])) . "',";
                            } else {
                                $line .= "'" . RpLine(addslashes($row2[$fs[$j]])) . "');\r\n";
                            }
                        }
                        $bakStr .= $line;
                    }
                }
            }
            file_put_contents("{$tmp_path}com_install.sql", $bakStr);

            foreach ($v['file'] as $f) {
                $aimUrl = str_replace('../', '', $f);
                $aimUrl = $tmp_path . str_replace('./', 'public/', $aimUrl);
                $this->fileclass->copyFile($f, $aimUrl, true);
            }
            $zip = "/www/wwwroot/xfcms6_dev/public/ext/{$name}.zip";
            exec("cd /www/wwwroot/xfcms6_dev/public/tmp/ && zip -r {$zip} ./");
            $this->fileclass->unlinkDir($tmp_path);
        }
    }

    public function ball($type) {
        $api_path = APP_PATH . "{$type}/{$type}api";
        $new_api_path = APP_PATH . "{$type}api";
        if (is_dir($new_api_path)) {
            $this->fileclass->unlinkDir($api_path);
            $this->fileclass->copyDir($new_api_path, $api_path, false);
        }
        $install_lock = APP_PATH . "{$type}/Install/install.lock";
        $new_install_lock = APP_PATH . "{$type}/Install/install.lock2";
        if (file_exists($install_lock)) {
            $this->fileclass->unlinkFile($new_install_lock);
            $this->fileclass->moveFile($install_lock, $new_install_lock);
        }
        $zip = "/www/wwwroot/xfcms6_dev/public/ext/{$type}.zip";
        $this->fileclass->unlinkFile($zip);
        $zip_path = "./{$type}/";
        exec("cd /www/wwwroot/xfcms6_dev/app/ && zip -r {$zip} {$zip_path}");

        //执行完之后
        if (is_dir($new_api_path)) {
            $this->fileclass->unlinkDir($api_path);
        }
        $this->fileclass->moveFile($new_install_lock, $install_lock);
    }

}
