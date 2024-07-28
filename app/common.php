<?php
// 应用公共文件


use app\common\service\storage\Driver;
use think\facade\Db;

if (!function_exists('log_err')) {
    /**
     * 记录错误
     * @param type $str
     */
    function log_err($str)
    {
        $day = date('Y-m-d');
        $destination = ERRLOG_PATH . "{$day}.log";
        $path = ERRLOG_PATH;
        if ($_SERVER['DOCUMENT_ROOT']) {
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/' . $destination;
            $path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
        }
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
        //检测日志文件大小，超过配置大小则备份日志文件重新生成
        if (is_file($destination) && 2097152 <= filesize($destination)) {
            rename($destination, dirname($destination) . '/' . "{$day}-" . time() . 'log');
        }
        $now = date('Y-m-d H:i:s');
        $logs = '';
        if ($str) {
            $logs .= $str . PHP_EOL;
        }
        $logs .= "request:" . json_encode($_REQUEST) . PHP_EOL;
        if (request()->isPost()) {
            $logs .= "post:" . json_encode($_POST) . PHP_EOL;
        }
        if (request()->isGet()) {
            $logs .= "get:" . json_encode($_GET) . PHP_EOL;
        }

        $input = file_get_contents('php://input', 'r');
        if ($input) {
            $logs .= "input:" . file_get_contents('php://input', 'r') . PHP_EOL;
        }
        $str = "[{$now}] " . $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI'] . ' ' . $_SERVER['REQUEST_METHOD'] . PHP_EOL . $logs . PHP_EOL;
        error_log($str, 3, $destination);

    }
}

if (!function_exists('p')) {
    /**
     * 打印函数
     * @param $data
     * @param int $flag
     */
    function p($data, $flag = 1)
    {
        header("content-type:text/html;charset=utf-8");
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($flag == 1)
            die;
    }

}

if (!function_exists('C')) {
    /**
     * 获取系统配置
     *
     * @param $name
     * @return mixed
     */
    function C($name)
    {
        $data = config($name);
        if (!$data) {
            $data = config('system.' . $name);
        }
        if ($data === null) {
            $data = config('my.' . $name);
        }

        return $data;
    }
}

if (!function_exists('js')) {
    /**
     * 使用JS跳转
     * @param type $url
     */
    function js($url = null)
    {
        $url = $url ? $url : url('/');
        if (request()->isAjax()) {
            $url=(string)$url;
            echo json_encode(array('info' => '', 'status' => 1, 'url' => $url));
            die;
        }
        echo "<script type='text/javascript'>location.href='{$url}'</script>";
        die;
    }
}

if (!function_exists('W')) {

    function W($qt, $parm = [])
    {
        $qt = explode('/', $qt);
        $widget = $qt[0];
        $name = $qt[1];
        echo app($widget)->$name($parm);

    }
}

if (!function_exists('json_msg')) {
    /**
     * 输出JSON格式数据，并退出
     * @param $status string 状态
     * @param $info string 信息
     * @param $data  array 数据
     * @return void
     */
    function json_msg($status = 0, $info = '', $url = '', $data = null)
    {
        $json = array();
        $json['status'] = $status;
        $json['info'] = $info;
        $json['url'] = $url;
        $json['data'] = $data;
        echo json_encode($json);
        die;
    }
}

if (!function_exists('get_client_ip')) {

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip[$type];
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos)
                    unset($arr[$pos]);
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

}

/**
 * 生成MD5的字符串
 * $str 字符串
 * $return string
 */
function xf_md5($str = 'lyy') {
    return md5('fc0bd22cbc58888888f8019765a10da' . md5($str . 'fc0bd22cbc58888888f8019765a10da'));
}

//验证手机号码
function verify_mobile($mobile){
    $mobile = intval($mobile);
    $pattern = '/^1[3456789]\d{9}$/';
    if (preg_match($pattern, $mobile)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 将自动判断网址是否加http://
 *
 * @param $http
 * @return  bool
 */
function is_contain_http($url) {
     $url = $url.'';
    if ($url == '')
        return false;

    if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://')
        return false;
    return true;
}

function is_mobile(){

    return app()->request->isMobile();
}




/**
 * 递归一个目录，可以返回数组
 * @param type $dir 操作的目录
 * @param type $flag 0=不删除，1=删除
 * @return type
 */
function xf_scandir($dir, $flag = 0) {
    $files = array();
    $i = 10;
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . '/' . $file)) {
                        $files[] = xf_scandir($dir . '/' . $file, $flag);
                    } else {

                        $files[] = $dir . '/' . $file;
                        if ($flag == 1)
                            unlink($dir . '/' . $file);
                        $i++;
                    }
                }
            }
            closedir($handle);
            return array_values($files);
        }
    }
}

/**
 * 删除一个文件夹下的所有文件
 * @param $path 目录地址
 * @parm $is_del_dir 是否删除该目录，默认不删除
 * @return array
 */
function deldir($path, $is_del_dir = 0) {
    //给定的目录不是一个文件夹
    if (!is_dir($path)) {
        return null;
    }

    $fh = opendir($path);
    while (($row = readdir($fh)) !== false) {
        //过滤掉虚拟目录
        if ($row == '.' || $row == '..') {
            continue;
        }

        if (!is_dir($path . '/' . $row)) {
            unlink($path . '/' . $row);
        }
        deldir($path . '/' . $row);
    }
    //关闭目录句柄，否则出Permission denied
    closedir($fh);
    //删除文件之后再删除自身
    if ($is_del_dir) {
        if (!rmdir($path)) {
            echo $path . '无权限删除<br>';
        }
    }

    return true;
}


/**
 * SQL监听
 */
function sqlListen(){
    Db::listen(function($sql, $runtime, $master) {
         echo ($sql).PHP_EOL;
    });
}



function fun_adm_each(&$array){
    $res = array();
    $key = key($array);
    if($key !== null){
        next($array);
        $res[1] = $res['value'] = $array[$key];
        $res[0] = $res['key'] = $key;
    }else{
        $res = false;
    }
    return $res;
}

/**
 * 获取图片文件地址
 * @param type $obj 路径
 * @param type $upload_type 类型
 * @param type $expire  oss过期时间
 * @return type string
 */
function get_img_url($obj, $upload_type = null, $config = array()) {
    if (!$obj) {
        return '';
    }
    if ($obj == '/public/img/empty.png') {
        return C('siteurl').$obj;
    }
    if($upload_type == 'video_thumb'){
        return C('wapurl') . '/static/admin/images/video.png';
    }

//    $obj = "http://xf01.cos.xinhu.wang/chen/test/images/2022-08/27/202208271628050643e4288.png?imageView2/1/w/400/h/600/q/10";
    $store = new Driver(null,$upload_type);
    return $store->getFielUrl($obj,$config);


}


/**
 * 过滤空数据的数组， 不包括0
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
if (!function_exists('filter_arr')) {

    function filter_arr($arr, $is = 1) {
        if (!$arr)
            return array();
        $data = array();
        foreach ($arr as $k => $v) {
            if ($is == 1) {
                if ($v || $v == '0') {
                    $data[$k] = $v;
                }
            } else {
                if ($v) {
                    $data[$k] = $v;
                }
            }
        }
        return $data;
    }

}
if (!function_exists('array_column2')) {
    function array_column2($arr, $key, $text='')
    {
        if (function_exists('array_column')) {
            return array_column($arr, $key);
        }
        if ($text) {
            return array_map(function ($val) use ($key, $text) {
                if ($val[$key] == $text) {
                    return $val[$key];
                }
            }, $arr);
        } else {
            return array_map(function ($val) use ($key, $text) {
                return $val[$key];
            }, $arr);
        }
    }
}

if (!function_exists('ext_htmlspecialchars')) {

    function ext_htmlspecialchars($value) {
        $value = htmlspecialchars($value, ENT_QUOTES);
//    $value = str_replace("0x", "0 x", $value);
        $value = str_ireplace(array("select ", "concat","unhex", "schema", "'"), "", $value);
        return $value;
    }
}


/**
 * 数组转JSON
 * @param type $input
 * @return type
 */
if (!function_exists('arr2json')) {
    function arr2json($input)
    {
        if (defined('JSON_UNESCAPED_UNICODE')) {
            return json_encode($input, JSON_UNESCAPED_UNICODE);
        }
        if (is_string($input)) {
            $text = $input;
            $text = str_replace('\\', '\\\\', $text);
            $text = str_replace(
                array("\r", "\n", "\t", "\""), array('\r', '\n', '\t', '\\"'), $text);
            return '"' . $text . '"';
        } else if (is_array($input) || is_object($input)) {
            $arr = array();
            $is_obj = is_object($input) || (array_keys($input) !== range(0, count($input) - 1));
            foreach ($input as $k => $v) {
                if ($is_obj) {
                    $arr[] = arr2json($k) . ':' . arr2json($v);
                } else {
                    $arr[] = arr2json($v);
                }
            }
            if ($is_obj) {
                return '{' . join(',', $arr) . '}';
            } else {
                return '[' . join(',', $arr) . ']';
            }
        } else {
            return $input . '';
        }
    }
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $color = '') {
    header("content-type:text/html;charset=utf-8");
    $str = '';
    if (is_numeric($color)) {
        if ($color == 1) {
            $str = "color:green;";
        } elseif ($color == 0) {
            $str = "color:red;";
        }
    } else {
        $str = "color:{$color};";
    }
    echo "<p style='{$str}'>{$msg}</p>";
//    flush();
//    ob_flush();
}

/**
 * 得到新订单号
 * @return  string
 */
function get_ordernum() {
    /* 选择一个随机的方案 */
    return date('ymdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}



/**
 * 安全过滤函数
 * @param $content array string object 数据
 * @return array string object 数据
 */
function cinputFilter($content) {
    if (is_string($content)) {
        return ext_htmlspecialchars(strip_tags(trim($content)));
    } elseif (is_array($content)) {
        foreach ($content as $key => $val) {
            $content[$key] = cinputFilter($val);
        }
        return $content;
    } elseif (is_object($content)) {
        $vars = get_object_vars($content);
        foreach ($vars as $key => $val) {
            $content->$key = cinputFilter($val);
        }
        return $content;
    } else {
        return $content;
    }
}

/**
把用户输入的文本转义（主要针对特殊符号和emoji表情）
 */
function userTextEncode($str) {
    if (!is_string($str))
        return $str;
    if (!$str || $str == 'undefined')
        return '';
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function($str) {
//        return addslashes($str[0]);
        return '#';
    }, $text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
    return json_decode($text);
}


/**
 * 验证文件是否合法
 * @param $file 文件名
 * @return bool or void
 */
function xf_validate_file($file) {
    if (0 === strpos($file, '.'))
        return FALSE;
    if (false !== strpos($file, '..'))
        return FALSE;

    if (false !== strpos($file, './'))
        return FALSE;
    if (':' == substr($file, 1, 1))
        return FALSE;
    if (preg_match('/\/@/', $file))
        return FALSE;

    return true;
}


//获取用户ID
function get_user_id() {
    return session('user.id');
}


/**
 * 对象转成数组
 * @param type $obj
 * @return type
 */
function object_to_array($obj) {
    $obj = (array) $obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array) object_to_array($v);
        }
    }
    return $obj;
}

/**
 * 用户名称
 * @param type $uid
 * @return string
 */
function getname($uid,$type = 1) {
    if($uid<=0){
        return "";
    }
    $realname = Db::name('user')->cache(true)->where(array('id' => $uid))->value('nickname');
    if($type == 2){
        $realname = '***'.mb_substr($realname,2,null,'utf-8');
        return "{$realname}";

    }
    return "{$realname}";
}
function getshopname($uid,$type = 1) {
    if($uid<=0){
        return "";
    }
    $realname = Db::name('suo_master')->cache(true)->where(array('id' => $uid))->value('realname');
    if($type == 2){
        $realname = '***'.mb_substr($realname,2,null,'utf-8');
        return "{$realname}";
    }
    return "{$realname}";
}

/**
 * 商品名称
 * @param type $uid
 * @return string
 */
function get_goods_name($goods_id) {
    if($goods_id<=0){
        return "";
    }
    $realname = Db::name('mall_goods')->cache(true)->where(array('goods_id' => $goods_id))->value('name');
    return "【{$goods_id}】{$realname}";
}

/**
 * 判断是否为微信浏览器
 * @return boolean
 */
function is_weixin() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}


/**
 * 判断是否为首页
 * @return boolean
 */
function is_home() {
    if (app('http')->getName() == config('app.default_app') && request()->controller() == 'Index' && request()->action() == 'index') {
        return true;
    } else {
        return false;
    }
}


/**
 * 获取 某个月的最大天数（最后一天）
 * @param $month 月
 * @param $year 年
 * @return int
 */
function getMonthLastDay($month, $year) {
    switch ($month) {
        case 4 :
        case 6 :
        case 9 :
        case 11 :
            $days = 30;
            break;
        case 2 :
            if ($year % 4 == 0) {
                if ($year % 100 == 0) {
                    $days = $year % 400 == 0 ? 29 : 28;
                } else {
                    $days = 29;
                }
            } else {
                $days = 28;
            }
            break;

        default :
            $days = 31;
            break;
    }
    return $days;
}

/**
 * 将UTF>gbk
 * @param type $content
 * @return type
 */
function utfToGbk($content) {
    if (is_string($content)) {
        return iconv('utf-8', 'gb2312', $content);

    } elseif (is_array($content)) {
        foreach ($content as $key => $val) {
            $content[$key] = utfToGbk($val);
        }
        return $content;
    } elseif (is_object($content)) {
        $vars = get_object_vars($content);
        foreach ($vars as $key => $val) {
            $content->$key = utfToGbk($val);
        }
        return $content;
    } else {
        return $content;
    }
}


//去除数组的某些字段
function get_arr_field($arr, $field) {
    $temp = array();
    foreach ($field as $v) {
        $temp[$v] = $arr[$v];
    }
    return $temp;
}



//获取后台数据转HTML
function contentHtml($content){
    $url=C('siteurl');
    $content = htmlspecialchars_decode($content);
    $content = str_replace(array($url), '', $content);
    $preg = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/';
    preg_match_all($preg, $content, $allImg);
    foreach ($allImg[1] as $k => $v) {
        $new_str = "<img src='" . get_img_url($v)  . "' style='width:100%; display:block;float:left;' />";
        $content = str_replace($allImg[0][$k], $new_str, $content);
    }
    return $content;
}

function nicknameEncryption($member_name){
    if(mb_strlen($member_name) == 0)  return '****';
    if(mb_strlen($member_name) == 1)  return mb_substr($member_name , 0 , 1) .'****';
    else return mb_substr($member_name , 0 , 1) . '****' . mb_substr($member_name ,-1);
}

//自定义函数手机号隐藏中间四位
function yc_phone($str){
    $str=$str;
    $resstr=substr_replace($str,'****',3,4);
    return $resstr;
}


//生成二维码
function create_qrcode($url,$uid){

    require_once getcwd() . '/../extend/phpqrcode/phpqrcode.php';
    # 引入phpqrcode sdk

    $qRcode = new \QRcode();
    // header("Content-type:image/png");
    $qrcode_path = 'uploads/qrcode/';
    is_dir($qrcode_path) OR mkdir($qrcode_path, 0777, true);
    $qrcode_img = $qrcode_path.$uid.'.png';

    // 纠错级别：L、M、Q、H
    $level = 'L';
    // 点的大小：1到10,用于手机端4就可以了
    $size = 4;

    $qRcode->png($url, $qrcode_img, $level, $size);

    $imagestring = base64_encode(ob_get_contents());
    ob_end_clean();

    // $result = ['status'=>true,  'data'=>$qrcode_img];
    return $qrcode_img;
}

function get_user_level(){
    $array = ['临时会员','普通会员','一星','二星','三星','四星','五星','六星','七星'];
    return $array;
}

function isPositiveInteger($str) {
    $pattern = '/^\d+$/';
    if (preg_match($pattern, $str)) {
        return true;
    } else {
        return false;
    }
}

function validateAlphaNum($string) {
    return preg_match('/^[a-zA-Z0-9]+$/', $string);
}
