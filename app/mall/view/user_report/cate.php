
<div class="x-body">
    {include file="user_report/nav" /}
    <table class="layui-table" style=" text-align: center;">
             <tr style=" background: #e8e8e8;">
                <td colspan="100">
                    <div class="layui-row">
                            <form class="layui-form layui-col-md12 x-so" id="f" action="<?php echo url(app()->request->action()); ?>">
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
                </td>
               
            </tr>
            <tr style=" background: #f1f1f1;">
                <td>
                    等级
                </td>
                <td>
                    数量
                </td>
                <td>
                    比例
                </td>
            </tr>
            <?php
             $ranks = (new \app\user\model\User())->getRanks(true);
            $total = array_sum( array_column2($data, 'count')) + 0;
           foreach($data as $v){
               $b1 = number_format($v['count']/$total, 4, '.', '')*100;
            ?>
            <tr>
                <td>
                     <?php echo $ranks[$v[$cate_field]]; ?>
                </td>
                <td>
                    {$v.count}
                </td>
                <td>
                 
                    <div class="layui-progress layui-progress-big" lay-showpercent="true">
                    <div class="layui-progress-bar" lay-percent="{$b1}%"></div>
                  </div>

                </td>
            </tr>
           <?php } ?>
            
        </table>
</div>

<script src="__LIB__/echarts.min.js" type="text/javascript"></script>