<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'规则名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【bro1】 start  !-->
<?php echo form_input(array('field'=>'bro1','fname'=>'一级佣金比例','defaultvalue'=>$info?$info['bro1']*100:'0.00','col'=>4,'unit'=>"%")); ?>
<!--  【bro1】 end  !-->
<!--  【bro2】 start  !-->
<?php echo form_input(array('field'=>'bro2','fname'=>'二级佣金比例','defaultvalue'=>$info?$info['bro2']*100:'0.00','col'=>4,'unit'=>"%")); ?>
<!--  【bro2】 end  !-->
<!--  【bro3】 start  !-->
<?php echo form_input(array('field'=>'bro3','fname'=>'三级佣金比例','defaultvalue'=>$info?$info['bro3']*100:'0.00','col'=>4,'unit'=>"%")); ?>
<!--  【bro3】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


