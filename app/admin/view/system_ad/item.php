<div class="x-body">
    {include file="system_ad/top" /}
</div> 
<div class="mt10"></div>
<?php 
echo form_start(array('url'=>url('item')));
echo $info? hide_input(array(
    'name'=>'field',
    'val'=>$info['id']
)):'';
echo form_input(array(
    'field'=>'name',
    'fname'=>'广告名称',
     'defaultvalue'=>$info?$info['name']:$defaultValue['name'],
));
echo form_input(array(
    'field'=>'key',
    'fname'=>'唯一标识',
     'defaultvalue'=>$info?$info['key']:$defaultValue['key'],
    'msg'=>'请输入英文，用来代替以前的数字获取:get_ad(key)'
));
echo form_input(array(
    'field'=>'width',
    'fname'=>'宽',
     'defaultvalue'=>$info?$info['width']:$defaultValue['width'],
));
echo form_input(array(
    'field'=>'height',
    'fname'=>'高',
     'defaultvalue'=>$info?$info['height']:$defaultValue['height'],
));

echo form_input(array(
    'field'=>'remark',
    'fname'=>'提示信息',
     'defaultvalue'=>$info?$info['remark']:$defaultValue['remark'],
));
echo submit();
echo form_end();
?>

