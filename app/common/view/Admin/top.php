{__NOLAYOUT__}
<div class="x-nav" style=" margin-bottom: 5px;">
    <div class="pear-btn-group">
        <a class="pear-btn  pear-btn-sm" href="javascript://" onclick="backoff()" ><i class=" layui-icon layui-icon-left"></i></a>
        <a class="pear-btn  pear-btn-sm"  href="javascript:window.location.reload();"><i class=" layui-icon layui-icon-refresh"></i></a>
        <a class="pear-btn  pear-btn-sm" href="javascript:history.go(1)" ><i class=" layui-icon layui-icon-right"></i></a>
    </div>
</div>
<script type="text/javascript">
    <?php $admin_app = C('admin_app'); ?>
    function backoff(){
        var referrer = document.referrer.toLowerCase();
        if(referrer.match(/<?php echo $admin_app; ?>\/index\/index/i) ==null ){
            history.go(-1)
        }else{
            layui.use(["notice"], function() {
                layui.notice.error('已经是最后一页了');
            })
        }

    }
</script>