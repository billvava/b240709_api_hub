<?php

namespace app\common\lib;

use app\home\model\Category;
use think\exception\HttpResponseException;
use think\Request;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Cache;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Util {

    /**
     * 发送邮件
     * @param type $touser 接收者邮箱
     * @param type $title 邮箱标题
     * @param type $content 邮箱内容，HTML格式
     * @return boolean
     */
    public function email($touser, $title, $content) {
        tool()->classs('smtp');
        //******************** 配置信息 ********************************
        $smtpserver = C('smtpserver'); //SMTP服务器
        $smtpserverport = 25; //SMTP服务器端口
        $smtpusermail = C('smtpusermail'); //SMTP服务器的用户邮箱
        $smtpemailto = $touser; //发送给谁
        $smtpuser = C('smtpusermail'); //SMTP服务器的用户帐号
        $smtppass = C('smtppass'); //SMTP服务器的用户密码
        $mailtitle = $title; //邮件主题
        $mailcontent = $content; //邮件内容
        $mailtype = "HTML"; //邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new \smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false; //是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
        if ($state == "") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 发送短信验证码
     * @param type $tel 手机号码
     * @param type $tpl_id 模板号
     * @return boolean 成功则返回验证码，失败返回false
     */
    public function sms($tel, $tpl_id = null) {
        if ($tpl_id == null) {
            $tpl_id = C('sms_tplid');
        }
        $code = rand(100000, 999999);
        $tpl_value = [
            'code' => $code
        ];
        $res = $this->_sms($tel, $tpl_id, $tpl_value);
        if ($res['status'] == 1) {
            return array('status' => 1, 'info' => '发送成功', 'code' => $code);
        } else {
            return array('status' => 0, 'info' => $res['info']);
        }
    }

    /*
     * 发送短信
     */

    public function _sms($tel, $tpl_id, $tpl_value = array()) {
        $text = "";
        if (!$tel) {
            return array('status' => 0, 'info' => '手机不存在');
        }
        if (!$tpl_id) {
            return array('status' => 0, 'info' => '模板不存在');
        }
        if ($tpl_value && is_array($tpl_value)) {
            foreach ($tpl_value as $k => $v) {
                $nv = urlencode($v);
                $text .= "#{$k}#={$nv}&";
            }
            $text = trim($text, '&');
        }
        $params = [
            'token' => C('apitoken'),
            'phone' => $tel,
            'tplId' => $tpl_id,
            'text' => $text,
        ];
        $apiurl = C('apiurl');
        $host = $apiurl . "/api/index/sendsms";
        $postdata = http_build_query($params);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 5
            )
        );
        $context = stream_context_create($options);
        $result = json_decode(file_get_contents($host, false, $context));
        return (array) $result;
    }

    //云片网短信
    public function yp_sms($tel, $tpl_id = null) {
        tool()->classs('Yunpian');
        $yunpian = new \Yunpian();
        if ($tpl_id == null) {
            $tpl_id = C('yunpian_tplid');
        }
        $code = rand(1000, 9999);
        $new_code = urlencode($code);
        $tpl_value = "#code#={$new_code}";
        $res = $yunpian->send_sms($tel, $tpl_id, $tpl_value);
        if ($res['status'] == 1) {
            return array('status' => 1, 'info' => '发送成功', 'code' => $code);
        } else {
            return array('status' => 0, 'info' => $res['info']);
        }
    }

    /**
     * 动态加载配置参数
     */
    public function load_config() {
        $config_list = cache('xf_config_list');
        if (!$config_list) {
            $config_list = Db::name('system_config')->field('field,val')->select()->toArray();
            $temp = array();
            foreach ($config_list as $key => $value) {
                $temp[$value['field']] = $value['val'];
            }
            cache('xf_config_list', $temp);
            $config_list = $temp;
//            $config_list = $temp;
//            $cache_config = base_path() . 'common/conf/cache.php';
//            $content = '<?php return array(';
//            foreach ($temp as $key => $value) {
//                $value = str_replace("'", "\'", $value);
//                $content .= "'{$key}' => '{$value}',";
//            }
//            $content .= ");";
//            file_put_contents($cache_config, $content);
        }

        Config::set($config_list, 'system');
    }

    /**
     * 404页面
     */
    public function no_found() {

        $response = view(app()->getRootPath() . 'view/public/404.php');
        throw new HttpResponseException($response);
        die;
    }

    /**
     * 获取详情页面URL
     * @param type $cid
     * @return type
     */
    public function get_show_url($cid, $catid = null, $suffix = '.html') {
        if ($catid == null) {
            $name = 'xf_catid_' . $cid;
            $catid = cache($name);
            if (!$catid) {
                $catid = Db::name('content')->where('cid', $cid)->value('catid');
                cache($name, $catid);
            }
        }
        $D = new Category();
        $data = $D->get_all();
        return __ROOT__ . '/' . $data[$catid]['dir'] . '/' . $cid . $suffix;
    }

    /**
     * 获取列表页面URL
     * @param type $catid
     * @return type
     */
    public function get_list_url($catid, $suffix = '/') {
        $D = new Category();
        $res = $D->get_all();
        return __ROOT__ . '/' . $res[$catid]['dir'] . $suffix;
    }

    /**
     * 设置301跳转
     *
     */
    public function set_host() {
        $the_host = $_SERVER['HTTP_HOST'];
        $url = C('siteurl');
        $wapurl = C('wapurl');
        $url_host = str_replace('http://', '', $url);
        $wapurl_host = str_replace('http://', '', $wapurl);
        if ($the_host != $url_host && $the_host != $wapurl_host) {
            if (!$url) {
                return '';
            }
            header('HTTP/1.1 301 Moved Permanently');
            header('Location:' . $url);
            die;
        } else if (is_mobile() && $the_host != $wapurl_host && $wapurl_host) {
            if (!$wapurl) {
                return '';
            }
            header('HTTP/1.1 301 Moved Permanently');
            header('Location:' . $wapurl);
            die;
        } else if ($url_host != $wapurl_host) {
            if ($url_host == $the_host) {
                return 'pc';
            } else if ($wapurl_host == $the_host) {
                return 'wap';
            }
        } else {
            return '';
        }
    }

    /**
     * 将excel文件转换成数组
     * 需要先安装
     * composer require phpoffice/phpspreadsheet
     * 安装条件 php> 7.1  php安装了fileinfo 扩展  Mac 没有扩展的请自重
     *
     * @param $inputFileName
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Calculation\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function ReadExcel($inputFileName) {


        $whatTable = 0;
        $filename = $inputFileName;

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filename);
        // 实例化阅读器对象。
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        // 将文件读取到到$spreadsheet对象中
        $spreadsheet = $reader->load($filename);
        // 获取当前文件内容
        $worksheet = $spreadsheet->getActiveSheet();
        // 工作表总数
        $sheetAllCount = $spreadsheet->getSheetCount();
        // 工作表标题
        for ($index = 0; $index < $sheetAllCount; $index++) {
            $title[] = $spreadsheet->getSheet($index)->getTitle();
        }
        // 读取第一个工作表
        $sheet = $spreadsheet->getSheet($whatTable);
        // 取得总行数
        $highest_row = $sheet->getHighestRow();
        // 取得列
        $highest_column = $sheet->getHighestColumn();
        // 转化为数字
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highest_column);
        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            for ($j = 1; $j <= $highest_row; $j++) {
                $conent = $sheet->getCellByColumnAndRow($i, $j)->getValue();
                $conent = $sheet->getCellByColumnAndRow($i, $j)->getCalculatedValue();
                $info[$j][$i] = $conent;
            }
        }
        return $info;
    }

    /**
     * 禁止收录
     * 关闭APP_DEBUG后自动删除robots.txt
     */
    public function ban_embody() {
        if (request()->isAjax()) {
            return;
        }
        $filename = 'robots.txt';
        if (C('test_env') == 1) {
            $str = "User-agent: * \n";
            $str .= "Disallow: /";
            file_put_contents($filename, $str);
        } else {
            /*
              User-agent: *
              Allow: *
             */
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }

    //网站状态
    public function site_status() {
        if (request()->isAjax()) {
            return;
        }
        if (C('site_status') == 1) {
            return true;
        } else {
            $this->msg(C('site_close_msg'));
        }
    }

    /**
     * 导出xls表格
     * @param string $filename 文件名，不用带后缀
     * @param array $title 第一行标题 格式如 array('订单号', '付款方式',)
     * @param array $data 数据，格式如 array(0=>array('ordernum'=>'C12233','pay_type'=>'微信支付'),1=>array('ordernum'=>'C12233','pay_type'=>'微信支付'),)
     */
    public function xls($filename, $title, $data) {
//        if (!class_exists('finfo')) {
//            $this->old_xls($filename, $title, $data);
//            return;
//        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(0);
        $sheet->setTitle($filename);
        $line = 1;
        $titel2 = [];
        foreach ($title as $k => $v) {
            $titel2[$k + 1] = $v;
        }
        $sheet->getRowDimension($line)->setRowHeight(30);
        foreach ($titel2 as $k => $v) {
            $sheet->getCellByColumnAndRow($k, $line)->setValue($v);
        }
        $line++;
        foreach ($data as $l => $item) {
            $i = 1;
            foreach ($item as $k => $v) {
                $sheet->getCellByColumnAndRow($i, $line)->setValue($v)->setDataType('str');
                $i++;
            }
            $sheet->getStyle($line)->getAlignment()->setWrapText(true);
            $line++;
        }
        ob_end_clean();
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $filename . '.xls"');
        header("Content-Disposition:attachment;filename={$filename}.xls"); //attachment新窗口打印inline本窗口打印
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * 导出xls表格
     * @param string $filename 文件名，不用带后缀
     * @param array $title 第一行标题 格式如 array('订单号', '付款方式',)
     * @param array $data 数据，格式如 array(0=>array('ordernum'=>'C12233','pay_type'=>'微信支付'),1=>array('ordernum'=>'C12233','pay_type'=>'微信支付'),)
     */
    public function old_xls($filename, $title, $data) {
        $title = utfToGbk($title);
        $data = utfToGbk($data);
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        if (is_array($title)) {
            foreach ($title as $key => $value) {
                echo $value . "\t";
            }
        }
        echo "\n";
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                foreach ($value as $_key => $_value) {
                    echo $_value . "\t";
                }
                echo "\n";
            }
        }
    }

    /**
     * 格式化导出
     * @param type $filename
     * @param type $title
     * @param type $data
     */
    public function format_xls($filename, $title, $data) {
        $title = utfToGbk($title);
        $data = utfToGbk($data);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Type: application/force-download");

        header("Content-disposition: attachment; filename=" . $filename . ".xls");
        header('Expires:0');
        header('Pragma:public');
        echo '<meta http-equiv=Content-Type; content=text/html;charset=gb2312>';
        echo '<html><table  border="1">';
        if (is_array($title)) {
            echo '<tr>';
            foreach ($title as $key => $value) {
                echo '<td style="text-align:center;font-size:12px;" width="*">' . $value . '</td>';
            }
            echo '</tr>';
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                echo '<tr>';
                foreach ($value as $_key => $_value) {
                    $type = '';
                    if ($_value > 100000000000000) {
                        $type = 'mso-number-format:\'\@\';';
                    }
                    echo '<td style="text-align:left;font-size:12px; ' . $type . '" width="*" >' . $_value . '</td>';
                }
                echo '</tr>';
            }
        }
        echo '</table></html>';
    }

    /**
     * 系统提示页面
     * @param type $msg
     */
    public function msg($msg) {
        $this->msg_tpl($msg, 'info');
    }

    /**
     * 系统提示页面
     * @param type $msg
     */
    public function s($msg) {
        $this->msg_tpl($msg, 'success');
    }

    /**
     * 系统提示页面
     * @param type $msg
     */
    public function e($msg) {
        $this->msg_tpl($msg, 'cancel');
    }

    public function msgn($msg) {
        if (request()->isAjax()) {
            json_msg(0, $msg);
        } else {
            $this->msg_tpl($msg, 'info', false);
        }
    }

    private function msg_tpl($msg, $type = 'info', $show_btn = 1) {

        View::assign('msg', $msg);
        View::assign('type', $type);
        View::assign('show_btn', $show_btn);
        $response = view(app()->getRootPath() . 'view/public/msg.php');
        throw new HttpResponseException($response);
        die();
    }

    public function check_debug() {
        if (\think\facade\App::isDebug() == true &&
                strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === false &&
                strpos($_SERVER['REMOTE_ADDR'], '127.0.0.1') === false &&
                strpos($_SERVER['SERVER_ADDR'], '127.0.0.1') === false &&
                strpos($_SERVER['HTTP_HOST'], "192.168") === false &&
                strpos($_SERVER['SERVER_ADDR'], '127.0.0.1') === false &&
                $_SERVER['SERVER_ADDR'] != '::1'
        ) {

            return false;
        }
        return true;
    }

    /**
     * 请求接口
     * @param type $param
     * @return type
     */
    public function requestApiUrl($param = array()) {
        $apiurl = C('apiurl');
        $apikey = C('apikey');
        $param_str = '';
        $param['system_program'] = C('system_program');
        $param['system_versions'] = C('system_versions');
        $param['request_ip'] = get_client_ip();
        foreach ($param as $key => $value) {
            $param_str .= "&{$key}={$value}";
        }
        // $dev_json = file_get_contents($apiurl . '?token=' . $apikey . $param_str);
        $dev_json = file_get_contents($apiurl . '/api/index/dev?token=' . $apikey . $param_str);
        return json_decode($dev_json, true);
    }

    //异步请求
    public function asyn_request($url, $param = array()) {
        ignore_user_abort(true); // 忽略客户端断开
        set_time_limit(0);    // 设置执行不超时
        $urlinfo = parse_url($url);
        $host = $urlinfo['host'];
        $path = $urlinfo['path'];
        $query = isset($param) ? http_build_query($param) : '';
        $port = 80;
        $errno = 0;
        $errstr = '';
        $timeout = 30;
        $fp = fsockopen($host, $port, $errno, $errstr, $timeout);
        $out = "POST " . $path . " HTTP/1.1\r\n";
        $out .= "host:" . $host . "\r\n";
        $out .= "content-length:" . strlen($query) . "\r\n";
        $out .= "content-type:application/x-www-form-urlencoded\r\n";
        $out .= "connection:close\r\n\r\n";
        $out .= $query;
        fputs($fp, $out);
        fclose($fp);
    }

}
