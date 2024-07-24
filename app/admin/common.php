<?php
// 这是系统自动生成的公共文件


/**
 * 获取主题
 * @return type
 */
function getTheme()
{
    return cookie('admin_theme') ? cookie('admin_theme') : '';
}

/**
 * 判断IE是低于某版本
 * @param type $verison
 * @return boolean
 */
function ieLower($verison = 8)
{
    $ieArr = array('6', '7', '8', '9', '10', '11');
    $flag = false;
    foreach ($ieArr as $value) {
        if ($value <= $verison) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE {$value}.0") !== false) {
                $flag = true;
                break;
            } else {

            }
        }
    }
    return $flag;
}

/**
 * 创建token
 * @return type
 */
function create_token()
{
    $q = xf_md5(uniqid());
    session('xf_token', $q);
    return $q;
}

/**
 * 验证token
 */
function check_token()
{
    $h_token = $_REQUEST['xf_token'];
    $s = session('xf_token');
    if ($h_token != $s || !$s) {
        return false;
    } else {
        return true;
    }
}

//验证密码
function checkPwd(){
    return 'aaaa';
}


/**
 * 递归组成
 * @staticvar array $list
 * @param type $arr
 * @param type $id
 * @param type $lev
 * @return type
 */
function rec($arr, $id, $key = 'id', $lev = 0) {
    static $list = array();
    foreach ($arr as $k => $v) {
        if ($v['pid'] == $id) {
            $v['lev'] = $lev;
            $v['lev2'] = $lev * 2;
            $list[] = $v;
            rec($arr, $v[$key], $key, $lev + 1);
        }
    }
    return $list;
}


// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from, $to) {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}


/**
+----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function byte_format($size, $dec = 2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $a[$pos];
}


/**
 *
 * @param type $exttable
 * @return boolean
 */
 function letters_num($str) {
    if (preg_match("/^[a-za-z]{1}[a-za-z0-9]{1,}$/", $str)) {
        return true;
    } else {
        return false;
    }
}


/**
 * 验证是否全为小写英文、下划线和数字
 * @param type $field
 * @return boolean
 */
function check_en($field) {
    if (preg_match("/[a-z]+[0-9_]*/", $field)) {
        return true;
    } else {
        return false;
    }
}

 function checkLevel($level) {
    $in = input();
    if ($level == 3 && (!$in['lev1'] || !$in['lev2'])) {
        return false;
    } else if ($level == 2 && (!$in['lev1'] )) {
        return false;
    }
    return true;
}

