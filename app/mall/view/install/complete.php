{__NOLAYOUT__}
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{:C('title')} - {:C('system_program')}{:C('system_versions')}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <script src="__LIB__/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="__LIB__/zui/css/zui.min.css"/>
    <link href="__ADMIN__/css/admin.css" rel="stylesheet" type="text/css"/>
    <script src="__LIB__/layer/layer.js" type="text/javascript"></script>
    <script type="text/javascript">
        var siteurl = "{:url('/')}";
        var root = "__ROOT__/index.php";
        var root_path = "__ROOT__";
        var admin_name = "<?php echo ADMIN_MODULE; ?>";
    </script>
    <script src="__LIB__/public.js" type="text/javascript"></script>
    <script src="__LIB__/zui/js/zui.min.js" type="text/javascript"></script>
    <script src="__ADMIN__/index.js" type="text/javascript"></script>
</head>
<nav class="navbar navbar-default" role="navigation">
             <ul class="nav navbar-nav nav-justified">
                <li class="cat-item active"><a href="javascript:;">环境检测</a></li>
                <li class="cat-item active"><a href="javascript:;">安装</a></li>
                <li class="cat-item active"><a href="javascript:;">完成</a></li>
            </ul>
        </nav>
<div class="panel panel-success" style="margin: 10px;">
            <div class="panel-heading">安装完成</div>
            <div class="panel-body">
                <h1 class="text-success">安装已经完成了</h1>
                <p><a href="<?php echo url('xf/index/index');?>" target="_blank" class="btn btn-lg btn-primary">登陆后台</a>
                    <a href="<?php echo url('index/index',array('lid'=>1));?>" target="_blank"  class="btn btn-lg btn-success">访问首页</a>
                </p>
            </div>
        </div>

