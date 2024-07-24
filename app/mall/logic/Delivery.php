<?php

namespace app\mall\logic;

use think\facade\Db;

class Delivery
{
    /**
     * Desc: 计算运费
     * @param $goods
     * @param $user_address
     * @return float|int
     */
    public function calculateFreight($goods, $user_address)
    {
        $shipping_price = 0;
        $template_list = [];

        if (empty($user_address)) {
            return $shipping_price;
        }


        foreach ($goods as $good) {


            //指定运费模板
            if ($good['info']['delivery_id'] > 0) {
                $template_list[$good['info']['delivery_id']][] = $good;
            }
        }
        foreach ($template_list as $template_id => $template_goods) {
            $temp = [];
            $temp['delivery_id'] = $template_id;
            $temp['total_volume'] = 0;
            $temp['total_weight'] = 0;
            $temp['goods_num'] = 0;
            foreach ($template_goods as $template_good) {
                $temp['total_weight'] += $template_good['ginfo']['weight'] * $template_good['num'];
                $temp['goods_num'] += $template_good['num'];
                $temp['total_volume'] += $template_good['ginfo']['volume'] * $template_good['num'];
            }
            $shipping_price += $this->calculate($temp, $user_address);
        }

        return $shipping_price;
    }


    /**
     * Desc: 计算运费
     * @param $data
     * @param $user_address
     * @return float|int
     */
    public function calculate($data, $user_address)
    {
        $shipping_price = 0;

        $freight = $this->getFreightsByAddress($data['delivery_id'], $user_address);

        if (empty($freight)) {
            return $shipping_price;
        }
        $unit = 0;
        //按重量计算
        if ($freight['method'] == 2) {
            $unit = $data['total_weight'];
        }

        //按件数计算
        if ($freight['method'] == 1) {
            $unit = $data['goods_num'];
        }

        //按体积计算
        if ($freight['method'] == 3) {
            $unit = $data['total_volume'];
        }

        if ($unit > $freight['first'] && $freight['additional'] > 0) {
            $left = ceil(($unit - $freight['first']) / $freight['additional']);//取整
            return $freight['first_fee'] + $left * $freight['additional_fee'];
        } else {
            return $freight['first_fee'];
        }
    }


    /**
     * Desc: 通过用户地址获取运费模板
     * @param $address
     */
    public function getFreightsByAddress($template_id, $address)
    {
        $district_id = $address['country'];
        $city_id = $address['city'];
        $province_id = $address['province'];

        $freights = Db::name('mall_delivery_rule')->alias('a')
            ->join('mall_delivery b', 'a.delivery_id = b.delivery_id')
            ->where('a.delivery_id', $template_id)
            ->order(['a.rule_id' => 'asc'])
            ->select()->toArray();

        foreach ($freights as $freight) {
            $region_ids = explode(',', $freight['region']);
            if (in_array($district_id, $region_ids)) {
                return $freight;
            }

            if (in_array($city_id, $region_ids)) {
                return $freight;
            }

            if (in_array($province_id, $region_ids)) {
                return $freight;
            }

            if ($freight['region'] = 'all') {
                $national_freight = $freight;
            }
        }

        //会员的省市区id在商家的运费模板(指定地区)中找不到,查一下商家的全国运费模板
        return $national_freight;
    }


}
