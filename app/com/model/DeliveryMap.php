<?php

declare (strict_types = 1);

namespace app\com\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class DeliveryMap extends Model {

    protected $name = 'delivery_map';

    /**
     * 是否包含该点
     * @param type $lat 纬度
     * @param type $lng 经度
     * @return boolean
     */
    public function isHasPoint($lat, $lng) {
        $data = $this->getAll();
        if (!$data) {
            return false;
        }
        if (!$lat || !$lng) {
            return false;
        }
        $point = ['lat' => $lat, 'lng' => $lng];
        $flag = false;
        foreach ($data as $v) {
            $vs = json_decode($v, true);
            $zong = [];
            foreach ($vs as $vv) {
                $tmp_v = explode(',', $vv);
                $zong[] = ['lat' => $tmp_v[0], 'lng' => $tmp_v[1]];
            }
            $is = $this->isPointInPolygon($zong, $point);
            if ($is == true) {
                $flag = true;
                break;
            }
        }
        return $flag;


//        $this->where
    }

    public function getAll() {
        $caname = "delivery_map_all";
        $data = cache($caname);
        if (!$data) {
            $positions = $this->removeOption()->column('positions');
            $data = $positions ? $positions : [];
            cache($caname, $data);
        }
        return $data;
    }

    /**
     * 验证区域范围
     * @param array $coordArray 区域
     * @param array $point      验证点
     * @return bool
     */
    public function isPointInPolygon($coordArray, $point) {
        if (!is_array($coordArray) || !is_array($point))
            return false;
        $maxY = $maxX = 0;
        $minY = $minX = 9999;
        foreach ($coordArray as $item) {
            if ($item['lng'] > $maxX)
                $maxX = $item['lng'];
            if ($item['lng'] < $minX)
                $minX = $item['lng'];
            if ($item['lat'] > $maxY)
                $maxY = $item['lat'];
            if ($item['lat'] < $minY)
                $minY = $item['lat'];
            $vertx[] = $item['lng'];
            $verty[] = $item['lat'];
        }
        if ($point['lng'] < $minX || $point['lng'] > $maxX || $point['lat'] < $minY || $point['lat'] > $maxY) {
            return false;
        }

        $c = false;
        $nvert = count($coordArray);
        $testx = $point['lng'];
        $testy = $point['lat'];
        for ($i = 0, $j = $nvert - 1; $i < $nvert; $j = $i++) {
            if (( ($verty[$i] > $testy) != ($verty[$j] > $testy) ) && ($testx < ($vertx[$j] - $vertx[$i]) * ($testy - $verty[$i]) / ($verty[$j] - $verty[$i]) + $vertx[$i]))
                $c = !$c;
        }
        return $c;
    }

}
