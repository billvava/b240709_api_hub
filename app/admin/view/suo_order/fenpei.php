<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">

        <!--  【id】 start  !-->
        <input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
        <!--  【id】 end  !-->
        <!--  【user_id】 start  !-->

        <div class="layui-form-item  layui-col-xs10 layui-col-md4" id="contentshow_master_id">
            <label class="layui-form-label">订单号</label>
            <div class="layui-input-block">
                <div>
                    <input type='text' disabled readonly value='{$info.ordernum}'  class='layui-input'>
                </div>
                <div class="layui-word-aux mt5"></div>
            </div>
        </div>
        <div class="layui-form-item  layui-col-xs10 layui-col-md4" id="contentshow_master_id">
            <label class="layui-form-label">金额</label>
            <div class="layui-input-block">
                <div>
                    <input type='text' disabled readonly value='{$info.total_str}'  class='layui-input'>
                </div>
                <div class="layui-word-aux mt5"></div>
            </div>
        </div>
        <div class="layui-form-item  layui-col-xs10 layui-col-md4" id="contentshow_master_id">
            <label class="layui-form-label">地址</label>
            <div class="layui-input-block">
                <div>
                    <input type='text' disabled  readonly value='{$info.reference}{$info.address}'  class='layui-input'>
                </div>
                <div class="layui-word-aux mt5"></div>
            </div>
        </div>
        <!--  【country】 end  !-->
        <!--  【update_time】 start  !-->
        <?php $items = (new \app\admin\model\SuoMaster())->getOption('realname',['level'=>1]);
        echo select(array('items'=>$items,'field'=>'master_id','fname'=>'选择师傅','defaultvalue'=>'','col'=>4)); ?>
        <!--  【update_time】 end  !-->
        <!--  【type】 start  !-->
        <!--  【type】 end  !-->
        <?php echo submit(); ?>
    </form>
</div>


