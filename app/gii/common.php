<?php

// 这是系统自动生成的公共文件
//把带下划线的表名转换为驼峰命名（首字母大写）
function tableNameToModelName($tableName) {
    $tempArray = explode('_', $tableName);
    $result = "";
    for ($i = 0; $i < count($tempArray); $i++) {
        $result .= ucfirst($tempArray[$i]);
    }
    return $result;
}
/**
 * 驼峰命名转下划线命名
 * @param type $camelCaps
 * @param type $separator
 * @return type
 */
function uncamelize($camelCaps, $separator = '_') {
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

//把带下划线的列名转换为驼峰命名（首字母小写）
function columNameToVarName($columName) {
    $tempArray = explode('_', $columName);
    $result = "";
    for ($i = 0; $i < count($tempArray); $i++) {
        $result .= ucfirst($tempArray[$i]);
    }
    return lcfirst($result);
}

/**
 * 写入文件
 * @param type $filename
 * @param type $str
 * @return type
 */
function writeFile($filename, $str) {
    if (!file_exists($filename)) {
        file_put_contents($filename, '');
    }
    // 首先我们要确定文件存在并且可写。
    if (is_writable($filename)) {
        // 在这个例子里，我们将使用添加模式打开$filename，
        // 因此，文件指针将会在文件的末尾，
        // 那就是当我们使用fwrite()的时候，$somecontent将要写入的地方。
        if (!$handle = fopen($filename, 'w')) {
            return array('status' => 0, 'msg' => "不能打开文件{$filename}");
        }
        // 将$somecontent写入到我们打开的文件中。
        if (fwrite($handle, $str) === FALSE) {
            return array('status' => 0, 'msg' => "不能写入到文件{$filename}");
        }
        return array('status' => 1, 'msg' => "成功地将写入到文件{$filename}");
        fclose($handle);
    } else {
        return array('status' => 0, 'msg' => "文件{$filename}不可写");
    }
}

function arr_echo($arr, $is_die = 0) {
    if ($arr['status'] == 1) {
        fecho($arr['msg']);
    } else {
        error_echo($arr['msg'], $is_die);
    }
}

function fecho($str) {
    echo "{$str}<br/>" . PHP_EOL;
//    flush();
//    ob_flush();
}

function error_echo($str, $is_die = 0) {
    echo "<font style='color:red;'>{$str}</font><br/>" . PHP_EOL;
//    flush();
//    ob_flush();
    if ($is_die == 1) {
        echo "<font style='color:red;'>程序出现异常,已经终止运行,请检测后重新运行！</font><br/>" . PHP_EOL;
        die;
    }
}

function success_echo($str, $is_die = 0) {
    echo "<font style='color:green;'>{$str}</font><br/>" . PHP_EOL;
    flush();
//    ob_flush();
    if ($is_die == 1) {
        echo "<font style='color:green;'>程序运行完毕！</font><br/>" . PHP_EOL;
        die;
    }
}

function die_echo($str) {
    error_echo($str, 1);
}

/**
 * 递归创建目录，若传的$dir不是绝对路径，则会和运行此方法的目录同级
 * @param type $dir
 * @return boolean
 */
function mkdirs($dir) {
    if (is_dir($dir)) {
        return array('status' => 1, 'msg' => "目录{$dir}已存在,无需创建");
    }
    if (mkdir($dir, 0777, true)) {
        return array('status' => 1, 'msg' => "目录{$dir}创建成功");
    } else {
        return array('status' => 0, 'msg' => "目录{$dir}创建失败");
    }
}
