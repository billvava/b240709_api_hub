<?php
// 配置结构说明
// '类名' => [
//     'config' => [], // 类初始化配置__construct可用接收参数 如配置APPID等基础信息 可调用C函数
//     'action' => [ // 方法配置 优先级:类方法检测 > 方法映射检测
//         '调用方法名' => '映射类方法名' // 如: unifiedPay => pay
//     ],
//     'result' => [ // 返回配置
//         '类方法名' => [ // 如: notify
//             '统一返回字段名' => '映射类返回字段' // 如: ordernum => data.transaction_id
//         ]
//     ]
// ]

return [
    // 支付宝
    'Alipay' => [
        'config' => [
            'appid' => '', // https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
            'notify_url' => '', // 支付成功后异步回调地址
            'return_url' => '', // 支付成功后跳转地址
            'public_key' => '', // 支付宝公钥
            'private_key' => '', // 商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
        ],
        'action' => [ // 执行方法 => 映射类方法
            'payForPc' => 'payForPc', // 电脑端支付
            'payForWap' => 'payForWap', // 手机端支付
            'payForApp' => 'payForH5', // H5/APP/JS调用支付
            'payForQrcode' => 'payForQrcode', // 扫码支付
            'transfer' => 'transfer', // 转账
            'checkOrderTransfer' => 'checkOrderTransfer', // 转账订单查询
            'refund' => 'refund', // 退款
            'orderClose' => 'orderClose', // 关闭订单 用于交易创建后，用户在一定时间内未进行支付，可调用该接口直接将未付款的交易进行关闭。
            'notify' => 'rsaCheck', // 支付成功后签名验证
        ],
        // 返回值文档查看 https://opendocs.alipay.com/apis
        'result' => [
            'rsaCheck' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'transaction' => 'trade_no', // 流水号
                'ordernum' => 'out_trade_no', // 系统订单号
                'total' => 'total_amount', // 支付金额
                'remark' => 'remark', // 支付备注 支付传什么就返回什么
                'msg' => 'info', // 提示信息
                'echo' => 'echo' // 成功后需要echo出的内容
            ]
        ]
    ],
    // 微信支付
    'minpro_pay' => [
        'config' => [
            'appid' => C('appid'),
            'wxshopid' => C('wxshopid'),
            'wxshoppwd' => C('wxshoppwd'),
            'apppwd' => C('apppwd'),
        ],
        'action' => [ // 执行方法 => 映射类方法
            'payForApp' => 'app_pay', // APP支付
            'payForMini' => 'pay', // 小程序支付
            'refund' => 'refund', // 退款
            'notify' => 'notify', // 回调函数
        ],
        'result' => [
            // key = 库类方法名
            'app_pay' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'ordernum' => 'data.ordernum', // 发起订单号
                'is_pay' => 'data.is_pay', // 是否支付
                'orderid' => 'data.orderid', // 订单ID
                'parameters' => 'data.parameters', // App发起包
                'msg' => 'info' // 提示信息
            ],
            'pay' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'ordernum' => 'data.ordernum', // 发起订单号
                'is_pay' => 'data.is_pay', // 是否支付
                'orderid' => 'data.orderid', // 订单ID
                'parameters' => 'data.parameters', // 小程序发起包
                'msg' => 'info' // 提示信息
            ],
            'refund' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'msg' => 'info' // 提示信息
            ],
            'notify' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'transaction' => 'transaction_id', // 流水号
                'ordernum' => 'out_trade_no', // 系统订单号
                'total' => 'total_fee', // 支付金额 类返回时已按分转换为元
                'remark' => 'remark', // 支付备注 支付传什么就返回什么
                'msg' => 'info', // 提示信息
                'echo' => 'echo' // 成功后需要echo出的内容
            ]
        ]
    ],
    // 富有支付
    'minprofy_pay' => [
        'config' => [
            'appid' => C('appid'),
            'ins_cd' => '08M0026926',
            'mchnt_cd' => '',
            'host' => 'https://spay-mc.fuioupay.com/',
            'bak_host' => 'https://spay-xs.fuioupay.com/',
            'api_host' => 'http://pay.beejian.com/',
            'no_code' => '1434',
            'key' => '',
            'public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZVpcAcSNs5bzh58nJyIawx20Y9gYZ/XBC8FRS26SOtOqEdWVSIQXNsFjdr4DsTUtY4S9b69J+P0sOsUx7W9ZdcLdLzawW6JoJvPr7H/wgiDOS7qAeirqu3leMo9zA1tPVjTs10TDVrnRgO1hrefvvtg9hc0bDOXfQ1/w5xovLnQIDAQAB'
        ],
        'action' => [
            'payForMini' => 'pay', // 小程序支付
            'refund' => 'refund', // 退款
            'notify' => 'notify', // 回调函数
        ],
        'result' => [
            // key = 库类方法名
            'pay' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'ordernum' => 'data.ordernum', // 发起订单号
                'is_pay' => 'data.is_pay', // 是否支付
                'orderid' => 'data.orderid', // 订单ID
                'parameters' => 'data.parameters', // 小程序发起包
                'msg' => 'info' // 提示信息
            ],
            'refund' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'msg' => 'info' // 提示信息
            ],
            'notify' => [
                'status' => 'status', // 返回状态值 1成功 0失败
                'transaction' => 'transaction_id', // 流水号
                'no' => 'mchnt_order_no', // 富有单号
                'ordernum' => 'ordernum', // 系统订单号
                'total' => 'total', // 支付金额
                'remark' => 'remark', // 支付备注 支付传什么就返回什么
                'msg' => 'info', // 提示信息
                'echo' => 'echo' // 成功后需要echo出的内容
            ]
        ]
    ],
];