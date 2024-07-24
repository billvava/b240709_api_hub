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
<!--  【winning_numbers】 start  !-->
<?php echo form_input(array('field'=>'winning_numbers','fname'=>'中奖号码','defaultvalue'=>$info?$info['winning_numbers']:'0','col'=>4)); ?>
<!--  【winning_numbers】 end  !-->
<!--  【beishu】 start  !-->
<?php $itms = $all_lan["beishu"];   echo radio(array('field'=>'beishu','items'=>$itms,'fname'=>'倍数','defaultvalue'=>$info?$info['beishu']:'1',)); ?>
<!--  【beishu】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【daijinquan】 start  !-->
<?php echo form_input(array('field'=>'daijinquan','fname'=>'消耗代金券','defaultvalue'=>$info?$info['daijinquan']:'0.00','col'=>4)); ?>
<!--  【daijinquan】 end  !-->
<!--  【bonus】 start  !-->
<?php echo form_input(array('field'=>'bonus','fname'=>'奖励','defaultvalue'=>$info?$info['bonus']:'0','col'=>4)); ?>
<!--  【bonus】 end  !-->
<!--  【num_json】 start  !-->
<?php echo form_input(array('field'=>'num_json','fname'=>'num_json','defaultvalue'=>$info?$info['num_json']:'','col'=>4)); ?>
<!--  【num_json】 end  !-->
<!--  【activity_id】 start  !-->
<?php echo form_input(array('field'=>'activity_id','fname'=>'期数','defaultvalue'=>$info?$info['activity_id']:'','col'=>4)); ?>
<!--  【activity_id】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【end_time】 start  !-->
<?php echo form_input(array('field'=>'end_time','fname'=>'结束时间','defaultvalue'=>$info?$info['end_time']:'','col'=>4)); ?>
<!--  【end_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


