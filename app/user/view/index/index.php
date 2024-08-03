<div class="x-body ">
    {include file="index/top" /}
   
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text" name="id" value="{$in.id}"  placeholder="用户编号"  class="layui-input">
            <input type="text" name="username" value="{$in.username}"  placeholder="用户名"  class="layui-input">
            <input type="text" name="nickname" value="{$in.nickname}"  placeholder="昵称"  class="layui-input">
            <?php $is = lang('is'); $orders = array('dot'=>'按积分','bro'=>'按佣金','money'=>'按余额'); ?>
            <div class="layui-input-inline">
                <select name="status">
                  <option value="">状态</option>
                  <?php foreach($is as $k=>$v){ ?>
                  <option value="{$k}"  <?php if($in['status']!='' && $in['status']==$k){echo 'selected=""';} ?>>{$v.str}</option>
                  <?php } ?>
                </select>
              </div>
             <div class="layui-input-inline">
                <select name="rank">
                  <option value="">等级</option>
                  <?php foreach($ranks as $k=>$v){ ?>
                  <option value="{$k}"  <?php if($in['rank']!='' && $in['rank']==$k){echo 'selected=""';} ?>>{$v}</option>
                  <?php } ?>
                </select>
              </div>
            <div class="layui-input-inline">
                <select name="order">
                  <option value="">排序</option>
                  <?php foreach($orders as $k=>$v){ ?>
                  <option value="{$k}"  <?php if($in['order']!='' && $in['order']==$k){echo 'selected=""';} ?>>{$v}</option>
                  <?php } ?>
                </select>
              </div>
            <input type="text"   name='start_datetime' value="{$in.start_datetime}" readonly=""  placeholder="注册开始时间"  class="layui-input jeDateTime">
            <input type="text"   name='end_datetime' value="{$in.end_datetime}"  readonly="" placeholder="注册截止时间"  class="layui-input jeDateTime">
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <div class="table-container">
    <table class="layui-table ">
    <thead>
        <tr>
            <th>编号</th>
            <th>头像</th>
            <th>用户名</th>
<!--            <th>昵称</th>-->
            <th>手机</th>
            <th>余额</th>
            <th>绿色积分</th>
            <th>代金券</th>
            <th>进货券</th>
            <th>兑换券</th>
            <th>贡献值</th>
             <th>推广码</th>
            <th>推荐人ID</th>
            <th>注册</th>
            <th>等级</th>
<!--            <th>代理</th>-->
<!--            <th>分公司</th>-->
<!--            <th>董事</th>-->
<!--            <th>合伙人</th>-->
<!--            <th>区域</th>-->
            <th>状态</th>
            <th>操作</th>
            
            
        </tr>
    </thead>
    <tbody>
        <?php $rank_all=(new \app\user\model\User())->rank_all(); ?>
        {foreach name="data.list" item="v"}
        <?php  $is_ball = ($v['openid'] || $v['unionid'] )?1:0; ?>
        <tr class="layui-form">
               <td>{$v.id}</td>
               <td><?php echo_img($v['headimgurl']); ?></td>
               <td>{$v.username}</td>
<!--                <td>{$v.nickname}</td>-->
               <td>{$v.tel}</td>
               <td>{$v.money}</td>
            <td>{$v.lvse_dot}</td>
               <td>{$v.daijinquan}</td>
            <td>{$v.jinhuoquan}</td>
            <td>{$v.duihuanquan}</td>
            <td>{$v.gongxianzhi}</td>
                <td>{$v.invitation_code}</td>
               <td>{$v.pid}</td>
               <td><?php echo $v['create_time'];  ?></td>
               <td><?php echo $ranks[$v['rank']];  ?></td>

<!--            <td><p>--><?php //echo fast_check(array(
//                    'key'=>'id',
//                    'keyid'=>$v['id'],
//                    'field'=>'is_agent',
//                    'url'=>url('set_val'),
//                    'txt'=>"是",
//                    'check'=>$v['is_agent']
//                )); ?><!--</p>-->
<!---->
<!--            </td>-->
<!---->
<!--            <td><p>--><?php //echo fast_check(array(
//                        'key'=>'id',
//                        'keyid'=>$v['id'],
//                        'field'=>'is_company',
//                        'url'=>url('set_val'),
//                        'txt'=>"是",
//                        'check'=>$v['is_company']
//                    )); ?><!--</p>-->
<!---->
<!--            </td>-->

<!--            <td><p>--><?php //echo fast_check(array(
//                    'key'=>'id',
//                    'keyid'=>$v['id'],
//                    'field'=>'is_director',
//                    'url'=>url('set_val'),
//                    'txt'=>"是",
//                    'check'=>$v['is_director']
//                )); ?><!--</p>-->
<!---->
<!--            </td>-->
<!---->
<!--            <td><p>--><?php //echo fast_check(array(
//                        'key'=>'id',
//                        'keyid'=>$v['id'],
//                        'field'=>'is_hehuo',
//                        'url'=>url('set_val'),
//                        'txt'=>"是",
//                        'check'=>$v['is_hehuo']
//                    )); ?><!--</p>-->
<!---->
<!--            </td>-->





<!--              <td>--><?php //echo $v['address_text'];  ?><!--</td>-->
               <td><p><?php echo fast_check(array(
                      'key'=>'id',
                       'keyid'=>$v['id'],
                       'field'=>'status',
                      'url'=>url('set_val'),
                      'txt'=>"正常",
                        'check'=>$v['status']
                  )); ?></p>
               <p><?php echo $is_ball==1?'已绑微信':'未绑微信'; ?></p>
               </td>
               <td>
                   
                   <div class="btn-group"><a href='{:url('item_show',array('id'=>$v['id']))}'  class="pear-btn pear-btn-primary">详情</a>
                        <button class="pear-btn pear-btn-primary" type="button" data-toggle="dropdown">操作<i class="iconfont icon-unfold"></i></button>
       
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
          <li>
              <a  href="###" onclick="show_url('{:url('Address/index',array('user_id'=>$v['id']))}')">地址信息</a>
          </li>

            <!--       <?php if(is_dir(app()->getBasePath()."Mall")){ ?>
          <li>
              <a  href="###" onclick="show_url('{:url('Mall/Coupon/index',array('user_id'=>$v['id']))}')">优惠券</a>
          </li>
          
          <li>
              <a  href="###" onclick="show_url('{:url('Mall/Coupon/send',array('user_id'=>$v['id']))}')">发放优惠券</a>
          </li>
          <?php } ?>
          <li>
            <a onclick="show_url('<?php //echo url('User/Msg/index',array('user_id'=>$v['id'])) ?>//')" href="javascript://">站内信</a>
          </li>
           <li>
           <a onclick="show_url('<?php //echo url('User/Msg/item',array('user_id'=>$v['id'])) ?>//')" href="javascript://">发送站内信</a>
          </li>
                                   <li>
            <a onclick="show_url('<?php //echo url('User/Msg/wx_msg',array('user_id'=>$v['id'])) ?>//')" href="javascript://">发送微信消息</a>
          </li>
       
          
       <li>
             <a  href="###" onclick="show_url('{:url('Log/dot',array('user_id'=>$v['id']))}')">积分记录</a>
          </li> -->
           <li>
              <a  href="###" onclick="show_url('{:url('Log/money',array('user_id'=>$v['id']))}')">余额记录</a>
          </li>
<!--           <li>-->
<!--              <a  href="###" onclick="show_url('{:url('Log/bro',array('user_id'=>$v['id']))}')">佣金记录</a>-->
<!--          </li>-->

            <li>
                <a onclick="del('{:url('user_del',array('id'=>$v['id']))}')" href="javascript://">删除</a>
            </li>

          <li>
            <a href="###"  onclick="show_url('{:url('item',array('id'=>$v['id']))}')">编辑</a>
          </li>

            <!-- <li>
     <a  onclick="del('{:url('user_res',array('id'=>$v['id']))}','确定重置密码为<?php //echo C('res_pwd'); ?>//吗？')" href="javascript://">重置密码</a>
       </li>
            -->
          <li>
            <a  onclick="log_handle('<?php echo $v['id']; ?>','充值余额','money',1)"   href="javascript://">充值余额</a>
          </li>
          <li>
            <a  onclick="log_handle('<?php echo $v['id']; ?>','扣除余额','money',2)"  href="javascript://">扣除余额</a>
          </li>

            <li>
                <a  onclick="log_handle('<?php echo $v['id']; ?>','充值绿色积分','lvse_dot',1)"   href="javascript://">充值绿色积分</a>
            </li>
            <li>
                <a  onclick="log_handle('<?php echo $v['id']; ?>','扣除绿色积分','lvse_dot',2)"  href="javascript://">扣除绿色积分</a>
            </li>

            <li>
                <a  onclick="log_handle('<?php echo $v['id']; ?>','充值代金券','daijinquan',1)"   href="javascript://">充值代金券</a>
            </li>
            <li>
                <a  onclick="log_handle('<?php echo $v['id']; ?>','扣除代金券','daijinquan',2)"  href="javascript://">扣除代金券</a>
            </li>
          
          
         <!-- <li>
            <a  onclick="log_handle('<?php echo $v['id']; ?>','增加积分','dot',1)"  href="javascript://">增加积分</a>
          </li>
          <li>
            <a  onclick="log_handle('<?php echo $v['id']; ?>','扣除积分','dot',2)" href="javascript://">扣除积分</a>
          </li>
          -->

            <li style="display: none">
            <a  onclick="log_handle('<?php echo $v['id']; ?>','增加佣金','bro',1)" href="javascript://">增加佣金</a>
          </li>
          <li style="display: none">
            <a onclick="log_handle('<?php echo $v['id']; ?>','扣除佣金','bro',2)" href="javascript://">扣除佣金</a>
          </li>
          
          
         
          
          <li>
            <a  onclick="show_url('<?php echo url('show_parent',array('user_id'=>$v['id'])); ?>')" href="javascript://" >查看Ta的上级</a>
          </li>
          
          <li>
            <a onclick="show_url('<?php echo url('tree_img',array('user_id'=>$v['id'])); ?>')" href="javascript://">查看Ta的下级</a>
          </li>

            <!--
          <?php  if($is_ball){ ?>
           <li>
               <a  onclick="del('<?php echo url('clearwx',array('id'=>$v['id'])); ?>')" href="javascript://">解除微信绑定</a>
          </li>
          <?php } ?>
            -->
          <li class="divider"></li>

        </ul>
      </div>
                     </td>
           </tr>
       {/foreach}
       
        
    </tbody>
</table>
    </div>    
       {$data.page|raw}
</div>
<?php $user_money_cate = lang('user_money_cate');  $user_bro_cate = lang('user_bro_cate');  $user_dot_cate = lang('user_dot_cate');  ?>

<script type="text/html" id="money_tpl">
<?php foreach($user_money_cate as $k=>$v){ ?>
<option value="{$k}">{$v}</option>
<?php } ?>
</script>

<script type="text/html" id="bro_tpl">
<?php foreach($user_bro_cate as $k=>$v){ ?>
<option value="{$k}">{$v}</option>
<?php } ?>
</script>


<script type="text/html" id="dot_tpl">
<?php foreach($user_dot_cate as $k=>$v){ ?>
<option value="{$k}">{$v}</option>
<?php } ?>
</script>

<div id="call" style="display: none; width: 500px;">
    <div class="x-body">
  <div class="modal-body">
      <form  id="log_handle_form">
        <select class="form-control" name="cate" id="cate_select" onchange="change_send_type()">
               
        </select>
       <input value="" placeholder="&nbsp;&nbsp;额度"   class="form-control mt10" name='total' />     
       <input value="" placeholder="&nbsp;&nbsp;备注（选填）"   class="form-control mt10" name='msg' />
      <input type="hidden" name="user_id" id='user_id' value="">
      <input type="hidden" name="table" id='table' value="">
      <input type="hidden" name="type" id='type' value="">
      <button class="pear-btn pear-btn-primary mt10" onclick="sub()" type="button">确定</button>
    </form>
  </div>
        
</div>

</div>
<script type="text/javascript">
function sub(){
    ajax('{:url('log_handle')}',  $('#log_handle_form').serialize(), function(data) {
        document.location.reload();
    }, function(data) {
        log_handle(user_id,msg,table,type);
    });
}    
function log_handle(user_id,msg,table,type){
    $("#cate_select").html($("#"+table+"_tpl").html());
    $("#user_id").val(user_id);
    $("#table").val(table);
    $("#type").val(type);
      layer.open({
        type: 1,
        title: '用户ID【' + user_id +"】"+ msg,
        closeBtn: 1, //不显示关闭按钮
        shift: 2,
        area: '500px,300px',
        shadeClose: true, //开启遮罩关闭
        content:$("#call")
    });
    
}
</script>
