<?php

declare (strict_types = 1);

namespace app\suoapi\model;

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
                'tel|手机' => 'require|mobile',
                'verify|验证码' => 'require',
                'pwd|新密码' => 'require|length:6,16',
                'repwd|确认密码' => 'require|length:6,16',
            ],
            'message' => []
        ];
    }

    public function type1() {
        return [
            'rule' => [
                'tel|手机' => 'require|mobile',
                'realname|姓名' => 'require',
            ],
            'message' => []
        ];
    }

    public function type4() {
        return [
            'rule' => [
                'tel|手机' => 'require|mobile',
                'realname|姓名' => 'require',
            ],
            'message' => []
        ];
    }
    public function type2() {
        return [
            'rule' => [
                'tel|手机' => 'require|mobile',
                'realname|姓名' => 'require',
                'ruzhu_type|类型'=>'require',
            ],
            'message' => []
        ];
    }

    public function type3() {
        return [
            'rule' => [
                'tel|手机' => 'require|mobile',
                'realname|姓名' => 'require',
                'company|公司'=>'require',
            ],
            'message' => []
        ];
    }
}
