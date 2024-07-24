
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="<?php echo url(app()->request->action()); ?>">
            <input type="text" name="username" value="{$in.username}"  placeholder="请输入用户名"  class="layui-input">
            <input type="text" name="ip" value="{$in.ip}"  placeholder="请输入ip"  class="layui-input">
            <button class="pear-btn pear-btn-primary  pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <div class="table-container">
    <table class="layui-table" >
        <thead>
            <tr>
        <th>系统编号</th>
        <th>用户名</th>
        <th>时间</th>
        <th>IP</th>
        <th>状态</th>
        </thead>
        <tbody>
             <?php  foreach($data['list'] as $v){ ?>
            <tr>
             <td>{$v.id}</td>
            <td>{$v.username}</td>
            <td>{$v.create_time}</td>
             <td>{$v.ip}</td>
             <td <?php echo $v['is_error']==0?'':'style="color:red"'; ?>><?php echo $v['is_error']==0?'成功':'失败'; ?></td>
            </tr>
             <?php } ?>
        </tbody>
    </table>
        {$data.page|raw}
   </div>

</div>
