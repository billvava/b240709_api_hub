<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【cate】 start  !-->
<?php echo form_input(array('field'=>'cate','fname'=>'分类','defaultvalue'=>$info?$info['cate']:'','col'=>4)); ?>
<!--  【cate】 end  !-->
<!--  【order_id】 start  !-->
<?php echo form_input(array('field'=>'order_id','fname'=>'订单','defaultvalue'=>$info?$info['order_id']:'','col'=>4)); ?>
<!--  【order_id】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


