<?php

function select_city($params) {
    @extract($params);
    $str = '';
    $val = isset($val) ? $val : $defaultvalue;
    foreach ($items as $v) {
        $a = "";
        if ($v['checked'] == 1 || ($val == $v['val'] && $val != '')) {
            $a = "selected=''";
        }
        $str .= "<option value='{$v['val']}'  {$a}>{$v['name']}</option>" . PHP_EOL;
    }
    $col = $col ? $col : 8;
    $html = " <div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
    <label class='layui-form-label'>{$fname}</label>
    <div class='layui-input-block'>
        <div>
      <select name='{$field}' lay-filter='{$field}' >
        <option value=''>请选择</option>
        {$str}
      </select>
      </div>
      <div class='layui-word-aux mt5'>{$msg}</div>
    </div>
  </div>";
    $requestFnName = $requestFnName ? $requestFnName : 'requestFnName' . $field;
    $html.="
            <script type='text/javascript'>
 
            $(function() {
                  layui.use('form', function(){
                   var form = layui.form;
                  form.on('select({$field})', function(data){
                    var callfname='{$callfname}';
                    if(!callfname){
                        return false;
                    }
                     var val=data.value;
                     if(val){
                        eval(''+callfname+'(val)');
                     }
                     });
                })
            })
           function {$requestFnName}(val) {
                var url='{$requestUrl}';
               if(!url){
                    return false;
                }
                ajax('{$requestUrl}',{val:val},function(res) {
                    $('#contentshow_{$field}').find('select').html('');
                    
                $.each(res.data, function(key, val) {
                         var option1 = $(\"<option>\").val(val.id).text(val.name);
                       $('#contentshow_{$field}').find('select').append(option1);
                              
               }); 
                  form.render('select');
                })
                
            }
 
        </script>
    ";
    return $html;
}
