<style>
    .layui-inline{
        margin-bottom: 10px;
    }
</style>
<div class='x-body' >


    <?php if($is_add==1){ ?>
    <xblock>
        <a class="pear-btn pear-btn-primary " href="{:url('add',['type'=>1])}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>
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
	<label class='layui-form-label'>姓名</label>
	<div class='layui-input-inline'>
		<input type='text' name='realname'  value='<?php echo input('get.realname'); ?>' class='layui-input'>

	</div>
</div>

<div class='layui-inline' >
	<label class='layui-form-label'>地址</label>
	<div class='layui-input-inline'>
		<input type='text' name='address'  value='<?php echo input('get.address'); ?>' class='layui-input'>

	</div>
</div>

<?php $itms =  $all_lan['status']; ?><?php  echo new_list_select(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>input('get.status'),'requestFnName'=>'status')); ?>
            <?php $itms =  $type_array; ?><?php  echo new_list_select(array('field'=>'type_id','items'=>$itms,'fname'=>'类型','defaultvalue'=>input('get.type_id'),'requestFnName'=>'type_id')); ?>
            <div class='layui-inline' >
	<label class='layui-form-label'>电话</label>
	<div class='layui-input-inline'>
		<input type='text' name='tel'  value='<?php echo input('get.tel'); ?>' class='layui-input'>

	</div>
</div>

<div class='layui-inline' >
	<label class='layui-form-label'>联系人</label>
	<div class='layui-input-inline'>
		<input type='text' name='linkman'  value='<?php echo input('get.linkman'); ?>' class='layui-input'>

	</div>
</div>


<?php $itms =  $all_lan['is_auth']; ?><?php  echo new_list_select(array('field'=>'is_auth','items'=>$itms,'fname'=>'是否实名','defaultvalue'=>input('get.is_auth'),'requestFnName'=>'is_auth')); ?>
<?php $itms =  $all_lan['is_work']; ?><?php  echo new_list_select(array('field'=>'is_work','items'=>$itms,'fname'=>'是否工作','defaultvalue'=>input('get.is_work'),'requestFnName'=>'is_work')); ?>


            <input type="hidden"  name="p" value="1">
            <input type="hidden"  name="level" value="1">
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
                <th>类型</th>
                <th>门店</th>
                <th>性别</th>

                <th>姓名</th>
                <th>头像</th>
                <th>地址</th>
                <th>状态</th>
                <th>排序</th>
                <th>电话</th>
                <th>联系人</th>
                <th>是否实名</th>
                <th>是否工作</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php $color = lang('color'); ?>    
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
                    <th><?php echo $v['yy_type_str']; ?></th>

                    <th><?php echo $v['shop_name']; ?></th>
                    <th><?php echo $all_lan['sex'][$v['sex']]; ?></th>

                    <th><?php echo $v['realname']; ?></th>
<th><?php  echo_img($v['headimgurl']); ?></th>
<th><?php echo $v['address']; ?></th>
<td><?php echo fast_check(array('key'=>$pk,'keyid'=>$v[$pk],'field'=>'status','url'=>url('set_val'),'txt'=>'开启','check'=>$v['status'])); ?></td>
<th><input type='text' class='layui-input w100' data-type='setval'    data-key='{$pk}'  data-keyid='<?php echo $v[$pk]; ?>'  data-field='sort'  data-url='{:url('set_val')}' value='<?php echo $v['sort']; ?>'/></th>
<th><?php echo $v['tel']; ?></th>
<th><?php echo $v['linkman']; ?></th>
                    <td><?php echo fast_check(array('key'=>$pk,'keyid'=>$v[$pk],'field'=>'is_auth','url'=>url('set_val'),'txt'=>'开启','check'=>$v['is_auth'])); ?></td>
                    <td><?php echo fast_check(array('key'=>$pk,'keyid'=>$v[$pk],'field'=>'is_work','url'=>url('set_val'),'txt'=>'开启','check'=>$v['is_work'])); ?></td>

                    <th>
                        <?php if($is_edit==1){ ?>
                        <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('edit',array($pk=>$v[$pk]) ); ?>">编辑</a>
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
