<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【num】 start  !-->
<?php //echo form_input(array('field'=>'num','fname'=>'数量','defaultvalue'=>$info?$info['num']:'','col'=>4)); ?>
<!--  【num】 end  !-->
<!--  【address】 start  !-->
<?php //echo form_input(array('field'=>'address','fname'=>'收货地址','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【tel】 start  !-->
<?php //echo form_input(array('field'=>'tel','fname'=>'手机号','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【confirm_time】 start  !-->
<?php //echo datetime(array('field'=>'confirm_time','fname'=>'处理时间','defaultvalue'=>$info?$info['confirm_time']:'','col'=>4)); ?>
<!--  【confirm_time】 end  !-->
<!--  【real_name】 start  !-->
<?php //echo form_input(array('field'=>'real_name','fname'=>'姓名','defaultvalue'=>$info?$info['real_name']:'','col'=>4)); ?>
<!--  【real_name】 end  !-->
<!--  【stock】 start  !-->
<?php //echo form_input(array('field'=>'stock','fname'=>'剩余库存','defaultvalue'=>$info?$info['stock']:'0','col'=>4)); ?>
<!--  【stock】 end  !-->
<!--  【product_id】 start  !-->
<?php //echo form_input(array('field'=>'product_id','fname'=>'商品id','defaultvalue'=>$info?$info['product_id']:'','col'=>4)); ?>
<!--  【product_id】 end  !-->
<!--  【price】 start  !-->
<?php //echo form_input(array('field'=>'price','fname'=>'单价','defaultvalue'=>$info?$info['price']:'0.00','col'=>4)); ?>
<!--  【price】 end  !-->
<!--  【total_price】 start  !-->
<?php //echo form_input(array('field'=>'total_price','fname'=>'总额','defaultvalue'=>$info?$info['total_price']:'0.00','col'=>4)); ?>
<!--  【total_price】 end  !-->
<!--  【product_name】 start  !-->
<?php //echo form_input(array('field'=>'product_name','fname'=>'商品','defaultvalue'=>$info?$info['product_name']:'','col'=>4)); ?>
<!--  【product_name】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


