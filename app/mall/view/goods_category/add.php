
<script src="__LIB__/plupload/plupload.full.min.js" type="text/javascript"></script>
<?php tool()->func('form'); ?>

<div class='panel xf_panel ' style="margin: 10px;" >
    
    <div class='panel-body'>
        
        <ol class="breadcrumb">
            <li><a href="{:url('index')}">{$name}</a></li>
            <li class="active">{$title}</li>
        </ol>
    
         <form class="form-horizontal"  action="{:url('add')}" method="post" enctype="multipart/form-data">
            
              <div class="form-group">
                    <label for="admin-pid" class="col-sm-2">上级类目</label>
                    <div class="col-md-6 col-sm-10">
                        <select  class="form-control"  name="pid">
                            <option value="0">无</option>
                            <?php    function categoryTreeToHtml($data){  ?> 
                                <?php foreach($data as $v){ ?>
                               <option value="{$v.category_id}" <?php if( I('pid')==$v['category_id']){ echo "selected=''";} ?> ><?php echo str_repeat ("----" , $v['lev']-1);  ?>{$v.name}</option>
                                 <?php if(!empty($v['children'])){  categoryTreeToHtml($v['children']);  } ?>
                                <?php } ?>
                             <?php } categoryTreeToHtml($data); ?>
                        </select>
                     
                    </div>
                  </div>
             
            <?php $attrData=$model->attributeLabels(); $autoField = $model->getAutoField(); $defaultValue = $model->defaultValue(); ?>
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
                
                
              <?php echo thumb(array(
                    'defaultvalue'=>$info['thumb'],
                    'fname'=>'缩略图',
                    'field'=>'thumb',
                )); ?>
                
                
                <div class="form-group">
                    <label for="admin-thumb" class="col-sm-2">是否显示</label>
                    <div class="col-md-6 col-sm-10">
                        <label for="is_show0">
                            <input type="radio" value="0" id="is_show0" name="is_show" />不显示
                        </label>
                         <label for="is_show1">
                        <input type="radio" value="1"  checked="" id="is_show1" name="is_show"  />显示
                        </label>
                    </div>
                </div>
                
                 <div class="form-group">
                    <label for="admin-thumb" class="col-sm-2">是否推荐</label>
                    <div class="col-md-6 col-sm-10">
                        <label for="is_nav0">
                            <input type="radio" value="0"   id="is_nav0" name="is_nav" />不显示
                        </label>
                         <label for="is_nav1">
                        <input type="radio" value="1"  checked="" id="is_nav1" name="is_nav"  />显示
                        </label>
                    </div>
                </div>
                
                
                <div class="form-group">
                  <label  class="col-sm-2"></label>
                  <div class="col-md-6 col-sm-10">
                   <button type="submit" class="btn btn-primary btn-block">保存</button>
                  </div>
                </div>

        </form>
    </div>
</div>

