<?php

declare (strict_types = 1);

namespace app\comapi\model;

class Rule {

    public function tel_reg() {
        return [
            'rule' => [
                'tel|手机' => 'require|length:11,11|mobile',
                'verify|验证码' => 'require',
                'pwd|密码' => 'require|length:6,16',
                'repwd|确认密码' => 'require|length:6,16',
            ],
            'message' => []
        ];
    }

    public function forget() {
        return [
            'rule' => [
                'tel|手机' => 'require|length:11,11|mobile',
                'verify|验证码' => 'require',
                'pwd|新密码' => 'require|length:6,16',
                'repwd|确认密码' => 'require|length:6,16',
            ],
            'message' => []
        ];
    }
    public function save_info() {
        return [
            'rule' => [
                'headimgurl|头像' => 'require',
                'nickname|昵称' => 'require|length:1,11',
                'tel|手机' => 'require|length:11,11|mobile',
            ],
            'message' => []
        ];
    }
}
