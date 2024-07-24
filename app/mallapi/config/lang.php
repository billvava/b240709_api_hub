<?php
// +----------------------------------------------------------------------
// | 多语言设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    'extend_list'     => [
        'zh-cn'    => [
            app()->getBasePath() . 'mall/lang/zh-cn.php',
            app()->getBasePath() . 'user/lang/zh-cn.php',
        ],
    ],
];
