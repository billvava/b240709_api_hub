
<div class="x-body">
    
    <div class="layui-table-tool-temp"> 
       <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}"> <i class="layui-icon layui-icon-add-1"></i> 新增 </a>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('log')}"> <i class="layui-icon layui-icon-shrink-right"></i> 登陆日志 </a>
   </div>


    <div class="layui-row mt10">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text" name="username" value="{$in.username}"  placeholder="请输入用户名"  class="layui-input">
            <button class="pear-btn pear-btn-primary  pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <div class="table-container">
    <table class="layui-table" >
        <thead>
            <tr>
        <th>系统ID</th>
        <th>用户名</th>
        <th>角色名</th>
        <th>状态</th>
        <th>操作</th>
        </thead>
        <tbody>
             <?php  foreach($data as $v){ ?>
            <tr>
             <td>{$v.id}</td>
            <td>{$v.username}</td>
            <td>{$v.role_name}</td>
             <td><?php echo $admin_status[$v['status']]; ?></td>
                <td>
                    <a href="{:url('item',array('id'=>$v['id']))}" class="pear-btn pear-btn-primary  pear-btn pear-btn-primary -xs">编辑</a>
                    <a  onclick="del('{:url('del',array('id'=>$v['id']))}')" class="pear-btn pear-btn-primary  pear-btn pear-btn-primary -xs">删除</a>
                </td>
            </tr>
             <?php } ?>
        </tbody>
    </table>
   </div>

</div>
