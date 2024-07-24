<style>
    .layui-inline{
        margin-bottom: 10px;
    }
</style>
<div class='x-body' >


    <?php if($is_add==1){ ?>
<!--    <xblock>-->
<!--        <a class="pear-btn pear-btn-primary " href="{:url('add')}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>-->
<!--    </xblock>-->
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

<!--<div class='layui-inline' >-->
<!--	<label class='layui-form-label'>状态</label>-->
<!--	<div class='layui-input-inline'>-->
<!--		<input type='text' name='draw'  value='--><?php //echo input('get.draw'); ?><!--' class='layui-input'>-->
<!---->
<!--	</div>-->
<!--</div>-->

<!--<div class='layui-inline' >-->
<!--	<label class='layui-form-label'>倍数</label>-->
<!--	<div class='layui-input-inline'>-->
<!--		<input type='text' name='beishu'  value='--><?php //echo input('get.beishu'); ?><!--' class='layui-input'>-->
<!---->
<!--	</div>-->
<!--</div>-->

<!--<div class='layui-inline' >-->
<!--	<label class='layui-form-label'>中奖数</label>-->
<!--	<div class='layui-input-inline'>-->
<!--		<input type='text' name='num'  value='--><?php //echo input('get.num'); ?><!--' class='layui-input'>-->
<!---->
<!--	</div>-->
<!--</div>-->

<?php echo list_rangedate(array('field'=>'create_time','fname'=>'时间','defaultvalue'=>input('get.create_time'))); ?>

            <input type="hidden"  name="p" value="1">
            <div class="layui-form-item" style=" margin-top: 10px;">
                <div class='layui-inline'>
                    <label class='layui-form-label'></label>
                    <div class='layui-input-inline'>
                        <button class="pear-btn pear-btn-primary  "  type="button"  onclick="sub('{:url('index')}')" >搜索</button>
                        <?php if($is_xls==1){ ?>
<!--                        <button class="pear-btn pear-btn-primary  "  type="button"  onclick="sub('{:url('xls')}')">导出</button>-->
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
<!--                    <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> |-->
                    <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(':checked')); })">反选</a>
                </th>
                <?php } ?>
                <th>id</th>
<th>手机号码</th>

<th>状态</th>

                <th>代金券</th>
<th>倍数</th>
<th>中奖数</th>
<th>时间</th>

<!--                <th>操作</th>-->
            </tr>
            </thead>
            <tbody>
            <?php $color = lang('color'); ?>    
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
<th><?php echo $v['tel']; ?></th>
<th><font style='<?php echo $color[$v['draw']];  ?>'><?php  echo $all_lan['draw'][$v['draw']]; ?></font></th>
                    <th style="color: red">-<?php echo $v['daijinquan']; ?></th>
<th><?php echo $v['beishu']; ?></th>
<th><?php echo $v['num'] > 0? $v['num'] :'未中奖'; ?></th>
<th><?php echo $v['create_time']; ?></th>

<!--                    <th>-->
<!--                        --><?php //if($is_edit==1){ ?>
<!--                        <a  class="pear-btn pear-btn-primary pear-btn-sm" href="--><?php //echo url('edit',array($pk=>$v[$pk]) ); ?><!--">编辑</a>-->
<!--                        --><?php //} ?>
<!--                        --><?php //if($is_del==1){ ?>
<!--                        <a class="pear-btn pear-btn-danger pear-btn-sm" href="--><?php //echo url('delete',array($pk=>$v[$pk]) ); ?><!--" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>-->
<!--                        --><?php //} ?>
<!--                    </th>-->
                </tr>
            {/foreach}
            <?php if($is_del==1){ ?>
<!--            <tr>-->
<!--                <td colspan="100">-->
<!--                    <a  href="javascript://" onclick="forever_copy()"   >复制</a> | <a  href="javascript://" onclick="forever_del()"   >批量删除</a>-->
<!--                </td>-->
<!--            </tr>-->
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
