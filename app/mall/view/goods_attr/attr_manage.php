
<div class='x-body' >

    <blockquote class="layui-elem-quote">
        {$name} - {$title}
    </blockquote>
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回列表</a>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('attr_show',array('attr_id'=>$info['attr_id']))}">新增属性</a>
    </xblock>
   
    <div class='table-container'>
        
        <table class="layui-table ">
   
    <thead>
        <tr>
             <th>
                 <a class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                <a class="x-a"  href="javascript://"  onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</a>
            </th>
             <th>属性</th>
              <th>提示</th>
              <th>默认值</th>
             <th>类型</th>
           
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
         <form id="del_form" action="<?php echo url('attr_del'); ?>" method="post" onsubmit="return check()">
        {foreach name='data' item='v' key='k' }
          <tr>
              <th class="w100"><input type="checkbox" value="<?php echo $v['field_id']; ?>" name="field_id[]"/></th>
              <th><input type="text" class="layui-input w100" data-type='setval'  data-key='<?php echo 'field_id'; ?>'  data-keyid='<?php echo $v['field_id']; ?>' data-field='name'   data-url="{:url('set_attr')}" value="{$v.name}" /></th>
              <th><input type="text" class="layui-input w100" data-type='setval'  data-key='<?php echo 'field_id'; ?>'  data-keyid='<?php echo $v['field_id']; ?>' data-field='remark'   data-url="{:url('set_attr')}" value="{$v.remark}" /></th>
              <th><input type="text" class="layui-input w100" data-type='setval'  data-key='<?php echo 'field_id'; ?>'  data-keyid='<?php echo $v['field_id']; ?>' data-field='default'   data-url="{:url('set_attr')}" value="{$v.default}" /></th>
            <th><?php echo $types[$v['type']]; ?></th>
            <th><a href="<?php echo url('attr_del',array('field_id'=>$v['field_id']) ); ?>" class="pear-btn pear-btn-primary pear-btn-sm" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                </th>
            </tr>     
         {/foreach}
         <tr>
             <td>
                 <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm">删除</button>
             </td>
         </tr>
         </form>
    </tbody>
</table>
              {$data.page|raw}
    </div>
</div>
<script type="text/javascript">

function check(){
    var r=window.confirm("确定删除吗？");
    return r;
}
</script>
