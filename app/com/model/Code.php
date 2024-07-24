<?php

namespace app\com\model;

use think\facade\Db;
use think\Model;

class Code {

    /**
     * 生成唯一的核销码
     * @return string
     */
    public static function get_code() {
        $code = rand(10000000, 99999999);
        $model = Db::name('mall_code');
        $is = $model->where(['id' => $code])->find();
        if (!$is) {
            $model->insert(['id' => $code]);
            return $code;
        } else {
            return self::get_code();
        }
    }

}
