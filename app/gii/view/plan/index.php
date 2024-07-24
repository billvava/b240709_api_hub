<link href="__STATIC__/pearadmin/component/layui/css/layui.css" rel="stylesheet" type="text/css"/>
<link href="__ADMIN__/css/admin.css" rel="stylesheet" type="text/css"/>
<div class="x-body">
    <blockquote class="layui-elem-quote">
    输入<span class='x-red'>效果图目录路径</span>，生成页面进度<span class='x-red'>excel表</span><br/>
    </blockquote>
    <blockquote class="layui-elem-quote" style='line-height: 27px;'>
        <p>设计图目录位置（index.php相对目录）:</p>
        <form action="{:url('excel')}">
            <input type="text" name="path" placeholder="起始目录位置" value="./test/"  class="layui-input"> 
        </form>
        <span class="fr"><a href="JavaScript:;" class='layui-btn layui-btn-sm' id='sub'>生成excel</a></span>
    </blockquote>
</div>
<script>
    $(function () {
        $('#sub').click(function () {
            $('form').submit();
        })
    })
</script>
<style>
body{margin: 0 auto}
.layui-elem-quote{overflow: hidden}
.layui-input{width: 835px; float: left;}
.layui-btn{height: 34px; line-height: 34px;}
</style>