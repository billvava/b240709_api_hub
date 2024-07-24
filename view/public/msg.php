{__NOLAYOUT__}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>系统提示</title>
    <link href="__LIB__/weixin/css/weui.min.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<div class="weui-msg">
    <!--<div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>-->
     <div class="weui-msg__icon-area"><i class="weui-icon-{$type} weui-icon_msg"></i></div>
     
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">{$msg}</h2>
        <p class="weui-msg__desc"></p>
    </div>
    <?php if($show_btn){ ?>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a a href="/"  class="weui-btn weui-btn_primary">返回首页</a>
            <a  href="javascript://" onclick="self.location=document.referrer;" class="weui-btn weui-btn_default">返回上一页</a>
        </p>
    </div>
    <?php } ?>

    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__links">
                <a href="javascript:void(0);" class="weui-footer__link">{:C('titlte')}</a>
            </p>
            <!--<p class="weui-footer__text">Copyright © 2008-2016 weui.io</p>-->
        </div>
    </div>
</div>
    
    </body>