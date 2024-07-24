<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
        <input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'联系人','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【realname】 start  !-->
<?php //echo form_input(array('field'=>'realname','fname'=>'联系人','defaultvalue'=>$info?$info['realname']:'','col'=>4)); ?>
<!--  【realname】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'联系手机','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【province】 start  !-->

<!--  【province】 end  !-->
<!--  【city】 start  !-->

<!--  【city】 end  !-->
<!--  【country】 start  !-->

<!--  【country】 end  !-->
<!--  【address】 start  !-->
<!--  【address】 end  !-->
<!--  【status】 start  !-->
        <!--<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'',)); ?>!-->
<!--  【status】 end  !-->
<!--  【thumb】 start  !-->
<?php //echo photo(array('field'=>'thumb','fname'=>'展示图','defaultvalue'=>$info?$info['thumb']:'','col'=>4)); ?>
<!--  【thumb】 end  !-->
<!--  【lat】 start  !-->
<!--  【lat】 end  !-->
<!--  【lng】 start  !-->
<!--  【lng】 end  !-->
<!--  【】 start  !-->
<?php //echo
//sel_dot([
//        'col'=>8,
//        'name'=>'经纬度',
//        'lat_name'=>'lat',
//        'lng_name'=>'lng',
//        'lat_val'=>$info['lat'],
//        'lng_val'=>$info['lng'],
//        'msg'=>'',
//        'other'=>'',
//    ]); ?>
<!-- 【】 end  !-->

<?php echo  select_areas([
        'col'=>4,
         'province_name'=>'province',
          'city_name'=>'city',
          'country_name'=>'country',
          'province_val'=>$info['province'],
          'city_val'=>$info['city'],
          'country_val'=>$info['country'],
          'name'=>'省市区',
    ]); ?>

<!--    --><?php //echo form_input(array('field'=>'address','fname'=>'地址','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>

        <!--  【】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


