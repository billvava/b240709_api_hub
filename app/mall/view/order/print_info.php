<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>订单打印</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <link href="__LIB__/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <link href="__PUBLIC__/admin/css/xadmin.css" rel="stylesheet" type="text/css"/>
    <script src="__LIB__/jquery.min.js" type="text/javascript"></script>
    <style>
        .address p{
            width: 100%;
            display: block;
        }
         .address p span{
            width: 48%;
            display: inline-block;
        }
    </style>
</head>
<body>
    
    <div class="x-body">
    <blockquote class="layui-elem-quote"  style=" text-align: right">
        <a class="pear-btn pear-btn-primary pear-btn-sm">返回</a> <a class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="p()">打印</a>
    </blockquote>    
        <?php foreach($data['list'] as $v){ ?>
        <table class="layui-table" style=" margin-bottom: 50px;">
        <thead>
              <tr>
                <th colspan="100" style=" text-align: center; height: 50px; font-size: 16px;">
                <p>订单信息</p>
                <p>{$v.ordernum}</p>
                </th>
               
                </tr>
            <tr>
                <th>
                  货号
                </th>
                <th>商品名称</th>
                <th>单价</th>
                <th>属性</th>
                <th>数量</th>
                <th>小计</th>
                </tr>
        </thead>
        <tbody>
        <?php foreach($v['goods_list'] as $g){ ?>
                <tr>
                    <th>
                      {$g.sku}
                    </th>
                    <th>{$g.name}</th>
                    <th>￥{$g.unit_price}</th>
                    <th>
                        <?php $pa = json_decode($g['param'],true); ?>
                        <?php if($pa[0]){echo '【'; foreach($pa as $gp){echo $gp['v']."&nbsp;";}echo '】';  } ?>
                    </th>
                    <th>{$g.num}</th>
                    <th>￥{$g.total_price}</th>
                </tr>
        <?php } ?>
            
            <tr>
                <th colspan="100" style=" text-align: right">
        <P><?php foreach($money_field as $kk=>$vv){ if($v[$kk]<=0){     continue;} ?>
                    {$vv}：￥<?php echo $v[$kk]; ?>  
                    <?php } ?>
                    <b style=" color: red">订单总金额：¥{$v['total']}</b>
                    </P>
                 <P>支付状态：<?php echo $is_pay[$v['is_pay']]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支付方式：<?php echo $pay_type[$v['pay_type']]; ?></P>
                 </th>
            </tr>
            <tr>
                <th colspan="100"  style=" text-align: left" class="address">
                    <p><span >用户名：{$v.username}</span>  <span>收货方式：<?php echo $send_type[$v['send_type']]; ?></span></p>
                    <?php if($v['send_type']!=2){ ?>
                    <p><span>收货人：{$v.linkman}</span>  <span>手机：<?php echo $v['tel']; ?></span></p>
                    <p>地址：{$v.province} {$v.city} {$v.country} {$v.address}</p>
                    <p><span>客户留言：{$v['message']?$v['message']:'无'}</span> <span >后台备注：{$v['admin_remark']?$v['admin_remark']:'无'}</span></p>
                    <p><span>下单时间：{$v['create_time']}</span> <span >订单号：{$v['ordernum']}</span></p>
                    <?php } ?>
                 </th>
            </tr>
        </tbody>
    </table>  
        
        <?php } ?>
        </div>
    
    <script type="text/javascript">
    function p(){
        $(".layui-elem-quote").remove();
        window.print();
    }
    </script>
</body>
</html>