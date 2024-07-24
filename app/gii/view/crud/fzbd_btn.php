{__NOLAYOUT__}
<?php $fz_type  = lang('fz_type'); ?>
<?php $type= $in['type'];$index = $in['index']; $item = $fz_type[$type];
tool()->classs('FileUtil');
$FileUtil = new \FileUtil();
$apps = $FileUtil->getDirList(APP_PATH);
?>
 <div class="layui-form-item {$type} {$type}{$index}" >
     <label class="layui-form-label" style="width: 120px;"><a href="javascript://" onclick="javascript:$('.{$type}{$index}').remove()">删除</a> {$item.name}：</label>
     <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" class=" layui-input" name="fzbd_{$type}[{$index}][name]" value="{$item.alias}" />
            
        </div>
     <?php foreach($item['param'] as $pv){  ?>   
     <label class="layui-form-label">{$pv.name}</label>
        <div class="layui-input-inline">
            <select class="form-control"  lay-ignore name="fzbd_{$type}[{$index}][{$pv.field}]">
                    <option value="" >请选择</option>
                    <?php foreach($all_fields as $k=>$v){ ?>
                    <option value="{$v.field}" <?php if( (is_array($pv['fast']) && in_array($v['field'], $pv['fast']) ) ||  (is_string($pv['fast']) && strpos($v['field'],  $pv['fast']) >0 ) ){ echo "selected=''"; } ?> >{$v.name}【{$v.field}】</option>
                    <?php } ?>
            </select>
        </div>
     <?php } ?>
     
     <?php if($item['show_model']==1){ $rand = rand(1,9999); ?>
      <label class="layui-form-label">模型位置</label>
      <div class="layui-input-inline" style="width: 210px;">
          <select class=" inline w100 select_model_app{$rand}"  lay-ignore name="fzbd_{$type}[{$index}][app]" onchange="change_app('{$rand}')">
                    <option value="" >应用</option>
                    <?php foreach($apps as $k=>$v){ ?>
                   <option value="{$v}" >{$v}</option>
                    <?php } ?>
            </select>
            <select class=" inline w100 select_model_file{$rand}"  lay-ignore name="fzbd_{$type}[{$index}][model]">
                    <option value="" >模型</option>
                    <?php foreach($apps as $k=>$v){ ?>
                    <option value="{$v}" >{$v}</option>
                    <?php } ?>
            </select>
        </div>
     <?php } ?>
</div>
