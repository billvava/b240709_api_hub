<div class="x-body">
    <div class="table-container">
        <table class="layui-table">
            <thead>
                 <tr>
                    <th colspan="100">
                        <div class="layui-row">
                            <form class="layui-form layui-col-md12 x-so" id="f" action="<?php echo url(app()->request->action()); ?>">
                                <a  class="pear-btn pear-btn-primary pear-btn-sm " href="javascript:$('#xls').val(1);$('#f').submit();">导出数据</a>
                                <a  class="pear-btn pear-btn-primary pear-btn-sm "  href="<?php echo url(app()->request->action()); ?>">全部</a>
                                <a  class="pear-btn pear-btn-primary pear-btn-sm "  href="javascript:$('#xls').val(0);$('#create_time_max').val('');$('#create_time_min').val('<?php echo date('Y-m-d H:i:s',  strtotime("-30 day")) ?>');$('#f').submit();">最近30天</a>
                                <a  class="pear-btn pear-btn-primary pear-btn-sm "  href="javascript:$('#xls').val(0);$('#create_time_max').val('');$('#create_time_min').val('<?php echo date('Y-m-d H:i:s',  strtotime("-90 day")) ?>');$('#f').submit();">最近90天</a>
                                <input type="text" name="create_time_min" id="create_time_min" value="{$in.create_time_min}" readonly="" placeholder="开始时间" class="layui-input jeDateTime">
                                - 
                                <input type="text" name="create_time_max" id="create_time_max"  value="{$in.create_time_max}" readonly="" placeholder="截止时间" class="layui-input jeDateTime">
                                <input type="hidden" name="p" value="1">
                                <input type="hidden" name="xls" id="xls" value="0">
                                <button class="pear-btn pear-btn-primary pear-btn-sm" type="submit"><i class="layui-icon"></i></button>
                            </form>
                        </div>
                      
                    </th>
                </tr>
                <tr class="user-head">
                    <th>排行</th>
                    <th>分类编号</th>
                    <th>分类</th>
                    <th>数量</th>
                    <th>金额</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['list'] as $k=>$v){ ?>
                <tr>
                    <th><?php echo  $k +1; ?></th>
                    <th>{$v.category_id}</th>
                    <th><?php echo  $data['cate'][$v['category_id']]?$data['cate'][$v['category_id']]:'未归类'; ?></th>
                    <th>{$v.num}</th>
                    <th>￥{$v.total}</th>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>