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
<!--  【price】 start  !-->
<?php

$order_type_arr = lang('order_type_arr');
foreach($order_type_arr as $v){
    echo form_input(array('field'=>'price'.$v['type'],'fname'=>$v['name'].'价格','defaultvalue'=>$info?$info['price'.$v['type']]:'','col'=>4));
}
 ?>
<!--  【price】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->

        <?php $itms = $cate;   echo radio(array('field'=>'cate_id','items'=>$itms,'fname'=>'分类','defaultvalue'=>$info?$info['cate_id']:'',)); ?>


<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【cate_id】 start  !-->
<!--  【cate_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


