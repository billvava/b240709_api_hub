<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【username】 start  !-->
<?php echo form_input(array('field'=>'username','fname'=>'用户名','defaultvalue'=>$info?$info['username']:'','col'=>4)); ?>
<!--  【username】 end  !-->
<!--  【headimgurl】 start  !-->
<?php echo form_input(array('field'=>'headimgurl','fname'=>'头像','defaultvalue'=>$info?$info['headimgurl']:'','col'=>4)); ?>
<!--  【headimgurl】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'手机号码','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【money】 start  !-->
<?php echo form_input(array('field'=>'money','fname'=>'收益','defaultvalue'=>$info?$info['money']:'0.00','col'=>4)); ?>
<!--  【money】 end  !-->
<!--  【type】 start  !-->
<?php $itms = $all_lan["type"];   echo radio(array('field'=>'type','items'=>$itms,'fname'=>'类型','defaultvalue'=>$info?$info['type']:'1',)); ?>
<!--  【type】 end  !-->
<!--  【shop_id】 start  !-->
<?php echo form_input(array('field'=>'shop_id','fname'=>'门店id','defaultvalue'=>$info?$info['shop_id']:'','col'=>4)); ?>
<!--  【shop_id】 end  !-->
<!--  【master_id】 start  !-->
<?php echo form_input(array('field'=>'master_id','fname'=>'师傅id','defaultvalue'=>$info?$info['master_id']:'','col'=>4)); ?>
<!--  【master_id】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


