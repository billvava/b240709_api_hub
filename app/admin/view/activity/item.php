<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【strat_time】 start  !-->
<?php //echo form_input(array('field'=>'strat_time','fname'=>'开始时间','defaultvalue'=>$info?$info['strat_time']:'','col'=>4)); ?>
<!--  【strat_time】 end  !-->
<!--  【end_time】 start  !-->
<?php //echo form_input(array('field'=>'end_time','fname'=>'结束时间','defaultvalue'=>$info?$info['end_time']:'','col'=>4)); ?>
<!--  【end_time】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【winning_numbers】 start  !-->
<?php echo form_input(array('field'=>'winning_numbers','fname'=>'中奖号码','defaultvalue'=>$info?$info['winning_numbers']:'','col'=>4)); ?>
<!--  【winning_numbers】 end  !-->
<!--  【numbers】 start  !-->

<!--  【numbers】 end  !-->
<!--  【count_daijinquan】 start  !-->

<!--  【count_daijinquan】 end  !-->
<!--  【status】 start  !-->

<!--  【status】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


