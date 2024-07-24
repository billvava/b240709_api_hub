<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【province】 start  !-->

<!--  【province】 end  !-->
<!--  【city】 start  !-->
        <?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>

<!--  【city】 end  !-->
<!--  【lng】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'',)); ?>
<!--  【status】 end  !-->
<!--  【】 start  !-->
<?php echo  select_areas([
        'col'=>4,
         'province_name'=>'province',
          'city_name'=>'city',
          'country_name'=>'',
          'province_val'=>$info['province'],
          'city_val'=>$info['city'],
          'country_val'=>$info[''],
          'name'=>'省市',
    ]); ?>
<!--  【】 end  !-->
<!--  【】 start  !-->
<?php echo  
sel_dot([
        'col'=>8,
        'name'=>'经纬度',
        'lat_name'=>'lat',
        'lng_name'=>'lng',
        'lat_val'=>$info['lat'],
        'lng_val'=>$info['lng'],
        'msg'=>'',
        'other'=>'',
    ]); ?>
<!--  【】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


