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
            <?php if($searchArr['id']){ ?><input type='text' name='id' value='<?php echo input('get.id'); ?>'  placeholder='系统ID'  class='layui-input'><?php } ?>
<?php if($searchArr['status']){ ?><?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo list_select(array('field'=>'status','items'=>$itms,'fname'=>'是否显示','defaultvalue'=>input('get.status'),'requestFnName'=>'status')); ?><?php } ?>
<?php if($searchArr['expresskey']){ ?><input type='text' name='expresskey' value='<?php echo input('get.expresskey'); ?>'  placeholder='快递code'  class='layui-input'><?php } ?>
<?php if($searchArr['expressname']){ ?><input type='text' name='expressname' value='<?php echo input('get.expressname'); ?>'  placeholder='快递名称'  class='layui-input'><?php } ?>
<?php if($searchArr['expresswebsite']){ ?><input type='text' name='expresswebsite' value='<?php echo input('get.expresswebsite'); ?>'  placeholder='快递官网'  class='layui-input'><?php } ?>
<?php if($searchArr['expresstelephone']){ ?><input type='text' name='expresstelephone' value='<?php echo input('get.expresstelephone'); ?>'  placeholder='快递电话'  class='layui-input'><?php } ?>

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
                <th>系统ID</th>
<th>是否显示</th>
<th>快递code</th>
<th>快递名称</th>
<th>快递官网</th>
<th>快递电话</th>

                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
<td><?php echo fast_check(array('key'=>$pk,'keyid'=>$v[$pk],'field'=>'status','url'=>url('set_val'),'txt'=>'开启','check'=>$v['status'])); ?></td>
<th><?php echo $v['expresskey']; ?></th>
<th><?php echo $v['expressname']; ?></th>
<th><?php echo $v['expresswebsite']; ?></th>
<th><?php echo $v['expresstelephone']; ?></th>

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
