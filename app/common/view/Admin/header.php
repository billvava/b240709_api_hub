{__NOLAYOUT__}
<?php tool()->func('html');tool()->func('form'); ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{:C('title')} - {:C('system_versions')}</title>
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
    <link  rel="stylesheet" href="__STATIC__/admin/css/admin.css?v=17" />
    <script src="__LIB__/jquery.min.js" type="text/javascript"></script>
    <script src="__STATIC__/pearadmin/component/layui/layui.js?v=7"></script>
	<script src="__STATIC__/pearadmin/component/pear/pear.js?v=1"></script>
    <script>
        var layer;
        $(function(){
            layui.use(['layer'], function(){
                layer = layui.layer;
            });
            <?php if(!isset($no_form)){ ?>
            layui.use(['element','form','upload'], function(){
            });
            <?php } ?>
        })

        var is_remember = false;
    </script>
    <script src="__LIB__/public.js?v=12" type="text/javascript"></script>
    <script src="__STATIC__/admin/js/xadmin.js?v=2" type="text/javascript"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="__LIB__/kindeditor/themes/default/default.css" />
    <script src="__LIB__/kindeditor/kindeditor-all-min.js" type="text/javascript"></script>
    <script charset="utf-8" src="__LIB__/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" charset="utf-8" src="__LIB__/neditor/neditor.config.js?v=2"></script>
    <script type="text/javascript" charset="utf-8" src="__LIB__/neditor/neditor.all.min.js"> </script>
    <script type="text/javascript" >
        UE.Editor.prototype.getActionUrl = function(action) {
            /* 按config中的xxxActionName返回对应的接口地址 */
            if (action == 'uploadimage' || action == 'uploadscrawl') {
                return '/<?php echo ADMIN_URL; ?>/Upload/upload_img';
            } else if (action == 'uploadvideo') {
                return '/<?php echo ADMIN_URL; ?>/Upload/upload_music';
            }else if (action == 'uploadfile') {
                return '/<?php echo ADMIN_URL; ?>/Upload/upload_file';
            } else {
                return this._bkGetActionUrl.call(this, action);
            }
        }
    </script>
    <script type="text/javascript" charset="utf-8" src="__LIB__/neditor/neditor.service.js"></script>
    <script type="text/javascript" src="__LIB__/plupload/plupload.full.min.js"></script>
    <script src="__LIB__/zui/js/zui.min.js" type="text/javascript"></script>
    <script src="__LIB__/zui/lib/sortable/zui.sortable.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var siteurl = "{:url('/')}";
        var root = "/index.php/";
        var root_path = "";
        var admin_name = '<?php echo ADMIN_URL; ?>';
    </script>
</head>
<body class="pear-container">
<?php if(!isset($show_top)){ ?>
    {:W('admin/top')}
<?php } ?>
<?php if(isset($name) && $name){ ?>
    <fieldset class="layui-elem-field layui-field-title" style=" margin-bottom: 0;">
        <legend>{$name}</legend>
    </fieldset>
<?php } ?>



