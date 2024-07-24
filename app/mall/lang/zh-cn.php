<?php

return array(
    'mall_content_type' => array(
        1 => '说明',
        2 => '帮助',
    ),
    'comment_rank' => array(
        1 => '好评',
        2 => '中评',
        3 => '差评',
    ),
    'after_status' => [
        0 => '未申请退款', 1 => '申请退款中', 2 => '等待退款', 3 => '已退款成功'
    ],
    'coupon_range' => array(
        1 => '全场可用',
//        2 => '限定商品', 3 => '限定分类'
    ),
    'coupon_end_type' => array(
        1 => '固定日期', 2 => '固定天数'
    ),
    'coupon_type' => array(
        1 => '模板备用', 
//        2 => '注册赠送', 3 => '购物赠送', 
        4 => '前台领取',
    ),
    'coupon_source' => array(
        1 => '后台赠送', 2 => '注册赠送', 3 => '购物赠送', 4 => '前台领取',
    ),
    'comment_type' => array(
        1 => '真实评论',
        2 => '虚拟评论'
    ),
    'comment_rank' => array(
        1 => '好评',
        2 => '中评',
        3 => '差评',
    ),
    'feedback_type' => array(
        1 => '退款且退货',
        2 => '退款',
        3 => '换货',
        4 => '其他'
    ),
    'order_log_cate' => array(
        1 => '创建订单',
        2 => '支付订单',
        3 => '订单发货',
        4 => '订单确认',
        5 => '订单评论',
        6 => '订单退款',
        7 => '订单关闭',
        8 => '订单前台删除',
        9 => '订单后台删除',
        10 => '修改信息',
        11 => '后台已支付',
    ),
    'order_log_type' => array(
        1 => '前台操作',
        2 => '后台操作',
    ),
    'order_is_send' => array(
        0 => '未发货',
        1 => '已发货',
    ),
    'pay_type' => array(
        1 => '货到付款',
        2 => '在线抵消',
        3 => '支付宝',
        4 => '微信支付',
        5 => '余额支付',
    ),
    'is_pay' => array(
        0 => '待支付',
        1 => '已支付',
        2 => '已退款',
        3 => '拒绝退款',
    ),
    'send_type' => array(
        1 => '快递配送',
        2 => '到店自提',
        3 => '商家自配',
        4 => '非自提',
    ),
    'order_status' => array(
        0 => '待付款',
        1 => '待发货',
        2 => '待收货',
        3 => '已完成',
        4 => '已关闭',
        5 => '拼团中',
        6 => '拼团失败',

    ),
    'order_status_field' => array(
        0 => 'wait_pay_count',
        1 => 'wait_send_count',
        2 => 'wait_get_count',
        3 => 'finish_count',
        4 => 'close_count',
    ),
    'feedback_status' => array(
        0 => '无',
        1 => '售后中',
        2 => '售后已处理',
        3 => '退货待填单号',
        4 => '退货已发货',
    ),
    'money_field' => array(
        'goods_total' => '商品价格',
        'send_money' => '物流费用',
        'money' => '余额抵扣',
        'rank_money' => '角色优惠',
        'dot_money' => '积分抵扣',
        'coupon_money' => '优惠券',
    ),
    'xls_field' => array(
        array(
            'field' => 'order_id',
            'name' => '系统编号',
            'checked' => 0,
        ),
        array(
            'field' => 'ordernum',
            'name' => '订单号',
            'checked' => 1,
        ),
        array(
            'field' => 'total',
            'name' => '订单金额',
            'checked' => 1,
        ),
        array(
            'field' => 'goods_total',
            'name' => '商品价格',
            'checked' => 1,
        ),
        array(
            'field' => 'delivery_total',
            'name' => '物流费用',
            'checked' => 0,
        ),
        array(
            'field' => 'money',
            'name' => '余额抵扣',
            'checked' => 0,
        ),
        array(
            'field' => 'rank_money',
            'name' => '角色优惠',
            'checked' => 0,
        ),
        array(
            'field' => 'dot_total',
            'name' => '积分抵扣',
            'checked' => 0,
        ),
        array(
            'field' => 'coupon_total',
            'name' => '优惠券',
            'checked' => 0,
        ),
        array(
            'field' => 'pay_status',
            'name' => '支付状态',
            'checked' => 1,
        ),
        array(
            'field' => 'pay_type',
            'name' => '支付方式',
            'checked' => 1,
        ),
        array(
            'field' => 'pay_time',
            'name' => '支付时间',
            'checked' => 0,
        ),
        array(
            'field' => 'create_time',
            'name' => '创建时间',
            'checked' => 0,
        ),

        array(
            'field' => 'username',
            'name' => '购买用户',
            'checked' => 0,
        ),
        array(
            'field' => 'linkman',
            'name' => '收货人',
            'checked' => 1,
        ),
        array(
            'field' => 'tel',
            'name' => '收货电话',
            'checked' => 1,
        ),
        array(
            'field' => 'province',
            'name' => '收货省份',
            'checked' => 1,
        ),
        array(
            'field' => 'city',
            'name' => '收货城市',
            'checked' => 1,
        ),
        array(
            'field' => 'country',
            'name' => '收货区域',
            'checked' => 1,
        ),
        array(
            'field' => 'address',
            'name' => '收货地址',
            'checked' => 1,
        ),
        array(
            'field' => 'delivery_status',
            'name' => '发货状态',
            'checked' => 0,
        ),
        array(
            'field' => 'delivery_type',
            'name' => '发货方式',
            'checked' => 0,
        ),
        array(
            'field' => 'message',
            'name' => '客户留言',
            'checked' => 1,
        ),
        array(
            'field' => 'admin_remark',
            'name' => '后台备注',
            'checked' => 1,
        ),
        array(
            'field' => 'goods_list',
            'name' => '商品',
            'checked' => 1,
        ),
    ),
    'order_source' => array(
        0 => '未归类',
        1 => '微信公众号',
        2 => '小程序',
        3 => 'PC网站',
    ),
    'order_type' => array(
        1 => '普通订单',
        2 => '秒杀订单',
        3 => '拼团订单',
        4 => '砍价订单',
        6 => '盲盒订单',
    ),
    'pt_status' => array(
        0 => '拼团进行中',
        1 => '支付中',
        2 => '拼团完成',
        3 => '拼团失败',
    ),
    'pt_type' => array(
        1 => '发团',
        2 => '参团',
    ),
    'goods_spec_field' => [
            [
            'name' => '图片',
            'type' => 'img',
            'is_must' => '0',
            'field' => 'spec_image'
        ],
            [
            'name' => '划线价',
            'type' => 'number',
            'is_must' => '1',
            'field' => 'market_price'
        ],
            [
            'name' => '售价',
            'is_must' => '1',
            'type' => 'number',
            'field' => 'price'
        ],
        [
            'name' => '成本价',
            'type' => 'number',
            'is_must' => '1',
            'field' => 'price2'
        ],
       
            [
            'name' => '库存',
            'is_must' => '1',
            'type' => 'number',
            'field' => 'stock'
        ],
            [
            'name' => '体积',
            'is_must' => '0',
            'type' => 'number',
            'field' => 'volume'
        ],
            [
            'name' => '重量(kg)',
            'is_must' => '0',
            'type' => 'number',
            'field' => 'weight'
        ]
    ],
);
