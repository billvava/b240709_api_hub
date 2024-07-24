<style>
    .items a{ cursor: pointer;}
</style>
<form class="layui-form"  action="{:url('item')}" method="post">
    
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<input type='hidden'   name='shop_id' value='<?php echo $info?$info['shop_id']:''; ?>'>


<?php echo goods_select(array(
    'field'=>'goods_id',
    'fname'=>'关联商品',
    'val'=>$info?$info['goods_id']:'',
    'items'=>$info['items'],
    'col'=>4,
    'msg'=>'规格未设置活动价格的时候，则使用原价进行活动下单')); ?>


<?php  form_input(array('field'=>'name','fname'=>'商品名','defaultvalue'=>$info?$info['name']:'','col'=>4,'msg'=>'不填则使用商品名称',)); ?>

<?php echo form_input(array('field'=>'num','fname'=>'名额','defaultvalue'=>$info?$info['num']:'100','col'=>4,'msg'=>'',)); ?>

<?php echo form_input(array('field'=>'pt_num','fname'=>'成团数量','defaultvalue'=>$info?$info['pt_num']:'2','col'=>4,'msg'=>'',)); ?>

    <?php echo form_input(array('field'=>'sale_num','fname'=>'销量','defaultvalue'=>$info?$info['sale_num']:'0','col'=>4,'msg'=>'',)); ?>

<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4,'msg'=>'',)); ?>

    <?php echo datetime(array('field'=>'start','fname'=>'开始时间','defaultvalue'=>$info?$info['start']:'','col'=>4,'msg'=>'',)); ?>

<?php echo datetime(array('field'=>'end','fname'=>'结束时间','defaultvalue'=>$info?$info['end']:'','col'=>4,'msg'=>'',)); ?>




<?php
 echo radio(array(
                'val'=>$info?$info['status']:1,
                'field'=>'status',
                'fname'=>'状态',
                'msg'=>'',
                'items'=>array(
                    array('val'=>1,'name'=>'上架'),
                    array('val'=>0,'name'=>'下架'),
                )
         ));
?>
	
<?php echo submit(); ?>
 </form>



<script src="__LIB__/template.js" type="text/javascript"></script>