<?php

declare (strict_types = 1);

namespace app\masterapi\model;

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
    public function shiming() {
        return [
            'rule' => [
                'tel|手机' => 'require|length:11,11|mobile',
                'code|验证码' => 'require',
                'realname|姓名' => 'require',
                'idcard|身份证号' => 'require|length:18,18',
                'idcard_front|身份证正面' => 'require',
                'idcard_back|身份证反面' => 'require',


            ],
            'message' => []
        ];
    }
}
