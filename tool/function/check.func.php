<?php

/**
 * 验证是否为英文
 * @param type $str
 * @return boolean
 */
function check_en($str) {
    if (preg_match("/^[a-zA-Z\s]+$/", $str)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 验证是否为大写英文
 * @param type $str
 * @return boolean
 */
function check_up_en($str) {
    if (preg_match("/^[A-Z\s]+$/", $str)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 验证是否为小写英文
 * @param type $str
 * @return boolean
 */
function check_lower_en($str) {
    if (preg_match("/^[a-z_\s]+$/", $str)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 检测是否为英文或英文数字的组合
 * @param type $str
 * @return boolean
 */
function check_ench($str) {
    if (!eregi("^[a-z0-9_\s]{1,26}$", $str)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 验证邮箱
 * @param type $str
 * @return boolean
 */
function check_email($str) {
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($str, '@') !== false && strpos($str, '.') !== false) {
        if (preg_match($chars, $str)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 验证手机号码
 * @param type $str
 * @return boolean
 */
function check_tel($str) {
    if (preg_match("/1\d{10}$/", $str)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 验证固定电话
 * @param type $str
 * @return boolean
 */
function check_telephone($str) {
    return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', $str);
}

/**
 * 验证qq号码
 * @param type $str
 * @return boolean
 */
function check_qq($str) {
    return preg_match('/^[1-9]\d{4,12}$/', $str);
}

/**
 * 验证邮政编码
 * @param type $str
 * @return boolean
 */
function check_zipcode($str) {
    return preg_match('/^[1-9]\d{5}$/', trim($str));
}

/**
 * 验证ip
 * @param type $str
 * @return boolean
 */
function check_ip($str) {
    if (empty($str))
        return false;
    if (!preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $str)) {
        return false;
    }
    $ip_array = explode('.', $str);
//真实的ip地址每个数字不能大于255（0-255）
    return ( $ip_array[0] <= 255 && $ip_array[1] <= 255 && $ip_array[2] <= 255 && $ip_array[3] <= 255 ) ? true : false;
}

/**
 * 验证身份证(中国)
 * @param type $str
 * @return boolean
 */
function check_idcard($str) {
    $str = trim($str);
    if (empty($str))
        return false;

    if (preg_match("/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i", $str))
        return true;
    else
        return false;
}

/**
 * 验证网址
 * @param type $str
 * @return boolean
 */
function check_url($str) {
    if (empty($str))
        return false;

    return preg_match('#(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?#i', $str) ? true : false;
}
