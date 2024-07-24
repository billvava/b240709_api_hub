


<div class='panel xf_panel ' style="margin: 10px;" >
    
    <div class='panel-body'>
        
        <ol class="breadcrumb">
            <li><a href="{:url('index')}">{$name}</a></li>
            <li><a href="<?php echo url('show',array($pk=>$info[$pk]) ); ?>"><?php echo $info[$pk]; ?></a></li>
            <li class="active">{$title}</li>
        </ol>
        
        <div style=" margin-bottom: 20px;">
            <a class="btn btn-default" href="<?php echo url('show',array($pk=>$info[$pk]) ); ?>">查看</a>
            <a class="btn btn-danger" href="<?php echo url('edit',array($pk=>$info[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
        </div>

        <form class="form-horizontal"  action="{:url('edit')}" method="post" enctype="multipart/form-data">
              <?php $attrData=$model->attributeLabels(); $autoField = $model->getAutoField();  ?>
              <?php foreach($attrData as $k=>$v){ ?>
                <?php if($autoField!=$k){ ?>
                  <div class="form-group">
                    <label for="admin-{$k}" class="col-sm-2">{$v}</label>
                    <div class="col-md-6 col-sm-10">
                      <input type="text" class="form-control" name="{$k}" id='admin-{$k}' value="<?php echo $info[$k]; ?>">
                    </div>
                  </div>
                <?php }else{ ?>
                <input type="hidden"   name="{$k}" value="<?php echo $info[$k]; ?>">
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


