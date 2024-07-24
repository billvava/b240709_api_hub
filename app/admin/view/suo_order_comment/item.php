<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【order_id】 start  !-->
<?php echo form_input(array('field'=>'order_id','fname'=>'订单ID','defaultvalue'=>$info?$info['order_id']:'','col'=>4)); ?>
<!--  【order_id】 end  !-->
<!--  【images】 start  !-->
<?php echo photo(array('field'=>'images','fname'=>'图片','val'=>json_decode($info['images'],true),'col'=>4,'select_num'=>5)); ?>
<!--  【images】 end  !-->
<!--  【rank】 start  !-->
<?php echo form_input(array('field'=>'rank','fname'=>'等级','defaultvalue'=>$info?$info['rank']:'1','col'=>4)); ?>
<!--  【rank】 end  !-->
<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【time】 start  !-->
<?php echo datetime(array('field'=>'time','fname'=>'时间','defaultvalue'=>$info?$info['time']:'','col'=>4)); ?>
<!--  【time】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【star】 start  !-->
<?php echo form_input(array('field'=>'star','fname'=>'星级','defaultvalue'=>$info?$info['star']:'5','col'=>4)); ?>
<!--  【star】 end  !-->
<!--  【type】 start  !-->
<?php echo form_input(array('field'=>'type','fname'=>'1=正常，2=虚拟','defaultvalue'=>$info?$info['type']:'1','col'=>4)); ?>
<!--  【type】 end  !-->
<!--  【is_anonymous】 start  !-->
<?php $itms = $all_lan["is_anonymous"];   echo radio(array('field'=>'is_anonymous','items'=>$itms,'fname'=>'是否匿名','defaultvalue'=>$info?$info['is_anonymous']:'1',)); ?>
<!--  【is_anonymous】 end  !-->
<!--  【reply】 start  !-->
<?php echo form_input(array('field'=>'reply','fname'=>'商家回复','defaultvalue'=>$info?$info['reply']:'','col'=>4)); ?>
<!--  【reply】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'99','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【is_img】 start  !-->
<?php $itms = $all_lan["is_img"];   echo radio(array('field'=>'is_img','items'=>$itms,'fname'=>'是否带图','defaultvalue'=>$info?$info['is_img']:'0',)); ?>
<!--  【is_img】 end  !-->
<!--  【master_id】 start  !-->
<?php echo form_input(array('field'=>'master_id','fname'=>'师傅','defaultvalue'=>$info?$info['master_id']:'','col'=>4)); ?>
<!--  【master_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


