<?php

/**
 * @name 添加字段
 */
function add_field($params) {
    @extract($params);
    $sql = "alter table `{$table}` Add column {$field}  DEFAULT NULL {$default} COMMENT '{$fname}'";
    return $sql;
}

function add_field_ext($params) {
    @extract($params);
    $sql = "alter table `{$table}` Add column {$field}  DEFAULT  {$default} COMMENT '{$fname}'";
    return $sql;
}

/**
 * 删除字段
 * @param type $params
 * @return type
 */
function drop_field($params) {
    @extract($params);
    $sql = "alter table `{$table}` drop column {$field} ";
    return $sql;
}

/**
 * @name 编辑字段
 */
function edit_field($params) {
    @extract($params);
    $sql = "alter table `{$table}` modify column {$field} DEFAULT NULL {$default}  COMMENT '{$fname}';";
    return $sql;
}

/**
 * 创建模型表
 * @param type $params
 * @return type
 */
function create_model_table($params) {
    @extract($params);
    $def = DB_PREFIX;
    $sql = "CREATE TABLE `{$def}content_{$model}` (
            `cid` mediumint(8) unsigned NOT NULL  COMMENT '主键',
            PRIMARY KEY (`cid`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='内容模型主表';";
    return $sql;
}

/**
 * 自动插入模型字段
 */
function insert_field($model_id) {
    $def = DB_PREFIX;
    $sql = "INSERT INTO `{$def}system_field` VALUES (null, '{$model_id}', 'cid', '', 'input', '', '0', '0', '主键',0),
            (null, '{$model_id}', 'catid', '', 'input', '', '0', '0', '栏目ID',0),
            (null, '{$model_id}', 'title', '', 'input', '{\"require\":\"1\"}', '1', '0', '标题',0),
            (null, '{$model_id}', 'thumb', '', 'thumb', '{\"create_thumn\":\"0\",\"thumb_width\":\"150\",\"thumb_height\":\"150\",\"create_water\":\"0\",\"water_text\":\"150\",\"require\":\"0\"}', '0', '0', '缩略图',0),
            (null, '{$model_id}', 'attr', '1=hot,2=top,3=scroll', 'checkbox', '{\"option\":\"热点|1,置顶|2,轮播|3\"}', '1', '0', '文档属性',0),
            (null, '{$model_id}', 'status', '0=禁用,1=启用', 'radio', '{\"option\":\"禁用|0,启用|1,\",\"defaultvalue\":\"1\",\"require\":\"1\"}', '1', '0', '状态',0),
            (null, '{$model_id}', 'sort', '', 'input', '', '0', '0', '排序',0),
            (null, '{$model_id}', 'user_id', '', 'input', '', '0', '0', '用户ID',0),
            (null, '{$model_id}', 'username', '', 'input', '', '0', '0', '用户名',0),
            (null, '{$model_id}', 'create_time', '', 'datetime', '', '0', '0', '建立时间',0),
            (null, '{$model_id}', 'update_time', '', 'datetime', '', '0', '0', '更新时间',0),
            (null, '{$model_id}', 'hits', '', 'input', '', '0', '0', '浏览量',0),
            (null, '{$model_id}', 'seotitle', '', 'input', '', '0', '0', 'seo标题',0),
            (null, '{$model_id}', 'seokeywords', '', 'input', '', '0', '0', 'seo关键字',0),
            (null, '{$model_id}', 'seodescription', '', 'textarea', '', '0', '0', 'seo描述',0);";
    return $sql;
}

/**
 * 复制表
 * @param type $copyTbale 要复制的表表名
 * @param type $newTable  新表表名
 * @return type
 */
function copy_table($copyTbale, $newTable) {
    return "CREATE TABLE  `$newTable`  LIKE `$copyTbale`";
}
