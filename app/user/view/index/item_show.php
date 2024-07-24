<?php $ho = new \app\home\model\O(); ?>
<link href="__ADMIN__/css/template.css" rel="stylesheet" type="text/css"/>
<div class="layui-fluid layadmin-homepage-fluid mt10">
  <div class="layui-row layui-col-space8">
    <div class="layui-col-md2">
      <div class="layadmin-homepage-panel layadmin-homepage-shadow">
        <div class="layui-card text-center">
          <div class="layui-card-body">
            <div class="layadmin-homepage-pad-ver">
              <img class="layadmin-homepage-pad-img" src="{$data.uinfo.headimgurl}" width="96" height="96">
            </div>
            <h4 class="layadmin-homepage-font">{$data.uinfo.username}</h4>
            <p class="layadmin-homepage-min-font">{$data.uinfo.rank_name}</p>
            <div class="layadmin-homepage-pad-ver">
              {$data.uinfo.create_time}
            </div>
          </div>
        </div>
        <p class="layadmin-homepage-about">
          编号：{$data.uinfo.id}  <br/>
          昵称：{$data.uinfo.nickname}<br/>
          <?php if($data['uinfo']['birthday']){  ?>
          生日：{$data.uinfo.birthday}
          <?php } ?>
        </p>
        <ul class="layadmin-homepage-list-group">
            <?php if($data['uinfo']['province']){  ?>
          <li class="list-group-item"><i class="layui-icon layui-icon-location"></i><?php echo $ho->getAreas($data['uinfo']['province']); echo $ho->getAreas($data['uinfo']['city']); ?></li>
            <?php } ?>
        </ul>
       
       
      </div>
    </div>
    <div class="layui-col-md10">
      <div class="layui-fluid layadmin-homepage-content">
        <div class="layui-row  layadmin-homepage-padding15">
          <hr class="new-section-xs">
          <div class="layui-col-md12">
              <a onclick="show_url('{:url('item',array('id'=>$data['uinfo']['id']))}')" href="javascript://" class="pear-btn pear-btn-primary layui-btn-warm">编辑信息</a>
               <?php if(is_dir(app()->getBasePath()."Mall")){ ?>
              <a  href="javascript:;" class="pear-btn pear-btn-primary" onclick="show_url('{:url('Mall/Coupon/index',array('user_id'=>$data['uinfo']['id']))}')">优惠券</a>
              <a  href="javascript:;" class="pear-btn pear-btn-primary" onclick="show_url('{:url('Mall/Coupon/send',array('user_id'=>$data['uinfo']['id']))}')">发放优惠券</a>
                <?php } ?>
            <a onclick="show_url('<?php echo url('User/Msg/index',array('user_id'=>$data['uinfo']['id'])) ?>')" href="javascript://"  class="pear-btn pear-btn-primary">站内信</a>
            <a onclick="show_url('<?php echo url('User/Msg/item',array('user_id'=>$data['uinfo']['id'])) ?>')" href="javascript://"  class="pear-btn pear-btn-primary">发送站内信</a>
            <a onclick="show_url('<?php echo url('User/Msg/wx_msg',array('user_id'=>$data['uinfo']['id'])) ?>')" href="javascript://"  class="pear-btn pear-btn-primary">发送微信消息</a>
            
             <a  href="javascript://"  class="pear-btn pear-btn-primary" onclick="show_url('{:url('Log/dot',array('user_id'=>$data['uinfo']['id']))}')">积分记录</a>
             <a  href="javascript://"  class="pear-btn pear-btn-primary" onclick="show_url('{:url('Log/money',array('user_id'=>$data['uinfo']['id']))}')">余额记录</a>
             <a  href="javascript://"  class="pear-btn pear-btn-primary" onclick="show_url('{:url('Log/bro',array('user_id'=>$data['uinfo']['id']))}')">佣金记录</a>
             
              <a  onclick="show_url('<?php echo url('show_parent',array('user_id'=>$data['uinfo']['id'])); ?>')"  class="pear-btn pear-btn-primary" href="javascript://" >Ta的上级</a>
              
               <a  onclick="show_url('<?php echo url('tree_img',array('user_id'=>$data['uinfo']['id'])); ?>')"  class="pear-btn pear-btn-primary" href="javascript://" >Ta的下级</a>
             <a  onclick="show_url('<?php echo url('Log/login',array('user_id'=>$data['uinfo']['id'])); ?>')"  class="pear-btn pear-btn-primary" href="javascript://" >登陆日志</a>
          </div>
        </div>
          
        <div class="layui-row layui-col-space20 layadmin-homepage-list-imgtxt">
            <div class="layui-col-md9">
                <div class="table-container">
                <table class="layui-table" >
                    <tr>
                        <td>邮箱：{$data['uinfo']['email']?$data['uinfo']['email']:'无'}</td>
                        <td>Q&nbsp;&nbsp;Q：{$data['uinfo']['email']?$data['uinfo']['email']:'无'}</td>
                        <td>注册IP：{$data['uinfo']['create_ip']?$data['uinfo']['create_ip']:'无'}</td>
                    </tr>
                    <tr>
                        <td>手机：{$data['uinfo']['tel']?$data['uinfo']['tel']:'无'}</td>
                        <td>上级：{$data['uinfo']['pid']?$data['uinfo']['pid']:'无'}</td>
                        <td>性别：<?php echo $sex[$data['uinfo']['sex']+0]; ?></td>
                    </tr>
                    <tr>
                        <td>微信：{$data['uinfo']['weixin']?$data['uinfo']['weixin']:'无'}</td>
                        <td>姓名：{$data['uinfo']['realname']?$data['uinfo']['realname']:'无'}</td>
                        <td>生日：{$data['uinfo']['birthday']?$data['uinfo']['birthday']:'无'}</td>
                    </tr>
                    
                  
                </table>
                    
                  <div class="layui-card">
                    <div class="layui-card-header">统计信息</div>
                    <div class="layui-card-body clearfix">
                        <div class="table-container">
                           
                            <table class="layui-table" style=" text-align: center;" >
                                <tr style=" font-weight: bold;background-color: #f2f2f2;">
                                    <td>积分</td>
                                    <td>佣金</td>
                                    <td>余额</td>
                                    <td>粉丝</td>
                                    <td>消费金额</td>
                                    <td>支付订单数量</td>
                                    <td>售后订单数量</td>
                                    <td>评价数量</td>
                                    <td>收藏商品数量</td>
                                </tr>
                            <tr>
                                <td>{$data['finfo']['dot']}</td>
                                <td>{$data['finfo']['bro']}</td>
                                <td>{$data['finfo']['money']}</td>
                                
                                <td>{$data.einfo.share_num}</td>
                                <td>{$data.einfo.mall_total}</td>
                                <td>{$data.einfo.mall_order_num}</td>
                                <td>{$data.einfo.mall_feek_num}</td>
                                <td>{$data.einfo.mall_com_num}</td>
                                <td>{$data.einfo.mall_atn_num}</td>
                            </tr>
                        </table>
                        </div>
                        
                    </div>   
                 </div>
                    
                    
                <div class="layui-card">
                    <div class="layui-card-header">收货信息<a href="{:url('Address/index',array('user_id'=>$data['uinfo']['id']))}" class="fr">查看更多</a></div>
                    <div class="layui-card-body clearfix">
                        <div class="table-container">
                           
                            <table class="layui-table" style=" text-align: center;" >
                                <tr style=" font-weight: bold;background-color: #f2f2f2;">
                                    <td>姓名</td>
                                    <td>手机</td>
                                    <td>地址</td>
                                    <td>默认</td>
                                </tr>
                                <?php foreach($data['add_list']['list'] as $v){ ?>
                                <tr class="layui-form">
                                    <td>{$v.name}</td>
                                    <td>{$v.tel}</td>
                                    <td>{$v.address}</td>
                                    <td>      
                                    <input type="checkbox" <?php if($v['is_default']==1){echo 'checked=""';} ?> disabled="" name="open" lay-skin="switch"  lay-text="默认">
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <?php if(!$data['add_list']['list']){ ?>
                                <tr >
                                    <td colspan="100">暂无数据</td>
                                </tr>
                                <?php } ?>
                            
                        </table>
                        </div>
                        
                    </div>   
                 </div> 
                    
<!--                --><?php //if(is_dir(app()->getBasePath().'Mall')){  $data['order_data'] = D('Mall/MallOrder')->getData(array('user_id'=>$data['uinfo']['id'],'get_status'=>1)); ?>
<!--                 <div class="layui-card">-->
<!--                    <div class="layui-card-header">订单记录<a href="{:url('Mall/Order/index',array('user_id'=>$data['uinfo']['id']))}" class="fr">查看更多</a></div>-->
<!--                    <div class="layui-card-body clearfix">-->
<!--                        <div class="table-container">-->
<!--                           -->
<!--                            <table class="layui-table" style=" text-align: center;" >-->
<!--                                <tr style=" font-weight: bold;background-color: #f2f2f2;">-->
<!--                                    <td>订单号</td>-->
<!--                                    <td>金额</td>-->
<!--                                    <td>状态</td>-->
<!--                                </tr>-->
<!--                                --><?php //foreach($data['order_data']['list'] as $v){ ?>
<!--                                <tr class="layui-form">-->
<!--                                    <td>{$v.ordernum}</td>-->
<!--                                    <td>{$v.goods_total}</td>-->
<!--                                    <td>{$v['status_data']['status']}</td>-->
<!--                                </tr>-->
<!--                                --><?php //} ?>
<!--                                 --><?php //if(!$data['order_data']['list']){ ?>
<!--                                <tr >-->
<!--                                    <td colspan="100">暂无数据</td>-->
<!--                                </tr>-->
<!--                                --><?php //} ?>
<!--                        </table>-->
<!--                        </div>-->
<!--                        -->
<!--                    </div>   -->
<!--                 </div>   -->
<!--                --><?php //} ?>
                    
                    
            </div>
          
        </div>
      </div>
    </div>
  </div>
</div>