
<div class="x-body">
    <form class="layui-form"  action="{:url('item')}" method="post">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>用户名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username"  name="username"  value="{$info.username}"  class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>角色</label>
            <div class="layui-input-block">
                 {foreach name='roles' item='v' key='k'}
                 <input type="radio" name="role_id" lay-skin="primary" title="{$v.name}" value="{$v.role_id}"   <?php if ($info['role_id'] == $v['role_id'] && $info) {  echo 'checked=""'; } ?>>
                {/foreach}
            </div>
        </div>
        
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                密码
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username"  placeholder="不修改密码则留空" name="pwd"   class="layui-input">
            </div>
        </div>
        
         <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>状态</label>
            <div class="layui-input-block">
                {foreach name='admin_status' item='v' key='k'}
                 <input type="radio" name="status" lay-skin="primary" title="{$v}" value="{$k}"   <?php if ( ($info['status'] == $k && $info ) || (!$info && $k==1) ) {  echo 'checked=""'; } ?> >
                {/foreach}
            </div>
        </div>
        
        
        
        <input type="hidden" name="id" value="{$info.id}" />
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="pear-btn pear-btn-primary " lay-filter="add" lay-submit="">
                保存
            </button>
        </div>
    </form>
</div>
