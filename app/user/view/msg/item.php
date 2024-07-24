<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="{:url('item')}" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->
<!--  【user_id】 end  !-->
<!--  【sender】 start  !-->
<!--  【sender】 end  !-->
<!--  【title】 start  !-->
<?php echo form_input(array('field'=>'title','fname'=>'标题','defaultvalue'=>$info?$info['title']:'','col'=>4)); ?>
<!--  【title】 end  !-->
<!--  【content】 start  !-->
<?php echo textarea(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?$info['content']:'','col'=>4)); ?>
<div class='layui-form-item layui-form-text layui-col-xs10 layui-col-md10' >
    <label class='layui-form-label'></label>
    <div class='layui-input-block'>
        <div></div>
        <div class='x-a'>站内信标题不能超过20个字，内容不能超过100个字。</div>   
    </div>
</div>

<?php echo textarea(array('fname'=>'接收用户编号','field'=>'user_id','msg'=>'多个用,号分隔','val'=>$in['user_id'],'col'=>4)); ?>
<!--  【content】 end  !-->
<!--  【time】 start  !-->
<!--  【time】 end  !-->
<!--  【is_read】 start  !-->
<!--  【is_read】 end  !-->
<!--  【is_del】 start  !-->
<!--  【is_del】 end  !-->

        <?php echo submit(); ?>
     </form>
</div>


