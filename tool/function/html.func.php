<?php

/**
 * 添加广告
 * @param type $no
 * @return type
 */
function add_ad($no) {
    return "<div class='panel panel-info'>
                <div class='panel-heading'>集合{$no} <a href=\"javascript://\" onclick=\"$('#parent_div{$no} input').val('')\"  data-toggle=\"tooltip\" title=\"各项留空，保存后自会自动删除\" data-placement=\"right\" class=\"btn btn-primary btn-mini\">清空</a></div>
                <div class='panel-body'  id=\"parent_div{$no}\">
                    
                     <div class='form-group'>
                        <label class='col-md-2 control-label'>大图{$no}</label>
                        <div class='col-md-8' id='big{$no}'>
                            <div>
                                <input type='text' name='big{$no}' class='form-control'   value=''   > 
                            </div>
                            <div id='big{$no}_process'>
                            </div>
                        </div>
                        <div class='col-md-2 text-danger'>
                        <a  href='javascript://'  id='upload_big{$no}'>本地上传</a>
                        <a  href='javascript://' onclick=\"tupianyulan('big{$no}')\">图片预览</a>    
                        </div>
                    </div>
                    
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>小图{$no}</label>
                        <div class='col-md-8' id='small{$no}'>
                             <div>
                            <input type='text' name='small{$no}' class='form-control'   value=''   > 
                             </div>   
                             <div id='small{$no}_process'>
                             </div>    
                        </div>
                        <div class='col-md-2 text-danger'>
                        <a  href='javascript://'  id='upload_small{$no}'>本地上传</a>
                             <a  href='javascript://' onclick=\"tupianyulan('small{$no}')\">图片预览</a> 
                        </div>
                    </div>
                    
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>链接{$no}</label>
                        <div class='col-md-8'>
                            <input type='text' name='link{$no}' value='' class='form-control'    > 
                        </div>
                        <div class='col-md-2 text-danger'>
                        如果没有，无须填写
                        </div>
                    </div>
                    
                    <div class='form-group'>
                        <label class='col-md-2 control-label'>文本{$no}</label>
                        <div class='col-md-8'>
                            <input type='text'  name='txt{$no}'  value=''   class='form-control'    > 
                        </div>
                        <div class='col-md-2 text-danger'>
                        如果没有，无须填写
                        </div>
                    </div>
                    
                     <div class='form-group'>
                        <label class='col-md-2 control-label'>排序{$no}</label>
                        <div class='col-md-8'>
                            <input type='text'  name='sort{$no}'  value='1'   class='form-control'    > 
                        </div>
                        <div class='col-md-2 text-danger'>
                        从小到大排序
                        </div>
                    </div>
                    
                </div>
              </div>
     ";
}

/**
 * 输出地图
 * @param type $width
 * @param type $height
 * @param type $id
 */
function show_map($width = '100%', $height = '320px', $id = '') {
    $positionid = C('positionid');
    $arr = explode(',', $positionid);
    $companyname = C('companyname');
    $companylinkman = C('companylinkman');
    $companytel = C('companytel');
    $companyaddress = C('companyaddress');
    $html = "<script type='text/javascript' src='http://api.map.baidu.com/api?v=1.5&ak=GYImfTdyAvzswuhCkA6zPnQnMlerGoF4'></script>
    <div style='width:{$width}; height:{$height}; margin:0 auto;'>
        <div id='{$id}' style='width:{$width}; height:{$height};overflow: hidden;margin:0;'></div>
    </div>

    <script type='text/javascript'>
    var map = new BMap.Map('{$id}');
    var point =  new BMap.Point({$arr[0]},{$arr[1]});
    map.enableScrollWheelZoom();
    map.enableContinuousZoom();
    map.centerAndZoom(point, 15);
    map.addControl(new BMap.NavigationControl());
    var marker = new BMap.Marker(point);
    map.addOverlay(marker); 
    marker.setAnimation(BMAP_ANIMATION_BOUNCE); 
    var sContent =\"<div class='myclass' style='height:50px; width:235px; font-size:12px; line-height:22px;'>{$companyname}<br>地址：{$companyaddress}<br>电话：{$companylinkman} {$companytel}</div>\";
    var infoWindow = new BMap.InfoWindow(sContent);
    map.centerAndZoom(point, 15);
    map.addOverlay(marker);
    marker.addEventListener('click', function(){          
       this.openInfoWindow(infoWindow);s
       document.getElementById('imgDemo').onload = function (){
           infoWindow.redraw();
       }
    });
    </script>";
    return $html;
}


function show_map2($width, $height, $id = '') {
    $positionid = C('positionid');
    $arr = explode(',', $positionid);
    $companyname = C('companyname');
    $companylinkman = C('companylinkman');
    $companytel = C('companytel');
    $companyaddress = C('companyaddress');
    $html = "<script type='text/javascript' src='//api.map.baidu.com/api?type=webgl&v=1.0&ak=GYImfTdyAvzswuhCkA6zPnQnMlerGoF4'></script>
    <div style='width:{$width}; height:{$height}; margin:0 auto;'>
        <div id='{$id}' style='width:{$width}; height:{$height};overflow: hidden;margin:0;'></div>
    </div>

    <script type='text/javascript'>

    var map = new BMapGL.Map('{$id}');  
    var point=new BMapGL.Point({$arr[0]},{$arr[1]});
    map.centerAndZoom(point,15); 
    
    var marker = new BMapGL.Marker(point);
    map.addOverlay(marker);
    
    
    
    map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
    var scaleCtrl = new BMapGL.ScaleControl();  // 添加比例尺控件
    map.addControl(scaleCtrl);
    var zoomCtrl = new BMapGL.ZoomControl();  // 添加缩放控件
    map.addControl(zoomCtrl);
    
    var opts = {
        width : 240,   
        height: 120, 
    }

    var sContent =\"<div class='myclass' style='padding:2px 14px; font-size:12px; line-height:22px;'>{$companyname}<br>地址：{$companyaddress}<br>电话：{$companylinkman} {$companytel}</div>\";

    var infoWindow = new BMapGL.InfoWindow(sContent, opts); 

    marker.addEventListener('click', function(){    

        map.openInfoWindow(infoWindow, point); //开启信息窗口
    }); 
    </script>";
    return $html;
}


/**
 * 邮件模板
 * @param type $title
 * @param type $content
 * @param type $footer
 * @return type
 */
function email($header, $content, $footer) {
    $title = C('title');
    $url = C('siteurl');
    return '<table style="margin: 25px auto;" border="0" cellspacing="0" cellpadding="0" width="648" align="center">
            <tbody>
            <tr><td style="color:#E67E7E;"><h1 style="margin-bottom:10px;font-size:14pt;">' . $title . '</h1></td></tr>
            <tr>
            <td style="border-left: 1px solid #E67E7E; padding: 20px 20px 0px; background: none repeat scroll 0% 0% #ffffff; border-top: 5px solid #E67E7E; border-right: 1px solid #E67E7E;">
            <p>' . $header . '</p>
            </td>
            </tr>
            <tr>
            <td style="border-left: 1px solid #E67E7E; padding: 10px 20px; background: none repeat scroll 0% 0% #ffffff; border-right: 1px solid #E67E7E;">
            <p>' . $content . '</p>
            </td>
            </tr>
            <tr>
            <td style="border-bottom: 1px solid #E67E7E; border-left: 1px solid #E67E7E; padding: 0px 20px 20px; background: none repeat scroll 0% 0% #ffffff; border-right: 1px solid #E67E7E;">
            <hr style="color:#ccc;">
            <p style="color:#E67E7E;font-size:9pt;">系统发送邮件，请勿回复；想了解更多信息，请访问 <a href="' . $url . '" target="_blank">"' . $url . '"</a></p>
            </td>
            </tr>
            </tbody>
            </table>';
}

/**
 * 配置的模板
 * @param type $params
 * @return string
 */
function inputType($params) {
    @extract($params);
    $html = '';
    $option = json_decode($option, true);
    if ($input_type == 'text' || $input_type == '') {
        $html .= '<input type="text" onchange="set_val(this)" name="' . $id . '" value="' . $val . '" class="layui-input" />';
    } elseif ($input_type == 'radio') {
        foreach ($option as $key => $value) {
            $checked = $value['val'] == $val ? 'checked' : '';
            $html .=<<<EOT
				<label for="radio-{$id}-{$key}">
					<input type="radio" onchange="set_val(this)"  id="radio-{$id}-{$key}" name="{$id}" value="{$value['val']}" {$checked} />{$value['text']}
				</label>
EOT;
        }
    } elseif ($input_type == 'select') {
        $html .= '<select onchange="set_val(this)"  name="' . $id . '" class="layui-col-md6">';
        foreach ($option as $key => $value) {
            $selected = $value['val'] == $val ? 'selected' : '';
            $html .= '<option value="' . $value['val'] . '" ' . $selected . '>' . $value['text'] . '</option>';
        }
        $html .= '</select>';
    } elseif ($input_type == 'textarea') {
        $html .= "<textarea onchange='set_val(this)'  class='layui-textarea' name='" . $id . "'>" . $val . "</textarea>";
    }

    return $html;
}

/**
 * 字符串转成数组
 */
function radiostr_to_array($str, $check_val = '') {
    if (!$str) {
        return null;
    }
    $arr_temp = array_filter(explode(',', $str));
    $res = array();
    foreach ($arr_temp as $v2) {
        $arr_temp2 = explode('|', $v2);
        $a = array(
            'name' => $arr_temp2[0],
            'val' => $arr_temp2[1],
            'checked' => false
        );
        if ($check_val != '' && $check_val == $arr_temp2[1]) {
            $a['checked'] = true;
        }
        $res[] = $a;
    }
    return $res;
}

/**
 * 字符串转成数组
 */
function checkstr_to_array($str, $check_val = '') {
    if (!$str) {
        return null;
    }
    $arr_temp = array_filter(explode(',', $str));
    $arr_default = explode(',', $check_val);
//    p($arr_default);
    $res = array();
    foreach ($arr_temp as $v2) {
        $arr_temp2 = explode('|', $v2);
        $a = array(
            'name' => $arr_temp2[0],
            'val' => $arr_temp2[1],
            'checked' => false
        );
        if ($arr_default && in_array($arr_temp2[1], $arr_default)) {
            $a['checked'] = true;
        }
        $res[] = $a;
    }
    return $res;
}

/**
 * 组图的默认值
 * @param type $files
 */
function zutu_val($field, $files) {
    if (!$field || !$field) {
        return '';
    }
    $imgs = array();
    if (is_string($files)) {
        $imgs = json_decode($files, true);
    } else if (is_array($files)) {
        $imgs = $files;
    }
    $defaultvalue = "";
    foreach ($imgs as $v) {
        $defaultvalue .= '<div class=\'img_item\'><a href="javascript:;" class="up-close" onclick="$(this).parents(\'.img_item\').remove();"></a><img class="img_thumb" src="' . get_img_url($v) . '"><input type="hidden" name="' . $field . '[]" value="' . ($v) . '"></div>';
    }
    return $defaultvalue;
}

/**
 * 输出图片的显示
 * @param type $str
 * @param type $show_img
 * @param int $width
 */
function echo_img($str, $show_img = 1, $width = 50) {
    $arr = '';
    if (is_string($str)) {
        $arr = json_decode($str, true);
    } else if (is_array($str)) {
        $arr = $str;
    }

    if (is_array($arr)) {
        $arr = array_map("get_img_url", $arr);
    } else {
        if ($width > 0) {
            $co = array('type' => 'resize', 'width' => $width);
        } else {
            $co = null;
            $width = 50;
        }
        $arr = array(get_img_url($str, null, $co));
    }

    foreach ($arr as $k => $v) {

        if ($show_img == 1 && $v) {
            echo "<a href='{$v}' target='_blank' ><img src='{$v}' style='width:{$width}px;height:{$width}px;' /></a>";
        } else  if($v){
            $s = $k + 1;
            echo "<a href='{$v}' target='_blank' >图{$s}</a> &nbsp;&nbsp;";
        }else{
             echo "";
        }
    }
}

/**
 * 快速编辑的input
 * @param type $params
 * @return type
 */
function fast_input($params) {
    @extract($params);
    $url = $url ? $url : url('set_val');
    $class = $class ? $class : 'w100';
    return "<input  data-key='{$key}'  data-keyid='{$keyid}'  data-field='{$field}'  data-url='{$url}' value='{$val}' type='text' class=' layui-input {$class}' data-type='setval'   />";
}

function fast_check($params) {
    extract($params);
    $url = $url ? $url : url('set_val');
    if ($check == 1) {
        $check_str = "checked=''";
    } else {
        $check_str = "";
    }
    if($txt && strpos($txt, '|')===false ){
        $txt="{$txt}|{$txt}";
    }
    return " <input type='checkbox' data-type='setval' data-key='{$key}' data-keyid='{$keyid}' data-field='{$field}' data-url='{$url}' lay-filter='setval'  lay-text='{$txt}'   {$check_str} lay-skin='switch'>";
}



function api_query()
{
    $apiurl =C('apiurl');
    $apitoken = C('apitoken');
    $html = '';
    if ($apitoken) {
        $res = file_get_contents($apiurl . "/api/index/getMyApi?token=" . $apitoken);
        $myres = json_decode($res, true);
        if ($myres['status'] == 1) {
            $html = "<div class='table-container'>
                <table class='layui-table'>
                    <tbody>
                    <tr>
                        <th>剩余快递查询量</th>
                        <td>
                            {$myres['data']['exp']} | <a href='{$myres['data']['exp_cz']}' target='_blank'>去充值</a></td>
                    </tr>
                    <tr>
                        <th>剩余短信发送量</th>
                        <td>
                            {$myres['data']['sms']} |  <a href='{$myres['data']['sms_cz']}' target='_blank'>去充值</a></td>
                    </tr>

                    </tbody>
                </table>
            </div>";

        }
    }
    return $html;

}