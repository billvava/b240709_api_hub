<?php

return array(
    'name' => '商城设置',
    'pid' => 0,
    'children' => array(
        array(
            'name' => '下单设置',
            'children' => array(
                array(
                    'field' => 'mall_can_dot',
                    'val' => '1',
                    'msg' => '',
                    'name' => '使用积分',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '1'
                ),
                array(
                    'field' => 'mall_can_coupon',
                    'val' => '1',
                    'msg' => '',
                    'name' => '使用优惠券',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '2'
                ),
                array(
                    'field' => 'mall_can_money',
                    'val' => '1',
                    'msg' => '',
                    'name' => '使用余额',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '3'
                ),
                array(
                    'field' => 'dot_scale',
                    'val' => '100',
                    'msg' => '1元=X个积分',
                    'name' => '1元等于',
                    'unit' => '个积分',
                    'input_type' => 'text',
                    'sort' => '4'
                ),
                array(
                    'field' => 'is_stock',
                    'val' => '1',
                    'msg' => '',
                    'name' => '判断库存',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '5'
                ),
                array(
                    'field' => 'dot_mall',
                    'val' => '0',
                    'msg' => '订单完成后触发',
                    'name' => '是否得积分',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '5'
                ),
                array(
                    'field' => 'feie_print',
                    'val' => '0',
                    'msg' => '订单支付时触发该打印',
                    'name' => '开启飞鹅打印',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '6'
                ),
                array(
                    'field' => 'weixin_msg',
                    'val' => '1',
                    'msg' => '对接公众号才生效',
                    'name' => '订单是否发微信消息给客户',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '10',
                ),
            ),
        ),
        array(
            'name' => '配送设置',
            'children' => array(
                array(
                    'field' => 'mall_delivery_free',
                    'val' => '0',
                    'msg' => '等于0则无此规则',
                    'name' => '满X元免配送费',
                    'input_type' => 'text',
                    'sort' => '3'
                ),
                array(
                    'field' => 'mall_peisong_on',
                    'val' => '1',
                    'msg' => '',
                    'name' => '快递配送',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '1'
                ),
                array(
                    'field' => 'mall_ziti_on',
                    'val' => '1',
                    'msg' => '',
                    'name' => '到店自提',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '2'
                ),
                array(
                    'field' => 'mall_ziti_name',
                    'val' => 'XX公司',
                    'msg' => '',
                    'name' => '自提点名称',
                    'input_type' => 'text',
                    'sort' => '3'
                ),
                array(
                    'field' => 'mall_ziti_address',
                    'val' => '15号',
                    'msg' => '',
                    'name' => '自提点地址',
                    'input_type' => 'text',
                    'sort' => '4'
                ),
                array(
                    'field' => 'mall_ziti_tel',
                    'val' => '',
                    'msg' => '',
                    'name' => '自提点电话',
                    'input_type' => 'text',
                    'sort' => '5'
                ),
            ),
        ),
        array(
            'name' => '其他设置',
            'children' => array(
                array(
                    'field' => 'mall_close_minute',
                    'val' => '15',
                    'msg' => '订单下单未付款，n分钟后自动关闭，设置为0不自动关闭',
                    'name' => '订单自动关闭',
                    'input_type' => 'text',
                    'sort' => '1',
                    'unit' => '分',
                ),
                array(
                    'field' => 'receive_days',
                    'val' => '15',
                    'msg' => '如果在期间未确认收货，系统自动完成收货，设置0天不自动收货',
                    'name' => '订单自动确认',
                    'input_type' => 'text',
                    'unit' => '天',
                    'sort' => '2',
                ),
                array(
                    'field' => 'feie_sn',
                    'val' => '',
                    'msg' => '飞鹅打印机的编号',
                    'name' => '打印机编号',
                    'input_type' => 'text',
                    'sort' => '6'
                ),
                array(
                    'field' => 'feie_key',
                    'val' => '',
                    'msg' => '飞鹅打印机的密钥',
                    'name' => '打印机密钥',
                    'input_type' => 'text',
                    'param' => '',
                    'sort' => '6'
                ),
                array(
                    'field' => 'is_sale',
                    'val' => '1',
                    'msg' => '',
                    'name' => '分销开关',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '11'
                ),
                array(
                    'field' => 'min_bro_money',
                    'val' => '100',
                    'msg' => '',
                    'name' => '最低提现佣金',
                    'input_type' => 'text',
                    'sort' => '12'
                ),
                array(
                    'field' => 'bro_agency_fee',
                    'val' => '0',
                    'msg' => '',
                    'name' => '提现手续费',
                    'input_type' => 'text',
                    'sort' => '13',
                    'unit' => '元'
                ),
                array(
                    'field' => 'bro_agency_ratio',
                    'val' => '0',
                    'msg' => '',
                    'name' => '提现手续费',
                    'input_type' => 'text',
                    'sort' => '13',
                    'unit' => '%'
                ),
                array(
                    'field' => 'order_xls_field',
                    'val' => 'ordernum,total,goods_total,pay_status,pay_type,goods_list,linkman,tel,province,city,country,address,message,admin_remark',
                    'msg' => '一般无须修改',
                    'name' => '订单导出配置',
                    'input_type' => 'text',
                    'sort' => '99',
                ),
            ),
        ),
        array(
            'name' => '支付配置',
            'children' => array(
                array(
                    'field' => 'weixin_pay',
                    'val' => '1',
                    'msg' => '',
                    'name' => '微信支付',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '7',
                ),
                array(
                    'field' => 'ali_pay',
                    'val' => '1',
                    'msg' => '',
                    'name' => '支付宝支付',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '8',
                ),
                array(
                    'field' => 'under_pay',
                    'val' => '1',
                    'msg' => '',
                    'name' => '货到付款',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '9',
                ),
                array(
                    'field' => 'freight_rule',
                    'val' => '1',
                    'msg' => '',
                    'name' => '运费组合策略',
                    'input_type' => 'radio',
                    'option' => '[{"name":"\u5F00\u542F","val":"1"},{"name":"\u5173\u95ED","val":"0"}]',
                    'option_text' => '0=关闭,1=开启',
                    'sort' => '12',
                ),
            ),
        ),
        array(
            'name' => '拼团配置',
            'children' => array(
                array(
                    'field' => 'close_pt',
                    'val' => '1',
                    'msg' => '单位/天',
                    'name' => '关闭拼团时间',
                    'input_type' => 'text',
                    'sort' => '1',
                ),
            ),
        ),
    ),
);
