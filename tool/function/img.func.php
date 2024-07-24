<?php

/**
 * 下载远程文件，包括图片
 * @param type $url
 * @param type $new_file_path
 * @return type
 */
function download_img($url, $dir, $new_file_path) {
    tool()->classs("curl/HttpRequest");
    tool()->classs("curl/HttpResponse");
    $request = new \HttpRequest();
    $response = $request
            ->header("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8")
            ->header("Accept-Encoding", "gzip, deflate, sdch")
            ->header("Accept-Language", "zh-CN,zh;q=0.8")
            ->header("Cache-Control:", "max-age=0")
            ->header("Connection", "keep-alive")
            ->header("Host", "wx.qlogo.cn")
            ->header("Referer", "http://www.qq.com")
            ->header("Upgrade-Insecure-Requests", "1")
            ->header("User-Agent", "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36")
            ->get($url);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return file_put_contents("{$dir}/{$new_file_path}", $response->body);
}
