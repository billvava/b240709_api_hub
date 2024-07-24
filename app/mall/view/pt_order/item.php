<div class='x-body'>
    <div class="layui-collapse" lay-filter="component-panel">
   
   
   
   
<div class='table-container'>
    <table class="layui-table list-form">


        <thead>
            
            <tr>
              
                <th >订单编号</th>
                <th >订单信息</th>
                <th >收货信息</th>
                <th >时间</th>
                <th >金额</th>
                <th>状态</th>
                <th>当前进度</th>
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
            <?php if($data['list']){  foreach($data['list'] as $v){ ?>
                <tr>
                  
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
                   </td>


                    <td>
                        <p>方式：<?php echo $send_type[$v['send_type']]; ?> <a href="javascript://" onclick="show_url('{:url('set_address',array('order_id'=>$v['order_id']))}','修改地址')" >修改</a></p>
                        <p>姓名：{$v.linkman}【{$v.tel}】</p>
                        <p>地址：{$v.province} {$v.city} {$v.country} {$v.address}</p>
                        <p>买家留言：<font style="color:red;">{$v['message']?$v['message']:'无'}</font></p>
                    </td>

                    <td>
                        <p><?php echo $v['create_time'];  ?></p>
                        <p>IP:<a href="https://www.baidu.com/s?wd={$v.ip}" title="查看IP所在地" target="_blank">{$v.ip}</a></p>
                    </td>

                   <td>
                       <?php foreach($money_field as $kk=>$vv){ if($v[$kk]<=0){     continue;} ?>
                       <p>{$vv}：￥<?php echo $v[$kk]; ?></p>
                       <?php } ?>
                       <p style="color:red;">实付金额：￥{$v.total}</p>
                    </td>
                    
                   <td>
                       <p class="<?php echo $txt_class[$v['pay_type']]; ?>">支付方式：<?php echo $pay_type[$v['pay_type']]; ?></p>
                       <p class="<?php echo $txt_class[$v['is_pay']]; ?>">付款状态：<?php echo $is_pay[$v['is_pay']]; ?></p>
                       <p class="<?php echo $txt_class[$v['is_send']]; ?> ">发货状态：<?php echo $order_is_send[$v['is_send']]; ?>
                       <?php if($v['is_send']==1){ ?>
                         <a href="javascript://" onclick="show_url('{:url('send_item',array('order_id'=>$v['order_id']))}','发货信息')">查看</a>
                       <?php } ?>
                       
                       </p>
                       <p class="<?php echo $is[$v['is_finish']]['class']; ?>">确认状态：<?php echo $is[$v['is_finish']]['name']; ?></p>
                       <p class="<?php echo $is[$v['is_finish']]['class']; ?>">评论状态：<?php echo $is[$v['is_comment']]['name']; ?></p>
                       <p class="<?php echo $is[$v['is_finish']]['class']; ?>">关闭状态：<?php echo $is[$v['is_close']]['name']; ?></p>
                       <p class="<?php echo $txt_class[$v['feedback_status']]; ?>  ">售后状态：<?php echo $feedback_status[$v['feedback_status']]; ?>  
                           <?php if($v['feedback_status']!=0){ ?>
                          <a href="javascript://" onclick="show_url('{:url('feedback',array('order_id'=>$v['order_id']))}','售后处理')">查看</a>
                           <?php } ?>
                       </p>
                       <?php if($v['refund_money']>0){ ?><p class="x-red">已退款<?php  echo $v['refund_money']; ?>元</p><?php } ?>
                    </td> 
                    
                   <td>
                      
                      <?php $option = $v['status_data'];  ?>
                      <p style="color:{$option.color}">{$option.status}</p>

                        
                   </td>

               </tr>
            <?php }  } ?>
             

        </tbody>
    </table>
       {$data.page|raw}
      </div>
</div>

{include file="order/js" /}
