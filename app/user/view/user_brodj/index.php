<?php $searchArr=$model->searchArr();?>
<div class='x-body' >


    <?php if($is_add==1){ ?>
    <xblock>
        <a class="pear-btn pear-btn-primary " href="{:url('add')}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>
    </xblock>
    <?php } ?>
    <?php if($is_search==1){ ?>
    <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" id="index_form" action=""  method="get">
            <?php if($searchArr['id']){ ?><input type='text' name='id' value='<?php echo input('get.id'); ?>'  placeholder='变化'  class='layui-input'><?php } ?>
<?php if($searchArr['user_id']){ ?><input type='text' name='user_id' value='<?php echo input('get.user_id'); ?>'  placeholder='用户'  class='layui-input'><?php } ?>
<?php if($searchArr['total']){ ?><input type='text' name='total' value='<?php echo input('get.total'); ?>'  placeholder='金额'  class='layui-input'><?php } ?>
<?php if($searchArr['expire']){ ?><?php echo list_rangedate(array('field'=>'expire','fname'=>'到期时间','defaultvalue'=>input('get.expire'))); ?><?php } ?>
<?php if($searchArr['time']){ ?><?php echo list_rangedate(array('field'=>'time','fname'=>'生成时间','defaultvalue'=>input('get.time'))); ?><?php } ?>

            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary  "  type="button"  onclick="sub('{:url('index')}')" >搜索</button>
            <?php if($is_xls==1){ ?>
            <button class="pear-btn pear-btn-primary  "  type="button"  onclick="sub('{:url('xls')}')">导出</button>
            <?php } ?>
        </form>
    </div>


    <?php } ?>

    <div class="table-container">
        <table class="layui-table"  lay-skin="line">

            <thead>
            <tr>
                <?php if($is_del==1){ ?>
                <th>
                    <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> |
                    <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(':checked')); })">反选</a>
                </th>
                <?php } ?>
                <th>变化</th>
<th>用户</th>
<th>金额</th>
<th>到期时间</th>
<th>生成时间</th>

                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
<th><?php echo $v['user_id']; ?></th>
<th><?php echo $v['total']; ?></th>
<th><?php echo $v['expire']; ?></th>
<th><?php echo $v['time']; ?></th>

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
