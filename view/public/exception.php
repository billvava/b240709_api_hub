
<?php
if(!function_exists('parse_padding')){
    function parse_padding($source)
    {
        $length  = strlen(strval(count($source['source']) + $source['first']));
        return 40 + ($length - 1) * 8;
    }
}

if(!function_exists('parse_class')){
    function parse_class($name)
    {
        $names = explode('\\', $name);
        return '<abbr title="'.$name.'">'.end($names).'</abbr>';
    }
}

if(!function_exists('parse_file')){
    function parse_file($file, $line)
    {
        return '<a class="toggle" title="'."{$file} line {$line}".'">'.basename($file)." line {$line}".'</a>';
    }
}

if(!function_exists('parse_args')){
    function parse_args($args)
    {
        $result = [];

        foreach ($args as $key => $item) {
            switch (true) {
                case is_object($item):
                    $value = sprintf('<em>object</em>(%s)', parse_class(get_class($item)));
                    break;
                case is_array($item):
                    if(count($item) > 3){
                        $value = sprintf('[%s, ...]', parse_args(array_slice($item, 0, 3)));
                    } else {
                        $value = sprintf('[%s]', parse_args($item));
                    }
                    break;
                case is_string($item):
                    if(strlen($item) > 20){
                        $value = sprintf(
                            '\'<a class="toggle" title="%s">%s...</a>\'',
                            htmlentities($item),
                            htmlentities(substr($item, 0, 20))
                        );
                    } else {
                        $value = sprintf("'%s'", htmlentities($item));
                    }
                    break;
                case is_int($item):
                case is_float($item):
                    $value = $item;
                    break;
                case is_null($item):
                    $value = '<em>null</em>';
                    break;
                case is_bool($item):
                    $value = '<em>' . ($item ? 'true' : 'false') . '</em>';
                    break;
                case is_resource($item):
                    $value = '<em>resource</em>';
                    break;
                default:
                    $value = htmlentities(str_replace("\n", '', var_export(strval($item), true)));
                    break;
            }

            $result[] = is_int($key) ? $value : "'{$key}' => {$value}";
        }

        return implode(', ', $result);
    }
}

    foreach ($traces as $v) {
    $error_time = session('error_time') + 0;
    if ((time() - $error_time > 5)) {
        session('error_time', time());
        log_err( $v['file'] ." ". $v['line'] . PHP_EOL .$v['message']);

        if(strpos($v['message'], '控制器不存在')!==false || strpos($v['message'], '非法操作')!==false){
            (new \app\common\lib\Util())->no_found();
        }
    }
}
$flag = 0;
if(config('my.show_product_err')==0 && !(new \app\common\lib\Util())->check_debug()){
    $flag = 1;
}
//if (\think\facade\App::isDebug()==false) {
//
//    (new \app\common\lib\Util())->no_found();
//
//}

?>
<html><head>
        <meta charset="UTF-8">
        <title>系统发生错误</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
        <meta name="robots" content="noindex,nofollow">
         <style>
          body{color:#333;font:16px Verdana,"Helvetica Neue",helvetica,Arial,'Microsoft YaHei',sans-serif;margin:0;padding:0 20px 20px;}h1{margin:10px 0 0;font-size:28px;font-weight:500;line-height:32px;}h2{color:#4288ce;font-weight:400;padding:6px 0;margin:6px 0 0;font-size:18px;border-bottom:1px solid #eee;}h3{margin:12px;font-size:16px;font-weight:bold;}abbr{cursor:help;text-decoration:underline;text-decoration-style:dotted;}a{color:#868686;cursor:pointer;}a:hover{text-decoration:underline;}.line-error{background:#f8cbcb;}.echo table{width:100%;}.echo pre{padding:16px;overflow:auto;font-size:85%;line-height:1.45;background-color:#f7f7f7;border:0;border-radius:3px;font-family:Consolas,"Liberation Mono",Menlo,Courier,monospace;}.echo pre > pre{padding:0;margin:0;}.exception{margin-top:20px;}.exception .message{padding:12px;border:1px solid #ddd;border-bottom:0 none;line-height:18px;font-size:16px;border-top-left-radius:4px;border-top-right-radius:4px;font-family:Consolas,"Liberation Mono",Courier,Verdana,"微软雅黑",serif;}.exception .code{float:left;text-align:center;color:#fff;margin-right:12px;padding:16px;border-radius:4px;background:#999;}.exception .source-code{padding:6px;border:1px solid #ddd;background:#f9f9f9;overflow-x:auto;}.exception .source-code pre{margin:0;}.exception .source-code pre ol{margin:0;color:#4288ce;display:inline-block;min-width:100%;box-sizing:border-box;font-size:14px;font-family:"Century Gothic",Consolas,"Liberation Mono",Courier,Verdana,serif;padding-left:40px;}.exception .source-code pre li{border-left:1px solid #ddd;height:18px;line-height:18px;}.exception .source-code pre code{color:#333;height:100%;display:inline-block;border-left:1px solid #fff;font-size:14px;font-family:Consolas,"Liberation Mono",Courier,Verdana,"微软雅黑",serif;}.exception .trace{padding:6px;border:1px solid #ddd;border-top:0 none;line-height:16px;font-size:14px;font-family:Consolas,"Liberation Mono",Courier,Verdana,"微软雅黑",serif;}.exception .trace h2:hover{text-decoration:underline;cursor:pointer;}.exception .trace ol{margin:12px;}.exception .trace ol li{padding:2px 4px;}.exception div:last-child{border-bottom-left-radius:4px;border-bottom-right-radius:4px;}.exception-var table{width:100%;margin:12px 0;box-sizing:border-box;table-layout:fixed;word-wrap:break-word;}.exception-var table caption{text-align:left;font-size:16px;font-weight:bold;padding:6px 0;}.exception-var table caption small{font-weight:300;display:inline-block;margin-left:10px;color:#ccc;}.exception-var table tbody{font-size:13px;font-family:Consolas,"Liberation Mono",Courier,"微软雅黑",serif;}.exception-var table td{padding:0 6px;vertical-align:top;word-break:break-all;}.exception-var table td:first-child{width:28%;font-weight:bold;white-space:nowrap;}.exception-var table td pre{margin:0;}.copyright{margin-top:24px;padding:12px 0;border-top:1px solid #eee;}pre.prettyprint .pln{color:#000}pre.prettyprint .str{color:#080}pre.prettyprint .kwd{color:#008}pre.prettyprint .com{color:#800}pre.prettyprint .typ{color:#606}pre.prettyprint .lit{color:#066}pre.prettyprint .pun,pre.prettyprint .opn,pre.prettyprint .clo{color:#660}pre.prettyprint .tag{color:#008}pre.prettyprint .atn{color:#606}pre.prettyprint .atv{color:#080}pre.prettyprint .dec,pre.prettyprint .var{color:#606}pre.prettyprint .fun{color:red}
        </style>
       <body>
        <div class="exception">
            <div class="message">
                <?php if($flag==0 && is_array($traces) ){ foreach($traces as $v){ ?>
                <div class="info">
                    <div>
                        
                        <h2><?php echo $v['file']?> <?php echo $v['line']?></h2>
                    </div>
                    <div><h1><?php echo htmlentities($v['message']); ?></h1></div>
                </div>
                <?php } } ?>
                
            </div>
             <div class="source-code">
                 <p><?php  echo ext_htmlspecialchars($_SERVER['HTTP_HOST']); ?><?php  echo  ext_htmlspecialchars($_SERVER['REQUEST_URI']); ; ?></p>
                  <p>系统已记录当前错误，我们会尽快处理</p>
                    
             </div>
             <div class="source-code">
                   <a   href="javascript:history.go(-1)">上一页</a> | <a   href="javascript://"  onclick="javascript:location.reload()" >刷新页面</a>  |  <a  class="x-a" href="/">返回首页</a>
                </div>
        </div>

    </body>
</html>