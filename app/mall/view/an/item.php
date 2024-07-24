<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【title】 start  !-->
<?php echo form_input(array('field'=>'title','fname'=>'公告','defaultvalue'=>$info?$info['title']:'','col'=>4)); ?>
<!--  【title】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【time】 start  !-->
<?php echo datetime(array('field'=>'time','fname'=>'发布时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>
<!--  【time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


