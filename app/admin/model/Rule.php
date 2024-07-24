<?php
declare (strict_types = 1);

namespace app\admin\model;




use think\facade\Validate;

class Rule
{



    public static function login(){

        return [
            'rule'=>[
                'username|用户名'  =>  'require|max:25',
                'pwd|密码' =>  'require',
            ],
            'message'=>[]
        ];

    }

    public static function config(){
        return [
            'rule'=>[
                'field|字段名'  =>  'require|length:1,25|unique:system_config|alphaDash',
                'name|名称' =>  'require|length:1,25',
            ],
            'message'=>[]
        ];
    }


    public static function AdminUser(){
        return [
            'rule'=>[
                'role_id|角色'  =>  'require',
                'username|用户名' =>  'require|length:1,25|unique:admin_user',
                'pwd|密码' =>  'length:1,25',
            ],
            'message'=>[]
        ];

    }

    public static function nodeItem() {
        Validate::maker(function($validate) {
            $validate->extend('checkLevel','checkLevel','请选择上级');
        });
        return [
            'rule'=>[
                'level|类别'  =>  'require|checkLevel',
                'name|程序名'  =>  'require',
                'title|显示名'  =>  'require',
                'status|状态'  =>  'require',
            ],
            'message'=>[]
        ];

    }

    public static function set(){
        return [
            'rule'=>[
                'keyid'  =>  'require',
                'key'  =>  'require',
                'field'  =>  'require',
            ],
            'message'=>[]
        ];
    }


}
