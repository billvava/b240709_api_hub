<link href="__ADMIN__/css/delivery.css" rel="stylesheet" type="text/css"/>
<?php   $defaultValue = $model->defaultValue(); ?>

<form class="layui-form x-body"  action="<?php echo url((app()->request->action()))?>" method="post">
    
<!--  【delivery_id】 start  !-->
<input type='hidden'   name='delivery_id' value='<?php echo $info?$info['delivery_id']:''; ?>'>
<!--  【delivery_id】 end  !-->
<!--  【name】 start  !-->
<?php echo form_input(array('field'=>'name','fname'=>'模板名称','defaultvalue'=>$info?$info['name']:'','col'=>4)); ?>
<!--  【name】 end  !-->
<!--  【method】 start  !-->
<?php echo radio(array('field'=>'method','fname'=>'计费方式','defaultvalue'=>$info?$info['method']:'1','items'=>array(
    array('name'=>'按件数','val'=>'1'),
    array('name'=>'按重量','val'=>'2'),
))); ?>

<div class="layui-form-item  layui-col-xs10 layui-col-md8" id="contentshow_rule">
    <label class="layui-form-label">区域及运费</label>
    <div class="layui-input-block">
        <div>
            <table class="layui-table regional-table">
                    <tbody>
                    <tr>
                        <th width="42%">可配送区域</th>
                        <th>
                            <span class="first">首件 (个)</span>
                        </th>
                        <th>运费 (元)</th>
                        <th>
                            <span class="additional">续件 (个)</span>
                        </th>
                        <th>续费 (元)</th>
                    </tr>
                     <?php if($info['rule']){  foreach ($info['rule'] as $item){  ?>
                                <tr>
                                    <td class="am-text-left">
                                        <p class="selected-content am-margin-bottom-xs">
                                            {$item['region_content']}
                                        </p>
                                        <p class="operation am-margin-bottom-xs">
                                            <a class="edit" href="javascript:;">编辑</a>
                                            <a class="delete" href="javascript:;">删除</a>
                                        </p>
                                        <input type="hidden"  class="layui-input w100"  name="delivery[rule][region][]"
                                               value="{$item['region']}">
                                    </td>
                                    <td>
                                        <input type="number"  class="layui-input w100" name="delivery[rule][first][]"
                                               value="{$item['first']}" required>
                                    </td>
                                    <td>
                                        <input type="number"   class="layui-input w100" name="delivery[rule][first_fee][]"
                                               value="{$item['first_fee']}" required>
                                    </td>
                                    <td>
                                        <input type="number"   class="layui-input w100" name="delivery[rule][additional][]"
                                               value="{$item['additional']}">
                                    </td>
                                    <td>
                                        <input type="number"   class="layui-input w100" name="delivery[rule][additional_fee][]"
                                               value="{$item['additional_fee']}">
                                    </td>
                                </tr>
                     <?php } } ?>
                    <tr>
                        <td colspan="5" class="am-text-left">
                            <a class="add-region pear-btn pear-btn-primary pear-btn-sm" href="javascript:;">
                                点击添加可配送区域和运费
                            </a>
                            如果点击无反应，就点击下<a href="javascript:location.replace(location.href);">刷新页面</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
        </div>
        <div class="x-a mt5">
            
            
        </div>    
    </div>
</div>


<!--  【method】 end  !-->
<!--  【sort】 start  !-->
<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'0','msg'=>'(数字越小越靠前)','col'=>2)); ?>
<!--  【sort】 end  !-->
  <?php echo submit(); ?>
 </form>
<div class="regional-choice"></div>
<script src="__ADMIN__/js/RegionalChoice.js" type="text/javascript"></script>
<script src="__ADMIN__/js/delivery.js" type="text/javascript"></script>
<script  type="text/javascript">
 $(function () {
        // 初始化区域选择界面
        var datas = JSON.parse('<?php echo $regionData; ?>');
        // 配送区域表格
        new Delivery({
            table: '.regional-table',
            regional: '.regional-choice',
            datas: datas
        });
        layui.use(['form','element'],function() {
            form  = layui.form;
            form.on('radio(method)', function(e){
               if(e.value=='1'){
                   $(".first").text("首件 (个)");
                   $(".additional").text("续件 (个)");
               }else if(e.value=='2'){
                   $(".first").text("首重 (Kg)");
                   $(".additional").text("续重 (Kg)");
               }
            });
            <?php if($info){ ?>
            layui.$('[name="method"]:eq(<?php echo $info['method']-1; ?>)').next('.layui-form-radio').click();
            <?php } ?>
        });
         
    });
</script>