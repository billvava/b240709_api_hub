<?php
// 全局中间件定义文件
return [
    //加载配置文件
    \app\common\middleware\ConfigLoad::class,

    // 全局请求缓存
//     \think\middleware\CheckRequestCache::class,
    // 多语言加载
     \think\middleware\LoadLangPack::class,
    // Session初始化
     \think\middleware\SessionInit::class,
    
      \think\middleware\LoadLangPack::class,
    
];
