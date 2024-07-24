<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【ordernum】 start  !-->
<?php echo form_input(array('field'=>'ordernum','fname'=>'订单号','defaultvalue'=>$info?$info['ordernum']:'','col'=>4)); ?>
<!--  【ordernum】 end  !-->
<!--  【user_id】 start  !-->
<?php echo form_input(array('field'=>'user_id','fname'=>'用户ID','defaultvalue'=>$info?$info['user_id']:'','col'=>4)); ?>
<!--  【user_id】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【goods_total】 start  !-->
<?php echo form_input(array('field'=>'goods_total','fname'=>'订单金额','defaultvalue'=>$info?$info['goods_total']:'','col'=>4)); ?>
<!--  【goods_total】 end  !-->
<!--  【total】 start  !-->
<?php echo form_input(array('field'=>'total','fname'=>'金额','defaultvalue'=>$info?$info['total']:'','col'=>4)); ?>
<!--  【total】 end  !-->
<!--  【is_pay】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'is_pay','items'=>$itms,'fname'=>'支付状态','defaultvalue'=>$info?$info['is_pay']:'0',)); ?>
<!--  【is_pay】 end  !-->
<!--  【tra_no】 start  !-->
<?php echo form_input(array('field'=>'tra_no','fname'=>'流水号','defaultvalue'=>$info?$info['tra_no']:'','col'=>4)); ?>
<!--  【tra_no】 end  !-->
<!--  【pay_time】 start  !-->
<?php echo datetime(array('field'=>'pay_time','fname'=>'支付时间','defaultvalue'=>$info?$info['pay_time']:'','col'=>4)); ?>
<!--  【pay_time】 end  !-->
<!--  【pay_type】 start  !-->
<?php echo form_input(array('field'=>'pay_type','fname'=>'支付方式','defaultvalue'=>$info?$info['pay_type']:'','col'=>4)); ?>
<!--  【pay_type】 end  !-->
<!--  【is_feek】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'is_feek','items'=>$itms,'fname'=>'退款状态','defaultvalue'=>$info?$info['is_feek']:'0',)); ?>
<!--  【is_feek】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


