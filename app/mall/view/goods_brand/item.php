


<div class='x-body '>
    
        <xblock>
            <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回列表</a>
        </xblock>
    
        
        <form class="layui-form"  action="{:url('item')}"  method="post" enctype="multipart/form-data">
                <?php $attrData=$model->attributeLabels(); $autoField = $model->getAutoField();  ?>
                <?php foreach($attrData as $k=>$v){ ?>
                  <?php if($autoField!=$k){ 
                      echo form_input(array(
                    'fname'=>$v,
                    'field'=>$k,
                    'val'=>$info[$k],
                     'msg'=>'',
                ));   ?>
                 
                  <?php }else{ ?>
                  <input type="hidden"   name="{$k}" value="<?php echo $info[$k]; ?>">
                  <?php } ?>
                  <?php } ?>
                  <?php 
                 echo form_input(array(
                    'fname'=>'首字母',
                    'field'=>'letter',
                    'val'=>$info['letter'],
                     'msg'=>'只能输入一个字母',
                ));
                  echo thumb(array(
                    'defaultvalue'=>$info['thumb'],
                    'fname'=>'缩略图',
                    'field'=>'thumb',
                )); 
                  echo submit(); ?>
      </form>
</div>


