<div class="x-body">
<?php  foreach($sendInfo as $v){ ?>    
<div class="layui-card">
            <div class="layui-card-header">{$v.express_name} {$v.express_code}</div>
            <div class="layui-card-body clearfix">
               
                <?php   if($v['data'] ) { foreach ($v['data'] as $key => $vv) { ?>
                <p>{$vv.time} {$vv.context}</p>      
                <?php  } }else{ echo "<p>暂无信息</p>"; }  ?>
            </div>
</div>

<?php } ?>
</div>