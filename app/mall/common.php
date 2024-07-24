<?php

/**
 * 表单多维数据转换
 * 例：
 * 转换前：{"x":0,"a":[1,2,3],"b":[11,22,33],"c":[111,222,3333,444],"d":[1111,2222,3333]}
 * 转换为：[{"a":1,"b":11,"c":111,"d":1111},{"a":2,"b":22,"c":222,"d":2222},{"a":3,"b":33,"c":3333,"d":3333}]
 * @param $arr array 表单二维数组
 * @param $fill boolean fill为false，返回数据长度取最短，反之取最长，空值自动补充
 * @return array
 */
function form_to_linear($arr, $fill = false) {
    $keys = [];
    $count = $fill ? 0 : PHP_INT_MAX;
    foreach ($arr as $k => $v) {
        if (is_array($v)) {
            $keys[] = $k;
            $count = $fill ? max($count, count($v)) : min($count, count($v));
        }
    }
    if (empty($keys)) {
        return [];
    }
    $data = [];
    for ($i = 0; $i < $count; $i++) {
        foreach ($keys as $v) {
            $data[$i][$v] = isset($arr[$v][$i]) ? $arr[$v][$i] : null;
        }
    }
    return $data;
}

//求两个数组的笛卡尔积
if (!function_exists('combineArray')) {

    function combineArray($arr1, $arr2) {
        $result = array();
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

}
if (!function_exists('combineDika')) {

    function combineDika($dikad, $dalen) {

        $data = $dikad;
        $cnt = $dalen;
        $result = array();
        if(!$dikad){
            return $result;
        }
        foreach ($data[0] as $item) {
            $result[] = array($item);
        }
        for ($i = 1; $i < $cnt; $i++) {
            $result = combineArray($result, $data[$i]);
        }
        return $result;
    }

}



 function specNameRequire() {

    $spec_name = input('spec_name');
    if (count($spec_name) != count(array_filter($spec_name))) {
        return false;
    }
    return true;
}

 function specNameRepetition() {
    $spec_name = input('spec_name');
    if (count($spec_name) != count(array_unique($spec_name))) {
        return false;
    }
    return true;
}

 function specValueRequire() {
    $spec_name = input('spec_values');
    if (count($spec_name) != count(array_filter($spec_name))) {
        return false;
    }
    return true;
}

 function specValueRepetition() {
    $spec_values = input('spec_values');
    foreach ($spec_values as $k => $v) {
        $row = explode(',', $v);
        if (count($row) != count(array_unique($row))) {
            return false;
        }
    }
    return true;
}

function specPrice(){
    $price = input('price');
    if(is_array($price)){
        foreach ($price as $v){
            if ($v < 0.01) {
                return false;
            }
        }
        return true;
    }
    return false;
}

function is_price($price) {
    if ($price < 0.01) {
        return false;
    } else {
        return true;
    }
}