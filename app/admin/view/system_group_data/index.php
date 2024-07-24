<style>
    .layui-inline{
        margin-bottom: 10px;
    }
</style>
<div class='x-body' >


    <?php if($is_add==1){ ?>
    <xblock>
<!--        <a class="pear-btn pear-btn-sm pear-btn-primary " href="{:url('SystemGroup/index')}"> <i class="layui-icon layui-icon-layim-download"></i> 返回</a>-->

        <a class="pear-btn pear-btn-sm pear-btn-primary " href="{:url('add',['group_id'=>$group_id])}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>
    </xblock>
    <?php } ?>
    <?php if($is_search==1){ ?>
    <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" id="index_form" action=""  method="get">


<?php $itms =  $all_lan['status']; ?><?php  echo new_list_select(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>input('get.status'),'requestFnName'=>'status')); ?>
            <input type="hidden"  name="group_id" value="{$group_id}">

            <input type="hidden"  name="p" value="1">
            <div class="layui-form-item" style=" margin-top: 10px;">
                <div class='layui-inline'>
                    <label class='layui-form-label'></label>
                    <div class='layui-input-inline'>
                        <button class="pear-btn pear-btn-sm pear-btn-primary  "  type="button"  onclick="sub('{:url('index')}')" >搜索</button>
                        <?php if($is_xls==1){ ?>
                        <button class="pear-btn pear-btn-sm pear-btn-primary  "  type="button"  onclick="sub('{:url('xls')}')">导出</button>
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
                <?php foreach($group_info['fields'] as $fv){ ?>
                    <th>{$fv.name}</th>
                <?php } ?>
<th>排序</th>
<th>状态</th>
<th>添加时间</th>

                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php $color = lang('color'); ?>    
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
                    <?php foreach($group_info['fields'] as $fv){ ?>
                        <th><?php echo $v['value'][$fv['field']]; ?></th>
                    <?php } ?>
<th><input type='text' class='layui-input w100' data-type='setval'    data-key='{$pk}'  data-keyid='<?php echo $v[$pk]; ?>'  data-field='sort'  data-url='{:url('set_val')}' value='<?php echo $v['sort']; ?>'/></th>
<td><?php echo fast_check(array('key'=>$pk,'keyid'=>$v[$pk],'field'=>'status','url'=>url('set_val'),'txt'=>'开启','check'=>$v['status'])); ?></td>
<th><?php echo $v['create_time']; ?></th>

                    <th>
                        <?php if($is_edit==1){ ?>
                        <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('edit',array($pk=>$v[$pk],'group_id'=>$group_id) ); ?>">编辑</a>
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
