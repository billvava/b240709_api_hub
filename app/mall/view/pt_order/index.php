


<div class='x-body' >
  <div class="layui-collapse" lay-filter="component-panel" >
    <div class="layui-colla-item">
      <h2 class="layui-colla-title">订单筛选<i class="layui-icon layui-colla-icon"></i></h2>
      <div class="layui-colla-content layui-show">
        <div class="layui-row mt10">
                <?php 
                  $pt_type = lang('pt_type');
        $pt_status = lang('pt_status');
                ?>
                        
            <form class="layui-form layui-col-md12 x-so" id="order_form" action="" style=" padding: 10px;">
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>基础筛选</legend>
                        </fieldset>
                        <div class="layui-row">
                            

                            <div class="layui-input-inline">
                                <select name="type">
                                  <option value="">类型</option>
                                  <?php foreach ($pt_type as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['type']==$k&& $in['type']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>

                             <div class="layui-input-inline">
                                <select name="status">
                                  <option value="">进度</option>
                                  <?php foreach ($pt_status as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['status']==$k&& $in['status']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div> 

                           

                        </div>
                         <div class="layui-row mt5">
                             <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户编号"  class="layui-input">
                            <input type="text" name="ordernum" value="{$in.ordernum}"  placeholder="订单编号"  class="layui-input">
                             <input type="text"   name='p_ordernum' value="{$in.p_ordernum}"  placeholder="团号"  class="layui-input ">
                             <input type="text"   name='mul_time' value="{$in.mul_time}" readonly=""  id="mul_time"  placeholder="创建时间"  class="layui-input ">
                            <input type="text"   name='goods_id' value="{$in.goods_id}"  placeholder="商品编号"  class="layui-input ">
                            <input type="text"   name='goods_name' value="{$in.goods_name}"   placeholder="商品名称"  class="layui-input ">
                        </div>

                            
                        <fieldset class="layui-elem-field layui-field-title">
                        </fieldset>
                          <div class="layui-row mt5">  
                           <button class="pear-btn pear-btn-primary pear-btn-sm " onclick="$('#order_form').attr('action','{:url('index')}');" type="submit">确认搜索</button>
                           <a class="pear-btn pear-btn-primary pear-btn-sm " href="{:url('index')}">重置搜索</a>
                          </div>

                    </form>
                  


            </div>
      </div>
    </div>
    
  </div>

    
    
    <div class="table-container">

        <table class="layui-table layui-form" >
   
    <thead>
        <tr>
            
             <th class="w50">系统编号</th>
             <th>订单号</th>
            <th>团号</th>
             <th>类型</th>
              <th>创建</th>
            <th>进度</th>
            <th>商品</th>
             <th>用户</th>
           <th>门店</th>
           
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php $color=lang('color'); ?>
        {foreach name='data.list' item='v' key='k' }
          <tr>
              <th class="w50"><?php echo $v['id']; ?></th>
              <th >
                 <?php echo $v['ordernum']; ?>
                  </th>
<th> <?php echo $v['p_ordernum']; ?></th>

<th><?php echo $v['type_str']; ?>

</th>
<th><?php echo $v['time']; ?>

</th>
<th >
    
    <p style="<?php echo $color[$v['status']]; ?>"><?php echo $v['status_str'];  ?></p>
         <p ><?php echo $v['num'];  ?>/<?php echo $v['group_num'];  ?></p>
        </th>
<th>【{$v.goods_id}】<?php echo $v['goods_name']; ?></th>
<th>【{$v.user_id}】<?php  echo \think\facade\Db::name('user')->where(array('id'=>$v['user_id']))->cache(true)->value('nickname'); ?></th>

<th><?php echo $shops[$v['shop_id']]; ?></th>
                <th>
                    <a href="javascript://" onclick="show_url('<?php echo url('order/index',array('p_ordernum'=>$v['p_ordernum']) ); ?>')" class="pear-btn pear-btn-primary pear-btn-sm ">该团订单</a>
                </th>
            </tr>     
         {/foreach}
         <?php if(!$data['list']){ ?>
         <tr>
             <td colspan="100" style="text-align: center">
               暂无数据
             </td>
         </tr>
         <?php } ?>
         
         </form>
    </tbody>
</table>
              {$data.page|raw}
    </div>
</div>
<script type="text/javascript">
$(function(){
    layui.use('laydate', function(){
        var laydate = layui.laydate;
         //日期范围
        laydate.render({
           theme: '#677ae4',
          elem: '#mul_time'
          ,range: true
        });
        laydate.render({
           theme: '#677ae4',
          elem: '#mul_pay_time'
          ,range: true
        });
    });
    
  
//    $('#xls_field_check').sortable({selector: '.layui-form-checkbox'})
})
</script>
