<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【node】 start  !-->
<?php echo form_input(array('field'=>'node','fname'=>'操作结点','defaultvalue'=>$info?$info['node']:'','col'=>4)); ?>
<!--  【node】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【admin_id】 start  !-->
<?php echo form_input(array('field'=>'admin_id','fname'=>'操作人','defaultvalue'=>$info?$info['admin_id']:'','col'=>4)); ?>
<!--  【admin_id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'操作名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【param】 start  !-->
<?php echo form_input(array('field'=>'param','fname'=>'操作参数','defaultvalue'=>$info?$info['param']:'','col'=>4)); ?>
<!--  【param】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


