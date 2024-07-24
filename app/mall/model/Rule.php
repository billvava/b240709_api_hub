<?php
declare (strict_types = 1);

namespace app\mall\model;




class Rule
{


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


    /**
     * 规则
     * @return type
     */
    public function goodsItem() {
        return [
            'rule'=>[
                'name'  =>  'require|length:1,50',
                'small_title|副标题'  =>  'length:0,50',
                'wares_no|商家编码'  =>  'length:0,50',
                'unit|计量单位'  =>  'length:0,5',
            ],
            'message'=>[]
        ];

    }

    public function GoodsOneSpec() {
        return [
            'rule'=>[
                'one_price|价格'  =>  'require|is_price',
                'one_cost_price|成本'  =>  'is_price',
            ],
            'message'=>[]
        ];
    }


    public function GoodsMoreSpec() {
        return [
            'rule'=>[
                'spec_name'  =>  'specNameRequire|specNameRepetition',
                'spec_values'  =>  'specValueRequire|specValueRepetition',
                'price'=>'specPrice'
            ],
            'message'=>[]
        ];

    }




}
