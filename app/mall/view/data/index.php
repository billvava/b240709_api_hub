
<style>
    body {
                background-color: whitesmoke !important;
        }
    .wait_handle{
        font-size: 18px;
    }
    .wait_handle .layui-col-sm4{
        padding: 0 50px;
    }
    .wait_handle_item{
        width:100%;
        display: block;
        height: 50px;
        line-height: 50px;
        border-bottom: 1px solid #e6e6e6;
    }
    
    .operate{
        display: block;
        
    }
    #operate a{
        display: block;
        text-align: center;
        width: 140px;
        padding-bottom: 10px;
        float: left;
        color: #666;
    }
    #operate a:hover{
        color: #fff;
        background: #677ae4;
    }
    #operate > a > i{
        display: block;
        font-size: 50px;
        line-height: 85px;
    }
    #operate a cite{
      display: block;
         font-size: 20px;
    line-height: 26px;
    }
    
    .overview{
        
    }
    .overview div{
        display: block;
        text-align: center;
        padding-bottom: 10px;
        float: left;
        color: #666;
    }
    .overview span{
        display: block;
        font-size: 50px;
        line-height: 85px;
        color: red;
    }
    .overview cite{
      display: block;
         font-size: 20px;
    line-height: 26px;
    font-style: normal;
    }
</style>
<!--<link href="__ADMIN__/css/control.css" rel="stylesheet" type="text/css"/>-->
<div class="layui-fluid x-body">



    <?php
    tool()->func('html');
    echo api_query();
    ?>

    <div class="layui-row layui-col-space15">

        <?php foreach($data['data_any'] as $v){ ?>
        <div class="layui-col-sm6 layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    {$v.title}
                    <span class="layui-badge {$v.class} layuiadmin-badge">{$v.alias}</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font">{$v.pre}{$v.count}</p>
                    <p>
                        {$v.name} 
                    </p>
                </div>
            </div>
        </div>
        <?php } ?>
     
        
        
        <div class="layui-col-sm12">
            <div class="layui-card">
              <div class="layui-card-header">
                待处理事务
            
              </div>
             
              <div class="layui-card-body">
                <div class="layui-row wait_handle">
                  <div class="layui-col-sm4">
                      <a class="wait_handle_item" href="{:url('mall/Order/index',array('status'=>0))}"><span class="fl">待付款订单</span><span class="fr <?php echo $data['census']['wait_pay_count']>0?' x-red':''; ?>">（{$data['census']['wait_pay_count']}）</span></a>
                      <a class="wait_handle_item" href="{:url('mall/Order/index',array('status'=>1))}"><span class="fl">待发货订单</span><span class="fr <?php echo $data['census']['wait_send_count']>0?' x-red':''; ?>">（{$data['census']['wait_send_count']}）</span></a>

                  </div>
                      <div class="layui-col-sm4">
                     <a class="wait_handle_item" href="{:url('mall/Order/index',array('status'=>2))}"><span class="fl">待确认订单</span><span class="fr <?php echo $data['census']['wait_finish_count']>0?' x-red':''; ?>">（{$data['census']['wait_finish_count']}）</span></a>
                      <a class="wait_handle_item" href="{:url('mall/Order/index',array('status'=>4))}"><span class="fl">已关闭订单</span><span class="fr <?php echo $data['census']['wait_send_count']>0?' x-red':''; ?>">（{$data['census']['wait_send_count']}）</span></a>

                  </div>
                    
                </div>
              </div>
            </div>
          </div>
        
        
        <?php $navs = array(
            
            array(
                'name'=>'添加商品',
                'ico'=>'icon-add',
                'url'=>url('Goods/item'),
            ),
            array(
                'name'=>'订单列表',
                'ico'=>'icon-activity_fill',
                'url'=>url('Order/index'),
            ),
             array(
                'name'=>'用户管理',
                'ico'=>'icon-group_fill',
                'url'=>url('user/Index/index'),
            ),
            
         
            array(
                'name'=>'广告管理',
                'ico'=>'icon-task_fill',
                'url'=>url(ADMIN_URL. '/SystemAd/index'),
            ),
//            array(
//                'name'=>'秒杀管理',
//                'ico'=>'icon-time_fill',
//                'url'=>url('KillGoods/index'),
//            ),
        ); ?>
         <div class="layui-col-sm12">
            <div class="layui-card">
              <div class="layui-card-header">
                运营快捷入口
              </div>
                <div class="layui-card-body clearfix " id="operate">
                    <?php foreach($navs as $v){ ?>
                  <a href="{$v.url}">
                      <i class="iconfont {$v.ico}" ></i>
                      <cite>{$v.name}</cite>
                  </a>
                 <?php } ?>
              </div>
            </div>
          </div>
        <div class="layui-col-md12 clearfix" >
            <div class="layui-card  layui-col-md5 fl"  style="width: 49%;">
                    <div class="layui-card-header">商品总览</div>
                    <div class="layui-card-body clearfix overview">
                        <?php foreach($data['goods_overview'] as $v){ ?>
                         <div class="layui-col-md3 layui-col-xs12">
                            <span>{$v.count}</span>
                            <cite>{$v.name}</cite>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="layui-card  layui-col-md5 fr"  style="width: 49%;" >
                    <div class="layui-card-header">用户总览</div>
                    <div class="layui-card-body clearfix overview">
                        <?php foreach($data['user_overview'] as $v){ ?>
                         <div class="layui-col-md3 layui-col-xs12">
                            <span>{$v.count}</span>
                            <cite>{$v.name}</cite>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            
        </div>
       
        
        <div class="layui-col-md12" >
                <div class="layui-card">
                    <div class="layui-card-header">最近七天</div>
                    <div class="layui-card-body clearfix ">
                        <div class="layui-col-md6 layui-col-xs12">

                        <div id="main" style="width: 100%;height:400px;"></div>

                        </div>
                         <div class="layui-col-md6 layui-col-xs12">

                        <div id="main2" style="width: 100%;height:400px; "></div>

                        </div>   
                    </div>
                </div>
            </div>

    </div>
</div>

<script src="__LIB__/echarts.min.js" type="text/javascript"></script>
<script  type="text/javascript">
var dataAxis =  [ <?php echo implode(',', $data['oqi']['names']) ; ?>];
var data = [<?php echo implode(',', $data['oqi']['c']) ; ?>];
var append_data = [<?php echo implode(',', $data['oqi']['t']) ; ?>];
var yMax = 500;
var dataShadow = [];
option = {
    title: {
        text: "最近七天有效订单",
        subtext: '',
        left:100,
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
            type : 'line'        // 默认为直线，可选为：'line' | 'shadow'
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
            barMaxWidth:15

        },
         {
            name:'金额',
            type: 'bar',
            data: append_data,
            barMaxWidth:15
        }
    ]
};
var myChart = echarts.init(document.getElementById('main'));
 myChart.setOption(option);
 
 
 
 
 
 
 var dataAxis2 =  [ <?php echo implode(',', $data['uqi']['names']) ; ?>];
var data2 = [<?php echo implode(',', $data['uqi']['c']) ; ?>];
option2 = {
    title: {
        text: "最近七天新增会员",
        subtext: '',
        left:100,
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
            type : 'line'       
        }
    },
     xAxis: {
        data: dataAxis2,
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
            type: 'line',
            data: data2,
            barMaxWidth:15

        }
    ]
};
var myChart2 = echarts.init(document.getElementById('main2'));
 myChart2.setOption(option2);
</script>