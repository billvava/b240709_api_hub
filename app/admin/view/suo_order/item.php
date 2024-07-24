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
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'',)); ?>
<!--  【status】 end  !-->
<!--  【pay_status】 start  !-->
<?php $itms = $all_lan["pay_status"];   echo radio(array('field'=>'pay_status','items'=>$itms,'fname'=>'支付状态','defaultvalue'=>$info?$info['pay_status']:'0',)); ?>
<!--  【pay_status】 end  !-->
<!--  【sn】 start  !-->
<?php echo form_input(array('field'=>'sn','fname'=>'订单号','defaultvalue'=>$info?$info['sn']:'','col'=>4)); ?>
<!--  【sn】 end  !-->
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
<?php $itms = $all_lan["pay_type"];   echo radio(array('field'=>'pay_type','items'=>$itms,'fname'=>'支付类型','defaultvalue'=>$info?$info['pay_type']:'',)); ?>
<!--  【pay_type】 end  !-->
<!--  【pay_time】 start  !-->
<?php echo datetime(array('field'=>'pay_time','fname'=>'支付时间','defaultvalue'=>$info?$info['pay_time']:'','col'=>4)); ?>
<!--  【pay_time】 end  !-->
<!--  【comment_status】 start  !-->
<?php $itms = $all_lan["comment_status"];   echo radio(array('field'=>'comment_status','items'=>$itms,'fname'=>'评论状态','defaultvalue'=>$info?$info['comment_status']:'',)); ?>
<!--  【comment_status】 end  !-->
<!--  【total】 start  !-->
<?php echo form_input(array('field'=>'total','fname'=>'订单金额','defaultvalue'=>$info?$info['total']:'0.00','col'=>4)); ?>
<!--  【total】 end  !-->
<!--  【address】 start  !-->
<?php echo form_input(array('field'=>'address','fname'=>'地址','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【message】 start  !-->
<?php echo form_input(array('field'=>'message','fname'=>'客户留言','defaultvalue'=>$info?$info['message']:'','col'=>4)); ?>
<!--  【message】 end  !-->
<!--  【province】 start  !-->

<!--  【province】 end  !-->
<!--  【city】 start  !-->

<!--  【city】 end  !-->
<!--  【country】 start  !-->

<!--  【country】 end  !-->
<!--  【update_time】 start  !-->
<?php echo datetime(array('field'=>'update_time','fname'=>'更新时间','defaultvalue'=>$info?$info['update_time']:'','col'=>4)); ?>
<!--  【update_time】 end  !-->
<!--  【type】 start  !-->
<?php echo form_input(array('field'=>'type','fname'=>'类型','defaultvalue'=>$info?$info['type']:'','col'=>4)); ?>
<!--  【type】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


