
    <div class="x-body">
        <blockquote class="layui-elem-quote">
        用户名：{$adminInfo.username} 所属角色：{$adminInfo.role_name}
        </blockquote>
        <form class="layui-form"  action='__SELF__' method='post'>
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>原密码
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="pwd" class="layui-input" required>
              </div>
             
          </div>
            
          <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>新密码
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="new_pwd" class="layui-input" required>
              </div>
             
          </div>
            
           <div class="layui-form-item">
              <label class="layui-form-label">
                  <span class="x-red">*</span>确认新密码
              </label>
              <div class="layui-input-inline">
                  <input type="text"  name="new_pwd2" class="layui-input" required>
              </div>
             
          </div> 
         
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" type="submit">
                  保存
              </button>
          </div>
      </form>
    </div>
    