<?php tool()->func('html');tool()->func('form'); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>开发平台</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link href="//at.alicdn.com/t/font_1108287_v6oxqcv9bcs.css" rel="stylesheet" type="text/css"/>
    <link href="__STATIC__/pearadmin/component/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="__STATIC__/pearadmin/component/pear/css/pear.css" />
    <!-- 加 载 样 式-->
    <!--<link rel="stylesheet" href="__STATIC__/pearadmin/admin/css/load.css" />-->
    <!-- 布 局 样 式 -->
    <link rel="stylesheet" href="__STATIC__/pearadmin/admin/css/admin.css?v=4" />
    
    <link href="__STATIC__/pearadmin/component/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    
    <link  rel="stylesheet" href="__STATIC__/admin/css/admin.css?v=17" />
    <script src="__LIB__/jquery.min.js" type="text/javascript"></script>
    <script src="__STATIC__/pearadmin/component/layui/layui.js?v=7"></script>
    <script>
        var layer;
        $(function(){
            layui.use(['layer'], function(){
                layer = layui.layer;
            });
            <?php if(!isset($no_form)){ ?>
            layui.use(['element','form'], function(){
            });
            <?php } ?>
        })
    </script>
    <script src="__LIB__/public.js?v=12" type="text/javascript"></script>
    <script src="__STATIC__/admin/js/xadmin.js?v=2" type="text/javascript"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script type="text/javascript">
        var siteurl = "{:url('/')}";
        var root = "/index.php/";
        var root_path = "";
        var admin_name = '<?php echo ADMIN_URL; ?>';
    </script>
    <style>
        .iframe2{
            border:none;
            height: 100%;
            margin: 0;
            padding: 0;
            width:calc(100% - 120px);
            margin-left: 120px;
        }
         .iframeMain{
    width: 100%; height: 500px;  border: none;
}
    </style>
</head>
<body  <?php if(request()->controller()!='Index'){ echo 'style=" padding: 10px;"'; } ?>>
     {__CONTENT__}
</body>
</html>