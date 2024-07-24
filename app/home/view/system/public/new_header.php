<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link href="__LIB__/sell/css/font-awesome.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" type="text/css" href="__LIB__/sell/css/style.css">
    <script src="__LIB__/jquery.min.js" type="text/javascript"></script>
    <script src="__LIB__/layer/layer.js" type="text/javascript"></script>
    <script type="text/javascript" src="__LIB__/public.js"></script>
    <title>{$seo.title}</title>
    <meta name="Keywords" content="{$seo.keywords}" />
    <meta name="description" content="{$seo.description}" />
        <script>
            var root='__ROOT__';
            <?php 
            if(session('user.id')){
                echo "var uid='99999';";
            }else{
                echo "var uid='';";
            } 
            $lib = A('Common/Lib');
            $ad = D(ADMIN_MODULE.'/Ad');
            $c = D('H/Category');
            $wap_shop_color = lang('wap_shop_color');
            ?>   
	</script>
</head>
    
       