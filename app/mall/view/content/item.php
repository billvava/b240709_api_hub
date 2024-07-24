<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【title】 start  !-->
<?php echo form_input(array('field'=>'title','fname'=>'标题','defaultvalue'=>$info?$info['title']:'','col'=>4)); ?>
<?php echo form_input(array('field'=>'key','fname'=>'标识','defaultvalue'=>$info?$info['key']:'','col'=>4)); ?>

<!--  【title】 end  !-->
<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'文字说明','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【type】 start  !-->

<?php  echo select(array('field'=>'type','fname'=>'类型','defaultvalue'=>$info?$info['type']:'','col'=>4,'items'=>$mall_content_type)); ?>

<!--  【type】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'9','col'=>4)); ?>
<!--  【sort】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


