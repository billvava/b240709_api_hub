<div class="x-body">
    {include file="mall_order_report/nav" /}
    <?php    
    ?>
<div class="mt10">
    <div class=" table-container">
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
        </table>
    <div id="main" style="width: 100%;height:600px; margin-top: 20px;"></div>
    </div>
    
</div>

<script src="__LIB__/echarts.min.js" type="text/javascript"></script>
<script  type="text/javascript">
var dataAxis =  [ <?php echo implode(',', $data['names']) ; ?>];
var data = [<?php echo implode(',', $data['count']) ; ?>];
var yMax = 500;
var dataShadow = [];
option = {
    title: {
        text: "订单金额分布",
        subtext: ''
    },
     toolbox: {
        show: true,
        feature: {
            left:100,
            magicType: {type: ['line', 'bar']},
            restore: {},
            saveAsImage: {}
        }
    },
     tooltip : {
        trigger: 'axis',
        axisPointer : {          
            type : 'shadow'
        }
    },
    xAxis: {
        data: dataAxis,
         axisTick: {
            alignWithLabel: true
        },
        axisLine: {
            show: false
        },
        z: 10
    },
    yAxis: {
        axisLine: {
            show: false
        },
        axisTick: {
            show: false
        },
        axisLabel: {
            textStyle: {
                color: '#999'
            }
        }
    },
    dataZoom: [
        {
            type: 'inside'
        }
    ],
    series: [
        {
            name:'数量',
            type: 'bar',
            data: data,
            barMaxWidth:30
        }
    ]
};
var myChart = echarts.init(document.getElementById('main'));
 myChart.setOption(option);
</script>
