<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【pay_status】 start  !-->
<?php $itms = $all_lan["pay_status"];   echo radio(array('field'=>'pay_status','items'=>$itms,'fname'=>'支付状态','defaultvalue'=>$info?$info['pay_status']:'0',)); ?>
<!--  【pay_status】 end  !-->
<!--  【ordernum】 start  !-->
<?php echo form_input(array('field'=>'ordernum','fname'=>'订单号','defaultvalue'=>$info?$info['ordernum']:'','col'=>4)); ?>
<!--  【ordernum】 end  !-->
<!--  【fund_status】 start  !-->
<?php $itms = $all_lan["fund_status"];   echo radio(array('field'=>'fund_status','items'=>$itms,'fname'=>'退款状态','defaultvalue'=>$info?$info['fund_status']:'',)); ?>
<!--  【fund_status】 end  !-->
<!--  【fund_money】 start  !-->
<?php echo form_input(array('field'=>'fund_money','fname'=>'退款金额','defaultvalue'=>$info?$info['fund_money']:'0.00','col'=>4)); ?>
<!--  【fund_money】 end  !-->
<!--  【trade_no】 start  !-->
<?php echo form_input(array('field'=>'trade_no','fname'=>'第三方交易号','defaultvalue'=>$info?$info['trade_no']:'','col'=>4)); ?>
<!--  【trade_no】 end  !-->
<!--  【pay_type】 start  !-->
<?php $itms = $all_lan["pay_type"];   echo radio(array('field'=>'pay_type','items'=>$itms,'fname'=>'支付类型','defaultvalue'=>$info?$info['pay_type']:'0',)); ?>
<!--  【pay_type】 end  !-->
<!--  【pay_time】 start  !-->
<?php echo datetime(array('field'=>'pay_time','fname'=>'支付时间','defaultvalue'=>$info?$info['pay_time']:'','col'=>4)); ?>
<!--  【pay_time】 end  !-->
<!--  【total】 start  !-->
<?php echo form_input(array('field'=>'total','fname'=>'订单金额','defaultvalue'=>$info?$info['total']:'0.00','col'=>4)); ?>
<!--  【total】 end  !-->
<!--  【update_time】 start  !-->
<?php echo datetime(array('field'=>'update_time','fname'=>'更新时间','defaultvalue'=>$info?$info['update_time']:'','col'=>4)); ?>
<!--  【update_time】 end  !-->
<!--  【order_id】 start  !-->
<?php echo form_input(array('field'=>'order_id','fname'=>'订单ID','defaultvalue'=>$info?$info['order_id']:'','col'=>4)); ?>
<!--  【order_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


