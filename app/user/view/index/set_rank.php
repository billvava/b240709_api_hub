<div class='x-body'>
    <form class="layui-form"  action="<?php echo url(app()->request->action()); ?>" method="post">
 <?php  $ranks = (new \app\user\model\User())->getRanks(true); ?>
 <div class='layui-form-item layui-col-xs10 layui-col-md8'>
    <label class='layui-form-label'>等级</label>
    <div class='layui-input-block'>
        <div>
            <?php foreach($ranks as $k=>$v){ ?>
            <input type='radio' name='rank' value='{$k}'   title='{$v}' >
            <?php } ?>
        </div>
          <div class='x-a mt5'></div>       
    </div>
</div>
<?php echo textarea(array('fname'=>'用户编号','field'=>'user_id','msg'=>'多个用,号分隔，所有用户的话无需填写','val'=>$in['user_id'],'col'=>4)); ?>

        <?php echo submit(); ?>
     </form>
</div>


