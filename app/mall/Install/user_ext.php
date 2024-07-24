<?php

return array(
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_total float(10,2)",
        'fname' => "消费金额",
        'default' => "0",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_order_avg float(10,2)",
        'fname' => "订单均价",
        'default' => "0",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_new_time datetime",
        'fname' => "最近购买时间",
        'default' => "NULL",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_order_num int(8)",
        'fname' => "支付订单数量",
        'default' => "0",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_feek_num int(8)",
        'fname' => "售后订单数量",
        'default' => "0",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_com_num int(8)",
        'fname' => "评价数量",
        'default' => "0",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "mall_atn_num int(8)",
        'fname' => "收藏商品数量",
        'default' => "0",
    ),
    array(
        'table' => '{{pre}}user_ext',
        'field' => "get_coupon int(8)",
        'fname' => "是否领取新人券",
        'default' => "0",
    ),
);
