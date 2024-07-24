<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
<!--  【user_id】 start  !-->

<!--  【user_id】 end  !-->
<!--  【realname】 start  !-->
<?php echo form_input(array('field'=>'realname','fname'=>'姓名','defaultvalue'=>$info?$info['realname']:'','col'=>4)); ?>
<!--  【realname】 end  !-->
<!--  【tel】 start  !-->
<?php echo form_input(array('field'=>'tel','fname'=>'手机','defaultvalue'=>$info?$info['tel']:'','col'=>4)); ?>
<!--  【tel】 end  !-->
<!--  【status】 start  !-->
        <div class="layui-form-item  layui-col-xs10 layui-col-md4" id="contentshow_tel">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <div><input style=" border: none;" type="text" disabled value="<?php echo $all_lan["status"][$info['status']]; ?>" readonly class="layui-input"></div>
                <div class="layui-word-aux mt5"></div>
            </div>
        </div>

<!--  【status】 end  !-->
<!--  【up_time】 start  !-->
<?php echo datetime(array('field'=>'up_time','fname'=>'更新时间','defaultvalue'=>$info?$info['up_time']:'','col'=>4)); ?>
<!--  【up_time】 end  !-->
<!--  【type】 start  !-->
        <div class="layui-form-item  layui-col-xs10 layui-col-md4" id="contentshow_tel">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-block">
                <div><input style=" border: none;" type="text" disabled value="<?php echo $all_lan["type"][$info['type']]; ?>" readonly class="layui-input"></div>
                <div class="layui-word-aux mt5"></div>
            </div>
        </div>
<!--  【type】 end  !-->
<!--  【weixin】 start  !-->
<?php echo form_input(array('field'=>'weixin','fname'=>'微信','defaultvalue'=>$info?$info['weixin']:'','col'=>4)); ?>
<!--  【weixin】 end  !-->
<!--  【ruzhu_type】 start  !-->
<?php echo form_input(array('field'=>'ruzhu_type','fname'=>'入驻类型','defaultvalue'=>$info?$info['ruzhu_type']:'','col'=>4)); ?>
<!--  【ruzhu_type】 end  !-->
<!--  【city】 start  !-->

<!--  【city】 end  !-->
<!--  【province】 start  !-->

<!--  【province】 end  !-->
<!--  【country】 start  !-->

<!--  【country】 end  !-->
<!--  【company】 start  !-->
<?php echo form_input(array('field'=>'company','fname'=>'公司','defaultvalue'=>$info?$info['company']:'','col'=>4)); ?>
<!--  【company】 end  !-->
<!--  【daili_city】 start  !-->
<?php echo form_input(array('field'=>'daili_city','fname'=>'期望代理城市','defaultvalue'=>$info?$info['daili_city']:'','col'=>4)); ?>
<!--  【daili_city】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


