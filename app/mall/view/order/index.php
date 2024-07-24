<div class='x-body'>
    <div class="layui-collapse" lay-filter="component-panel">
    <div class="layui-colla-item">
      <h2 class="layui-colla-title">提示<i class="layui-icon layui-colla-icon"></i></h2>
      <div class="layui-colla-content">
           <blockquote class="layui-elem-quote">
            <p>关闭订单，会返回订单相关/积分/优惠券给用户，微信支付的金额需要另外《点击退款》</p>
            <p>《确认订单》会执行发放积分/佣金的设置</p>
        </blockquote>
          
      </div>
      
    </div>
   
   {include file="order/search" /}
   
   
<div class='table-container'>
    <table class="layui-table list-form">


        <thead>
            <tr>
                <th  colspan="100">
                    <a  class="pear-btn pear-btn-primary pear-btn-sm " href="{:url('mul_send')}"  >批量发货</a>
                </th>
             
            </tr>
            <tr>
                 <th  class="w50">
                        <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                       <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</a>
                    </th>
                <th >订单编号</th>
                <th >订单信息</th>
                <th >收货信息</th>
                <th >时间</th>
                <th >金额</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php 
          
            $ho = new \app\home\model\O();
           
            $color = lang('color');

            ?>
            <?php if(!$data['list'] ){ ?>
             <tr>
                 <td colspan="100" style=" text-align: center; height: 100px;">当前条件暂无数据</td>
             </tr>
            <?php } ?>
            <?php foreach($data['list'] as $v){ ?>
                <tr>
                    <th class="w50"><input type="checkbox" lay-ignore value="<?php echo $v['order_id']; ?>"  /></th> 
                  
                   <td>
                       <p>【{$v.order_id}】{$v.ordernum}</p>
                       <p class="<?php echo $is[$v['is_u_del']]['class']; ?>">前台：<?php echo $is[$v['is_u_del']]['show']; ?></p>
                       <p class="<?php echo $is[$v['is_a_del']]['class']; ?>">后台：<?php echo $is[$v['is_a_del']]['show']; ?></p>
                         {foreach name="v.goods_list" item="g"}
                        <p class="text-primary">{$g.name} {$g.spec_str} X {$g.num}</p>
                        {/foreach}
                     
                     </div>
                   </td>


                   <td>
                       <p>后台：{$v['admin_remark']?$v['admin_remark']:'无'}</p>
                       <p>
                         买家：【{$v.user_id}】{$v.username}
                     </p>
                       <p style="<?php echo $color[$v['type']]; ?>">
                           订单类型：<?php echo $order_type[$v['type']]; ?>
                       </p>
                       <p >
                           供应商：<?php echo $v['shop_info']['name']; ?>
                       </p>
                   </td>


                    <td>
                        <p>方式：<?php echo $send_type[$v['send_type']]; ?> <a href="javascript://" onclick="show_url('{:url('set_address',array('order_id'=>$v['order_id']))}','修改地址')" >修改</a></p>
                        <p>姓名：{$v.linkman}【{$v.tel}】</p>
                        <p>地址：{$v.province} {$v.city} {$v.country} {$v.address}</p>
                        <p>买家留言：<font style="color:red;">{$v['message']?$v['message']:'无'}</font></p>
                    </td>

                    <td>
                        <p><?php echo $v['create_time'];  ?></p>
                    </td>

                   <td>
                       <?php foreach($money_field as $kk=>$vv){ if($v[$kk]<=0){     continue;} ?>
                       <p>{$vv}：￥<?php echo $v[$kk]; ?></p>
                       <?php } ?>
                       <p style="color:red;">实付金额：￥{$v.total}</p>
                    </td>
                    
                   <td>
                       <p style="<?php echo $color[$v['status']]; ?>"><?php echo $order_status[$v['status']]; ?></p>

                       <?php if($v['pay_status']>0){ ?>
                       <p style="<?php echo $color[$v['pay_type']]; ?>">支付方式：<?php echo $pay_type[$v['pay_type']]; ?></p>
                       <?php } ?>
                       <p style="<?php echo $color[$v['pay_status']]; ?>">付款状态：<?php echo $is_pay[$v['pay_status']]; ?></p>
                       <p style="<?php echo $color[$v['delivery_status']]; ?> ">发货状态：<?php echo $order_is_send[$v['delivery_status']]; ?>
                       <?php if($v['delivery_status']==1){ ?>
                         <a href="javascript://" onclick="show_url('{:url('send_item',array('order_id'=>$v['order_id']))}','发货信息')">查看</a>
                       <?php } ?>
                       
                       </p>

                       <?php if($v['app_btn']['after_res'] == 1){ ?>
                       <p style="<?php echo $color[$v['after_status']]; ?> ">相关售后：<?php echo $v['after_status_str']; ?>
                               <a href="javascript://" onclick="show_url('{:url('AfterSale/index',array('order_id'=>$v['order_id']))}','售后')">查看</a>

                       </p>
                     <?php } ?>
                      
                       <?php if($v['refund_total']>0){ ?><p class="x-red">已退款<?php  echo $v['refund_total']; ?>元</p><?php } ?>
                    </td> 
                    
                   <td>
                      
                       <?php foreach($v['admin_btn'] as $av){ ?><span onclick="handle(this)" data-bindtap="{$av.bindtap}"  data-order_id="{$v.order_id}"  data-ordernum="{$v.ordernum}" class=" pear-btn pear-btn-sm <?php echo $av['btn']; ?>">{$av.name}</span><?php } ?>
                        <div class="btn-group dropup mt5">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                          更多
                          <i class="iconfont icon-unfold"></i>
                        </button>
                        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="dropdownMenu1">
                             
                          <li>
                            <a onclick="order_send({$v['order_id']})" href="javascript://">设置发货信息</a>
                          </li>  
                          <li>
                            <a onclick="order_is_pay({$v['order_id']})" href="javascript://">设置订单为已付款</a>
                          </li> 
                          <li>
                              <a href="javascript://" onclick="show_url('{:url('print_info',array('order_id'=>$v['order_id']))}')">打印订单</a>
                          </li>  
                           <li>
                              <a href="javascript://" onclick="del('{:url('feie_print',array('order_id'=>$v['order_id']))}','确定执行飞鹅打印？')">飞鹅打印</a>
                          </li>  
                          <li>
                            <a onclick="del('{:url('finish',array('order_id'=>$v['order_id']))}','确定设置订单为已确认？')" href="javascript://">确认订单</a>
                          </li>  
                          <li>
                            <a onclick="del('{:url('close',array('order_id'=>$v['order_id']))}','确定关闭这个订单？')" href="javascript://">关闭订单</a>
                          </li> 
                          <li>
                            <a  onclick="setremark({$v['order_id']},'{$v['admin_remark']?$v['admin_remark']:'无'}')" href="javascript://">设置后台备注</a>
                          </li>  
                          <?php if($v['is_pay']==0 && $v['is_finish']==0){ ?>
                          <li>
                              <a  href="javascript://"  onclick="set_total({$v['order_id']})">修改订单金额</a>
                          </li>
                          <?php } ?>
                           <li>
                            <a onclick="show_url('<?php echo url('User/AdminMsg/item',array('user_id'=>$v['user_id'])) ?>')" href="javascript://">发送站内信</a>
                          </li> 
						   <li>
                            <a onclick="show_url('<?php echo url('User/AdminMsg/wx_msg',array('user_id'=>$v['user_id'])) ?>')" href="javascript://">发送微信消息</a>
                          </li> 
                           <li>
                            <a onclick="show_url('<?php echo url('log',array('ordernum'=>$v['ordernum'],'order_id'=>$v['order_id'])) ?>')" href="javascript://">查看订单日志</a>
                          </li>  

                           <li>
                            <a onclick="show_url('<?php echo url('bro_info',array('order_id'=>$v['order_id'])) ?>','佣金信息')" href="javascript://">佣金信息</a>
                          </li>

                        <?php if($v['pay_status'] ==1){ ?>
                        <li><a href="javascript://" onclick="tuik('{$v.ordernum}','{$v.order_id}','{$v.total}')">点击退款</a></li>
                        <?php } ?>

                        </ul>
                      </div>
                   </td>

               </tr>
            <?php } ?>
                <tr>
                    <td colspan="100">
                      <a  href="javascript://" onclick="mul_finsh()"   >确认订单</a> |
                      <a  href="javascript://" onclick="mul_close()"   >关闭订单</a>  |
                      <a  href="javascript://" onclick="mul_p()"  >打印</a>  |
                      <a  href="javascript://" onclick="all_hand('is_a_del',1)"  >后台隐藏</a>  |
                      <a  href="javascript://" onclick="all_hand('is_a_del',0)"  >后台显示</a>  |
                      <a  href="javascript://" onclick="all_hand('is_u_del',1)"  >前台隐藏</a>  |
                      <a  href="javascript://" onclick="all_hand('is_u_del',0)"   >前台显示</a>  |
                      <a  href="javascript://" onclick="forever_del()"   >永久删除</a>
                    </td>
                </tr>

        </tbody>
    </table>
       {$data.page|raw}
      </div>
</div>
{include file="order/js" /}

