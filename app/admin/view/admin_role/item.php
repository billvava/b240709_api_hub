
<div class="x-body">
    <form class="layui-form"  action="{:url('item')}" method="post">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>角色名
            </label>
            <div class="layui-input-inline">
                <input type="text" name="name"  value="{$info.name}"   class="layui-input">
            </div>
        </div>
      
        
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                备注
            </label>
            <div class="layui-input-inline">
                <input type="text" name="remark"  value="{$info.remark}" class="layui-input">
            </div>
        </div>
        
       
        <input type="hidden" name="role_id" value="{$info.role_id}" />
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="pear-btn pear-btn-primary " lay-filter="add" lay-submit="">
                保存
            </button>
        </div>
    </form>
</div>
