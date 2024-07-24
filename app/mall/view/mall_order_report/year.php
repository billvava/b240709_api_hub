<div class="x-body">
    {include file="mall_order_report/nav" /}
    <?php 
    $names = array(); 
    $vals = array();  
    $ts = array();
    foreach($data as $k=>$v){ 
        $vals[] = $v['count']; 
        $names[] = "'".$v['month']."年'";
        if($total_field){
            $ts[] = $v['total']+0;
        }
    } 
    $append_data = "";
    if($ts){
        $append_data = ",{
            name:'总和',
            type: 'bar',
            barMaxWidth:30,
            data: append_data,
        }";
    }
    ?>
      <div class="mt10">
      
        
          
          
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
        text: "按年{$name}",
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
         <?php echo $append_data; ?>
    ]
};
var myChart = echarts.init(document.getElementById('main'));
 myChart.setOption(option);
</script>
