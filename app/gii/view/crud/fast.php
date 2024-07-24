{__NOLAYOUT__}
  <?php
    $edit_type = lang('edit_type');
    $search_type=lang('search_type');
    $max =  count($all_fields);
    foreach($all_fields as $k=>$v){ $index = $k+1; ?>

 <tr>
    <td>{$v.field}</td>
    <td>{$v.name}</td>
    <td>
        <select class="form-control" name="showfield_{$v.field}"  lay-ignore >
                <?php foreach($edit_type as $f=>$t){ ?>
                    <option value="{$f}" <?php if($f==$v['edit_type']){ echo 'selected="selected"'; } ?> >{$t}</option>
                <?php } ?>
            </select>
    </td>
    <td class="w100">
        <select class="form-control"  lay-ignore name="searchstatus_{$v.field}">
                <option value="0" <?php if(0==$v['search_status']){ echo 'selected="selected"'; } ?>  >否</option>
                <option value="1" <?php if(1==$v['search_status']){ echo 'selected="selected"'; } ?>  >是</option>
        </select>
    </td>
     <td>
        <select class="form-control searchtype"  lay-ignore name="searchtype_{$v.field}" ff="{$v.field}">
                <?php foreach($search_type as $f=>$t){ ?>
                    <option value="{$f}" <?php if($f==$v['search_type']){ echo 'selected="selected"'; } ?> >{$t}</option>
                <?php } ?>
            </select>
    </td>
</tr>
 <tr  style="display: none" id="searchbox_{$v.field}" >
    <td colspan="100"class="searchbox_">
           <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>下拉参数</label>
        </div> 
          <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>手动参数</label>
            <div class='layui-input-block'>
                <div> <input class="form-control" name="searchparam_{$v.field}"  placeholder="是=1,否=0 ,和数据源二选一填写" value="{$v.param}" /></div>
                <div class='layui-word-aux mt5'  ></div>    
            </div>
        </div>
          <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>数据源</label>
            <div class='layui-input-block'>
                <div> <input class="form-control" name="searchsource_{$v.field}"  placeholder="模块/模型/方法/关联数据字段('没有不用填')" /></div>
                <div class='layui-word-aux mt5'  ></div>    
            </div>
        </div>
        <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>关联字段</label>
            <div class='layui-input-block'>
                <div>
                    <select class="form-control"   lay-ignore name="searchguanlian_{$v.field}" >
                    <option value=""  >不关联</option>
                    <?php foreach($all_fields as $gl_k=>$gl_v){ ?>
                        <option value="{$gl_v.field}"  >{$gl_v.name}</option>
                    <?php } ?>
                </select>
                </div>
                <div class='layui-word-aux mt5'  ></div>    
            </div>
        </div>
         <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>回调地址</label>
            <div class='layui-input-block'>
                <div>
                    <input type="text" placeholder="模块/控制器/方法"  name="searchcallback_{$v.field}" class="form-control" />
                </div>
                <div class='layui-word-aux mt5'  ></div>    
            </div>
        </div>
         
    </td>
</tr> 
<?php } ?>
       