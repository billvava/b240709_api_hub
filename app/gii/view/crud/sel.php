{__NOLAYOUT__}
<?php 
$field_type = lang('field_type');
 $max =  count($all_fields);
  foreach($all_fields as $k=>$v){ $index = $k+1; ?>
<tr>
    <td>{$v.field}</td>
    <td>{$v.name}</td>
    <td>
         <select class="form-control select_x" lay-ignore name="fieldtype_{$v.field}" ff="{$v.field}">
              <?php foreach($field_type as $f=>$t){ ?>
                  <option value="{$f}" <?php if($v['is_hide']==1 && $f=='hide_input'){echo "selected=''";} ?> <?php if($v['type']==$f){echo "selected=''";} ?> >{$t}</option>
              <?php } ?>
           </select>
    </td>
    <td>
        <input type="text"  name="fieldval_{$v.field}" value="{$v.default}" class=" form-control" />
    </td>
    <td>
        <input type="text" placeholder="下拉,单选：是=1,否=0  其他无效"  name="fieldparam_{$v.field}" class=" form-control"  value="{$v.param}" />
            <input type="hidden"  name="fieldname_{$v.field}"  value="{$v.name}" />
    </td>
</tr>
<tr  style="display: none" id="select_{$v.field}" >
    <td colspan="100">
         <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>下拉参数</label>
        </div> 
        <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>数据源</label>
            <div class='layui-input-block'>
                <div>                <input class="layui-input"  name="source_{$v.field}" class=" form-control"  placeholder="模块/模型/方法/关联数据字段('没有不用填')" /></div>
                <div class='layui-word-aux mt5'  ></div>    
            </div>
        </div>
         <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
            <label class='layui-form-label'>关联字段</label>
            <div class='layui-input-block'>
                <div> 
                <select class="form-control" name="guanlian_{$v.field}"  lay-ignore >
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
                <div>    <input type="text" placeholder="模块/控制器/方法"  name="callback_{$v.field}" class=" form-control"   /></div>
                <div class='layui-word-aux mt5'  ></div>    
            </div>
        </div>
        
    </td>
</tr>
<?php } ?>


