<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url(app()->request->action()); ?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<?php echo select(array('field'=>'cate_id','fname'=>'分组','defaultvalue'=>$info?$info['cate_id']:'0','col'=>4,'items'=>$lan,'msg'=>'必须选到第二级')); ?>

<!--  【field】 start  !-->
<?php echo form_input(array('field'=>'field','fname'=>'字段名','defaultvalue'=>$info?$info['field']:'','col'=>4)); ?>
<!--  【field】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'中文名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->

<!--  【msg】 start  !-->
<?php echo form_input(array('field'=>'msg','fname'=>'提示','defaultvalue'=>$info?$info['msg']:'','col'=>4)); ?>
<!--  【msg】 end  !-->
<!--  【val】 start  !-->
<?php echo form_input(array('field'=>'val','fname'=>'默认值','defaultvalue'=>$info?$info['val']:'','col'=>4)); ?>
<!--  【val】 end  !-->
<!--  【type】 start  !-->
<!--  【input_type】 start  !-->
<?php echo select(array('field'=>'input_type','fname'=>'类型','defaultvalue'=>$info?$info['input_type']:'','col'=>4,'items'=>lang('input_type'))); ?>
<!--  【input_type】 end  !-->
<!--  【type_group】 start  !-->
<!--  【type_group】 end  !-->
<!--  【option】 start  !-->
<?php echo form_input(array('field'=>'option_text','fname'=>'参数','defaultvalue'=>$info?$info['option_text']:'','col'=>4,'msg'=>'单选：是=1,否=0，图片：./favicon.ico')); ?>
<!--  【option】 end  !-->
<!--  【option_text】 start  !-->
<!--  【option_text】 end  !-->
<!--  【is_show】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'is_show','items'=>$itms,'fname'=>'显示状态','defaultvalue'=>$info?$info['is_show']:'1',)); ?>
<!--  【is_show】 end  !-->
<!--  【unit】 start  !-->
<?php echo form_input(array('field'=>'unit','fname'=>'单位','defaultvalue'=>$info?$info['unit']:'','col'=>4,'msg'=>'仅限文本框有效')); ?>
<!--  【unit】 end  !-->
<!--  【cate_id】 start  !-->
<!--  【cate_id】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


