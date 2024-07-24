{__NOLAYOUT__}

<div id="xfpage"></div>


<script>
    console.log('xaad','{$url}&p='+1);
    layui.use('laypage', function(){
        laypage = layui.laypage;
        //执行一个laypage实例
        laypage.render({
            elem: 'xfpage' //注意，这里的 test1 是 ID，不用加 # 号
            ,count: '{$totalRows}' //数据总数，从服务端得到
            ,limit:'{$listRows}'
            ,curr:'{$nowPage}'
            ,theme: '#1E9FFF'
            ,layout: ['prev', 'page', 'next', 'skip','count']
            ,jump: function(obj, first){
                if(!first){
                    var p=obj.curr
                    location.href='<?php echo $url?>&p='+p

                }
            }
        });
    });
</script>