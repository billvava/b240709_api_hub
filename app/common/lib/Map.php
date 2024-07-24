<?php

namespace app\common\Lib;

use app\home\model\Category;
use think\exception\HttpResponseException;
use think\Request;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Cache;

class Map {



    /**
     * 坐标转地址
     * @param $latitude
     * @param $longitude
     * @return mixed|object|\think\App
     */

    public function getAddress($latitude,$longitude){
        $point = $latitude . ',' . $longitude;
        $name='get_address_xadxa'.$point;
        if(cache($name)) return cache($name);
        $map_key=C('map_key');
        $url="https://apis.map.qq.com/ws/geocoder/v1/?location={$point}&key={$map_key}";
        $data=json_decode(file_get_contents($url),true);
        cache($name,$data,3600);
        return $data;
    }


    /**
     * 地址转坐标
     * @param $address
     * @return mixed|object|\think\App
     */
    public function getLocation($address){
        $name='get_location_xadxa'.$address;
        if(cache($name)) return cache($name);
        $map_key=C('map_key');
        $url="https://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key={$map_key}";
        $data=json_decode(file_get_contents($url),true);
        cache($name,$data,3600);
        return $data;
    }


}
