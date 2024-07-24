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
            <div class="success">
                <svg viewBox="64 64 896 896" data-icon="check-circle" width="80px" height="80px" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M699 353h-46.9c-10.2 0-19.9 4.9-25.9 13.3L469 584.3l-71.2-98.8c-6-8.3-15.6-13.3-25.9-13.3H325c-6.5 0-10.3 7.4-6.5 12.7l124.6 172.8a31.8 31.8 0 0 0 51.7 0l210.6-292c3.9-5.3.1-12.7-6.4-12.7z"></path><path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64zm0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z"></path></svg>
            </div>
            <h2 class="title"><?php echo($info?$info:'成功!!!');  ?></h2>
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