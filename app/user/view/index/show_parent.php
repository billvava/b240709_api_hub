<div class="x-body">
    <div class="table-container">
        
        <table class="layui-table " >
            <thead>
                <tr>
                     <th>用户编号</th>
                    <th>级别</th>
                    <th>昵称</th>

                </tr>
            </thead>
            <tbody>
                <?php
                for($i=1;$i<=3;$i++){

                     if($pinfo['pid'.$i]>0){ 
                    ?>

                    <tr>
                        <td><?php echo $pinfo['pid'.$i]; ?></td>
                       <td><?php echo $i; ?>级上级</td>
                       <td><?php echo $pinfo['nickname'.$i]; ?></td>
                    </tr>
                     <?php } } ?>
                    
                    <?php if(!$pinfo['pid'.$i]){ ?>
                    <tr>
                        <td colspan="100">暂无数据</td>
                    </tr>
                    <?php } ?>
                    
                    
            </tbody>
        </table>
    </div>
</div>
   
