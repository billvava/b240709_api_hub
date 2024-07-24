
<div class='x-body' >


     <?php if($is_add==1){ ?>     
    <xblock>
         <a class="pear-btn pear-btn-primary " href="{:url('add')}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>
    </xblock>
     <?php } ?>        
     <?php if($is_search==1){ ?>  
      <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" id="index_form" action=""  method="get">
            <input type='text' name='sn' value='<?php echo input('get.sn'); ?>'  placeholder='退款单号'  class='layui-input'>
            <input type='text' name='username' value='<?php echo input('get.username'); ?>'  placeholder='用户'  class='layui-input'>
<!--            <select name='user_id' id='user_id_select2' lay-ignore  class='inline w150'>-->
<!--                    <option value=''>用户</option>-->
<!--                    --><?php //if($in['user_id']){ ?>
<!--                    <option value='{$in['user_id']}' selected=''>--><?php //echo getname($in['user_id']); ?><!--</option>-->
<!--                    --><?php //} ?>
<!--                </select>-->
<!--<input type='text' name='order_id' value='<?php echo input('get.order_id'); ?>'  placeholder='订单id'  class='layui-input'>
<select name='refund_type' lay-ignore  class='inline w150'>
                    <option value=''>退款类型</option>
                    <?php foreach($all_lan['refund_type'] as $k=>$v){ ?>
                    <option value='{$k}' <?php if($in['refund_type']==$k && $in['status']!='' ){ echo 'selected=""'; } ?> >{$v}</option>
                    <?php } ?>
                </select>-->
<select name='status' lay-ignore  class='inline w150'>
                    <option value=''>售后状态</option>
                    <?php foreach($all_lan['status'] as $k=>$v){ ?>
                    <option value='{$k}' <?php if($in['status']==$k && $in['status']!='' ){ echo 'selected=""'; } ?> >{$v}</option>
                    <?php } ?>
                </select>
<select name='del' lay-ignore  class='inline w150'>
                    <option value=''>撤销状态</option>
                    <?php foreach($all_lan['del'] as $k=>$v){ ?>
                    <option value='{$k}' <?php if($in['del']==$k && $in['status']!='' ){ echo 'selected=""'; } ?> >{$v}</option>
                    <?php } ?>
                </select>

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
             <th>退款单号</th>
<th>用户</th>
<th>退款原因</th>
<th>退款图片</th>
<th>退款类型</th>
<th>退款金额</th>
<th>快递</th>
<th>时间</th>
<th>撤销状态</th>
<th>售后状态</th>

            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php $color = lang('color'); ?>
        {foreach name='data.list' item='v' key='k' }
          <tr class="layui-form">
                <?php if($is_del==1){ ?><th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
              <th><?php echo $v['sn']; ?></th>
              <th><?php echo getname($v['user_id']); ?></th>
<th>
    <p><?php echo $v['refund_reason']; ?></p>
<p><?php echo $v['refund_remark']; ?></p>

</th>
<th><?php echo echo_img($v['refund_image']); ?></th>
<th><?php echo $all_lan['refund_type'][$v['refund_type']]; ?></th>
<th><?php echo $v['refund_price']; ?></th>
<th>
    <p>公司名称:<?php echo $v['express_name']; ?></p>
<p>快递单号:<?php echo $v['invoice_no']; ?></p>
<p>备注说明:<?php echo $v['express_remark']; ?></p>
<p>凭证:<?php echo $v['express_image']; ?></p>
<p>确认时间:<?php echo $v['confirm_take_time']; ?></p>
</th>
<th><p>创建：<?php echo $v['create_time']; ?></p>
<p>更新：<?php echo $v['update_time']; ?></p>
<p>更新：<?php echo $v['update_time']; ?></p>
<p>审核：<?php echo $v['audit_time']; ?></p>

</th>
<th style="<?php echo $color[$v['del']]; ?>"><?php echo $all_lan['del'][$v['del']]; ?></th>
<th style="<?php echo $color[$v['status']]; ?>"><?php echo $all_lan['status'][$v['status']]; ?></th>

                <th>
                    <a  class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="show_url('<?php echo url('edit',array('id'=>$v['id'])); ?>')">售后详情</a> 
                </th>
            </tr>     
         {/foreach}
       
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
