<form class="form-horizontal" role="form" action="<?php echo url('run'); ?>" method="post" target="iframe_crud">
    <pre style=" border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
测试表：
CREATE TABLE IF NOT EXISTS `xf_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `total` float(8,2) DEFAULT NULL COMMENT '金额',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态|1=可用,2=不可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8  COMMENT='报表测试';
</pre>
<div class='layui-form-item  layui-col-xs10 layui-col-md4' >
    <label class='layui-form-label'>表名</label>
    <div class='layui-input-block'>
        <div><input type='text'    name="table_name" placeholder="" id="table_name"  value="" class='layui-input'></div>
        <div class='layui-word-aux mt5'  id="tableNameMsg">不用写前缀</div>    
    </div>
</div>
<fieldset class="layui-elem-field layui-field-title" style="overflow: hidden; clear: both;">
  <legend>时间段统计（按天，按月，按年）</legend>
</fieldset>
<table class="layui-table ">
    <thead>
        <tr>
            <td>是否生成</td>
            <td>时间字段</td>
            <td>附加条件</td>
            <td>份额字段</td>
        </tr>
    </thead>
    <tbody id="field_form">
             <tr>
                 <td>
                     <select  class="form-control" name="time_is">
                        <option value="1"  selected="">生成</option>
                        <option value="0">不生成</option>
                    </select>   
                 </td>
                 <td>
                       <p><input type="text" name="time_field" id="time_field" placeholder="" value="" class="form-control"></p>
                      <p>根据该字段生成按年，按月，按日的数据，必须是datetime类型的</p>
                 </td>
                <td>
                    <p><input type="text" name="time_where" id="time_where"  placeholder="不写则不限范围"  value="" class="form-control"></p>
                 <p>示例：is_pay=1 and type=2</p>
                </td>
                <td>
                    <p><input type="text" name="total_field"  id="total_field" placeholder="可不填"  value="" class="form-control"></p>
                     <p>示例金额统计：total</p>
                </td>
            </tr>
     </tbody>
  </table>
 
<fieldset class="layui-elem-field layui-field-title" style="overflow: hidden; clear: both;">
  <legend>分类占比统计（选填）</legend>
</fieldset>
<table class="layui-table ">
    <thead>
        <tr>
            <td>是否生成</td>
            <td>分类字段
</td>
            <td>分类注释
</td>
            <td>附加条件</td>
        </tr>
    </thead>
    <tbody >
             <tr>
                 <td>
                    <select  class="form-control" name="cate_is">
                                    <option value="1">生成</option>
                                    <option value="0" selected="">不生成</option>
                                </select>
                 </td>
                 <td>
                       <p> <input type="text" name="cate_field" id="cate_field" placeholder="" value="" class="form-control"></p>
                 </td>
                <td>
                    <p><input type="text" name="cate_comment" id="cate_comment" placeholder="" value="" class="form-control"></p>
                 <p> <font class="text-danger" >示例：1=可用,2=不可用</font></p>
                </td>
                <td>
                    <p><input type="text" name="cate_where" placeholder="不写则不限范围"  value="" class="form-control"></p>
                     <p>示例：is_pay=1 and type=2</p>
                </td>
            </tr>
     </tbody>
  </table>   
    
 
<fieldset class="layui-elem-field layui-field-title" style="overflow: hidden; clear: both;">
  <legend>生成位置</legend>
</fieldset>
<table class="layui-table ">
    <thead>
        <tr>
            <td>应用</td>
            <td>覆盖
</td>
            <td>控制器
</td>
            <td>视图</td>
             <td>名称</td>
        </tr>
    </thead>
    <tbody id="field_form">
             <tr>
                 <td>
                    <input class="form-control" name="module"  value="<?php echo ADMIN_MODULE; ?>">  
                 </td>
                 <td>
                       <select name="is_cover" class="form-control">
                              <option value="0">不覆盖</option>
                               <option value="1">直接覆盖</option>
                          </select>
                 </td>
                <td>
                    <input class="form-control" placeholder="" name="c_name"  id="c_name" value="">
                </td>
                <td>
                   <input type="text" class="form-control" name="view_name"  id="view_name" placeholder="" >
                </td>
                
                <td>
                  <input class="form-control" placeholder="" name="comment"  id="comment"   placeholder="必填"  value="">
                </td>
            </tr>
     </tbody>
  </table>
               
              
                <div class="form-group">
                  <div class="col-md-offset-2 col-md-10">
                     <input type="submit" id="submit" class="btn btn-primary " value="确定执行" data-loading="稍候..."> 
                  </div>
                </div>
                
</form>
                
               
<iframe id="iframe_crud" name="iframe_crud" class="iframeMain" src="" ></iframe>
                
                
                
            
       
        <script type="text/javascript">
            $(function(){
                $("#table_name").blur( function () { 
                    var t  = $("#table_name").val();
                 
                    ajax('<?php echo url('check_tb'); ?>',{table_name:t},function(data){
                        $("#c_name").val(data.data.file_name);
                        $("#view_name").val(data.data.view_name);
                        $("#comment").val(data.data.table_name);
                        
                        $("#total_field").val(data.data.total_field);
                        $("#time_field").val(data.data.time_field);
                          $("#cate_field").val(data.data.cate_field);
                          $("#cate_comment").val(data.data.cate_comment);
                    });
               });
            })
        
        
        </script>