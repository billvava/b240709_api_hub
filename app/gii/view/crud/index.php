<form class="layui-form"  action="{:url('crud/add')}" method="post" target="iframe_crud">

    <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
        <label class='layui-form-label'>表名</label>
        <div class='layui-input-block'>
            <div><input type='text'   name="tableName" placeholder="不用写前缀" id="tableName" value="" class='layui-input'></div>
            <div class='layui-word-aux mt5'  id="tableNameMsg">输入表名即可看到效果</div>    
        </div>
    </div>
    <div class='layui-form-item  layui-col-xs10 layui-col-md4' >
        <label class='layui-form-label'>排序规则</label>
        <div class='layui-input-block'>
            <div><input type='text'  name="sort_rule" placeholder="id desc"   id="sort_rule" class='layui-input'></div>
            <div class='layui-word-aux mt5'  ></div>    
        </div>
    </div>
    <fieldset class="layui-elem-field layui-field-title" style=" overflow: hidden; clear: both;">
        <legend>列表页面设置</legend>
    </fieldset> 
    <table class="layui-table ">
        <thead>

            <tr>
                <td>字段名</td>
                <td>名称</td>
                <td style="color: red">列表显示</td>
                <td  style="color: red" class="w100">是否搜索</td>
                <td>搜索类型</td>
            </tr>
        </thead>
        <tbody id="last_edit">
            <tr>
            <td colspan="100">暂无数据</td>
            </tr>
        </tbody>
    </table>

    <fieldset class="layui-elem-field layui-field-title" style="overflow: hidden; clear: both;">
        <legend>详情页表单类型</legend>
    </fieldset>
    <table class="layui-table ">
        <thead>

            <tr>
                <td>字段名</td>
                <td>名称</td>
                <td>类型</td>
                <td>默认值</td>
                <td>其他参数</td>
            </tr>
        </thead>
        <tbody id="field_form">
            <tr>
            <td colspan="100">暂无数据</td>
            </tr>
        </tbody>
    </table>
    <fieldset class="layui-elem-field layui-field-title" style="overflow: hidden; clear: both;">
        <legend>详情页复杂表单</legend>
    </fieldset>
    <?php $fz_type  = lang('fz_type'); ?>
    <div class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-inline">
                <select class="form-control"  lay-ignore id="fzbd_type">
                    <option value="" >请选择</option>
                    <?php foreach($fz_type as $k=>$v){ ?>
                    <option value="{$k}" >{$v.name}</option>
                    <?php } ?>
                </select>
            </div>
            <button type="button" id="fzbd_type_btn" class="pear-btn pear-btn-md pear-btn-primary" >
                <i class="layui-icon layui-icon-add-1"></i>
            添加
            </button>
        </div>
    </div>
    <div class="layui-form" id="fzbd_type_div">

    </div>
    <fieldset class="layui-elem-field layui-field-title" style="overflow: hidden; clear: both;">
        <legend>列表页显示字段</legend>
    </fieldset> 
    <table class="layui-table ">
        <tbody id="index_edit">
            <tr>
                <td colspan="100">暂无数据</td>
            </tr>
        </tbody>
    </table>
   

    <fieldset class="layui-elem-field layui-field-title" style=" overflow: hidden; clear: both;">
    <legend>生成位置</legend>
    </fieldset> 
    <table class="layui-table ">
        <thead>
            <tr>
                <td>模块</td>
                <td>控制器</td>
                <td>模型</td>
                <td>视图</td>
                <td>此次模板</td>
            </tr>
        </thead>
        <tbody >
            <tr>
                <td><input type="text" name="moduleName" id="moduleName" value="<?php echo ADMIN_MODULE; ?>" class=" form-control" ></td>
                <td><input class="form-control" placeholder="默认跟表名一样" name="cName" id="cName" value=""></td>
                <td><input class="form-control"  name="modelName"  placeholder="默认跟表名一样"  id="modelName" value=""></td>
                <td><input type="text" class="form-control"  name="viewName"  placeholder="默认跟表名一样"  id="viewName" ></td>
                <td><input type="text" readonly="" class="form-control" name="theme"  placeholder="默认为default_tpl"  id="theme" value="default_tpl"></td>
            </tr>
        </tbody>
    </table>
    <table class="layui-table ">
        <thead>
            <tr>
                <td>导出</td>
                <td>搜索</td>
                <td>新增</td>
                <td>编辑</td>
                <td>删除</td>
                <td>覆盖</td>
                <td>菜单</td>
            </tr>
        </thead>
        <tbody >
            <tr>
                <td>
                    <select name="is_xls" class="form-control">
                        <option value="1">生成</option>
                        <option value="0">不生成</option>
                    </select>
                </td>
                <td>
                    <select name="is_search" class="form-control">
                        <option value="1">生成</option>
                        <option value="0">不生成</option>
                    </select>
                </td>
                <td>
                    <select name="is_add" class="form-control">
                        <option value="1">生成</option>
                        <option value="0">不生成</option>
                    </select>
                </td>
                <td>
                    <select name="is_edit" class="form-control">
                        <option value="1">生成</option>
                        <option value="0">不生成</option>
                    </select>
                </td>
                <td>
                    <select name="is_del" class="form-control">
                        <option value="1">生成</option>
                        <option value="0">不生成</option>
                    </select>
                </td>
                <td>
                    <select name="is_cover" class="form-control">
                        <option value="0">不覆盖</option>
                        <option value="1">直接覆盖</option>
                    </select>
                </td>
                <td>
                    <select name="is_create_auth" class="form-control">
                        <option value="1">生成</option>
                        <option value="0" selected="">不生成</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit  id="submit"  type="submit">立即提交</button>
        </div>
    </div>
</form>
<iframe id="iframe_crud" name="iframe_crud" class="iframeMain" src="{:url('crud/add')}" ></iframe>
{include file="crud/js" /}