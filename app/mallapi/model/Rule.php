<?php

declare (strict_types = 1);

namespace app\mallapi\model;

class Rule {

    public function address() {
        return [
            'rule' => [
                'name|收货人' => 'require|length:1,50',
                'tel|手机号码' => 'require|mobile',
                'reference|位置' => 'require',

                'address|具体门牌' => 'require|length:1,255',

            ],
            'message' => []
        ];
    }

    public function bank() {
        return [
            'rule' => [
                'num|卡号' => 'require|length:1,50',
                'name|银行卡名称' => 'require|length:1,50',
                'address|开户行名称' => 'require|length:1,50',
                'realname|持卡人' => 'require|length:1,50',
                'tel|预留电话' => 'require|mobile',
            ],
            'message' => []
        ];
    }

    public static function set() {
        return [
            'rule' => [
                'keyid' => 'require',
                'key' => 'require',
                'field' => 'require',
            ],
            'message' => []
        ];
    }

    /**
     * 规则
     * @return type
     */
    public function goodsItem() {
        return [
            'rule' => [
                'name' => 'require|length:1,50',
                'small_title|副标题' => 'length:0,50',
                'wares_no|商家编码' => 'length:0,50',
                'unit|计量单位' => 'length:0,5',
            ],
            'message' => []
        ];
    }

    public function GoodsOneSpec() {
        return [
            'rule' => [
                'one_price|价格' => 'require|is_price',
                'one_cost_price|成本' => 'is_price',
            ],
            'message' => []
        ];
    }

    public function GoodsMoreSpec() {
        return [
            'rule' => [
                'spec_name' => 'specNameRequire|specNameRepetition',
                'spec_values' => 'specValueRequire|specValueRepetition',
                'price' => 'specPrice'
            ],
            'message' => []
        ];
    }

}
