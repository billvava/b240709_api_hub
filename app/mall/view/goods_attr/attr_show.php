

<div class='x-body' >
    <blockquote class="layui-elem-quote">
        {$name} - {$title}
    </blockquote>
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('attr_manage',array('attr_id'=>$info['attr_id']))}">返回"{$title}"的属性管理</a>
    </xblock>
    
    
            <form class="form-horizontal"  action="{:url('attr_show')}" method="post" enctype="multipart/form-data">
                        
                
                <?php
                echo form_input(array(
                    'fname'=>'属性名',
                    'field'=>'name',
                    'val'=>$data['name'],
                ));
                ?>
                
                <div class='layui-form-item  layui-col-xs8 layui-col-md8' >
                    <label class='layui-form-label'>类型</label>
                    <div class='layui-input-block'>
                        <div><select class="form-control" required="" name="type" id="type" onchange="typechange()">
                                  <?php foreach($types as $k=>$v){ ?>
                                  <option value="{$k}">{$v}</option>
                                  <?php } ?>
                              </select></div>
                        <div class='x-a mt5'></div>    
                    </div>
                </div>    
                      
                  <?php
                echo textarea(array(
                    'fname'=>'参数',
                    'field'=>'param',
                    'val'=>$data['param'],
                    'msg'=>'一行代表一个可选值'
                ));
                
                 echo form_input(array(
                    'fname'=>'默认值',
                    'field'=>'default',
                    'val'=>$data['default'],
                ));
                 
                    echo form_input(array(
                    'fname'=>'提示信息',
                    'field'=>'remark',
                    'val'=>$data['remark'],
                ));
                    
                      echo form_input(array(
                    'fname'=>'排序',
                    'field'=>'sort',
                    'val'=>$data?$data['sort']:99,
                ));
                      
                      echo submit();
                ?>
                     
                
                
               
                      <input type="hidden"   name="field_id" value="<?php echo $data['field_id']; ?>">
                      <input type="hidden"   name="attr_id" value="<?php echo $info['attr_id']; ?>">

                      

          </form>
        
</div>

<script type="text/javascript">
function typechange(){
    var type = $("#type").val();
    if(type==1 || type==4){
        $("#contentshow_param textarea").prop('disabled',true);
    }else{
        $("#contentshow_param textarea").prop('disabled',false);
    }
}
typechange();
</script>
