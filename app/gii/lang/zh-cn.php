<?php

return array(
    'auto_seach_type' => array(
        'form_input' => array('id','title*', 'name*', 'tel*','linkman*','address*','remark*','message*','code*','num*','time*','card*','company*'),
        'form_select' => array('user_id','status*'),
    ),
    'auto_field_type' => array(
        'thumb' => array('img', 'thumb', 'logo', 'tu'),
        'hide' => array('user_id', 'goods_id', 'province','shengfen','city','chengshi','country','quyu'),
        'images' => array('imgs', 'images', 'zutu'),
        'editor' => array('content'),
//        'sel_dot' => array('lat', 'lng', 'latitude', 'longitude'),
        'radio' => array('status', 'is_*'),
    ),
    'field_type' => array(
        'input' => '文本框',
        'hide_input' => '隐藏框',
        'hide' => '不显示',
        'radio' => '单选',
        'fdate' => '日期',
        'datetime' => '日期时间',
        'editor' => '编辑器',
        'ffile' => '单文件',
        'images' => '多图片',
        'textarea' => '多行文本',
        'thumb' => '单图片',
        'ftime' => '时间',
        'selec' => '下拉框选择',
        'rangedate' => '日期范围'
    ),
    'fz_type' => [
        'sel_dot' => [
            'name' => '经纬度选择',
            'alias' => '经纬度',
            'param' => [
                [
                    'name' => '经度',
                    'field' => 'lng',
                    'fast'=>['lng'],
                ],
                [
                    'name' => '纬度',
                    'field' => 'lat',
                     'fast'=>['lat'],
                ],
            ],
        ],
        'area1' => [
            'name' => '省份',
             'alias' => '省份',
            'param' => [
                [
                    'name' => '省',
                    'field' => 'province',
                    'fast'=>['province','shengfen'],
                ]
            ],
        ],
        'area2' => [
            'name' => '省市',
            'alias' => '省市',
            'param' => [
                [
                    'name' => '省',
                    'field' => 'province',
                    'fast'=>['province','shengfen'],
                ],
                 [
                    'name' => '市',
                    'field' => 'city',
                    'fast'=>['city','chengshi'],
                ],
            ],
        ],
        'area3' => [
            'name' => '省市区',
            'alias' => '省市区',
            'param' => [
                [
                    'name' => '省',
                    'field' => 'province',
                    'fast'=>['province','shengfen'],
                ],
                 [
                    'name' => '市',
                    'field' => 'city',
                    'fast'=>['city','chengshi'],
                ],
                [
                    'name' => '区',
                    'field' => 'country',
                    'fast'=>['country','quyu'],
                ]
            ],
        ],
         'select_data_user' => [
            'name' => '用户关联',
             'alias' => '用户',
            'param' => [
                 [
                    'name' => '用户',
                    'field' => 'field',
                    'fast'=>['user_id','uid'],
                ]
            ],
        ],
         'select_data_goods' => [
            'name' => '商品关联',
             'alias' => '商品',
            'param' => [
                 [
                    'name' => '商品',
                    'field' => 'field',
                     'fast'=>['goods_id'],
                ]
            ],
        ],
        'select_model' => [
            'name' => '下拉框（模型关联）',
            'alias' => '',
            'param' => [
                 [
                    'name' => '字段',
                    'field' => 'field',
                    'fast'=>'*id',
                ]
            ],
            'show_model'=>1
        ],
    ],
    'edit_type' => array(
        'wu' => '不显示',
        'no' => '文字显示',
        'text' => '文字编辑',
        'images' => '图片显示',
        'fast_check' => '开关编辑',
        'lan' => '语言包',
    ),
    'search_type' => array(
        'text' => '文字',
        'selec' => '下拉框选择',
        'rangedate' => '日期范围'
    ),
);
