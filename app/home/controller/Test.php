<?php

namespace app\home\controller;

use app\BaseController;
use app\user\model\User;
use think\App;
use think\facade\Env;
use think\facade\Db;
use think\facade\View;

class Test extends Common {

    public function __construct(App $app) {
        parent::__construct($app);
    }

    public function load() {

      $info =   Db::name('mall_order')->find(24);
        (new \app\mall\logic\Order())->pay_success($info);
        die;
        echo xf_md5(1234);
        die;
        $headerArray = array("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.9",
            "Connection: keep-alive",
            "Cookie: __guid=6752679.1407902579759530000.1623306090523.406; PHPSESSID=4bab1verd0eijm0iq2c1k64oa7; monitor_count=13",
            "Host: doc.we7shop.com",
            "Referer: http://doc.we7shop.com/apidoc/detail/login/id/15?referer=http%3A%2F%2Fdoc.we7shop.com%2F%2Fapidoc%2Fdetail%2Fid%2F15%2Fpageid%2F44",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36"
        );
        $curl = curl_init();
        $url = "http://doc.we7shop.com//apidoc/detail/id/15/pageid/44";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        echo $output;
        die;
        echo $html;
    }

    public function show_dir() {
        tool()->classs('FileUtil');
        $dir = new \FileUtil();
        $res = $dir->getOnleFileList("./test");

        foreach ($res as $v) {
            echo "{$v}<br/>";
        }
    }

    public function flux() {
        $file = fopen("yanye.com.log", "r");
        $total = 0;
        while (!feof($file)) {
            $hang = fgets($file);
           
            $as = explode(' ', $hang);
            
                    if($as[6] > 0){
                        $total += $as[6];
                    }
        }
        echo $total;
        fclose($file);
    }

}
