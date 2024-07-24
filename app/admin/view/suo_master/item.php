<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<?php
$flag = 1;
if($in['type'] == 2 || $info['level'] == 2  ){
    $flag = 2;

}
?>
        <?php if($in['type']){ ?>
            <input type='hidden'   name='level' value='<?php echo $in['type']; ?>'>
            <input type='hidden'   name='type' value='<?php echo $in['type']; ?>'>
<?php }else{ ?>
            <input type='hidden'   name='level' value='<?php echo $info['level']; ?>'>
            <input type='hidden'   name='type' value='<?php echo $info['type']; ?>'>
        <?php } ?>

        <?php if($flag == 2  ){ ?>
            <?php echo form_input(array('field'=>'shop_name','fname'=>'店名','defaultvalue'=>$info?$info['shop_name']:'','col'=>4)); ?>
        <?php } ?>
        <!--  【id】 end  !-->
<!--  【realname】 start  !-->
<?php echo form_input(array('field'=>'realname','fname'=>'姓名','defaultvalue'=>$info?$info['realname']:'','col'=>4)); ?>
<!--  【realname】 end  !-->
<!--  【headimgurl】 start  !-->
<?php echo thumb(array('field'=>'headimgurl','fname'=>'头像','defaultvalue'=>$info?$info['headimgurl']:'','col'=>4)); ?>
<!--  【headimgurl】 end  !-->
<!--  【lat】 start  !-->
<!--  【lat】 end  !-->
<!--  【lng】 start  !-->
<!--  【lng】 end  !-->
<!--  【address】 start  !-->
<?php echo form_input(array('field'=>'address','fname'=>'地址','defaultvalue'=>$info?$info['address']:'','col'=>4)); ?>
<!--  【address】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'电话','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【linkman】 start  !-->
<?php echo form_input(array('field'=>'linkman','fname'=>'联系人','defaultvalue'=>$info?$info['linkman']:'','col'=>4)); ?>
<!--  【linkman】 end  !-->
<!--  【remark】 start  !-->
<?php echo form_input(array('field'=>'remark','fname'=>'擅长','defaultvalue'=>$info?$info['remark']:'','col'=>4)); ?>
<!--  【remark】 end  !-->
 <?php echo form_input(array('field'=>'business_hours','fname'=>'营业时间','defaultvalue'=>$info?$info['business_hours']:'','col'=>4)); ?>
        <?php echo form_input(array('field'=>'idcard','fname'=>'身份证号码','defaultvalue'=>$info?$info['idcard']:'','col'=>4)); ?>

<?php echo form_input(array('field'=>'jiedan_num','fname'=>'接单数量','defaultvalue'=>$info?$info['jiedan_num']:'','col'=>4)); ?>

        <?php echo form_input(array('field'=>'star','fname'=>'评分','defaultvalue'=>$info?$info['star']:'5.0','col'=>4)); ?>
        <?php echo form_input(array('field'=>'key','fname'=>'搜索关键词','defaultvalue'=>$info?$info['key']:'','col'=>4)); ?>

<?php echo photo(array('field'=>'idcard_front','fname'=>'身份证正面','val'=>$info['idcard_front'],'col'=>4,'select_num'=>1)); ?>
 <?php echo photo(array('field'=>'idcard_back','fname'=>'身份证反面','val'=>$info['idcard_back'],'col'=>4,'select_num'=>1)); ?>

<?php echo photo(array('field'=>'shop_imgs','fname'=>'资质展示照片','val'=>json_decode($info['shop_imgs'],true),'col'=>4,'select_num'=>5)); ?>




        <?php if($flag == 2  ){ ?>
            <?php echo photo(array('field'=>'yyzz','fname'=>'营业执照','val'=>json_decode($info['yyzz'],true),'col'=>4,'select_num'=>5)); ?>
        <?php } ?>

        <!--  【shop_id】 start  !-->
<!--  【shop_id】 end  !-->
<!--  【pwd】 start  !-->
<?php echo form_input(array('field'=>'pwd','fname'=>'密码','defaultvalue'=>'','msg'=>'留空则不修改密码','col'=>4)); ?>
<!--  【pwd】 end  !-->
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
<!--  【shop_id】 start  !-->
<?php    echo  ($info['level']!=2 && $in['type']!=2) ? select(array('field'=>'shop_id','items'=>(new \app\admin\model\SuoMaster())->getOption('shop_name',['level'=>2]),'fname'=>'所属门店','defaultvalue'=>$info?$info['shop_id']:'','col'=>4)) : ''; ?>
<!--  【shop_id】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


