<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【thumb】 start  !-->
<?php echo photo(array('field'=>'thumb','fname'=>'图片','defaultvalue'=>$info?$info['thumb']:'','col'=>4)); ?>
<!--  【thumb】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【stock】 start  !-->

<!--  【stock】 end  !-->
<!--  【price】 start  !-->
<?php echo form_input(array('field'=>'price','fname'=>'售价','defaultvalue'=>$info?$info['price']:'0.00','col'=>4)); ?>
<!--  【price】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


