<?php

// 中间件配置
return [
    //版本号
    'system_versions' => '6.7.1',
    'system_program' => 'XFCMS',
    'cate_cache' => true, //栏目获取是否开启缓存
    'cate_cache_time' => 86400, //栏目缓存时间，0=无限，86400为一天
    'content_cache' => true, //栏目列表获取是否开启1缓存
    'content_cache_time' => 86400, //栏目列表缓存时间，0=无限，86400为一天
    'admin_handler_on' => false, //是否开启后台操作日志
    'set_host_on' => false, //是否开启301跳转
    'cate_show_cache_time' => 3600 * 24, //前台详情页静态化时间
    'cate_list_cache_time' => 3600 * 24, //前台列表页静态化时间
    'admin_app' => 'xf',
    'data_page_count' => 20,
    'page_num'=>20,
    'plug' => 'xf',
    'login_error_sys_count' => 50, //每天登录连续可错误次数
    'login_error_admin_count' => 5, //单个用户登录连续可错误次数
    'login_auto_day' => 15, //自动登录天数
    'apiurl' => 'http://api.glxinhu.com:81', //商户接口地址
    'apitoken' => 'f9bc5737dce3e546a57af65900f97988', //商户密钥
    'apikey' => 'chenyeyu',
    'res_pwd' => '123456',
   
    'aliyun_access_key_id' => 'LTAILufOqv1s4LZ1',
    'aliyun_access_key_secret' => '',
    'aliyun_host' => 'http://oss-cn-shenzhen.aliyuncs.com',
    'aliyun_cdn' => 'http://oss-cn-shenzhen.aliyuncs.com',
    'aliyun_oss_bucket' => "xfbase",
    'aliyun_oss_dir' => "test",
    'aliyun_endpoint' => 'oss-cn-shenzhen.aliyuncs.com',
    
    //上传文件方式，localhost ，oss
    'upload_type' => 'localhost',
    //上传大小 M
    'max_file_size' =>20,
    'show_error' => 1,
    //腾讯地图key
    'map_key' => '6ARBZ-2TGW3-N5O3L-YVOPU-SKTUT-G7B6O',
    //是否显示线上环境的错误
    'show_product_err' => 0,
    //默认编辑类型 kindeditor neditor
    'editor_type' => 'neditor',
    //临时二维码目录
    'temp_ewm_path' => './uploads/temp_ewm/',
    
    //小程序公共目录
    'com_cdn'=>'http://xfchen.xinhu.wang/com/',
    
    //支付方式
    'pay_type' => 'wx_js',
    'pay_types' => [
        'fy_miniprogram' => [
            'type' => 4,
            'name' => '微信支付F',
        ],
        'fy_offiaccount' => [
            'type' => 4,
            'name' => '公众号支付F',
        ],
        'wx_js' => [
            'type' => 4,
            'name' => '微信支付',
        ],
        'wx_app' => [
            'type' => 4,
            'name' => '微信APP支付',
        ],
        'ali_app' => [
            'type' => 1,
            'name' => '阿里APP支付',
        ],
        
    ],

    //后台操作日志
    'admin_log'=>false,
    'admin_module'=>['admin','mall','user','com'],


    //储存
    'storage'=>[
        'default' => 'cos', //默认的上传方式
        'max_file_size'=>20, //单位M
        'engine' => [
            'local' => [],

            'oss' => [
                'bucket' => 'weimeigj',
                'access_key_id' => '',
                'access_key_secret' => '',
                'endpoint' => 'weimeigj.oss-cn-shenzhen.aliyuncs.com',
                'bucket_domain' => 'http://weimeigj.oss-cn-shenzhen.aliyuncs.com',
                'cdn_domain' => 'http://weimei.xinhu.wang',
                'dir'=>'',

            ],
            'cos' => [
                'bucket' => 'xf01-1313130749',
                'region' => 'ap-guangzhou',
                'secret_id' => 'AKIDvhFZfXifNtCH9mBMJ8jFZkiS0LTN2Ukl',
                'secret_key' => 'qTr1xe8OQDn9HDuOi8NTQCqKisnW2LUT',
                'cdn_domain' => 'http://xf01.cos.xinhu.wang',
                'dir'=>'suoye/',
            ],
        ],
        'fileAllowExt'=>[
            'images' => ['jpg', 'gif', 'png', 'bmp', 'jpeg'],
            'document' => ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf', 'txt', 'zip', 'rar'],
            'audio' => ['mp3'],
            'video' => ['mp4'],
        ]
    ]

];
