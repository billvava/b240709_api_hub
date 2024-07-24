<?php

/**
 * 输出文本框的内容
 * @param  [type]  $pee [description]
 * @param  boolean $br  [description]
 * @return [type]       [description]
 */
if (!function_exists('wpautop')) {

    function wpautop($pee, $br = true) {
        $pre_tags = array();

        if (trim($pee) === '')
            return '';

        $pee = $pee . "\n"; // just to make things a little easier, pad the end

        if (strpos($pee, '<pre') !== false) {
            $pee_parts = explode('</pre>', $pee);
            $last_pee = array_pop($pee_parts);
            $pee = '';
            $i = 0;

            foreach ($pee_parts as $pee_part) {
                $start = strpos($pee_part, '<pre');

                // Malformed html?
                if ($start === false) {
                    $pee .= $pee_part;
                    continue;
                }

                $name = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = substr($pee_part, $start) . '</pre>';

                $pee .= substr($pee_part, 0, $start) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }

        $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
        // Space things out a little
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|noscript|legend|section|article|aside|hgroup|header|footer|nav|figure|details|menu|summary)';
        $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines

        if (strpos($pee, '</object>') !== false) {
            // no P/BR around param and embed
            $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
            $pee = preg_replace('|\s*</object>|', '</object>', $pee);
            $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
        }

        if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false) {
            // no P/BR around source and track
            $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
            $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
            $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
        }

        $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
        // make paragraphs, including one at the end
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee = '';

        foreach ($pees as $tinkle) {
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }

        $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        if ($br) {
            $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }

        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);

        if (!empty($pre_tags))
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

        return $pee;
    }

}


/**
 * 获取完整URI，不包括端口
 * @return string
 */
if (!function_exists('getFullUri')) {


    function getFullUri() {
        return 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
    }

}

/**
 * 计算倒计时
 * $time 要计算的时间戳
 * $type 返回字符串的格式
 * @return string
 */
if (!function_exists('countdown')) {

    function countdown($time1, $type = 1) {
        $span = $time1 - time();
        $d = 0;
        $h = 0;
        $m = 0;
        $s = 0;
        if ($span >= 0) {
            $d = floor($span / 60 / 60 / 24);
            $h = floor($span / 60 / 60 % 24);
            $m = floor($span / 60 % 60);
            $s = floor($span % 60);
        }
        if ($d < 10) {
            $d = '0' . $d;
        }
        if ($h < 10) {
            $h = '0' . $h;
        }
        if ($m < 10) {
            $m = '0' . $m;
        }
        if ($s < 10) {
            $s = '0' . $s;
        }
        switch ($type) {
            case 1:
                $str = "<span class='fl'>$d</span>
                        <em class='fl'>天</em>
                        <span class='fl'>$h</span>
                        <em class='fl'>时</em>
                        <span class='fl'>$m</span>
                        <em class='fl'>分</em>";
                break;
            case 2:
                $str = "还剩{$d}天{$h}时{$m}分";
                break;
            case 3:
                $str = $d . $h . $m;
            case 4:
                $str = $d;
            default:
                break;
        }
        return $str;
    }

}



if (!function_exists('geshihuamiao')) {

    function geshihuamiao($span) {
        $d = 0;
        $h = 0;
        $m = 0;
        $s = 0;
        if ($span >= 0) {
            $d = floor($span / 60 / 60 / 24);
            $h = floor($span / 60 / 60 % 24);
            $m = floor($span / 60 % 60);
            $s = floor($span % 60);
        }
        if ($d < 10) {
            $d = '0' . $d;
        }
        if ($h < 10) {
            $h = '0' . $h;
        }
        if ($m < 10) {
            $m = '0' . $m;
        }
        if ($s < 10) {
            $s = '0' . $s;
        }
        return [
            'd'=>$d,
            'h'=>$h,
            'm'=>$m,
            's'=>$s,

        ];
    }

}

/**
 * 获取域名
 * $url Url
 * @return string
 */
if (!function_exists('get_domain')) {

    function get_domain($url) {
        $pattern = "/[/w-]+/.(com|net|org|gov|biz|com.tw|com.hk|com.ru|net.tw|net.hk|net.ru|info|cn|com.cn|net.cn|org.cn|gov.cn|mobi|name|sh|ac|la|travel|tm|us|cc|tv|jobs|asia|hn|lc|hk|bz|com.hk|ws|tel|io|tw|ac.cn|bj.cn|sh.cn|tj.cn|cq.cn|he.cn|sx.cn|nm.cn|ln.cn|jl.cn|hl.cn|js.cn|zj.cn|ah.cn|fj.cn|jx.cn|sd.cn|ha.cn|hb.cn|hn.cn|gd.cn|gx.cn|hi.cn|sc.cn|gz.cn|yn.cn|xz.cn|sn.cn|gs.cn|qh.cn|nx.cn|xj.cn|tw.cn|hk.cn|mo.cn|org.hk|is|edu|mil|au|jp|int|kr|de|vc|ag|in|me|edu.cn|co.kr|gd|vg|co.uk|be|sg|it|ro|com.mo)(/.(cn|hk))*/";
        preg_match($pattern, $url, $matches);
        if (count($matches) > 0) {
            return $matches[0];
        } else {
            $rs = parse_url($url);
            $main_url = $rs["host"];
            if (!strcmp(long2ip(sprintf("%u", ip2long($main_url))), $main_url)) {
                return $main_url;
            } else {
                $arr = explode(".", $main_url);
                $count = count($arr);
                $endArr = array("com", "net", "org"); //com.cn net.cn 等情况
                if (in_array($arr[$count - 2], $endArr)) {
                    $domain = $arr[$count - 3] . "." . $arr[$count - 2] . "." . $arr[$count - 1];
                } else {
                    $domain = $arr[$count - 2] . "." . $arr[$count - 1];
                }
                return $domain;
            }
        }
    }

}

/**
 * 获取今年的开始时间时间戳
 * @param $year 年份 如2014
 * @return int
 */
if (!function_exists('xf_getYearTime')) {

    function xf_getYearTime($year = 0) {
        if ($year != 0) {
            return strtotime("1 January $year");
        }
        $y = date("y", time());
        return strtotime("1 January $y");
    }

}


/**
 * 按照数组中的某一字段排序
 * @param  [type] $array [description]
 * @param  [type] $key   [description]
 * @param  string $order [description]
 * @return [type]        [description]
 */
if (!function_exists('arr_sort')) {

    function arr_sort($array, $key, $order = "asc") {//asc是升序 desc是降序
        $arr_nums = $arr = array();
        foreach ($array as $k => $v) {
            $arr_nums[$k] = $v[$key];
        }

        if ($order == 'asc') {
            asort($arr_nums);
        } else {
            arsort($arr_nums);
        }

        foreach ($arr_nums as $k => $v) {
            $arr[$k] = $array[$k];
        }

        return $arr;
    }

}


/**
 * 创建XML中节点
 * @param $dom dom
 * @param $item item
 * @param $data 值
 * @param $attribute 属性
 * @return void
 */
if (!function_exists('create_item')) {

    function create_item($dom, $item, $data, $attribute) {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                //  创建元素
                $$key = $dom->createElement($key);
                $item->appendchild($$key);
                //  创建元素值
                $text = $dom->createTextNode($val);
                $$key->appendchild($text);
                if (isset($attribute[$key])) {
                    //  如果此字段存在相关属性需要设置
                    foreach ($attribute[$key] as $akey => $row) {
                        //  创建属性节点
                        $$akey = $dom->createAttribute($akey);
                        $$key->appendchild($$akey);
                        // 创建属性值节点
                        $aval = $dom->createTextNode($row);
                        $$akey->appendChild($aval);
                    }
                }
            }
        }
    }

}


/**
 * 数组转出xml
 */
if (!function_exists('arrayToXml')) {

    function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

}

if (!function_exists('byte_format')) {

    function byte_format($size, $dec = 2) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

}

/**
 * Excel导出
 * @param $fileName（文件名）
 * @param $headArr （表头）
 * @param $data  （每一行的数据）
 * @throws \PHPExcel_Exception
 * @throws \PHPExcel_Reader_Exception
 */
if (!function_exists('out_excel')) {

    function out_excel($fileName, $headArr, $data, $is_out = 1) {
        include_once (INCLUDE_PATH . "PHPExcel/PHPExcel/PHPExcel.php");
        include_once (INCLUDE_PATH . "PHPExcel/PHPExcel/Writer/IWriter.php");
        include_once (INCLUDE_PATH . "PHPExcel/PHPExcel/Writer/Abstract.php");
        include_once (INCLUDE_PATH . "PHPExcel/PHPExcel/Writer/Excel2007.php");
        include_once (INCLUDE_PATH . "PHPExcel/PHPExcel/Writer/Excel5.php");
        include_once (INCLUDE_PATH . "PHPExcel/PHPExcel/IOFactory.php");
        if (empty($fileName)) {
            exit;
        }
        $date = date("Y_m_d", time());
        $fileName .= ".xlsx";

        //创建新的PHPExcel对象
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        $key2 = ord("A");
        $colum2 = '';
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum2 . $colum . '1', $v);
            if ($key < 90) {
                $key += 1;
            } else {
                $key = ord("A");
                $colum2 = chr($key2);
                $key2++;
            }
        }
        //exit;
        $column = 2;

        foreach ($data as $key => $rows) { //行写入
            $span = ord("A");
            $span2 = ord("A");
            $j2 = '';
            foreach ($rows as $keyName => $value) {// 列写入
                $j = chr($span);
                //$objActSheet->setCellValue($j.$column, $value);
                //把每个单元格设置成分文本类型
                //dump($j2.$j.$column);
                $objActSheet->setCellValueExplicit($j2 . $j . $column, $value, \PHPExcel_Cell_DataType::TYPE_STRING);

                if ($span < 90) {
                    $span += 1;
                } else {
                    $span = ord("A");
                    $j2 = chr($span2);
                    $span2++;
                }
            }
            $column++;
        }
        // exit;
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        //将输出重定向到一个客户端web浏览器(Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//    if(!empty($_GET['excel'])){
//      $objWriter->save('php://output'); //文件通过浏览器下载
//    }else{
//      $objWriter->save($fileName); //脚本方式运行，保存在当前目录
//    }
        if ($is_out == 1) {
            $objWriter->save('php://output');
            exit;
        } else {
            $objWriter->save($fileName);
        }
    }

}


/*
 * 转成多少天前
 */
if (!function_exists('time_tran')) {

    function time_tran($the_time) {
        $now_time = date("Y-m-d H:i:s", time());
        //echo $now_time; 
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return '刚刚';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内 
                            return floor($dur / 86400) . '天前';
                        } else {
                            return $the_time;
                        }
                    }
                }
            }
        }
    }

}

/*
 * 获取微秒的时间戳
 */
if (!function_exists('msectime')) {


    function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

}

/*
 * 字符串转成数组
 * 是=1,否=0
 */
if (!function_exists('str_to_arr')) {

    function str_to_arr($str, $type = 1) {
        $items = array();
        if (is_string($str)) {
            $str = str_replace('，', ',', $str);
            $ss = array_filter(explode(',', $str));
            foreach ($ss as $v) {
                $vv = explode('=', $v);
                if ($type == 1) {
                    $items[$vv[0]] = $vv[1];
                }
                if ($type == 2) {
                    $items[] = array(
                        'val' => $vv[0], 'name' => $vv[1],
                    );
                }
                if ($type == 3) {
                    $name = is_numeric($vv[1]) ? $vv[0] : $vv[1];
                    $val = is_numeric($vv[1]) ? $vv[1] : $vv[0];
                    $items[] = array(
                        'name' => $name, 'val' => $val,
                    );
                }
            }
        }
        return $items;
    }

}


/**
 * json转成数组的PHP代码
 * @param type $array
 * @return string
 */
if (!function_exists('array_tocode1')) {
    function array_tocode1($array) {
        if (is_array($array)) {
            $str = "array(";
            foreach ($array AS $key => $value) {
                $str .= '"' . $key . '"=>' . array_tocode1($value) . ',';
            }
            $str = substr($str, 0, strlen($str) - 1);
            $str .= ')';
            return $str;
        } else {
            return '"' . $array . '"';
        }
    }
}
