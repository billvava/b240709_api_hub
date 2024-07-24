<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【thumb】 start  !-->
<?php echo photo(array('field'=>'thumb','fname'=>'展示图','defaultvalue'=>$info?$info['thumb']:'','col'=>4)); ?>
<!--  【thumb】 end  !-->
<!--  【lat】 start  !-->
<?php echo sel_dot(array('field'=>'lat','fname'=>'纬度','defaultvalue'=>$info?$info['lat']:'','col'=>4)); ?>
<!--  【lat】 end  !-->
<!--  【lng】 start  !-->
<?php echo sel_dot(array('field'=>'lng','fname'=>'经度','defaultvalue'=>$info?$info['lng']:'','col'=>4)); ?>
<!--  【lng】 end  !-->
<!--  【address】 start  !-->
<?php echo form_input(array('field'=>'address','fname'=>'地址','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【status】 start  !-->
<!--  【sort】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'电话','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【linkman】 start  !-->
<?php echo form_input(array('field'=>'linkman','fname'=>'联系人','defaultvalue'=>$info?$info['linkman']:'','col'=>4)); ?>
<!--  【linkman】 end  !-->
<!--  【yy_time】 start  !-->
<?php echo form_input(array('field'=>'yy_time','fname'=>'营业时间','defaultvalue'=>$info?$info['yy_time']:'','col'=>4)); ?>
<!--  【yy_time】 end  !-->


<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
        <?php echo submit(); ?>
     </form>
</div>


