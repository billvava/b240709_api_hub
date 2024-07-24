<?php

return array(
    'order_type'=>[
        1=>'开锁',
        2=>'换锁',
        3=>'安装锁',
        4=>'维修锁',
        5=>'配钥匙'
    ],
    'order_type_arr'=>[
        [
          'type'=>1,
          'name'=>'开锁',
        'icon'=>'home/icon-type-1.png',
        ],
        [
            'type'=>2,
            'name'=>'换锁',
            'icon'=>'home/icon-type-2.png',
        ],
        [
            'type'=>3,
            'name'=>'安装锁',
            'icon'=>'home/icon-type-3.png',
        ],
        [
            'type'=>4,
            'name'=>'维修锁',
            'icon'=>'home/icon-type-4.png',
        ],
        [
            'type'=>5,
            'name'=>'配钥匙',
            'icon'=>'home/icon-type-5.png',
        ],

    ],

    'master_order_type_arr'=>[
        [
          'type'=>1,
          'name'=>'开锁',
        'icon'=>'word-order/icon-type-1.png',
        ],
        [
            'type'=>2,
            'name'=>'换锁',
            'icon'=>'word-order/icon-type-2.png',
        ],
        [
            'type'=>3,
            'name'=>'安装锁',
            'icon'=>'word-order/icon-type-3.png',
        ],
        [
            'type'=>4,
            'name'=>'维修锁',
            'icon'=>'word-order/icon-type-4.png',
        ],
        [
            'type'=>5,
            'name'=>'配钥匙',
            'icon'=>'word-order/icon-type-5.png',
        ],

    ],
    //系统颜色
    'wap_shop_color' => array(
        'main' => '#0a8a48', //这是一个绿色 #0a8a48
        'btn' => '#ff6500', //这是红色
        'txt' => '#fff', //白色
        'btn_se' => '#fd9a34', //淡一点的红色
        'small' => '#888', //灰色
    ),
    //颜色
    'color' => array(
        0 => 'color:#6A90AA;',
        1 => 'color:#000000;',
        2 => 'color:#927D7D;',
        3 => 'color:#761E1E;',
        4 => 'color:#337E33;',
        5 => 'color:#094F3C;',
        6 => 'color:#CE4AC3;',
        7 => 'color:#FB0718;',
        8 => 'color:#462EAE;',
        9 => 'color:#E88A1A;',
        10 => 'color:#6A90AA;',
    ),
    'status_class' => array(
        0 => 'x-red',
        1 => 'x-a',
    ),
    'hide_class' => array(
        0 => 'x-a',
        1 => 'x-red',
    ),
    //性别
    'sex' => array(
        0=>'暂无',
        1 => '男',
        2 => '女',
        3 => '未知',
    ),
    'wxsao' => '微信接口加载未完成，请重试！',
    'res_success' => '重置密码成功',
    'res_error' => '重置密码失败',
    's' => '操作成功',
    'e' => '操作失败',
    'comment_class1' => 'label-success',
    'comment_class2' => 'label-warning',
    'comment_class3' => 'label-danger',
    'tixian0' => '未处理',
    'tixian1' => '已打款',
    'tixian2' => '已拒绝',
    'verify_error' => '验证码错误',

    'empty_header' => 'http://xf01.cos.xinhu.wang/suoye/static/common/default-avatar.png',
    'wx_is_sub' => array(
        0 => '',
        1 => '关注回复',
    ),
    'is' => array(
        0 => array(
            'name' => '否',
            'str' => '禁用',
            's' => '未使用',
            'show' => '显示',
            'class' => '',
        ),
        1 => array(
            'name' => '是',
            'str' => '正常',
            's' => '已使用',
            'show' => '隐藏',
            'class' => 'x-a',
        ),
    ),
    'txt_class' => array(
        0 => 'x-hui',
        1 => 'x-b',
        2 => 'x-g',
        3 => 'x-red',
        4 => 'x-a',
    ),
    //自动增加字段
    'auto_time'=>array(
        'create_time'
    ),
    //搜索时间字段
    'search_time'=>array(
        'create_time','time','update_time','pay_time'
    ),
    'search_field' => array('title', 'name', 'content', 'ordernum', 'username', 'msg', 'remark', 'message'),
    

);
