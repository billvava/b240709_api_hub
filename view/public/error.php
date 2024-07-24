{__NOLAYOUT__}
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>系统提示</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="__STATIC__/pearadmin/component/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="__STATIC__/pearadmin/component/pear/css/pear.css" />
    <link rel="stylesheet" href="__STATIC__/pearadmin/admin/css/admin.css" />
    <link rel="stylesheet" href="__STATIC__/pearadmin/admin/css/other/result.css" />
</head>

<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <div class="result">
            <div class="error">
                <svg viewBox="64 64 896 896" data-icon="close-circle" width="80px" height="80px" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M685.4 354.8c0-4.4-3.6-8-8-8l-66 .3L512 465.6l-99.3-118.4-66.1-.3c-4.4 0-8 3.5-8 8 0 1.9.7 3.7 1.9 5.2l130.1 155L340.5 670a8.32 8.32 0 0 0-1.9 5.2c0 4.4 3.6 8 8 8l66.1-.3L512 564.4l99.3 118.4 66 .3c4.4 0 8-3.5 8-8 0-1.9-.7-3.7-1.9-5.2L553.5 515l130.1-155c1.2-1.4 1.8-3.3 1.8-5.2z"></path><path d="M512 65C264.6 65 64 265.6 64 513s200.6 448 448 448 448-200.6 448-448S759.4 65 512 65zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path></svg>
            </div>
            <?php $msg = $info?$info:'错误!!!' ?>
            <h2 class="title"><?php echo($msg?$msg:'错误!!!');  ?></h2>
            <p class="desc">
                系统会在 <b id='wait'><?php echo($wait?$wait:5); ?></b> 秒内自动跳转
            </p>
            <?php if($wait>0){ ?>
                <div class="action">
                    <a id="href"  href="<?php echo($url); ?>" class="pear-btn pear-btn-success">直接跳转</a>
                    &nbsp;&nbsp;&nbsp;
                    <!--<a class="pear-btn"  href="/">返回首页</a>-->
                </div>
            <?php }else{ ?>
                <div class="action">
                    <a  href="javascript://" onclick="javascript:history.go(-1)" class="pear-btn pear-btn-success">返回上页</a>
                    &nbsp;&nbsp;&nbsp;
                    <a class="pear-btn"   href="javascript://" onclick=" parent.layer.close(parent.layer.getFrameIndex(window.name));">关闭窗口</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

</body>


<script>
    <?php if($wait>0){ ?>
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time == 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
    <?php } ?>
</script>
</html>