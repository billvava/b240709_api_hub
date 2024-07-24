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
	<label class='layui-form-label'>用户ID</label>
	<div class='layui-input-inline'>
		<input type='text' name='user_id'  value='<?php echo input('get.user_id'); ?>' class='layui-input'>

	</div>
</div>

<?php echo list_rangedate(array('field'=>'time','fname'=>'申请时间','defaultvalue'=>input('get.time'))); ?>
<?php echo list_rangedate(array('field'=>'update_time','fname'=>'处理时间','defaultvalue'=>input('get.update_time'))); ?>
<?php $itms =  $all_lan['status']; ?><?php  echo new_list_select(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>input('get.status'),'requestFnName'=>'status')); ?>

<?php $itms =  $all_lan['channel_cate']; ?><?php  echo new_list_select(array('field'=>'channel_cate','items'=>$itms,'fname'=>'渠道','defaultvalue'=>input('get.channel_cate'),'requestFnName'=>'channel_cate')); ?>

<div class='layui-inline' >
	<label class='layui-form-label'>流水号</label>
	<div class='layui-input-inline'>
		<input type='text' name='order_num'  value='<?php echo input('get.order_num'); ?>' class='layui-input'>

	</div>
</div>




<div class='layui-inline' >
	<label class='layui-form-label'>账号</label>
	<div class='layui-input-inline'>
		<input type='text' name='num'  value='<?php echo input('get.num'); ?>' class='layui-input'>

	</div>
</div>

<div class='layui-inline' >
	<label class='layui-form-label'>姓名</label>
	<div class='layui-input-inline'>
		<input type='text' name='realname'  value='<?php echo input('get.realname'); ?>' class='layui-input'>

	</div>
</div>

<div class='layui-inline' >
	<label class='layui-form-label'>手机</label>
	<div class='layui-input-inline'>
		<input type='text' name='tel'  value='<?php echo input('get.tel'); ?>' class='layui-input'>

	</div>
</div>


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
                <td colspan="100" style=" color: red;">出现【待核实】的状态，请向技术人员咨询或者进入商户平台进行查询，以免重复转账</td>

            </tr>
            <tr>
                <?php if($is_del==1){ ?>
                <th>
                    <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> |
                    <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(':checked')); })">反选</a>
                </th>
                <?php } ?>
                <th>金额</th>
<th>时间</th>
<th>状态</th>

                <th>提现渠道</th>

                <th>账号</th>
<th>姓名</th>
                <th>其他</th>
                <th>最近信息</th>

                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php $color = lang('color');$cashout_cate = lang('cashout_cate'); ?>
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th>

                        <p>类型：<?php  echo $cashout_cate[$v['cate']]; ?></p>
                        <p>申请金额：{$v.money}</p>
                        <p>应付金额：{$v.real_total}</p>
                        <p>手续金额：{$v.plus_total}</p></th>
<th>
    <p>用户：<?php echo getname($v['user_id']); ?></p>

    <p>申请时间：<?php echo $v['time']; ?></p>
    <p>处理时间：<?php echo $v['update_time']; ?></p>

</th>
<th><font style='<?php echo $color[$v['status']];  ?>'><?php  echo $all_lan['status'][$v['status']]; ?></font></th>


<th><font style='<?php echo $color[$v['channel_cate']];  ?>'><?php  echo $all_lan['channel_cate'][$v['channel_cate']]; ?></font></th>


<th><input type="text" value="<?php echo $v['num']; ?>" name="num" class="layui-input">
    <input type="hidden" value="<?php echo $v['id']; ?>" class="layui-input">
</th>
<th><?php echo $v['realname']; ?>
<p>手机：<?php echo $v['tel']; ?></p>
</th>
                    <th>
                        <?php if($v['channel_cate']=='bank'){ ?>
                            <p>银行：   <?php echo $v['name']; ?></p>
                            <p>开户行：   <?php echo $v['address']; ?></p>
                        <?php } ?>
                    </th>
                    <th> <div style="width: 200px; overflow: scroll"><?php echo $v['order_num']; ?>

                            <p><?php echo $v['api_json']; ?></p></div>
                    </th>
                    <th>
                        <?php if($is_edit==1){ ?>
                        <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('edit',array($pk=>$v[$pk]) ); ?>">编辑</a>
                        <?php } ?>
                        <?php if($is_del==1){ ?>
                        <a class="pear-btn pear-btn-danger pear-btn-sm" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                        <?php } ?>

                        <?php if( in_array($v['status'],[0]) && in_array($v['channel_cate'],['weixin','alipay'])){ ?>
                            <a  class="pear-btn pear-btn-danger pear-btn-sm"  href="javascript://" onclick="dakuan('<?php echo $v['id']; ?>')"><?php echo $all_lan['channel_cate'][$v['channel_cate']]; ?>直接打款</a>
                        <?php } ?>
                        <?php if( in_array($v['status'],[0,3])){ ?>
                        <a  class="pear-btn pear-btn-primary pear-btn-sm"  href="javascript://" onclick="del('{:url('change_status',array('id'=>$v['id'],'status'=>1))}','确定修改为：已打款吗？')">已打款</a>
                        <a  class="pear-btn pear-btn-warming pear-btn-sm"  href="javascript://" onclick="del('{:url('change_status',array('id'=>$v['id'],'status'=>2))}','确定修改为：驳回？')">驳回</a>
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
<script src="__STATIC__/pearadmin/component/pear/pear.js"></script>
<script type="text/javascript">
var notice;
$(function(){
    $("input[type=text]").change(function(){
        var t = $(this);
        var i = t.next('input').val();
        var v =  t.val();
        send_update(v,i);
    });
    
    layui.use(['notice','form'], function () {
    notice = layui.notice;
    var form = layui.form;
    //此处即为 radio 的监听事件
        form.on('radio', function(data){
        var t = $(data.elem);
         send_update(data.value,t.attr('lay-filter'));
        });
       
    });

   
    
})

function send_update(num,i){
       $.post('{:url('set_num')}',{num:num,id:i},function(data){
            notice.success(data.info);
      },'json');
}
    function sub(url){
        $("#index_form").attr('action',url).submit();
    }
    function dakuan(id){

        layer.confirm('确定打款吗', function() {
            load();
            ajax('<?php echo url('transfer'); ?>',{ id:id},function (res){
                location.reload();
            } ,function (res){
                location.reload();
            });
        });

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
