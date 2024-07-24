<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'银行名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【address】 start  !-->
<?php echo form_input(array('field'=>'address','fname'=>'开户点','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【num】 start  !-->
<?php echo form_input(array('field'=>'num','fname'=>'账号','defaultvalue'=>$info?$info['num']:'','col'=>4)); ?>
<!--  【num】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【realname】 start  !-->
<?php echo form_input(array('field'=>'realname','fname'=>'姓名','defaultvalue'=>$info?$info['realname']:'','col'=>4)); ?>
<!--  【realname】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'手机','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【cate】 start  !-->
<?php $itms = $all_lan["cate"];   echo radio(array('field'=>'cate','items'=>$itms,'fname'=>'渠道分类','defaultvalue'=>$info?$info['cate']:'',)); ?>
<!--  【cate】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


