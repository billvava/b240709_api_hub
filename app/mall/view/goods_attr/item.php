


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
                <?php }  } ?>
              <?php     echo submit(); ?>
      </form>
</div>


