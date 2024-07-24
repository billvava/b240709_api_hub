<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Validate;
use think\Model;

/**
 * @mixin think\Model
 */
class Config extends Model
{
    protected $name="system_config";


    public function rules() {
        Validate::maker(function($validate) {
            $validate->extend('check_en','check_en','字段英文名称只能使用小写英文、下划线和数字');
        });
        return [
            'rule'=>[
                'field|字段名' =>  'require|unique:system_config|length:1,25|check_en',
                'name|名称'=> 'require|length:1,25',
            ],
            'message'=>[]
        ];


    }

    public function clear(){
        cache('xf_config_list',null);
    }







}