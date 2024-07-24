<?php
namespace app\gii\model;

use think\facade\Db;
use think\Model;

class O
{
    /**
     * 获取表名
     * @param type $TableName
     * @return boolean
     */

    public static function getTableName($TableName) {
        $c=config('database.connections.mysql');
        $DB_PREFIX = DB_PREFIX;
        $DB_NAME = $c['database'];
        $sql = "select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA='{$DB_NAME}' and TABLE_NAME='{$DB_PREFIX}{$TableName}' ;";
        $data = Db::query($sql);
        if (!$data) {
            return false;
        } else {
            return $data;
        }
    }
    /**
     * 查询表信息
     * @param type $TableName
     * @return boolean
     */
    public static function showTables($TableName){
        $c=config('database.connections.mysql');
        $DB_PREFIX = DB_PREFIX;
        $DB_NAME = $c['database'];
        $sql = "select table_name,table_comment from information_schema.tables  where table_schema = '{$DB_NAME}' and table_name ='{$DB_PREFIX}{$TableName}'";
        $data = Db::query($sql);
        if (!$data) {
            return false;
        } else {
            return $data[0];
        }

    }

    //获取列名列表
    public static function getTableInfoArray($tableName) {
        $c=config('database.connections.mysql');
        $DB_PREFIX = DB_PREFIX;
        $DB_NAME = $c['database'];
        $result = Db::query("select * from information_schema.columns where table_schema='" . $DB_NAME . "' and table_name='" . $DB_PREFIX . $tableName . "'");
        return $result;
    }

}