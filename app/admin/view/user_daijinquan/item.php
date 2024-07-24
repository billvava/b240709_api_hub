<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【type】 start  !-->
<?php $itms = $all_lan["type"];   echo radio(array('field'=>'type','items'=>$itms,'fname'=>'类型','defaultvalue'=>$info?$info['type']:'1',)); ?>
<!--  【type】 end  !-->
<!--  【total】 start  !-->
<?php echo form_input(array('field'=>'total','fname'=>'额度','defaultvalue'=>$info?$info['total']:'','col'=>4)); ?>
<!--  【total】 end  !-->
<!--  【current_total】 start  !-->
<?php echo form_input(array('field'=>'current_total','fname'=>'本次结余','defaultvalue'=>$info?$info['current_total']:'','col'=>4)); ?>
<!--  【current_total】 end  !-->
<!--  【ordernum】 start  !-->
<?php echo form_input(array('field'=>'ordernum','fname'=>'单号','defaultvalue'=>$info?$info['ordernum']:'','col'=>4)); ?>
<!--  【ordernum】 end  !-->
<!--  【msg】 start  !-->
<?php echo form_input(array('field'=>'msg','fname'=>'备注','defaultvalue'=>$info?$info['msg']:'','col'=>4)); ?>
<!--  【msg】 end  !-->
<!--  【time】 start  !-->
<?php echo datetime(array('field'=>'time','fname'=>'时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>
<!--  【time】 end  !-->
<!--  【cate】 start  !-->
<?php echo form_input(array('field'=>'cate','fname'=>'分类','defaultvalue'=>$info?$info['cate']:'0','col'=>4)); ?>
<!--  【cate】 end  !-->
<!--  【admin_id】 start  !-->
<?php echo form_input(array('field'=>'admin_id','fname'=>'操作管理员','defaultvalue'=>$info?$info['admin_id']:'','col'=>4)); ?>
<!--  【admin_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


