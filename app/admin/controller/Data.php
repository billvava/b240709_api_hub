<?php
namespace app\admin\controller;
use think\App;
use think\facade\Db;
use think\facade\View;

class Data extends Common
{
    public $table_list;
    public $c;
    public function __construct(App $app)
    {
        parent::__construct($app);
        set_time_limit(0);
        $this->c=config('database.connections.mysql');
        View::assign('c',$this->c);
        $this->table_list = array(
            'order',
            'order_goods',
            'admin_log',
            'money',
        );
        if (in_array(app()->request->action(), array('init_table', 'clear_t'))) {
            $def = DB_PREFIX;
            $res = Db::query("SHOW TABLES  LIKE \"%{$def}user%\" ");
            foreach ($res as $key => $value) {
                $this->table_list[] = str_replace($def, '', reset($value));
            }
        }

    }

    /*
     * 清空表
     */
    public function init_table() {
        echo "Stop";
    }

    /**
     * truncate table
     */
    public function clear_t() {
        $t = input('t');
        $def = DB_PREFIX;
        tool()->classs('Mysql_query');
        $Mysqli = new \Mysql_query();
        if ($t && in_array($t, $this->table_list)) {
            $Mysqli->query("truncate table {$def}{$t}");
            $this->success(lang('s'));
        }
        if (!$t) {
            foreach ($this->table_list as $value) {
                $Mysqli->query("truncate table {$def}{$value}");
            }
            $this->success(lang('s'));
        }
    }

    /*
     * 优化/修复/表结构
     */
    public function opimize() {
        $this->title = '修复、优化表结构';
        $returnStr = '';
        $c=$this->c;
        $host = $c['hostname'] . ($c['hostport']? ":" . $c['hostport'] : '');
        $user = $c['username'];
        $password = $c['password'];
        $dbname = $c['database'];
        $prefix = DB_PREFIX;

     
        $res = Db::query("show tables like '$prefix%'");
        $selStr = '<select name="tablename" class="select-3 form-control chosen">';
        foreach ($res as $t) {
            $table = reset($t);
            $selStr.= '<option value="' . $table . '">' . $table . '</option>';
        }
        $selStr .= '</select>';
        if (!empty($_POST['execute'])) {
            $dopost = input('post.dopost');
            $tablename = input('post.tablename');
            //优化表
            if ($dopost == 'opimize') {
                if (empty($tablename)) {
                    $returnStr .= "没有指定表名！";
                } else {
                    $rs = Db::execute("OPTIMIZE TABLE `$tablename`");
                    if ($rs)
                        $returnStr .= "执行优化表： $tablename  OK！";
                    else
                        $returnStr .= "执行优化表： $tablename  失败，原因是：" . $rs;
                }
            }
            //优化所有表
            else if ($dopost == "opimizeAll") {
                $res = Db::query("show tables like '$prefix%'");
                foreach ($res as $t) {
                    $table = reset($t);
                    $rs = Db::execute("OPTIMIZE TABLE `$table`");

                    if ($rs) {
                        $returnStr .= "优化表: {$table} ok!<br />\r\n";
                    } else {
                        $returnStr .= "优化表: {$table} 失败! 原因是: " . $rs . "<br />\r\n";
                    }
                }
            }
            //修复表
            else if ($dopost == "repair") {
                if (empty($tablename)) {
                    $returnStr .= "没有指定表名！";
                } else {

                    $rs = Db::execute("REPAIR TABLE `$tablename` ");
                    if ($rs)
                        $returnStr .= "修复表： $tablename  OK！";
                    else
                        $returnStr .= "修复表： $tablename  失败，原因是：" . $rs;
                }
            }
            //修复全部表
            else if ($dopost == "repairAll") {
                $res = Db::query("show tables like '$prefix%'");
                foreach ($res as $t) {
                    $table = reset($t);
                    $rs = Db::execute("REPAIR TABLE `$table`");
                    if ($rs) {
                        $returnStr .= "修复表: {$table} ok!<br />\r\n";
                    } else {
                        $returnStr .= "修复表: {$table} 失败! 原因是: " . $rs . "<br />\r\n";
                    }
                }
            }
            //查看表结构
            else if ($dopost == "viewinfo") {
                if (empty($tablename)) {
                    echo "没有指定表名！";
                } else {

                    $rs = Db::query("SHOW CREATE TABLE " . $dbname . "." . $tablename);
                    $ctinfo = $rs[0]['create table'];
                    $returnStr .= "<xmp>" . trim($ctinfo) . "</xmp>";
                }
            }
        }
        View::assign('selStr',$selStr);
        View::assign('returnStr',$returnStr);
        return $this->display();
    }

    /*
     * sql命令
     */
    public function sql() {
        $this->title = 'SQL命令';
        $returnStr = '';
        if (isset($_POST['execute'])) {
            $sqlquery = $_POST['sqlquery'];
            $sqlquery = trim(stripslashes($sqlquery));
            if (preg_match("#drop(.*)table#i", $sqlquery) || preg_match("#drop(.*)database#", $sqlquery)) {
                $returnStr = "<span style='font-size:10pt'>删除'数据表'或'数据库'的语句不允许在这里执行。</span>";
                $this->returnStr = $returnStr;
                $this->display();
                die;
            }
            //运行查询语句
            $c=$this->c;
            $host = $c['hostname'] . ($c['hostport']? ":" . $c['hostport'] : '');
            $user = $c['username'];
            $password = $c['password'];
            $dbname = $c['database'];
            $prefix = DB_PREFIX;
            mysqli_connect($host, $user, $password);
            mysqli_select_db($dbname);
            if (preg_match("#^select #i", $sqlquery)) {
                $res = mysqli_query($sqlquery);
                if (empty($res)) {
                    $returnStr = "运行SQL：{$sqlquery}，无返回记录！";
                } else {
                    $returnStr = "运行SQL：{$sqlquery}，共有" . mysqli_num_rows($res) . "条记录，最大返回100条！";
                }
                $j = 0;
                while ($row = mysqli_fetch_assoc($res)) {
                    $j++;
                    if ($j > 100) {
                        break;
                    }
                    $returnStr.="<hr size=1 width='100%'/>";
                    $returnStr.="记录：$j";
                    $returnStr.="<hr size=1 width='100%'/>";
                    foreach ($row as $k => $v) {
                        $v = strip_tags($v);
                        $returnStr.="<font color='red'>{$k}：</font>{$v}<br/>\r\n";
                    }
                }
            } else {
                //普通的SQL语句
                $sqlquery = str_replace("\r", "", $sqlquery);
                $sqls = preg_split("#;[ \t]{0,}\n#", $sqlquery);
                $nerrCode = "";
                $i = 0;
                foreach ($sqls as $q) {
                    $q = trim($q);
                    if ($q == "") {
                        continue;
                    }
                    mysqli_query($q);
                    $errCode = trim(mysqli_error());
                    if ($errCode == "") {
                        $i++;
                    } else {
                        $nerrCode .= "执行： <font color='blue'>$q</font> 出错，错误提示：<font color='red'>" . $errCode . "</font><br>";
                    }
                }
                $returnStr .= "成功执行{$i}个SQL语句！<br><br>";
                $returnStr .= $nerrCode;
            }
        }

        View::assign('returnStr',$returnStr);
        return $this->display();
    }


    /**
     * 备份数据
     * @auto true
     * @auth true
     * @menu true
     * @icon icon-service
     */
    public function bak() {

        $bkdir = "./xf_bak";
        if (!is_dir($bkdir)) {
            mkdir($bkdir, 0777, true);
        }
        if ($this->in['flag'] == 'bak') {
            $c=$this->c;

            $GLOBALS['cfg_dbhost'] = $c['hostname'] ;
            $GLOBALS['cfg_dbuser'] = $c['username'];
            $GLOBALS['cfg_dbpwd'] = $c['password'];
            $GLOBALS['cfg_dbname'] = $c['database'];
            $GLOBALS['cfg_dbprefix'] = "";
            $prefix = DB_PREFIX;
            $url = url('bak');
            //跳转到一下页的JS
            $gotojs = "function GotoNextPage(){
    document.gonext." . "submit();
}" . "\r\nset" . "Timeout('GotoNextPage()',500);";

            $dojs = "<script language='javascript'>$gotojs</script>";


            if (!is_dir($bkdir)) {
                mkdir($bkdir, 0775, true);
            }
            $sql = "show tables like '$prefix%'";
            $res = Db::query($sql);
            foreach ($res as $v) {
                $v = reset($v);
                $tablearr.="{$v},";
            }
            //初始化使用到的变量
            $tables = array_filter(explode(',', $tablearr));
            //初始化使用到的变量
            $isstruct = 1;
            if (!isset($isstruct)) {
                $isstruct = 0;
            }
            if (!isset($startpos)) {
                $startpos = 0;
            }
            if (!isset($iszip)) {
                $iszip = 0;
            }
            $nowtable = $this->in['nowtable'];

            if (empty($nowtable)) {
                $nowtable = '';
            }
            $fsize = $this->in['fsize'];
            if (empty($fsize)) {
                $fsize = 2048;
            }
            $fsizeb = $fsize * 1024;

            //第一页的操作

            if ($nowtable == '') {
                $tmsg = '';
                $dh = opendir($bkdir);
                while ($filename = readdir($dh)) {
                    if (!preg_match("#txt$#", $filename)) {
                        continue;
                    }
                    $filename = $bkdir . "/$filename";
                    if (!is_dir($filename)) {
                        unlink($filename);
                    }
                }
                closedir($dh);

                $tmsg .= "清除备份目录旧数据完成...<br />";

                if ($isstruct == 1) {
                    $bkfile = $bkdir . "/tables_struct_" . substr(md5(time() . mt_rand(1000, 5000) . ''), 0, 16) . ".txt";
                    $vres = Db::query("SELECT VERSION();");
                    $mysql_version = reset($vres[0]);
                    $fp = fopen($bkfile, "w");
                    foreach ($tables as $t) {
                        fwrite($fp, "DROP TABLE IF EXISTS `$t`;\r\n\r\n");

                        $res = Db::query("SHOW CREATE TABLE " . $dsql->dbName . "." . $t);
//                        p($res);
                        $tb_create_table = $res[0]['create table'];
//                        $dsql->SetQuery("SHOW CREATE TABLE " . $dsql->dbName . "." . $t);
//                        $dsql->Execute('me');
//                        $row = $dsql->GetArray('me', MYSQL_BOTH);
                        //去除AUTO_INCREMENT
                        $tb_create_table = preg_replace("#AUTO_INCREMENT=([0-9]{1,})[ \r\n\t]{1,}#i", "", $tb_create_table);
                        //4.1以下版本备份为低版本
                        if ($datatype == 4.0 && $mysql_version > 4.0) {
                            $eng1 = "#ENGINE=MyISAM[ \r\n\t]{1,}DEFAULT[ \r\n\t]{1,}CHARSET=" . $cfg_db_language . "#i";
                            $tableStruct = preg_replace($eng1, "TYPE=MyISAM", $row[1]);
                        }
                        //4.1以下版本备份为高版本
                        else if ($datatype == 4.1 && $mysql_version < 4.1) {
                            $eng1 = "#ENGINE=MyISAM DEFAULT CHARSET={$cfg_db_language}#i";
                            $tableStruct = preg_replace("TYPE=MyISAM", $eng1, $row[1]);
                        }
                        //普通备份
                        else {
                            $tableStruct = $tb_create_table;
                        }
                        fwrite($fp, '' . $tableStruct . ";\r\n\r\n");
                    }
                    fclose($fp);
                    $tmsg .= "备份数据表结构信息完成...<br />";
                }
                $tmsg .= "<font color='red'>正在进行数据备份的初始化工作，请稍后...</font>";

                $doneForm = "<form name='gonext' method='post' action='{$url}'>
           <input type='hidden' name='isstruct' value='$isstruct' />
           <input type='hidden' name='flag' value='bak' />
           <input type='hidden' name='fsize' value='$fsize' />
           <input type='hidden' name='tablearr' value='$tablearr' />
           <input type='hidden' name='nowtable' value='{$tables[0]}' />
           <input type='hidden' name='startpos' value='0' />
           <input type='hidden' name='iszip' value='$iszip' />\r\n</form>\r\n{$dojs}\r\n";
                \app\admin\Controller\PutInfo($tmsg, $doneForm);
                exit();
            }
//执行分页备份
            else {

                $j = 0;
                $fs = array();
                $bakStr = '';
                //分析表里的字段信息
                $DB_NAME = $this->c['database'];
                $result = Db::query("select * from information_schema.columns where table_schema='" . $DB_NAME . "' and table_name='" . $nowtable . "'");
                $intable = "INSERT INTO `$nowtable` VALUES(";
                foreach ($result as $rv) {
                    $fs[$j] = $rv['column_name'];
                    $j++;
                }
                $fsd = $j - 1;
                //读取表的内容
                $res = Db::query("SELECT * FROM `$nowtable` ");
                $m = 0;
                $bakfilename = "$bkdir/{$nowtable}_{$startpos}_" . substr(md5(time() . mt_rand(1000, 5000) . ''), 0, 16) . ".txt";

                foreach ($res as $row2) {
                    if ($m < $startpos) {
                        $m++;
                        continue;
                    }
                    //检测数据是否达到规定大小
                    if (strlen($bakStr) > $fsizeb) {
                        $fp = fopen($bakfilename, "w");
                        fwrite($fp, $bakStr);
                        fclose($fp);
                        $tmsg = "<font color='red'>完成到{$m}条记录的备份，继续备份{$nowtable}...</font>";
                        $doneForm = "<form name='gonext' method='post' action='{$url}'>
                <input type='hidden' name='isstruct' value='$isstruct' />
                <input type='hidden' name='flag' value='bak' />
                <input type='hidden' name='fsize' value='$fsize' />
                <input type='hidden' name='tablearr' value='$tablearr' />
                <input type='hidden' name='nowtable' value='$nowtable' />
                <input type='hidden' name='startpos' value='$m' />
                <input type='hidden' name='iszip' value='$iszip' />\r\n</form>\r\n{$dojs}\r\n";
                        PutInfo($tmsg, $doneForm);
                        exit();
                    }

                    //正常情况
                    $line = $intable;
                    for ($j = 0; $j <= $fsd; $j++) {
                        if ($j < $fsd) {
                            $line .= "'" . \app\admin\Controller\RpLine(addslashes($row2[$fs[$j]])) . "',";
                        } else {
                            $line .= "'" . RpLine(addslashes($row2[$fs[$j]])) . "');\r\n";
                        }
                    }
                    $m++;
                    $bakStr .= $line;
                }

                //如果数据比卷设置值小
                if ($bakStr != '') {
                    $fp = fopen($bakfilename, "w");
                    fwrite($fp, $bakStr);
                    fclose($fp);
                }
                for ($i = 0; $i < count($tables); $i++) {
                    if ($tables[$i] == $nowtable) {
                        if (isset($tables[$i + 1])) {
                            $nowtable = $tables[$i + 1];
                            $startpos = 0;
                            break;
                        } else {
                            PutInfo("完成所有数据备份！", "");
                            exit();
                        }
                    }
                }


                $tmsg = "<font color='red'>完成到{$m}条记录的备份，继续备份{$nowtable}...</font>";
                $doneForm = "<form name='gonext' method='post' action='{$url}'>
                      <input type='hidden' name='flag' value='bak' />
          <input type='hidden' name='isstruct' value='$isstruct' />
          <input type='hidden' name='fsize' value='$fsize' />
          <input type='hidden' name='tablearr' value='$tablearr' />
          <input type='hidden' name='nowtable' value='$nowtable' />
          <input type='hidden' name='startpos' value='$startpos'>\r\n</form>\r\n{$dojs}\r\n";
                PutInfo($tmsg, $doneForm);
                exit();
            }
        }
        $dh = opendir($bkdir);
        $filenames = array();
        while ($filename = readdir($dh)) {
            if (!preg_match("#txt$#", $filename)) {
                continue;
            }
            $f = $bkdir . "/{$filename}";
            $filenames[] = array(
                'file' => trim($f, '.'),
                'size' => filesize($f)
            );
        }
        closedir($dh);
        View::assign('filenames', $filenames);
        return $this->display();
    }

}

function PutInfo($msg1, $msg2) {
    global $cfg_dir_purview, $cfg_soft_lang;
    $msginfo = "<html>\n<head>
        <meta http-equiv='Content-Type' content='text/html; charset={$cfg_soft_lang}' />
        <title> 提示信息</title>
        <base target='_self'/>\n</head>\n<body leftmargin='0' topmargin='0'>\n<center>
        <br/>
        <div style='width:400px;padding-top:4px;height:24;font-size:10pt;border-left:1px solid #cccccc;border-top:1px solid #cccccc;border-right:1px solid #cccccc;background-color:#DBEEBD;'> 提示信息！</div>
        <div style='width:400px;height:100px;font-size:10pt;border:1px solid #cccccc;background-color:#F4FAEB'>
        <span style='line-height:160%'><br/>{$msg1}</span>
        <br/><br/></div>\r\n{$msg2}";
    echo $msginfo . "</center>\n</body>\n</html>";
}

function RpLine($str) {
    $str = str_replace("\r", "\\r", $str);
    $str = str_replace("\n", "\\n", $str);
    return $str;
}



