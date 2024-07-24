<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【realname】 start  !-->
<?php echo form_input(array('field'=>'realname','fname'=>'真实姓名','defaultvalue'=>$info?$info['realname']:'','col'=>4)); ?>
<!--  【realname】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'手机','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->

        <div class="layui-form-item  layui-col-xs10 layui-col-md4" id="contentshow_tel">
            <label class="layui-form-label">活动</label>
            <div class="layui-input-block">
                <div><input style=" border: none" disabled type="text" value="<?php echo $info['activity_name']; ?>" name="" class="layui-input"></div>
                <div class="layui-word-aux mt5"></div>
            </div>
        </div>
<!--  【activity_id】 start  !-->
<!--  【activity_id】 end  !-->
<!--  【activity_name】 start  !-->
<!--  【activity_name】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【activity_id】 start  !-->
<?php     select(array('field'=>'activity_id','items'=>(new \app\admin\model\SuoActivities())->getOption(),'fname'=>'活动名称','defaultvalue'=>$info?$info['activity_id']:'','col'=>4)); ?>
<!--  【activity_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


