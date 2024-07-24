<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【pid】 start  !-->
<?php  select(array('field'=>'pid','fname'=>'上级','defaultvalue'=>$info?$info['pid']:'0','col'=>4,'items'=>$top)); ?>
<!--  【pid】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


