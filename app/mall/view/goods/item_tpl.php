
<script type="text/html" id="template-goods-image">
    <li class="goods-li">
        <input name="goods_image[]" type="hidden" value="{image-src}">
        <img class="goods-img goods_image" src="{image-src}">
        <a class="goods-img-del-x" style="display: none;">x</a></li>
</script>
<script type="text/html" id="template-spec">
    <div class="goods-spec-div goods-spec" lay-verify="add_more_spec|repetition_spec_name" lay-verType="tips"
         autocomplete="off"
         switch-tab="1" verify-msg="至少添加一个规格，且规格需要规格值">
        <a class="goods-spec-del-x" style="display: none;">x</a>
        <div class="layui-form-item"><label class="layui-form-label">规格项：</label>
            <div class="layui-input-block" style="width: 500px">
                <div class="layui-input-inline">
                    <input type="hidden" name="spec_id[]" value="0">
                <input type="text" name="spec_name[]" lay-verify="more_spec_required" lay-verType="tips" switch-tab="1"
                       verify-msg="规格项不能为空"
                       placeholder="请填写规格名" autocomplete="off" class="layui-input spec_name" value="{value}">
                </div>
                <div class="layui-input-inline">
                    <input type="checkbox" class="batch-spec-image-switch" lay-filter="batch-spec-image-switch" lay-skin="switch" lay-text="有图片|无图片">
                </div>
            </div>
        </div>
        <div class="layui-form-item"><label class="layui-form-label"></label>
            <div class="layui-input-block goods-spec-value-dev" lay-verify="repetition_spec_value" lay-verType="tips"
                 switch-tab="1">
                <div class="layui-input-inline">
                    <input type="hidden" class="spec_values" name="spec_values[]" value="">
                    <input type="hidden" class="spec_value_ids" name="spec_value_ids[]" value="">
                    <a href="#" class="add-spec-value">+ 添加规格值</a>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="template-spec-value">
    <div class="layui-input-inline goods-spec-value" style="width: 90px">
        <a class="goods-spec-value-del-x" style="display: none;">x</a>
        <input value="{spec_value}" spec-value-temp-id="{spec_value_temp_id}" class="layui-input goods-spec-value-input"
               placeholder="规格值"
               lay-verify="more_spec_required" lay-verType="tips" switch-tab="1" verify-msg="规格值不能为空">
        <a class="click-a batch-spec-image">添加图片</a>
        <input type="hidden" class="goods-sepc-value-id-input" value="{spec_value_id}">
    </div>
</script>
<script type="text/html" id="template-spec-table-th">
    <colgroup>
        <col width="60px">
    </colgroup>
    <thead>
    <tr style="background-color: #f3f5f9">
        {spec_th}
        <?php foreach($goods_spec_field as $v){ ?>
    <th>
        <?php if($v['is_must']==1){ ?>
        <span class="form-label-asterisk">*</span>
        <?php } ?>
        {$v.name}</th>
    <?php } ?>
    </tr>
    </thead>
</script>
<script type="text/html" id="template-spec-table-tr">
    {spec_td}
    <?php foreach($goods_spec_field as $v){ ?>
	<td>
		<?php if($v['type']=='img'){ ?>
		<div class="goods-spec-img-div"><input name="{$v.field}[]" type="hidden"
											   value=""><img
				src="__STATIC__/admin/images/upload.png"
				class="goods-one-spec-img-add"></div>
		<?php } ?>
		 <?php if($v['type']=='number'){ ?>
	   <input type="number" class="layui-input"
			   autocomplete="off" switch-tab="1" 
			   name="{$v.field}[]">
		<?php } ?>
	  </td>
	<?php } ?>
    </tr>
</script>
