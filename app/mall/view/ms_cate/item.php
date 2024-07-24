<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【start】 start  !-->
<?php echo ftime(array('field'=>'start','fname'=>'开始','defaultvalue'=>$info?$info['start']:'','col'=>4)); ?>
<!--  【start】 end  !-->
<!--  【end】 start  !-->
<?php echo ftime(array('field'=>'end','fname'=>'结束','defaultvalue'=>$info?$info['end']:'','col'=>4)); ?>
<!--  【end】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


