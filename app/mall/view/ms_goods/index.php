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
            <?php if($searchArr['id']){ ?><input type='text' name='id' value='<?php echo input('get.id'); ?>'  placeholder='编号'  class='layui-input'><?php } ?>
<?php if($searchArr['goods_id']){ ?><input type='text' name='goods_id' value='<?php echo input('get.goods_id'); ?>'  placeholder='商品'  class='layui-input'><?php } ?>
<?php if($searchArr['num']){ ?><input type='text' name='num' value='<?php echo input('get.num'); ?>'  placeholder='库存数量'  class='layui-input'><?php } ?>
<?php if($searchArr['cate_id']){ ?><input type='text' name='cate_id' value='<?php echo input('get.cate_id'); ?>'  placeholder='分类'  class='layui-input'><?php } ?>
<?php if($searchArr['status']){ ?><?php $itms = array(array('val'=>'1','name'=>'是'),array('val'=>'0','name'=>'否'),); ?><?php  echo list_select(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>input('get.status'),'requestFnName'=>'status')); ?><?php } ?>
<?php if($searchArr['sort']){ ?><input type='text' name='sort' value='<?php echo input('get.sort'); ?>'  placeholder='排序'  class='layui-input'><?php } ?>
<?php if($searchArr['sale_num']){ ?><input type='text' name='sale_num' value='<?php echo input('get.sale_num'); ?>'  placeholder='已售数量'  class='layui-input'><?php } ?>
<?php if($searchArr['items']){ ?><input type='text' name='items' value='<?php echo input('get.items'); ?>'  placeholder='规格参数'  class='layui-input'><?php } ?>
<?php if($searchArr['min_price']){ ?><input type='text' name='min_price' value='<?php echo input('get.min_price'); ?>'  placeholder='最低价格'  class='layui-input'><?php } ?>
<?php if($searchArr['end']){ ?><?php echo list_rangedate(array('field'=>'end','fname'=>'结束时间','defaultvalue'=>input('get.end'))); ?><?php } ?>

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
<th>商品</th>
<th>数量</th>
<th>活动时间</th>
<th>状态</th>
<th>排序</th>
<th>最低价格</th>


                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
                    <th><?php echo $v['id']; ?></th>
<th>
    【{$v['goods_id']}】 <?php  echo \think\facade\Db::name('mall_goods')->where(array('goods_id'=>$v['goods_id']))->cache(true)->value('name'); ?>
    <p>{$v.end}到期</p>

</th>
<th>    <p>总：<?php echo $v['num']; ?></p>
    <p>已售<?php echo $v['sale_num']; ?></p>    </th>
<th><?php echo $v['start']; ?> ~ <?php echo $v['end']; ?></th>
<td><?php echo fast_check(array('key'=>$pk,'keyid'=>$v[$pk],'field'=>'status','url'=>url('set_val'),'txt'=>'开启','check'=>$v['status'])); ?></td>
<th><input type='text' class='layui-input w100' data-type='setval'    data-key='{$pk}'  data-keyid='<?php echo $v[$pk]; ?>'  data-field='sort'  data-url='{:url('set_val')}' value='<?php echo $v['sort']; ?>'/></th>

<th><?php echo $v['min_price']; ?></th>


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
