<div class="x-body">
    <div class="layui-table-tool-temp">
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">
            <i class="layui-icon layui-icon-add-1"></i> 新增
        </a>
    </div>
    <div class="table-container">
        <table class="layui-table" >
            <thead>
                <tr>
                    <th>系统ID</th>
                    <th>角色名</th>
                    <th>备注</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php  foreach($data as $v){ ?>
                <tr>
                    <td>{$v.role_id}</td>
                    <td>
                        <input class="layui-input w200" type="text" data-type='setval' data-key='role_id' data-keyid='{$v.role_id}' data-field='name' data-url="{:url('set')}" value="{$v.name}" />
                    </td>
                    <td>
                        <input class="layui-input w200" type="text" data-type='setval' data-key='role_id' data-keyid='{$v.role_id}' data-field='remark' data-url="{:url('set')}" value="{$v.remark}" />
                    </td>
                    <td>
                        <a href="{:url('nav',array('role_id'=>$v['role_id']))}" class="pear-btn pear-btn-primary   pear-btn-sm">菜单权限</a>
                        <a href="###" class="pear-btn pear-btn-danger   pear-btn-sm" onclick="del('{:url('del',array('role_id'=>$v['role_id']))}')">删除</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>