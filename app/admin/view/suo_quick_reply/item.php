<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【content】 start  !-->
<?php echo editor(array('field'=>'content','fname'=>'内容','defaultvalue'=>$info?htmlspecialchars_decode($info['content'], ENT_QUOTES):'',)); ?>
<!--  【content】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


