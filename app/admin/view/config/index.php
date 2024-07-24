<?php tool()->func('form');  ?>
<div class="x-body">
    <form class="layui-form">
    <div class="layui-tab layui-tab-card ">
        
        
        <ul class="layui-tab-title">
           <?php foreach($cate as $k=>$v){ ?>
          <li class="<?php if($k==0){ echo 'layui-this ';} ?>">{$v.name}</li>
           <?php } ?>
         
        </ul>
        
        <div class="layui-tab-content"  style=" overflow: hidden">
             <?php foreach($cate as $k=>$cv){ ?>
            <div class="layui-tab-item <?php if($k==0){ echo 'layui-show ';} ?>">
                
                <?php foreach($cv['children'] as $vv){ ?>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header" style=" background: #e6e6e6;">{$vv.name}</div>
                      <div class="layui-card-body" style=" overflow: hidden">
                          
                         <?php if(!is_array($data)) $data=[]; foreach($data as $v){
                             if($v['cate_id']!=$vv['id']){     continue;   }
                             $str = '';
                             $msg_f = '<font style="color:#757070">'.$v['field'].'</font> ';
                             $v['input_type']=='text' &&  $str = form_input(array(
                                 'val'=>$v['val'],
                                 'fname'=>$v['name'],
                                 'field'=>$v['field'],
                                 'msg'=> $msg_f.htmlspecialchars_decode($v['msg']) ,
                                 'unit'=>$v['unit']
                             ));
                             $v['input_type']=='img' &&  $str = photo(array(
                                 'val'=>$v['val'],
                                 'fname'=>$v['name'],
                                 'field'=>$v['field'],
                                 'filename'=>$v['option'],
                                 'msg'=>  $msg_f.htmlspecialchars_decode($v['msg']),
                                 'callback_rela'=>'thumb_callback'
                             ));
                             $v['input_type']=='textarea' &&  $str = textarea(array(
                                 'val'=>$v['val'],
                                 'fname'=>$v['name'],
                                 'field'=>$v['field'],
                                 'msg'=> $msg_f. htmlspecialchars_decode($v['msg']) 
                             ));
                             $v['input_type']=='radio' &&  $str = radio(array(
                                 'val'=>$v['val'],
                                 'items'=> json_decode($v['option'],true),
                                 'fname'=>$v['name'],
                                 'field'=>$v['field'],
                                 'msg'=>  $msg_f.htmlspecialchars_decode($v['msg']) 
                             ));
                             $v['input_type']=='fdate' &&  $str = fdate(array(
                                 'val'=>$v['val'],
                                 'fname'=>$v['name'],
                                 'field'=>$v['field'],
                                 'msg'=> $msg_f.htmlspecialchars_decode($v['msg']) ,
                                 'unit'=>$v['unit'],
                                  'callback'=>'input_callback'
                             ));
                              $v['input_type']=='datetime' &&  $str = datetime(array(
                                 'val'=>$v['val'],
                                 'fname'=>$v['name'],
                                 'field'=>$v['field'],
                                 'msg'=> $msg_f.htmlspecialchars_decode($v['msg']) ,
                                 'unit'=>$v['unit'],
                                  'callback'=>'input_callback'
                             ));
                             echo $str;
                          ?>
                          
                         <?php } ?>
                          
                          
                      </div>
                    </div>
                  </div>
                <?php } ?>
                
                
                
            </div>
            
            <?php } ?>
            
        </div>
      </div>
      </form>
</div>

<link rel="stylesheet" href="__STATIC__/pearadmin/component/pear/css/pear-module/notice.css">
<script src="__STATIC__/pearadmin/component/pear/pear.js"></script>


<script type="text/javascript">


var notice;
$(function(){
    $("input[type=text],textarea").change(function(){
        var t = $(this);
        var i = t.attr('name');
        var v =  t.val();
        send_update(v,i);
    });
    
    $(".imgMark").dblclick(function(){
       var t= $(this);
       send_update('',t.data('field'));
       
    });
   
    layui.use(['notice','form'], function () {
    notice = layui.notice;
    var form = layui.form;
    //此处即为 radio 的监听事件
        form.on('radio', function(data){
        var t = $(data.elem);
         send_update(data.value,t.attr('lay-filter'));
        });
       
    });
   
    
})
function thumb_callback(field,sel_img_src,sel_img_file){
    send_update(sel_img_src[0],field);
}
function input_callback(id,v){
      send_update(v,id);
}
function send_update(v,i){
       $.post('{:url('set_val_id')}',{val:v,id:i},function(data){
            notice.success(data.info);
      },'json');
}
</script>