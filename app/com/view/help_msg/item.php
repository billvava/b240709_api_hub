<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【content】 start  !-->

<!--  【content】 end  !-->
<!--  【imgs】 start  !-->
<!--  【imgs】 end  !-->
<!--  【time】 start  !-->
<?php echo datetime(array('field'=>'time','fname'=>'创建时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>
<!--  【time】 end  !-->
<!--  【up_time】 start  !-->
<?php echo datetime(array('field'=>'up_time','fname'=>'处理时间','defaultvalue'=>$info?$info['up_time']:'','col'=>4)); ?>
<!--  【up_time】 end  !-->
<!--  【up_msg】 start  !-->
<?php echo form_input(array('field'=>'up_msg','fname'=>'处理备注','defaultvalue'=>$info?$info['up_msg']:'','col'=>4)); ?>
<!--  【up_msg】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'0','name'=>'待处理'),array('val'=>'1','name'=>'已处理'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【tel】 start  !-->
<!--  【tel】 end  !-->
<!--  【title】 start  !-->
<!--  【title】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


