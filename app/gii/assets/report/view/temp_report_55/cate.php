
<div class="x-body">
    {include file="#view_name#/nav" /}
    <table class="layui-table" style=" text-align: center;">
            <tr style=" background: #f1f1f1;">
                <td>
                    分类
                </td>
                <td>
                    数量
                </td>
                <td>
                    比例
                </td>
            </tr>
            <?php
            $total = array_sum( array_column($data, 'count')) + 0;
           foreach($data as $v){
               $b1 = number_format($v['count']/$total, 4, '.', '')*100;
            ?>
            <tr>
                <td>
                     <?php echo $cate_lan[$v[$cate_field]] ? $cate_lan[$v[$cate_field]]:$v[$cate_field]; ?>
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