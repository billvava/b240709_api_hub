<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【title】 start  !-->
<?php echo form_input(array('field'=>'title','fname'=>'标题','defaultvalue'=>$info?$info['title']:'','col'=>4)); ?>
<!--  【title】 end  !-->
<!--  【start_time】 start  !-->
<?php echo datetime(array('field'=>'start_time','fname'=>'开始时间','defaultvalue'=>$info?$info['start_time']:'','col'=>4)); ?>
<!--  【start_time】 end  !-->
<!--  【end_time】 start  !-->
<?php echo datetime(array('field'=>'end_time','fname'=>'结束时间','defaultvalue'=>$info?$info['end_time']:'','col'=>4)); ?>
<!--  【end_time】 end  !-->
<!--  【num】 start  !-->
<input type='hidden'   name='num' value='<?php echo $info?$info['num']:'0'; ?>'>
<!--  【num】 end  !-->
<!--  【address】 start  !-->
<?php echo form_input(array('field'=>'address','fname'=>'地址','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【img】 start  !-->
<?php echo photo(array('field'=>'img','fname'=>'图片','defaultvalue'=>$info?$info['img']:'','col'=>4)); ?>
<!--  【img】 end  !-->
<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


