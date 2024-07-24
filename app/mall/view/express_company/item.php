<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'是否显示','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【expresskey】 start  !-->
<?php echo form_input(array('field'=>'expresskey','fname'=>'快递code','defaultvalue'=>$info?$info['expresskey']:'','col'=>4)); ?>
<!--  【expresskey】 end  !-->
<!--  【expressname】 start  !-->
<?php echo form_input(array('field'=>'expressname','fname'=>'快递名称','defaultvalue'=>$info?$info['expressname']:'','col'=>4)); ?>
<!--  【expressname】 end  !-->
<!--  【expresswebsite】 start  !-->
<?php echo form_input(array('field'=>'expresswebsite','fname'=>'快递官网','defaultvalue'=>$info?$info['expresswebsite']:'','col'=>4)); ?>
<!--  【expresswebsite】 end  !-->
<!--  【expresstelephone】 start  !-->
<?php echo form_input(array('field'=>'expresstelephone','fname'=>'快递电话','defaultvalue'=>$info?$info['expresstelephone']:'','col'=>4)); ?>
<!--  【expresstelephone】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


