<?php

declare (strict_types=1);

namespace app\home\model;

use think\facade\Db;
use think\Model;



class O
{


    /**
     * 获取省份
     * @return type
     */
    public function get_shengfen() {
        return $this->get_quyu(0);
    }

    /**
     * 获取区域
     * @param type $key_id
     * @return type
     */
    public function get_quyu($key_id=0) {
        if ($key_id !== 0 && !$key_id) {
            return null;
        }
        return Db::name('system_areas')->cache(true)->where(array('pid'=>$key_id))->order("sort asc")->select()->toArray();
    }

    /**
     * 获取单个地区集合
     */
    function get_areasitem($id) {
        if (!$id) {
            return null;
        }
        return Db::name('system_areas')->cache(true)->find($id);
    }

    /**
     * 获取省份
     * @return type
     */
    function getProvince() {
        return $this->get_quyu(0);
    }

    /**
     * 获取某区域名称
     * @param type $id
     * @return type
     */
    function getAreas($id) {
        $data = $this->get_areasitem($id);
        return $data['simple'];
    }

    /**
     * 生成时间段 12:00-12:30
     * @return [type] [description]
     */
    public function get_times() {
        $data = array();
        $shier = strtotime('2015-01-01 12:00:00');
        $jies = strtotime('2015-01-01 20:30:00');
        for ($i = $shier; $i <= $jies; $i += 1800) {
            $j = $i + 1800;
            $data[] = date('H:i', $i) . '-' . date('H:i', $j);
        }
        return $data;
    }


    /**
     * Notes:生成城市JSON  更新数据库后刷新JSON
     * User: lingyingyao
     * Date: 2022/1/5
     * Time: 6:01 下午
     */
    public function get_json(){
        $file = './city.json';
        if (!file_exists($file)) {
            $items = $this->get_json2(0);
            file_put_contents($file, json_encode($items, JSON_UNESCAPED_UNICODE));
        }
        $city_json = file_get_contents('city.json');
        return json_decode($city_json, true);
    }

    public function get_json2($id){
        $list=Db::name('system_areas')->where(['pid'=>$id])->cache(true)->order('sort asc')->select()->toArray();
        $items=[];
        foreach ($list as $v){
            $item=[
                'text'=>$v['simple'],
                'value'=>$v['id'],
            ];
            if($v['level']<3){
                $item['children']=$this->cc2($v['id']);
            }
            $items[]= $item;
        }
        return $items;
    }




}