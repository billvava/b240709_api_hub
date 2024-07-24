<div class="x-body">
    <div class="layui-row">
       <a class="pear-btn pear-btn-primary " href="{:url('index')}">返回列表</a>
    </div>
    <?php
    echo form_start(array('url'=>url('item')));
    $attrData=$model->attributeLabels(); $autoField = $model->getPk();
    foreach($attrData as $k=>$v){ 
        if($autoField!=$k){ 
            echo form_input(array('fname'=>$v,'field'=>$k,'val'=>$info[$k]));
        }else{
            echo hide_input(array('field'=>$k,'val'=>$info[$k]));
        }
        
    }
    echo submit();
    echo form_end();
    ?>
</div>

