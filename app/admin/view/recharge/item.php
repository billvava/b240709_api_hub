<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
        <input type='hidden'   name='user_id' value='<?php echo $info?$info['user_id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【money】 start  !-->
<?php echo form_input(array('field'=>'money','fname'=>'充值金额','defaultvalue'=>$info?$info['money']:'','col'=>4)); ?>
<!--  【money】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'手机号码','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'0',)); ?>
<!--  【status】 end  !-->
<!--  【img】 start  !-->

        <div class='layui-form-item  layui-col-xs10'  >
            <label class='layui-form-label'>付款截图</label>
            <div class='layui-input-block'>
                <div>
                    <a href="{$info['img']}" target="_blank" ><img src="{$info['img']}" style="width: 200px;height: 200px"></a>
                </div>

            </div>
        </div>";
<!--  【img】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


