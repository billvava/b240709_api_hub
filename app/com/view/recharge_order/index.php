<?php $searchArr=$model->searchArr();?>
<div class='x-body' >


    <?php if($is_search==1){ ?>
    <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" id="index_form" action=""  method="get">
<?php if($searchArr['ordernum']){ ?><input type='text' name='ordernum' value='<?php echo input('get.ordernum'); ?>'  placeholder='订单号'  class='layui-input'><?php } ?>

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
                <th>编号</th>
<th>订单号</th>
<th>用户ID</th>
<th>创建时间</th>
<th>订单金额</th>
<th>充值金额</th>
<th>支付状态</th>
<th>流水号</th>
<th>支付时间</th>

            </tr>
            </thead>
            <tbody>
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
<th><?php echo $v['ordernum']; ?></th>
<th><?php echo getname($v['user_id']); ?></th>
<th><?php echo $v['create_time']; ?></th>
<th><?php echo $v['total']; ?></th>
<th><?php echo $v['goods_total']; ?> + <?php echo $v['give']; ?></th>

<th><?php echo $v['is_pay']==1?'已支付':'未支付'; ?></th>

<th><?php echo $v['tra_no']; ?></th>
<th><?php echo $v['pay_time']; ?></th>

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
