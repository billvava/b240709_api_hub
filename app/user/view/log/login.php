
<div class='x-body' >


     <?php if($is_add==1){ ?>     
    <xblock>
         <a class="pear-btn pear-btn-primary" href="{:url('item')}">新增</a>
    </xblock>
     <?php } ?>        
     <?php if($is_search==1){ ?>  
     <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('login')}"  method="get" >
            <input type='text' name='id' value='<?php echo input('get.id'); ?>'  placeholder='系统编号'  class='layui-input'>
<input type='text' name='time' value='<?php echo input('get.time'); ?>'  placeholder='时间'  class='layui-input'>
<input type='text' name='ip' value='<?php echo input('get.ip'); ?>'  placeholder='IP'  class='layui-input'>
<input type='text' name='user_id' value='<?php echo input('get.user_id'); ?>'  placeholder='用户编号'  class='layui-input'>
<input type='text' name='source' value='<?php echo input('get.source'); ?>'  placeholder='来源'  class='layui-input'>

            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
            <input type="hidden" value="1" name="p" />
        </form>
    </div>
     <?php } ?>    
      <?php if($is_xls==1){ ?>  
      <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('xls')}" target="_blank"  method="get" >
            <input type='text' name='id' value='<?php echo input('get.id'); ?>'  placeholder='系统编号'  class='layui-input'>
<input type='text' name='time' value='<?php echo input('get.time'); ?>'  placeholder='时间'  class='layui-input'>
<input type='text' name='ip' value='<?php echo input('get.ip'); ?>'  placeholder='IP'  class='layui-input'>
<input type='text' name='user_id' value='<?php echo input('get.user_id'); ?>'  placeholder='用户编号'  class='layui-input'>
<input type='text' name='source' value='<?php echo input('get.source'); ?>'  placeholder='来源'  class='layui-input'>
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" >导出</button>
            <input type="hidden" value="1" name="p" />
        </form>
    </div>
    <?php } ?>
    
    <div class="table-container">
        <table class="layui-table">
   
    <thead>
        <tr>
            <?php if($is_del==1){ ?>
            <th>
                 <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(":checked")); })">反选</a>
             </th>
              <?php } ?>
             <th>系统编号</th>
<th>时间</th>
<th>IP</th>
<th>用户编号</th>
<th>来源</th>

            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        {foreach name='data.list' item='v' key='k' }
          <tr class="layui-form">
                <?php if($is_del==1){ ?><th style=" width: 100px;"><input type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
              <th><?php echo $v['id']; ?></th>
<th><?php echo $v['time']; ?></th>
<th><?php echo $v['ip']; ?></th>
<th><?php echo $v['user_id']; ?></th>
<th><?php echo $v['source']; ?></th>

                <th>
                     <?php if($is_edit==1){ ?>
                    <a  class="pear-btn pear-btn-primary" href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>">编辑</a>
                     <?php } ?>
                      <?php if($is_del==1){ ?>
                    <a class="pear-btn pear-btn-primary" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                     <?php } ?>
                </th>
            </tr>     
         {/foreach}
         <?php if($is_del==1){ ?>
         <tr>
             <td colspan="100">
                 <a  href="javascript://" onclick="forever_del()"    class="pear-btn pear-btn-primary ">删除</a>
             </td>
         </tr>
         <?php } ?>
    </tbody>
</table>
              {$data.page|raw}
    </div>          
</div>
<script type="text/javascript">
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
    $("input:checkbox:checked").each(function() {
        ids.push($(this).val());
    });
    if(ids.length <= 0){
        
        return null;
    }else{
        return ids;
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
