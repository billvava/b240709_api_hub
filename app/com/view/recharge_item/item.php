<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'99','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【money】 start  !-->
<?php echo form_input(array('field'=>'money','fname'=>'充值面额','defaultvalue'=>$info?$info['money']:'','col'=>4)); ?>
<!--  【money】 end  !-->
<!--  【amount】 start  !-->
<?php echo form_input(array('field'=>'give','fname'=>'赠送金额','defaultvalue'=>$info?$info['give']:'','col'=>4)); ?>
<!--  【amount】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


