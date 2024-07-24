

<div class='panel xf_panel ' style="margin: 10px;" >
    
    <div class='panel-body'>
        
        <ol class="breadcrumb">
            <li><a href="{:url('index')}">{$name}</a></li>
            <li class="active">{$title}</li>
        </ol>
    
         <form class="form-horizontal"  action="{:url('add')}" method="post" enctype="multipart/form-data">
              <?php $attrData=$model->attributeLabels(); $autoField = $model->getAutoField(); $defaultValue = $model->defaultValue();  ?>
               <?php foreach($attrData as $k=>$v){ ?>
               <?php if($autoField!=$k){ ?>
            <div class="form-group">
                  <label for="admin-{$k}" class="col-sm-2">{$v}</label>
                  <div class="col-md-6 col-sm-10">
                      <input type="text" class="form-control" name="{$k}" id='admin-{$k}' value="<?php echo $defaultValue[$k]; ?>">
                  </div>
                </div>
                 <?php } ?>
                 <?php } ?>
            <div class="form-group">
                  <label  class="col-sm-2"></label>
                  <div class="col-md-6 col-sm-10">
                   <button type="submit" class="btn btn-primary btn-block">保存</button>
                  </div>
                </div>

        </form>
    </div>
</div>


