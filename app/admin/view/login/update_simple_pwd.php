
<div class="x-body">
    <blockquote class="layui-elem-quote">
        您当前密码太过简单，请尽快修改 <a class="x-a" href="{:url('Index/index')}">暂时忽略</a>
    </blockquote>
    <form class="layui-form"  action="__SELF__" method="post">
       
        
        <div class="layui-form-item">
            <label for="pwd" class="layui-form-label">
                新密码
            </label>
            <div class="layui-input-inline">
                <div><input type="text" id="pwd"  name="pwd"   class="layui-input"></div>
                
            </div>
        </div>
      
        
        <input type="hidden" name="id" value="{$info.id}" />
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                保存
            </button>
        </div>
    </form>
</div>
