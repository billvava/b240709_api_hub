<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'标题','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【cate_id】 start  !-->

<?php $itms = $all_lan["cate_id"];   echo radio(array('field'=>'cate_id','items'=>$itms,'fname'=>'分类','defaultvalue'=>$info?$info['cate_id']:'1',)); ?>


<?php $itms = $ziliaobiaoqian;   echo checkbox(array('field'=>'tag_id',
    'items'=>$itms,'fname'=>'分类','defaultvalue'=>$info? explode(',',$info['tag_id']):[],)); ?>


        <!--  【cate_id】 end  !-->
<!--  【create_time】 start  !-->
<?php echo datetime(array('field'=>'create_time','fname'=>'发布时间','defaultvalue'=>$info?$info['create_time']:'','col'=>4)); ?>
<!--  【create_time】 end  !-->
<!--  【thumb】 start  !-->
<?php echo photo(array('field'=>'thumb','fname'=>'缩略图','defaultvalue'=>$info?$info['thumb']:'','col'=>4)); ?>
<!--  【thumb】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


