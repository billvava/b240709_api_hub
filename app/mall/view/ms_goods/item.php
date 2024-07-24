<?php   $defaultValue = $model->defaultValue(); ?>
<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【goods_id】 start  !-->
        <?php echo goods_select(array(
            'field'=>'goods_id',
            'fname'=>'关联商品',
            'val'=>$info?$info['goods_id']:'',
            'items'=>$info['items'],
            'col'=>4,
            'msg'=>'规格未设置活动价格的时候，则使用原价进行活动下单')); ?>
<!--  【goods_id】 end  !-->
<!--  【num】 start  !-->
<?php echo form_input(array('field'=>'num','fname'=>'总数量','defaultvalue'=>$info?$info['num']:'','col'=>4)); ?>
<!--  【num】 end  !-->
        <!--  【sale_num】 start  !-->
        <?php echo form_input(array('field'=>'sale_num','fname'=>'已售数量','defaultvalue'=>$info?$info['sale_num']:'0','col'=>4)); ?>
        <!--  【sale_num】 end  !-->
<!--  【cate_id】 start  !-->

<!--  【cate_id】 end  !-->
<!--  【status】 start  !-->
<?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'99','col'=>4)); ?>
<!--  【sort】 end  !-->

<!--  【items】 start  !-->

<!--  【items】 end  !-->
<!--  【min_price】 start  !-->

<!--  【min_price】 end  !-->
<!--  【end】 start  !-->
        <?php echo datetime(array('field'=>'start','fname'=>'开始时间','defaultvalue'=>$info?$info['start']:'','col'=>4)); ?>

        <?php echo datetime(array('field'=>'end','fname'=>'结束时间','defaultvalue'=>$info?$info['end']:'','col'=>4)); ?>
<!--  【end】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>

<script src="__LIB__/template.js" type="text/javascript"></script>

