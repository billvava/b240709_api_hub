<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【money】 start  !-->
<?php echo form_input(array('field'=>'money','fname'=>'申请金额','defaultvalue'=>$info?$info['money']:'0.00','col'=>4)); ?>
<!--  【money】 end  !-->
<!--  【time】 start  !-->
<?php echo datetime(array('field'=>'time','fname'=>'申请时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>
<!--  【time】 end  !-->
<!--  【update_time】 start  !-->
<?php echo datetime(array('field'=>'update_time','fname'=>'处理时间','defaultvalue'=>$info?$info['update_time']:'','col'=>4)); ?>
<!--  【update_time】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【order_num】 start  !-->
<?php echo form_input(array('field'=>'order_num','fname'=>'流水号','defaultvalue'=>$info?$info['order_num']:'','col'=>4)); ?>
<!--  【order_num】 end  !-->
<!--  【partner_trade_no】 start  !-->
<?php echo form_input(array('field'=>'partner_trade_no','fname'=>'微信退款号','defaultvalue'=>$info?$info['partner_trade_no']:'','col'=>4)); ?>
<!--  【partner_trade_no】 end  !-->
<!--  【real_total】 start  !-->
<?php echo form_input(array('field'=>'real_total','fname'=>'到账金额','defaultvalue'=>$info?$info['real_total']:'','col'=>4)); ?>
<!--  【real_total】 end  !-->
<!--  【plus_total】 start  !-->
<?php echo form_input(array('field'=>'plus_total','fname'=>'手续费','defaultvalue'=>$info?$info['plus_total']:'','col'=>4)); ?>
<!--  【plus_total】 end  !-->
<!--  【cate】 start  !-->
<?php echo form_input(array('field'=>'cate','fname'=>'金额分类','defaultvalue'=>$info?$info['cate']:'','col'=>4)); ?>
<!--  【cate】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'银行名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【address】 start  !-->
<?php echo form_input(array('field'=>'address','fname'=>'开户点','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【num】 start  !-->
<?php echo form_input(array('field'=>'num','fname'=>'账号','defaultvalue'=>$info?$info['num']:'','col'=>4)); ?>
<!--  【num】 end  !-->
<!--  【realname】 start  !-->
<?php echo form_input(array('field'=>'realname','fname'=>'姓名','defaultvalue'=>$info?$info['realname']:'','col'=>4)); ?>
<!--  【realname】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'手机','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【channel_cate】 start  !-->
<?php echo form_input(array('field'=>'channel_cate','fname'=>'渠道分类','defaultvalue'=>$info?$info['channel_cate']:'','col'=>4)); ?>
<!--  【channel_cate】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


