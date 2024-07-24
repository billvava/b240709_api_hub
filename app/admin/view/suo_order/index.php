<style>
    .layui-inline{
        margin-bottom: 10px;
    }
</style>
<div class='x-body' >


    <?php if($is_add==1){ ?>
    <xblock>
        <a class="pear-btn pear-btn-primary " href="{:url('add')}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>
    </xblock>
    <?php } ?>
    <?php if($is_search==1){ ?>
    <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" id="index_form" action=""  method="get">

            <div class='layui-inline' >
	<label class='layui-form-label'>编号</label>
	<div class='layui-input-inline'>
		<input type='text' name='id'  value='<?php echo input('get.id'); ?>' class='layui-input'>

	</div>
</div>

<div class='layui-inline' >
	<label class='layui-form-label'>用户</label>
	<div class='layui-input-inline'>
		<input type='text' name='user_id'  value='<?php echo input('get.user_id'); ?>' class='layui-input'>

	</div>
</div>

<?php echo list_rangedate(array('field'=>'create_time','fname'=>'创建时间','defaultvalue'=>input('get.create_time'))); ?>
<?php $itms =  $all_lan['status']; ?><?php  echo new_list_select(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>input('get.status'),'requestFnName'=>'status')); ?>
<?php $itms =  $all_lan['fund_status']; ?><?php  echo new_list_select(array('field'=>'fund_status','items'=>$itms,'fname'=>'退款状态','defaultvalue'=>input('get.fund_status'),'requestFnName'=>'fund_status')); ?>


            <?php $itms =  $all_lan['type']; ?><?php  echo new_list_select(array('field'=>'type','items'=>$itms,'fname'=>'类型','defaultvalue'=>input('get.type'),'requestFnName'=>'type')); ?>

            <?php $itms =  $all_lan['pay_type']; ?><?php  echo new_list_select(array('field'=>'pay_type','items'=>$itms,'fname'=>'支付类型','defaultvalue'=>input('get.pay_type'),'requestFnName'=>'pay_type')); ?>
<?php echo list_rangedate(array('field'=>'pay_time','fname'=>'支付时间','defaultvalue'=>input('get.pay_time'))); ?>
<?php $itms =  $all_lan['comment_status']; ?><?php  echo new_list_select(array('field'=>'comment_status','items'=>$itms,'fname'=>'评论状态','defaultvalue'=>input('get.comment_status'),'requestFnName'=>'comment_status')); ?>
<div class='layui-inline' >
	<label class='layui-form-label'>地址</label>
	<div class='layui-input-inline'>
		<input type='text' name='address'  value='<?php echo input('get.address'); ?>' class='layui-input'>

	</div>
</div>

<div class='layui-inline' >
	<label class='layui-form-label'>客户留言</label>
	<div class='layui-input-inline'>
		<input type='text' name='message'  value='<?php echo input('get.message'); ?>' class='layui-input'>

	</div>
</div>

<?php //echo list_rangedate(array('field'=>'update_time','fname'=>'更新时间','defaultvalue'=>input('get.update_time'))); ?>

            <input type="hidden"  name="p" value="1">
            <div class="layui-form-item" style=" margin-top: 10px;">
                <div class='layui-inline'>
                    <label class='layui-form-label'></label>
                    <div class='layui-input-inline'>
                        <button class="pear-btn pear-btn-primary  "  type="button"  onclick="sub('{:url('index')}')" >搜索</button>
                        <?php if($is_xls==1){ ?>
                        <button class="pear-btn pear-btn-primary  "  type="button"  onclick="sub('{:url('xls')}')">导出</button>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </form>
    </div>


    <?php } ?>

    <div class="table-container">
        <table class="layui-table"  >

            <thead>
            <tr>
                <?php if($is_del==1){ ?>
                <th>
                    <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> |
                    <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(':checked')); })">反选</a>
                </th>
                <?php } ?>
                <th>编号</th>
<th>状态</th>
<th>支付状态</th>
                <th>金额</th>

                <th>地址</th>
<th>客户信息</th>
                <th>师傅信息</th>


                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php $color = lang('color'); ?>    
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>

<td>
    <p >单号：<?php  echo $v['ordernum']; ?></p>
    <p style="<?php echo $color[$v['type']];  ?>">类型：<?php  echo $all_lan['type'][$v['type']]; ?></p>
    <?php if($v['type'] == 3){ ?>
    <p style="<?php echo $color[$v['type']];  ?>">数量：<?php  echo $v['num']; ?></p>
    <?php } ?>
    <p style='<?php echo $color[$v['status']];  ?>'>订单：<?php  echo $all_lan['status'][$v['status']]; ?></p>
    <p style='<?php echo $color[$v['comment_status']];  ?>'>评论：<a href="javascript://" onclick="show_url('<?php echo url('SuoOrderComment/index',['order_id'=>$v['id']]); ?>')"><?php  echo $all_lan['comment_status'][$v['comment_status']]; ?></a></p>

</td>
<th>
    <p style='<?php echo $color[$v['pay_status']];  ?>'>
        支付状态：<?php  echo $all_lan['pay_status'][$v['pay_status']]; ?></p>
    <p style='<?php echo $color[$v['pay_type']];  ?>'>支付方式：<?php  echo $all_lan['pay_type'][$v['pay_type']]; ?></p>
    <p >支付时间：<?php echo $v['pay_time']; ?></p>
    <p style='<?php echo $color[$v['fund_status']];  ?>'> 退款状态：<?php  echo $all_lan['fund_status'][$v['fund_status']]; ?></p>
</th>

                    <th>

                        <p> 总金额：<?php echo $v['total_str']; ?></p>
                        <p> 金额：<?php echo $v['total']; ?></p>

                        <p> 加价：<a href="javascript://" onclick="show_url('<?php echo url('SuoOrderJia/index',['order_id'=>$v['id']]); ?>')"><?php echo $v['jia_total']; ?></a></p>
                        <p> 加价次数：<?php echo $v['jia_num']; ?></p>
                        <p> 加单：<?php echo $v['is_jiadan_str']; ?></p>


                    </th>
<th>

    <p> <?php echo $v['province']; ?><?php echo $v['city']; ?><?php echo $v['country']; ?></p>
    <p> <?php echo $v['reference']; ?></p>
   <p> <?php echo $v['address']; ?></p>
    <p>投诉：<a href="javascript://" onclick="show_url('<?php echo url('com/HelpMsg/index',['order_id'=>$v['id']]); ?>')"><?php echo $v['tousu_status_str']; ?></a></p>


</th>
<th>
    <p>用户：<?php echo getname($v['user_id']); ?></p>
    <p>预约：<?php echo $v['up_str']; ?></p>

    <p>产品：<?php echo $v['product_cate_str']; ?></p>

    <p>留言：<?php echo $v['message']; ?></p>

        <p>图片：<?php foreach($v['imgs_arr'] as $ik=>$iv){ ?><a href="{$iv}" target="_blank">图<?php echo $ik+1; ?></a> <?php } ?></p>

</th>

                    <th>
                        <p>门店：<?php echo ($v['shop_id_str']); ?></p>

                        <p>姓名：<?php echo ($v['master_info']['realname']); ?></p>
                        <p>手机：<?php echo ($v['master_info']['tel']); ?></p>
                        <p>开始服务时间：<?php echo ($v['start_service_time']?$v['start_service_time']:'未开始'); ?></p>
                    </th>
                    <th>

                        <?php if($v['admin_fp'] == 1){ ?>
                            <a  class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="show_url('<?php echo url('fenpei',['id'=>$v['id']]); ?>')">手工分配师傅</a>
                        <?php } ?>
                        <?php if($v['admin_pay'] == 1){ ?>
                            <a  class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="del('<?php echo url('zhifu',['id'=>$v['id']]); ?>')">设为支付成功</a>
                        <?php } ?>
                        <?php if($v['admin_quxiao'] == 1){ ?>
                        <a  class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="del('<?php echo url('quxiao',['id'=>$v['id']]); ?>')">取消</a>
                        <?php } ?>
                        <?php if($v['admin_close'] == 1){ ?>
                            <a  class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="del('<?php echo url('close',['id'=>$v['id']]); ?>')">关闭</a>
                        <?php } ?>

                        <?php if($is_del==1){ ?>
                        <a class="pear-btn pear-btn-danger pear-btn-sm" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                        <?php } ?>
                    </th>
                </tr>
            {/foreach}
            <?php if($is_del==1){ ?>
            <tr>
                <td colspan="100">
                    <a  href="javascript://" onclick="forever_copy()"   >复制</a> | <a  href="javascript://" onclick="forever_del()"   >批量删除</a>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        {$data.page|raw}
    </div>
</div>
<script type="text/javascript">
    function sub(url){
        $("#index_form").attr('action',url).submit();
    }
    function all_hand(field,val){
        var ids = get_ids();
        if(ids==null){
            layer.alert('请先选择');
            return;
        }
        ajax('{:url('mul_set')}',{ '<?php echo $pk; ?>':ids ,field:field,val:val} );
    }
    function get_ids(){
        var ids = [];
        $(".sel_id:checked").each(function() {
            ids.push($(this).val());
        });
        if(ids.length <= 0){

            return null;
        }else{
            return ids;
        }
    }

    function forever_copy(){
        var ids = get_ids();
        if(ids==null){
            layer.alert('请先选择');
            return;
        }
        var r=window.confirm("确定复制吗？");
        if(r){
            ajax('{:url('copy')}',{"<?php echo $pk; ?>":ids } );
        }

    }
    function forever_del(){
        var ids = get_ids();
        if(ids==null){
            layer.alert('请先选择');
            return;
        }
        var r=window.confirm("确定删除吗？");
        if(r){
            ajax('{:url('delete')}',{"<?php echo $pk; ?>":ids } );
        }
    }
</script>
