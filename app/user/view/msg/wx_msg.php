<div class='x-body'>
    <form class="layui-form"  action="{:url('wx_msg')}" method="post">
<!--  【content】 start  !-->
<?php echo textarea(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?$info['content']:'','col'=>4)); ?>
<div class='layui-form-item layui-form-text layui-col-xs10 layui-col-md10' >
                <label class='layui-form-label'></label>
                <div class='layui-input-block'>
                    <div></div>
                    <div class='x-a'>该功能需要用户关注公众号，并且48小时内进入过公众号</div>   
                </div>
            </div>
<?php echo textarea(array('fname'=>'用户编号','field'=>'user_id','msg'=>'多个用,号分隔，所有用户的话无需填写','val'=>$in['user_id'],'col'=>4)); ?>

        <?php echo submit(); ?>
     </form>
</div>


