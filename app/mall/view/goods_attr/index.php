

<div class='x-body'  >
     <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">新增</a>
    </xblock>
   
    
  <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text" name="attr_id" value="{$in.attr_id}"  placeholder="系统编号"  class="layui-input">
            <input type="text" name="name" value="{$in.name}"  placeholder="名称"  class="layui-input">
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    
    

    
    <div class='table-container'>
        
        <table class="layui-table ">
   
    <thead>
        <tr>
           
             <th>系统编号</th>
<th>属性组名称</th>

            <th>操作</th>
        </tr>
    </thead>
    <tbody>
         <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
        <?php foreach($data['list'] as $k=>$v){ ?>
          <tr>
              <th><?php echo $v['attr_id']; ?></th>
<th><input type="text" class="layui-input w100" data-type='setval'  data-key='attr_id'  data-keyid='{$v.attr_id}' data-field='name'   data-url="{:url('set')}" value="{$v.name}" /></th>
<th><a href="<?php echo url('attr_manage',array($pk=>$v[$pk]) ); ?>" class="pear-btn pear-btn-primary pear-btn-sm ">属性管理</a>
    <a href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>"  class="pear-btn pear-btn-primary pear-btn-sm " onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                </th>
            </tr>     
        <?php } ?>
         
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
