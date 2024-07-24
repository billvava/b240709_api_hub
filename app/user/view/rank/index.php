<div class="x-body">
    {include file="rank/top" /}
     <div class='table-container'>
       
<table class="layui-table ">
    <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>折扣</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
       
        {foreach name="data.list" item="v"}
            <tr>
               <td>{$v.id}</td>
               <td>
                   <input type="text" class="w100 layui-input" data-type='setval'    data-key='id'  data-keyid='<?php echo $v['id']; ?>'  data-field='name'  data-url="{:url('set_val')}" value="{$v.name}"/></td>
               <td>
                   <div class="layui-progress" lay-showpercent="true">
                    <div class="layui-progress-bar" lay-percent="<?php echo $v['discount']; ?>%"></div>
                  </div>
                   
                   
               </td>
               <td>
                    <a class="pear-btn pear-btn-primary " href="{:url('item',array('id'=>$v['id']))}">编辑</a>
                   <a class="pear-btn pear-btn-primary layui-btn-danger " onclick="del('{:url('del',array('id'=>$v['id']))}')" href="javascript://"><i class="icon-times"></i> 删除</a>
               </td>
           </tr>
       {/foreach}
       
        
    </tbody>
</table>
      
    </div>
     {$data.page|raw}
</div>