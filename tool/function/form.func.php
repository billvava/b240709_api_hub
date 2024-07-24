<?php

/**
 * 新上传
 *
 * @param   [type]  $params  [$params description]
 *
 * @return  [type]           [return description]
 */
function photo($params) {
    @extract($params);
    $val = isset($val) ? $val : $defaultvalue;
    // $valArr = json_decode($val, true);
    $select_num = $select_num ? $select_num : 1;
    $valHtml = '';
    if (!is_array($val)) {
        $val = array($val);
    }
    foreach ($val as $v) {
        if (!$v) {
            continue;
        }
        $suffix =  strrchr($v, '.');
        $show_img = get_img_url($v);
        if($suffix=='.mp4'){
            $show_img= get_img_url($v,'video_thumb');
        }
        $valHtml .= '<div   ondblclick="$(this).remove();" onmouseover="mouseover(this)" onmouseout="mouseout(this)" class="img_item_box" style="position: relative;overflow:hidden; width: 100px; height: 100px; float: left;  margin: 0 15px 15px 0;">';
        $valHtml .= "<div class='imgMark' data-field='{$field}'>双击删除</div>";
        $valHtml .= "<a class='imgBoxA' href='javascript:;'><img src='" . $show_img . "'  style='width:100px;height:100px;'></a>";
        $valHtml .= "<input type='hidden' value='{$v}' name='{$field}";
        if ($select_num > 1) {
            $valHtml .= "[]";
        }
        $valHtml .= "' {$required} class='layui-input'>";
        $valHtml .= '</div>';
    }
    $col = $col ? $col : 8;
    $html = "<script>
        function mouseover (obj) {
            $(obj).find('.imgMark').show();
        }
        function mouseout (obj) {
            $(obj).find('.imgMark').hide();
        }
    </script>";
    $html .= " <div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block' id='{$field}'>";

    $html .= "<div   class='layui-word-aux mt5'>{$msg}</div>    
                    <div  id='{$field}_progress' class='layui-word-aux mt5'></div>    
                    <div style='margin-top:5px'>
                    <style>
                    .imgBoxA{float: left; text-align: center;}
                    .imgMark{position: absolute;left:0;top:0; width: 100%; height: 100%; z-index:2; background: black; opacity: 0.8;line-height: 100px;text-align:center;color: white;cursor: pointer; display: none;}
                    </style>
                    <div id='{$field}_imgbox'>";

    $html .= $valHtml;

    $html .= "</div>
                    <a id='{$field}_uploadBtn' class='imgBoxA' href='javascript://' style='border: 1px dashed #a0a0a0; width: 100px; height: 100px; display:inline-block' onclick=\"new_img('{$fname}', '{$field}', '{$select_num}','{$callback_rela}','{$filename}')\"><img src='/static/admin/images/upload.png'  style='width:38px;height:35px;display: block; margin: 23px auto 5px;'> +添加图片</a> 
                    </div>
                </div>
            </div>";
    $html .= "<script> $('#{$field}_imgbox').sortable({selector: '.img_item_box'}) ;</script>";


    return $html;
}

/**
 * @name 动态生成FORM的表单
 */
function checkbox($params) {
    @extract($params);
    $str = '';
    $val = isset($val) ? $val : $defaultvalue;
    if (!$val) {
        $val = [];
    }
    foreach ($items as $k=>$v) {
        $a = "";
        if (!isset($v['val'])) {
            if ((in_array($k,$val) && $val !== '')) {
                $a = "checked=''";
            }
            $str .= "<input type='checkbox' name='{$field}[]' value='{$k}'  lay-filter='{$field}' title='{$v}' {$a}>" . PHP_EOL;
        } else {
            if ($v['checked'] == 1 || (in_array($v['val'],$val))) {
                $a = "checked=''";
            }
            $str .= "<input type='checkbox' name='{$field}[]' value='{$v['val']}'  lay-filter='{$field}' title='{$v['name']}' {$a}>" . PHP_EOL;
        }

    }
    $html = " <div class='layui-form-item layui-col-xs10 layui-col-md8' pane='' ' id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block'>
                  <div>{$str}</div>
                  <div class='layui-word-aux mt5'>{$msg}</div>        
                </div>
              </div>";

    return $html;
}

function radio($params) {
    @extract($params);
    $str = '';
    $val = isset($val) ? $val : $defaultvalue;
    foreach ($items as $k => $v) {
        $a = "";
        if (!isset($v['val'])) {
            if (($val == $k && $val !== '')) {
                $a = "checked=''";
            }
            $str .= "<input type='radio' name='{$field}' value='{$k}'  lay-filter='{$field}' title='{$v}' {$a}>" . PHP_EOL;
        } else {
            if ($v['checked'] == 1 || ($val == $v['val'] && $val !== '')) {
                $a = "checked=''";
            }
            $str .= "<input type='radio' name='{$field}' value='{$v['val']}'  lay-filter='{$field}' title='{$v['name']}' {$a}>" . PHP_EOL;
        }
    }
    $col = $col ? $col : 8;
    $html = " <div class='layui-form-item layui-col-xs10 layui-col-md{$col}' id='contentshow_{$field}'>
            <label class='layui-form-label'>{$fname}</label>
            <div class='layui-input-block'>
                  <div>{$str}</div>
                  <div class='layui-word-aux mt5'>{$msg}</div>       
            </div>
        </div>";

    return $html;
}
function new_list_select($params){
    @extract($params);
    $str = '';
    $val = isset($val) ? $val : $defaultvalue;
    foreach ($items ?? [] as $k => $v) {
        $a = "";
        if (!isset($v['val'])) {
            if (($val == $k && $val != '')) {
                $a = "selected=''";
            }
            $str .= "<option value='{$k}'  lay-filter='{$field}' title='{$v}' {$a}>{$v}</option>" . PHP_EOL;
        } else {
            if ((isset($v['checked']) && $v['checked'] == 1) || ($val == $v['val'] && $val !== '')) {
                $a = "selected=''";
            }
            $str .= "<option  value='{$v['val']}'  lay-filter='{$field}' title='{$v['name']}' {$a}>{$v['name']}</option>" . PHP_EOL;
        }
    }
    $col = $col ? $col : 8;

    if ($is_search) {
        $is_search = "lay-search";
    }
    $html = "
            <div class='layui-inline'  id='contentshow_{$field}'>
            <label class='layui-form-label'>{$fname}</label>
            <div class='layui-input-inline'>
                <select  name='{$field}' lay-filter='{$field}' {$is_search}>
                     <option value=''>{$fname}</option>
                      {$str}
                </select>
            </div>
            </div>";

    $requestFnName = $requestFnName ? $requestFnName : 'requestFnName' . $field;
    $html .= "
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
                    $('#contentshow_{$field}').find('select').html('<option value=\"\">请选择</option>');
                    $.each(res.data, function(key, val) {
                        var option1 = $(\"<option>\").val(val.id).text(val.name);
                        $('#contentshow_{$field}').find('select').append(option1);
                         form.render('select');
                    })
                }) 
            }
        </script>
    ";
    return $html;

}
//筛选表单
function list_select($params) {
    @extract($params);
    $str = '';
    $val = isset($val) ? $val : $defaultvalue;
    foreach ($items ?? [] as $k => $v) {
        $a = "";
        if (!isset($v['val'])) {
            if (($val == $k && $val != '')) {
                $a = "selected=''";
            }
            $str .= "<option value='{$k}'  lay-filter='{$field}' title='{$v}' {$a}>{$v}</option>" . PHP_EOL;
        } else {
            if ((isset($v['checked']) && $v['checked'] == 1) || ($val == $v['val'] && $val !== '')) {
                $a = "selected=''";
            }
            $str .= "<option  value='{$v['val']}'  lay-filter='{$field}' title='{$v['name']}' {$a}>{$v['name']}</option>" . PHP_EOL;
        }
    }
    $col = $col ? $col : 8;

    if ($is_search) {
        $is_search = "lay-search";
    }
    $html = "
                <div class='layui-input-inline' id='contentshow_{$field}' >
                <select  name='{$field}' lay-filter='{$field}' {$is_search}>
                    <option value=''>{$fname}</option>
                     {$str}
                </select>
            </div>
    
    ";

    $requestFnName = $requestFnName ? $requestFnName : 'requestFnName' . $field;
    $html .= "
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
                    $('#contentshow_{$field}').find('select').html('<option value=\"\">请选择</option>');
                    
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

/**
 * 下拉框
 * @param type $params
 * @return type
 */
function select($params) {
    @extract($params);
    $str = '';
    $val = isset($val) ? $val : $defaultvalue;
    $items = $items ?: [];
    foreach ($items as $k => $v) {
        $a = "";
        if (!isset($v['val'])) {
            if (($val == $k && $val !== '')) {
                $a = "selected=''";
            }
            $str .= "<option value='{$k}'  lay-filter='{$field}' title='{$v}' {$a}>{$v}</option>" . PHP_EOL;
        } else {
            if ((isset($v['checked']) && $v['checked'] == 1) || ($val == $v['val'] && $val !== '')) {
                $a = "selected=''";
            }
            $str .= "<option  value='{$v['val']}'  lay-filter='{$field}' title='{$v['name']}' {$a}>{$v['name']}</option>" . PHP_EOL;
        }
    }
    $col = $col ? $col : 8;
    if ($is_search) {
        $is_search = "lay-search";
    }

    $html = " <div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
    <label class='layui-form-label'>{$fname}</label>
    <div class='layui-input-block'>
        <div>
      <select name='{$field}' lay-filter='{$field}'  {$is_search} >
        <option value=''>请选择</option>
        {$str}
      </select>
      </div>
      <div class='layui-word-aux mt5'>{$msg}</div>
    </div>
  </div>";
    $requestFnName = $requestFnName ? $requestFnName : 'requestFnName' . $field;
    $html .= "
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
                    $('#contentshow_{$field}').find('select').html('<option value=\"\">请选择</option>');
                    
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

function editor($params) {

    $editor_type = C('editor_type');
    if ($editor_type == 'kindeditor') {
        return kindeditor($params);
    }
    if ($editor_type == 'neditor') {
        return neditor($params);
    }
}

function kindeditor($params) {
    @extract($params);
    $fileManagerUrl = url(ADMIN_URL . '/Kindeditor/file_manager');
    $token = xf_md5(C('siteurl'));
    $fileUploadUrl = url(ADMIN_URL . '/Kindeditor/upload', array('token' => $token));
    $admin_id = session('admin.id');
    $root = __ROOT__;
    $admin_username = session('admin.username');
    //multiimage
    $col = $col ? $col : 8;
    $html = "<div class='layui-form-item  layui-col-xs10 layui-col-md{$col} '  id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block clearfix'>
                    <div><textarea name='{$field}'  id='{$field}' class=\"\" style=\"width:100%;height:400px;\">{$defaultvalue}</textarea></div>
                    <div class='layui-word-aux'>{$msg}</div>   
                </div>
            </div>";
    $html .= "<script type='text/javascript'>
            var editor{$field};
            KindEditor.ready(function(K) {
                    editor{$field} = K.create('textarea[name=\"{$field}\"]', {
                            allowFileManager : true,
                            fileManagerUrl:'{$fileManagerUrl}',
                            fileUploadUrl:'{$fileUploadUrl}',
                            items : [
                                'source','paragraphindent','plainpaste','selectall','undo','redo', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor','formatblock', 'bold', 'italic', 'underline',
                                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist',
                                'insertunorderedlist', 'lineheight', 'strikethrough','underline','clearhtml', '|', 'image','multiimage','flash','media','insertfile', 'link','unlink','table','fullscreen']
                    });
    
            });
            </script>";
    return $html;
}

function neditor($params) {
    @extract($params);
    //multiimage
    $col = $col ? $col : 8;
    $html = "<div class='layui-form-item  layui-col-xs10 layui-col-md{$col} '  id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block clearfix'>
                    <div><textarea name='{$field}'  id='{$field}' class=\"\" style=\"width:100%;height:400px;\">{$defaultvalue}</textarea></div>
                    <div class='layui-word-aux'>{$msg}</div>   
                </div>
            </div>";
    $html .= "<script type='text/javascript'>
            var ue_{$field} = UE.getEditor('{$field}');
            </script>";
    return $html;
}

/**
 * 单文件上传
 * @param type $params
 * @return type
 */
function ffile($params) {
    @extract($params);
    $url = url(ADMIN_URL . '/Upload/upload_file', array('field' => $field, 'catid' => $_GET['catid']));
    $root = __ROOT__;
    $col = $col ? $col : 8;
    $html = "
        
        <div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                    <label class='layui-form-label'>{$fname}</label>
                    <div class='layui-input-block' id='{$field}'>
                        <div><input type='text'  value='{$defaultvalue}'   name='{$field}'  {$required} class='layui-input'></div>
                        <div  id='progress{$field}' class='layui-word-aux mt5'>{$msg} <a href='javascript://' id='upload_{$field}'>上传</a></div>    
                    </div>
                    
                </div>
           
             

            <script type='text/javascript'>
            
                layui.use('upload', function(){
                  var upload = layui.upload;
                  //执行实例
                  var {$field}  = upload.render({
                    elem: '#upload_{$field}' //绑定元素
                    ,url: '{$url}' //上传接口
                    ,accept:'file'
                    ,exts:'zip|rar|doc|docx|xls|xlsx|ppt|pptx|txt|pdf'
                    ,done: function(res){
                      //上传完毕回调
                      if(res.status=='0'){
                            layer.alert(res.info);
                        }else{
                            $('#{$field} input').val(res.data.file);
                        }
                      
                    }
                    ,error: function(){
                      //请求异常回调
                         layer.alert('上传失败!');
                    }
                  });
                });
            
            </script>";
    return $html;
}

/**
 * 多图片
 * @param type $params
 * @return type
 */
function images($params) {
    @extract($params);
    $catid = $_GET['catid'] + 0;
    $col = $col ? $col : 8;
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                    <label class='layui-form-label'>{$fname}</label>
                    <div class='layui-input-block' id='{$field}'>
                        <div>{$val}</div>
                        <div class='layui-word-aux mt5'>{$msg}</div>        
                    </div>
                </div>
            <div class='layui-col-xs2'>
                <a  class='pear-btn pear-btn-primary ml5' href='javascript://'  onclick=\"upload_duo_img('{$field}','{$catid}')\">上传</a>
                <a class='pear-btn pear-btn-primary'  href='javascript://' onclick=\"show_img('{$field}',0)\">站内</a>
               </div>
            <script  type='text/javascript'>
            $('#{$field}').sortable({selector: '.img_item'})
            </script>";
    return $html;
}

/**
 * 短输入框
 * @param type $params
 * @return type
 */
function input_small($params) {
    @extract($params);
    $val = isset($val) ? $val : $defaultvalue;
    $html = "  <div class='layui-inline'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-inline'>
                      <input type='text' name='{$field}'  value='{$val}'  {$required}  autocomplete='off' class='layui-input'>
                </div>
                <div class='layui-word-aux mt5'>{$msg}</div>
              </div>";

    return $html;
}

/**
 * 输入框
 * @param type $params
 * @return type
 */
function form_input($params) {
    @extract($params);
    $val = isset($val) ? $val : $defaultvalue;
    $col = $col ? $col : 8;

    $unit_str = '';
    if (isset($unit) && $unit) {
        $required = $required . "style='width:90%;  display: inline-block;'";
        $unit_str = "<input value='{$unit}' type='button' style='
    display: inline-block;
    width: 10%;
    background: #f2f2f2;
    text-align: center;
    border-radius: unset;
    padding: 0;
' class='layui-input'>";
    }


    $html = " <div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block'>
                    <div><input type='text'  value='{$val}'   name='{$field}'  {$required} class='layui-input'>{$unit_str}</div>
                    <div class='layui-word-aux mt5'>{$msg}</div>    
                </div>
            </div>";

    return $html;
}

/**
 * 选择经纬度
 * @param type $params
 * @return type
  sel_dot([
  'col'=>8,
  'lat_name'=>'lat',
  'lng_name'=>'lng',
  'lat_val'=>$info['lat'],
  'lng_val'=>$info['lng'],
  'msg'=>'请选择',
  'other'=>'',
  ]);
 */
function sel_dot($map) {
    $col = $map['col'] ? $map['col'] : 8;
    $lat = $map['lat_name'] ? $map['lat_name'] : 'lat';
    $lng = $map['lng_name'] ? $map['lng_name'] : 'lng';
    $ad = url(ADMIN_URL . '/Index/sel_dot?field='.$lat);
    $lat_val = $map['lat_val'] ? $map['lat_val'] : '';
    $lng_val = $map['lng_val'] ? $map['lng_val'] : '';
    $name = $map['name'];
    $other = $map['other'] . '';
    $msg = $map['msg'] . '';
    $html = " <div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'>
                <label class='layui-form-label'>{$name}</label>
                <div class='layui-input-block'>
                    <div><input type='text'  placeholder='经度' value='{$lng_val}' readonly  name='{$lng}'  {$other} class='layui-input w200 inline'> - "
        . "<input type='text'   placeholder='纬度'   value='{$lat_val}' readonly  name='{$lat}'  {$other} class='layui-input w200 inline'></div>
                    <div class='layui-word-aux mt5'>{$msg}</div>    
                </div>
            </div>
            <script type='text/javascript'>
            
            if(typeof(sel_dot)=='undefined'){
                
                
                function sel_dot_{$lat}(lat,lng){
                    $('input[name={$lat}]').val(lat);
                    $('input[name={$lng}]').val(lng);
               }
               $(function(){
                    $('input[name={$lat}],input[name={$lng}]').click(function(){
                        show_url('{$ad}');
                    })
                })
            }
             
            </script>";

    return $html;
}


/*
 * 用户选择源
 */

function select_data_user($map) {
    return select_data([
        'col' => 4,
        'url' => url('user/Index/getuser'),
        'field' => $map['field'],
        'name' => $map['name'],
        'other' => $map['other'],
        'msg' => $map['msg'],
        'val' => $map['val'],
        'val_name' => $map['val'] ? getname($map['val']) : '',
    ]);
}

/*
 * 商品选择源
 */

function select_data_goods($map) {
    return select_data([
        'col' => 4,
        'url' => url('mall/Goods/getgoods'),
        'field' => $map['field'],
        'name' => $map['name'],
        'other' => $map['other'],
        'msg' => $map['msg'],
        'val' => $map['val'],
        'val_name' => $map['val'] ? get_goods_name($map['val']) : '',
    ]);
}

/*
 * 选择源
 */

function select_data($map) {
    $col = $map['col'] ? $map['col'] : 8;
    $url = $map['url'] . '';
    $field = $map['field'];
    $name = $map['name'];
    $other = $map['other'] . '';
    $msg = $map['msg'] . '';
    $opval = "";
    if ($map['val']) {
        $opval = "<option value='{$map['val']}' selected=''>{$map['val_name']}</option>";
    }
    $callback = $map['callback'] ? $map['callback'] . '(repo);':'';
    $html = "<div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'>
                <label class='layui-form-label'>{$name}</label>
                <div class='layui-input-block'>
                      <div>
                            <select name='{$field}' id='select2_{$field}' lay-ignore  class='select2 inline ' {$other}>
                                    <option value=''>请选择</option>
                                    {$opval}
                            </select>
                      </div>  
                      <div class='layui-word-aux mt5'>{$msg}</div>  
                </div>
            </div>
            <script type='text/javascript'>
                $(function(){
                    $('#select2_{$field}').select2({
                        language: 'zh-CN',
                        ajax: {
                            url: '{$url}',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    key: params.term,
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        },
                        escapeMarkup: function (markup) { return markup; },
                        minimumInputLength: 1,
                        templateResult: function (repo){return repo.text},
                        templateSelection: function (repo){
                            {$callback}
                            return repo.text;
                        }
                    });

                })
            </script>";
    return $html;
}

/**
 * 选择地区
  echo  select_areas([
  'col'=>4,
  'province_name'=>'province',
  'city_name'=>'city',
  'country_name'=>'country',
  'province_val'=>$info['province'],
  'city_val'=>$info['city'],
  'country_val'=>$info['country'],
  'name'=>'地区',
  ]);
 */
function select_areas($map) {
    $col = $map['col'] ? $map['col'] : 8;
    $name = $map['name'];
    $msg = $map['msg'] . '';
    $province_name = $map['province_name'];
    $city_name = $map['city_name'];
    $country_name = $map['country_name'];
    $province_val = $map['province_val'];
    $city_val = $map['city_val'];
    $country_val = $map['country_val'];
    $ho = new app\home\model\O();
    $province_op = $ho->get_shengfen();
    $province_html = "";
    $city_html = "";
    $country_html = "";
    foreach ($province_op as $v) {
        $s = '';
        if ($province_val == $v['id']) {
            $s = "selected='selected'";
        }
        $province_html .= " <option value='{$v['id']}' {$s} >{$v['name']}</option> " . PHP_EOL;
    }
    if ($province_val > 0) {
        $qy = $ho->get_quyu($province_val);
        if ($qy) {
            foreach ($qy as $v) {
                $s = '';
                if ($city_val == $v['id']) {
                    $s = "selected='selected'";
                }
                $city_html .= " <option value='{$v['id']}' {$s} >{$v['name']}</option> " . PHP_EOL;
            }
        }

        if ($city_val > 0) {
            $qy = $ho->get_quyu($city_val);
            if ($qy) {
                foreach ($qy as $v) {
                    $s = '';
                    if ($country_val == $v['id']) {
                        $s = "selected='selected'";
                    }
                    $country_html .= " <option value='{$v['id']}' {$s} >{$v['name']}</option> " . PHP_EOL;
                }
            }
        }
    }
    $rand = uniqid();
    if($province_name){
        $province_name = $province_name;
    }
     if($city_name){
        $city_name = $city_name;
    }
     if($country_name){
        $country_name = $country_name;
    }
    $js_func = "chang_area{$rand}";
    $get_url = url('home/ajax/ajax_address');
    $fj_html = "";
    if ($city_name) {
        $fj_html .= "- 
            <select name='{$city_name}' id='{$city_name}' lay-ignore   onchange=\"{$js_func}('{$city_name}','{$country_name}')\" class='select2 inline w100 {$province_name}' >
                    <option value=''>请选择</option>
                    {$city_html}
            </select> ";
        if ($country_name) {
            $fj_html .= "- 
            <select name='{$country_name}' id='{$country_name}' lay-ignore  class='select2 inline w100 {$province_name}' >
                    <option value=''>请选择</option>
                    {$country_html}
            </select>";
        }
    }
    $html = "
<div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'>
    <label class='layui-form-label'>{$name}</label>
    <div class='layui-input-block'>
        <div>
            <select name='{$province_name}' id='{$province_name}' lay-ignore  onchange=\"{$js_func}('{$province_name}','{$city_name}')\"  class='select2 inline w100' >
                    <option value=''>请选择</option>
                    {$province_html}
            </select> 
            {$fj_html}
        </div>
        <div class='layui-word-aux mt5'>{$msg}</div>
    </div>
</div>
<script type='text/javascript'>
function {$js_func}(id,child){
    var k = $('#'+id+' option:checked').val();
    if(id == '{$province_name}'){
        $('.{$province_name}').html(\"<option value=''>请选择</option>\");
    }
    ajax('{$get_url}',{id:k},function(data){
        $('#'+child).html(data.data.html);
    });
}
</script>";
    return $html;
}

/**
 * 文本域
 * @param type $params
 * @return type
 */
function textarea($params) {
    @extract($params);
    $val = isset($val) ? $val : $defaultvalue;
    $col = $col ? $col : 8;
    $html = "<div class='layui-form-item layui-form-text layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block'>
                    <div><textarea  name='{$field}' class='layui-textarea' {$require}>{$val}</textarea></div>
                    <div class='layui-word-aux'>{$msg}</div>   
                </div>
            </div>";
    return $html;
}

/**
 * 单图片
 * @param type $params
 * @return type
 */
function thumb($params) {
    @extract($params);
    $url = $serverUrl ? $serverUrl : url(ADMIN_URL . '/Upload/upload_file', array('field' => $field, 'catid' => $_GET['catid']));
    $root = __ROOT__;
    $column = $column ? $column : 2;
    $val = isset($val) ? $val : $defaultvalue;
    $img_url = get_img_url($val);
    $img_url = $img_url ? $img_url : 'javascript://';
    $col = $col ? $col : 8;
    $callback_str = '';
    if (isset($callback)) {
        $callback_str = "{$callback}('{$field}',json.data.file,json.data.url);";
    }
    $html = " <div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                    <label class='layui-form-label'>{$fname}</label>
                    <div class='layui-input-block' id='{$field}'>
                        <div><input type='text'  value='{$val}'   name='{$field}'  {$required} class='layui-input'></div>
                        <div   class='layui-word-aux mt5'>{$msg}</div>    
                        <div  id='{$field}_progress' class='layui-word-aux mt5'></div>    
                        <div style='margin-top:5px'><a href='{$img_url}' target='_blank'><img src='{$img_url}'  style='width:100px;height:100px;border:1px solid #ccc;'></a> </div>
                    </div>

                     <div class='layui-col-xs2' style='position:absolute;right:-18%;top:0;z-index:2;'>
                        <a  class='pear-btn pear-btn-primary ml5' href='javascript://' id='upload_{$field}'>上传</a>
                        <!--<a class='pear-btn pear-btn-primary'  href='javascript://' onclick=\"show_img('{$field}',1)\">站内</a>-->
                     </div>


                </div>
           

           
        <script type='text/javascript'>
        var {$field} = new plupload.Uploader({
                browse_button: 'upload_{$field}',
                url: '{$url}', 
                flash_swf_url: '{$root}/public/admin/plupload/Moxie.swf',
                silverlight_xap_url: '{$root}/public/admin/plupload/Moxie.xap',
                filters: {
                    max_file_size: '0',
                    mime_types: [ 
                        {title: '*', extensions: '*'}
                    ]
                },
                init: {
                    PostInit: function() {
                    },
                    //选择图片后
                    FilesAdded: function(up, files) {
                        plupload.each(files, function(file) {
                            {$field}.start();
                            return false;
                        });
                    },
                    //上传中
                    UploadProgress: function(up, file) {
                        $('#{$field}_progress').text('已上传'+file.percent+'%');
                    },
                    //异常处理
                    Error: function(up, err) {
                        layer.msg(err.message);
                    },
                    //图片上传后  
                    FileUploaded: function(up, file, res) {
                        var str = res.response;
                        var json = eval('(' + str + ')');
                        if(json.status=='0'){
                            layer.msg(json.info);
                        }else{
                            $('#{$field} input').val(json.data.file);
                            $('#{$field} img').attr('src',json.data.url);    
                            $('#{$field} a').attr('href',json.data.url);  
                            {$callback_str}
                        }

                    }
                }
            });

            {$field}.init();
            </script>";

    return $html;
}

//裁剪上传
function thumb_clip($params) {
    @extract($params);
    $column = $column ? $column : 2;
    $js = "";
    if ($is_func_auto == 1) {
        $func = "a{$field}" . time();
        $js = "function {$func}(file,url){  $('#{$field} input').val(file);$('#{$field} img').attr('src',url);        }";
    }
    $col = $col ? $col : 8;
    $url = get_img_url($defaultvalue);
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                    <label class='layui-form-label'>{$fname}</label>
                    <div class='layui-input-block' id='{$field}'>
                        <div><input type='text'  value='{$defaultvalue}'   name='{$field}'  {$required} class='layui-input'></div>
                        <div  id='{$field}_progress' class='layui-word-aux mt5'></div>    
                        <div style='margin-top:5px'><a href='{$url}' target='_blank'><img src='{$url}' style='width:100px;height:100px;'></a> </div>
                    </div>
                </div>
            <div class='layui-col-md2'>
                <a  class='pear-btn pear-btn-primary ml5' href='javascript://'  onclick=\"open_cut_upload({$width},{$height},'{$func}')\">上传</a>
             </div>
          
        <script type='text/javascript'>
         {$js}
        </script>";
    return $html;
}

//缩放上传
function thumb_zoom($params) {
    @extract($params);
    $column = $column ? $column : 2;
    $js = "";
    if ($is_func_auto == 1) {
        $func = "a{$field}" . time();
        $js = "function {$func}(file,url){  $('#{$field} input').val(file);$('#{$field} img').attr('src',url);        }";
    }
    $col = $col ? $col : 8;
    $url = get_img_url($defaultvalue);
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                    <label class='layui-form-label'>{$fname}</label>
                    <div class='layui-input-block' id='{$field}'>
                        <div><input type='text'  value='{$defaultvalue}'   name='{$field}'  {$required} class='layui-input'></div>
                        <div  id='{$field}_progress' class='layui-word-aux mt5'></div>    
                        <div style='margin-top:5px'><a href='{$url}' target='_blank'><img src='{$url}' style='width:100px;height:100px;'></a> </div>
                    </div>
                </div>
            <div class='layui-col-xs2'>
                <a  class='pear-btn pear-btn-primary ml5' href='javascript://'  onclick=\"open_cut_upload({$width},{$height},'{$func}')\">上传</a>
               </div>
             </div>
        <script type='text/javascript'>
         {$js}
        </script>";
    return $html;
}

/**
 * 时间
 * @param type $params
 * @return type
 */
function ftime($params) {
    @extract($params);
    $col = $col ? $col : 8;
    $val = isset($val) ? $val : $defaultvalue;
    
     $callback_str = '';
    if (isset($callback)) {
        $callback_str = "{$callback}('{$field}',value);";
    }
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
            <label class='layui-form-label'>{$fname}</label>
            <div class='layui-input-block'>
                <div><input type='text'  value='{$val}' readonly=''  id='{$field}'  name='{$field}'  {$required} class='layui-input '></div>
                <div class='layui-word-aux mt5'>{$msg}</div>    
            </div>
        </div><script>
layui.use('laydate', function(){
	var laydate = layui.laydate;
	laydate.render({
            elem: '#{$field}',
            type:'time',
            done: function(value, date){ {$callback_str} }
  });
})
</script>";
    return $html;
}

function fdate($params) {
    @extract($params);
    $col = $col ? $col : 8;
    $val = isset($val) ? $val : $defaultvalue;

    $callback_str = '';
    if (isset($callback)) {
        $callback_str = "{$callback}('{$field}',value);";
    }

    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
            <label class='layui-form-label'>{$fname}</label>
            <div class='layui-input-block'>
                <div><input type='text'  value='{$val}'  readonly='' id='{$field}'  name='{$field}'  {$required} class='layui-input '></div>
                <div class='layui-word-aux mt5'>{$msg}</div>    
            </div>
        </div><script>
layui.use('laydate', function(){
	var laydate = layui.laydate;
	laydate.render({
            elem: '#{$field}',
            done: function(value, date){ {$callback_str} }
  });
})
</script>";

    return $html;
}

function datetime($params) {
    @extract($params);
    $col = $col ? $col : 8;
    $val = isset($val) ? $val : $defaultvalue;

    $callback_str = '';
    if (isset($callback)) {
        $callback_str = "{$callback}('{$field}',value);";
    }
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
            <label class='layui-form-label'>{$fname}</label>
            <div class='layui-input-block'>
                <div><input type='text'  value='{$val}' readonly='' id='{$field}'   name='{$field}'  {$required} class='layui-input '></div>
                <div class='layui-word-aux mt5'>{$msg}</div>    
            </div>
        </div><script>
layui.use('laydate', function(){
	var laydate = layui.laydate;
	laydate.render({
            elem: '#{$field}',
            type:'datetime',
            done: function(value, date){ {$callback_str} }
  });
})
</script>";
    return $html;
}

function form_start($params) {
    @extract($params);
    $method = $method ? $method : "post";
    return "<form class=\"layui-form\"   enctype=\"multipart/form-data\" action=\"{$url}\" method=\"{$method}\">";
}

function form_end() {
    return "</form>";
}

function submit() {
    return '<div class="layui-form-item layui-col-xs6">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
        <button class="pear-btn pear-btn-primary " type="submit">
            保存
        </button>
        </div>
    </div>';
}

/**
 * 隐藏域
 * @param type $params
 * @return type
 */
function hide_input($params) {
    @extract($params);
    $val = isset($val) ? $val : $defaultvalue;
    return "<input type='hidden'   name='{$field}' value='{$val}'>";
}

/**
 * 手机编辑器
 * @param type $params
 * @return type
 */
function wap_editor($params) {
    @extract($params);
    $val = isset($val) ? $val : $defaultvalue;
    $col = $col ? $col : 8;
    $url = url(ADMIN_URL . '/index/wap_editor', array('func' => "wapditor_func_{$field}"));
    $html = " <div class='layui-form-item  layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
                <label class='layui-form-label'>{$fname}</label>
                <div class='layui-input-block'>
                    <div><a class=\" pear-btn pear-btn-primary\" href=\"javascript://\" onclick=\"open_wapditor_{$field}()\">设置</a><input type=\"hidden\" value='{$val}' name=\"{$field}\" /></div>
                    <div class='layui-word-aux mt5'>{$msg}</div>    
                </div>
            </div><script>
            function open_wapditor_{$field}(){
                    layer.open({
                        type: 2,
                        title: '编辑器',
                        shadeClose: false,
                        shade: 0.8,
                        area: ['380px', '90%'],
                        content: '{$url}' 
                      });   
            }
            function wapditor_func_{$field}(data){
                $('input[name={$field}]').val(data);
            }
                
</script>";

    return $html;
}

/**
 * 选择商品
 * @param type $params [field,fname,msg,items,val]
 * @return type
 */
function goods_select($params) {
    @extract($params);
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md4'  id='contentshow_goods_id'>
                    <label class='layui-form-label'>{$fname}</label>
                    <div class='layui-input-block' >
                       <input type='hidden'   name='{$field}' id='{$field}' value='{$val}' />
                        <a  class='pear-btn pear-btn-primary ml5' href='javascript://' onclick=\"select_goods('{$field}')\"  >选择商品</a>
                        <p class=\"layui-word-aux mt5\">{$msg}</p>
                        <table id=\"goods_id_list\" class=\"layui-table\" lay-size=\"sm\" style=\"width: 630px; display: none;\">

                        </table>
                    </div>
            </div><script type='text/html' id='{$field}_tpl'>
        <thead>
            <tr style='background-color: #f3f5f9'>
                <th style='width: 120px;text-align: center'>商品规格</th>
                <th style='width: 60px;text-align: center'>商品价格</th>
                <th style='width: 40px;text-align: center'>活动价格</th>
            </tr>
        </thead>
    <tbody>
          <%for(i in items){ var v=items[i];   %>
        <tr>
        <td style='text-align: center'><%:=v.spec_value_str%></td>
        <td style='text-align: center'><%:=v.price%></td>
        <td style='width: 40px;'><input  onkeyup='this.value=this.value.replace(/[^0-9]/g,'');' value='<%:=v.new_price%>' type='text' name='{$field}_items[<%:=v.id%>]' autocomplete='off' class='layui-input'></td>
        </tr>
         <%}%>
    </tbody>
</script> ";
    if ($items) {
        $items_str = json_encode($items);
        $html .= "<script>
	$(function(){
		var items = JSON.parse('{$items_str}');
		select_goods_callback('{$field}','{$val}',items);
	});
	</script>";
    }
    return $html;
}

function rangedate($params) {
    @extract($params);
    $col = $col ? $col : 8;
    $val = isset($val) ? $val : $defaultvalue;
    $html = "<div class='layui-form-item layui-col-xs10 layui-col-md{$col}'  id='contentshow_{$field}'>
            <label class='layui-form-label'>{$fname}</label>
            <div class='layui-input-block'>
                <div><input type='text'  value='{$val}'  readonly=''  name='{$field}'  {$required} class='layui-input' id='date_{$field}'></div>
                <div class='layui-word-aux mt5'>{$msg}</div>    
            </div>
        </div>
            <script type='text/javascript'>
                layui.use('laydate', function(){
                    var laydate = layui.laydate;
                    laydate.render({
                    elem: '#date_{$field}'
                    ,range: true
                    });
                });
            </script>
        ";


    return $html;
}

function list_rangedate($params) {
    @extract($params);
    $col = $col ? $col : 8;
    $val = isset($val) ? $val : $defaultvalue;
    $html = "<div class='layui-inline' >
	<label class='layui-form-label'>{$fname}</label>
	<div class='layui-input-inline'>
		<input type='text' name='{$field}' value='{$val}' readonly=''  placeholder='{$fname}'  class='layui-input' id='date_{$field}'>

	</div>
</div>

            <script type='text/javascript'>
                layui.use('laydate', function(){
                    var laydate = layui.laydate;
                    laydate.render({
                    elem: '#date_{$field}'
                    ,range: true
                    });
                });
            </script>
        ";


    return $html;
}

/*
* 列表用户筛选
*/
function list_select_user($map) {
    $col = $map['col'] ? $map['col'] : 8;
    $url = $map['url'] . '';
    $field = $map['field'];
    $name = $map['name'];
    $other = $map['other'] . '';
    $msg = $map['msg'] . '';
    $opval = "";
    if ($map['val']) {
        $opval = "<option value='{$map['val']}' selected=''>{$map['val_name']}</option>";
    }
    $html = "
            <div class='layui-inline'  id='contentshow_{$field}'>
            <label class='layui-form-label'>{$map['name']}</label>
            <div class='layui-input-inline'>
                <select  name='{$field}' lay-ignore  id='select2_{$field}' class='select2 inline w150' {$other}>
                     <option value=''>{$name}</option>
                      {$opval}
                </select>
            </div>
            </div>";
    $html .= "
            <script type='text/javascript'>
                $(function(){
                    $('#select2_{$field}').select2({
                        language: 'zh-CN',
                        ajax: {
                            url: '{$url}',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    key: params.term,
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        },
                        escapeMarkup: function (markup) { return markup; },
                        minimumInputLength: 1,
                        templateResult: function (repo){return repo.text},
                        templateSelection: function (repo){return repo.text}
                    });

                })
            </script>";
    return $html;
}
