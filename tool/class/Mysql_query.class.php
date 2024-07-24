<?php

/**
 * mysqli操作，非特殊情况下不建议使用
 */
class Mysql_query {

    public function query($sql) {
        $c=config('database.connections.mysql');

        if (function_exists('mysql_connect')) {
            $mysqli = mysql_connect($c['hostname'], $c['username'],$c['password']);
            mysql_query("SET NAMES 'utf8';", $mysqli);
            mysql_query("use " .  $c['database']. " ;", $mysqli);
            mysql_query($sql, $mysqli);
            mysql_close($mysqli);
        } else {
            $mysqli = mysqli_connect($c['hostname'], $c['username'], $c['password'], $c['database']);
            $mysqli->query("SET NAMES 'utf8';");
            $mysqli->query("use " .$c['database']. " ;");
            $mysqli->query($sql);
            $mysqli->close();
        }
    }

}
