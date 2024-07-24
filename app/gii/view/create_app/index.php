<form class="layui-form"  action="<?php echo url('run'); ?>" method="post" target="iframe_crud">
    <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
        <label class='layui-form-label'>模块名</label>
        <div class='layui-input-block'>
            <div><input type='text'   name="module" placeholder="必须英文"  class='layui-input'></div>
            <div class='layui-word-aux mt5'  ></div>    
        </div>
    </div>
    <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
        <label class='layui-form-label'>中文名称</label>
        <div class='layui-input-block'>
            <div><input type='text'  name="module_name" placeholder=""  class='layui-input'></div>
            <div class='layui-word-aux mt5'  ></div>    
        </div>
    </div>
    
    <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
        <label class='layui-form-label'>是否覆盖</label>
        <div class='layui-input-block'>
            <div> <select name="is_cover" class="form-control">
                              <option value="0">不覆盖</option>
                               <option value="1">直接覆盖</option>
                          </select></div>
            <div class='layui-word-aux mt5'  >会直接覆盖现有文件，请注意安全</div>    
        </div>
    </div>
    <div class="layui-form-item">
    <div class="layui-input-block">
        <button class="layui-btn" lay-submit  id="submit"  type="submit">立即提交</button>
    </div>
  </div>
</form>
  
                <iframe id="iframe_crud" name="iframe_crud" class="iframeMain" src="" ></iframe>
                
    
        
