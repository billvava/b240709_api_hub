
<div class='x-body '  >
    
    <div class='panel-body'>
    
          <xblock>
            <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回栏目</a>
        </xblock>
       
        <form class="layui-form"  action="{:url('item')}" method="post" enctype="multipart/form-data">
                
                    <div class='layui-form-item  layui-col-xs8 layui-col-md8' id="category_id_div" >
                        <label class='layui-form-label'>上级栏目</label>
                        <div class='layui-input-block'>
                            <div>
                                <select name='pid' id="pid"  lay-filter="pid"  lay-ignore <?php echo $info?'disabled=""':''; ?>   >
                                    <option value=''>请选择</option>
                                     <?php $pid = $info?$info['pid']:$in['pid']; echo (new \app\mall\model\GoodsCategory())->getAllOptionHtml($pid); ?>
                                  </select>
                          </div>
                          <div class='x-a mt5'>选择后不可更改</div>
                        </div>
                      </div>
            
                 <?php   
                 
                 echo hide_input(array(
                    'field'=>'category_id',
                    'val'=>$info['category_id'],
                ));  
                 
                 echo form_input(array(
                    'fname'=>'名称',
                    'field'=>'name',
                    'val'=>$info['name'],
                     'msg'=>'必填',
                ));  
                
                echo form_input(array(
                    'fname'=>'英文名称',
                    'field'=>'english',
                    'val'=>$info['english'],
                ));  
                  
                
                echo form_input(array(
                    'fname'=>'描述',
                    'field'=>'desc',
                    'val'=>$info['desc'],
                ));  
                 
                 ?>
               
           
            
                
                 <?php echo thumb(array(
                    'defaultvalue'=>$info['thumb'],
                    'fname'=>'缩略图',
                    'field'=>'thumb',
                ));
                 
                 echo submit();
                 ?>
                
             
                
               
           
    

    </form>
    </div>
</div>
