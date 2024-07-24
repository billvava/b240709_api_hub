<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'tel','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【draw】 start  !-->
<?php $itms = $all_lan["draw"];   echo radio(array('field'=>'draw','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['draw']:'0',)); ?>
<!--  【draw】 end  !-->
<!--  【beishu】 start  !-->
<?php $itms = $all_lan["beishu"];   echo radio(array('field'=>'beishu','items'=>$itms,'fname'=>'倍数','defaultvalue'=>$info?$info['beishu']:'1',)); ?>
<!--  【beishu】 end  !-->
<!--  【num】 start  !-->
<?php echo form_input(array('field'=>'num','fname'=>'中奖数','defaultvalue'=>$info?$info['num']:'0','col'=>4)); ?>
<!--  【num】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


