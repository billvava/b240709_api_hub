<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【token】 start  !-->
<?php echo form_input(array('field'=>'token','fname'=>'标识','defaultvalue'=>$info?$info['token']:'','col'=>4)); ?>
<!--  【token】 end  !-->
<!--  【name】 start  !-->
 <?php echo photo(array('field'=>'thumb','fname'=>'封面','defaultvalue'=>$info?$info['thumb']:'','col'=>4)); ?>

<?php echo form_input(array('field'=>'name','fname'=>'标题','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<?php echo select(array('field'=>'cate_id','fname'=>'分类','items'=>$cate,'defaultvalue'=>$info?$info['cate_id']:'','col'=>4)); ?>
        <?php echo datetime(array('field'=>'time','fname'=>'发布时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>

<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'显示'),array('val'=>'0','name'=>'隐藏'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【cate_id】 start  !-->
<!--  【cate_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


