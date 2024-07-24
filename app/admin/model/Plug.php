<?php

declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class Plug extends Model {

    protected $name = 'system_plug';

    public function dbName() {
        return $this->name;
    }

    /**
     * 
     * 是否已经安装
     * @param type $token
     * @return type
     */
    public function isInstalled($token) {
        $is = Db::name($this->dbName())->where(['token' => $token])->value('id');
        return $is ? 1 : 0;
    }

}
