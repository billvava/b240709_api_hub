<div class='x-body '  >
    {include file="mall_order_report/nav" /}
    <?php 
    $names = array(); 
    $vals = array();  
    $ts = array();
    foreach($data as $k=>$v){ 
        $vals[] = $v; 
        $names[] = "'".$k."'";
        if($total_field){
            $ts[] = $totals[$k]+0;
        }
    } 
    $append_data = "";
    if($ts){
        $append_data = ",{
            name:'总和',
            type: 'bar',
            data: append_data,
        }";
    }
    
    ?>
      <div class="mt10">
   
       <table class="layui-table" style=" text-align: center;">
                <tr style=" background: #e8e8e8;">
                    <td colspan="100">
                        <div class="layui-row">
                                  <form class="layui-form layui-col-md12 x-so" action="<?php echo url(app()->request->action()); ?>" method="get">
            <input type="text" value="{$time}" readonly="" name="time"    class="layui-input jeYear">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
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
var dataAxis =  [ <?php echo implode(',', $names) ; ?>];
var data = [<?php echo implode(',', $vals) ; ?>];
var append_data = [<?php echo implode(',', $ts) ; ?>];
var yMax = 500;
var dataShadow = [];
option = {
    title: {
        text: "{$time}{$name}",
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
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
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
         
        }
         <?php echo $append_data; ?>
    ]
};
var myChart = echarts.init(document.getElementById('main'));
 myChart.setOption(option);
</script>
