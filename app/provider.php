<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
    'Util' => app\common\lib\Util::class,
    'admin'=>app\common\lib\Admin::class,
    'js'=>app\common\lib\Js::class,
];
