<?php

return array(
    //主要参数
    'copy' => '技术服务商：新狐科技  电话：0771-5346690',
    'success' => '操作成功!',
    'error' => '操作失败!',
    'conf_msg' => '为保证系统正常，非开发者不得修改该配置',
    //meun控制器
    'add_success' => '操作成功，请刷新浏览器!',
    'add_error' => '操作失败，请刷新浏览器!',
    'meun_title' => '菜单管理',
    'exit_meun' => '该菜单下含有子菜单，无法删除，请先删除子菜单！',
    //login控制器
    'login_error' => '用户名或密码错误',
    'verify_error' => '验证码错误或超时',
    //admin控制器
    'admin_title' => '管理员管理',
    'only_shuzi_zimu' => '帐户和密码只能使用数字和字母',
    'admin_del_error' => '操作失败，该用户无法被删除',
    //model控制器
    'model_title' => '模型管理',
    //field控制器
    'field_title' => '字段管理',
    'par_msg' => '仅对varchar和char有效，float默认为8,2;editor,images自动为text,time为char(10),date,datetime为int(10),file,thumb为varchar(120),如需修改可在插入后再数据库处修改',
    //category_title控制器
    'category_title' => '分类管理',
    'category_del_error' => '该分类下含有子分类，无法删除，请先删除子分类！',
    //content控制器
    'content_title' => '内容管理',
    //广告管理
    'AD_TITLE' => '广告管理',
    //用户管理
    'user_title' => '用户管理',
    'res_success' => '重置密码成功',
    'res_error' => '重置密码失败',
    //角色管理
    'role_title' => '角色管理',
    //权限管理
    'auth_title' => '权限管理',
    //配置文件
    'config_title' => '系统配置',
    //缓存
    'cache_title' => '缓存管理',
    // 配置类型分组
    'config_type_group' => array('1' => '网站设置', '2' => '常用设置', '3' => '积分相关', '4' => '订单设置', '5' => '微信设置', '6' => '支付宝设置', '7' => '短信设置', '8' => '邮件设置', '9' => '银联支付', '10' => '水印设置'),
    // 配置输入类型
    'input_type' => array('text' => '文本框', 'radio' => '单选框', 'textarea' => '多行文本', 'img' => '上传图片', 'fdate' => '日期', 'datetime' => '日期时间'),
    //'checkbox' => '复选框', 'select' => '下拉框',
    'cate_html_status' => array(0 => '禁用', 1 => '开启'),
    'cate_is_hide' => array(0 => '显示', 1 => '隐藏'),
    //颜色
    'color' => array(
        '0' => 'color:#6A90AA;',
        '1' => 'color:#000000;',
        '2' => 'color:#927D7D;',
        '3' => 'color:#761E1E;',
        '4' => 'color:#337E33;',
        '5' => 'color:#094F3C;',
        '6' => 'color:#CE4AC3;',
        '7' => 'color:#FB0718;',
        '8' => 'color:#462EAE;',
        '9' => 'color:#E88A1A;',
        '10' => 'color:#6A90AA;',
    ),
    'wx_reply_type' => array(
        '1' => '文字回复',
        '2' => '图片回复',
        '3' => '素材回复',
    ),
    'rbac_status' => array(
        1 => '可用',
        0 => '禁用',
    ),
    'rbac_level' => array(
        1 => '模块',
        2 => '控制器',
        3 => '方法',
    ),
    'role_status' => array(
        1 => '可用',
        0 => '禁用',
    ),
    'nav_type' => array(
        1 => '系统',
        2 => '网址',
    ),
    'admin_status' => array(
        0 => '禁用',
        1 => '可用',
    ),
    'is_sys' => array(
        0 => '扩展',
        1 => '自带',
    ),
    'module_status' => array(
        1 => '可用',
        0 => '关闭',
    ),
    'message_type' =>array(1=>'普通留言',)
);
