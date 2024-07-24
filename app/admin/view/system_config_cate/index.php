
<div class='x-body' >


     <?php if($is_add==1){ ?>     
    <xblock>
         <a class="pear-btn pear-btn-primary " href="{:url('add')}"> <i class="layui-icon layui-icon-add-1"></i> 新增</a>
    </xblock>
     <?php } ?>        
     <?php if($is_search==1){ ?>  
      <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" id="index_form" action=""  method="get">
            <input type='text' name='name' value='<?php echo input('get.name'); ?>'  placeholder='中文名称'  class='layui-input'>
<select name='status' lay-ignore  class='inline w150'>
                    <option value=''>状态</option>
                    <?php foreach($all_lan['status'] as $k=>$v){ ?>
                    <option value='{$k}' <?php if($in['status']==$k && $in['status']!='' ){ echo 'selected=""'; } ?> >{$v}</option>
                    <?php } ?>
                </select>
<select name='pid' lay-ignore  class='inline w150'>
                    <option value=''>上级</option>
                    <?php foreach($pids as $k=>$v){ ?>
                    <option value='{$k}' <?php if($in['pid']==$k && $in['pid']!='' ){ echo 'selected=""'; } ?> >{$v}</option>
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
        <table class="layui-table"  >
   
    <thead>
        <tr>
            <?php if($is_del==1){ ?>
            <th>
                 <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(':checked')); })">反选</a>
             </th>
              <?php } ?>
             <th>中文名称</th>
<th>上级</th>
<th>排序</th>
<th>状态</th>

            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php function create_page_html($v,$all_lan,$pids){ $pk='id'; ?>
        <tr class="layui-form">
                <th style=" width: 100px;"><input  class="sel_id"  type="checkbox" lay-ignore value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> 
              <th><?php $class=""; if($v['pid']!=0){ $class=" w100 ml50";} echo fast_input(array('val'=>$v['name'],'key'=>'id','keyid'=>$v['id'],'field'=>'name','class'=>$class)) ; ?></th>
<th><?php echo $pids[$v['pid']]; ?></th>
<th><?php echo fast_input(array('val'=>$v['sort'],'key'=>'id','keyid'=>$v['id'],'field'=>'sort')) ; ?></th>
<th><?php echo fast_check(array('txt'=>'正常','items'=>$all_lan['status'],'check'=>$v['status'],'key'=>'id','field'=>'status','keyid'=>$v['id'])) ; ?></th>
                <th>
                    <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('edit',array($pk=>$v[$pk]) ); ?>">编辑</a>
                    <a class="pear-btn pear-btn-danger pear-btn-sm" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                </th>
         </tr>
        <?php if($v['children']){ foreach($v['children'] as $vv){ echo create_page_html($vv,$all_lan,$pids); } }  } ?>
        
        <?php foreach($data as  $v){ ?>
         <?php create_page_html($v,$all_lan,$pids); ?>
        <?php } ?>
     
         <?php if($is_del==1){ ?>
         <tr>
             <td colspan="100">
                 <a  href="javascript://" onclick="forever_copy()"   >复制</a> | <a  href="javascript://" onclick="forever_del()"   >批量删除</a>
             </td>
         </tr>
         <?php } ?>
    </tbody>
</table>
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
