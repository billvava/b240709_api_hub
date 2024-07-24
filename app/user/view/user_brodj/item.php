<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->
<?php echo form_input(array('field'=>'user_id','fname'=>'用户','defaultvalue'=>$info?$info['user_id']:'','col'=>4)); ?>
<!--  【user_id】 end  !-->
<!--  【total】 start  !-->
<?php echo form_input(array('field'=>'total','fname'=>'金额','defaultvalue'=>$info?$info['total']:'','col'=>4)); ?>
<!--  【total】 end  !-->
<!--  【expire】 start  !-->
<?php echo datetime(array('field'=>'expire','fname'=>'到期时间','defaultvalue'=>$info?$info['expire']:'','col'=>4)); ?>
<!--  【expire】 end  !-->
<!--  【time】 start  !-->
<?php echo datetime(array('field'=>'time','fname'=>'生成时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>
<!--  【time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


