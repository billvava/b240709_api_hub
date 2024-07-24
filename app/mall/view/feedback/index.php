<div class='x-body'>
   
<div class="layui-colla-content layui-show">
        <div class="layui-row mt10">
                
                        
            <form class="layui-form layui-col-md12 x-so" id="order_form" action="" >
                    
                        <div class="layui-row">
                            <div class="layui-input-inline">
                                <select name="status">
                                  <option value="">状态</option>
                                  <?php foreach ($feedback_status as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['status']==$k&& $in['status']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>
                             <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户编号"  class="layui-input">
                             <input type="text"   name='name' value="{$in.name}"  placeholder="姓名"  class="layui-input ">
                             <input type="text"   name='tel' value="{$in.tel}"  placeholder="手机"  class="layui-input ">
                             <input type="text"   name='remark' value="{$in.remark}"   placeholder="处理备注"  class="layui-input ">
                             <input type="text"   name='content' value="{$in.content}"   placeholder="申请缘由"  class="layui-input ">
                             
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
   
   
<div class='table-container'>
    <table class="layui-table list-form">


        <thead>
           
            <tr>
                
               
                <th >申请缘由</th>
                <th >类型</th>
                <th >状态</th>
                <th >备注</th>
                <th >图片</th>
                <th >退货物流</th>
                <th >创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php $color = lang('color');$feedback_type = lang('feedback_type'); if($data['list']){  foreach($data['list'] as $v){ ?>
         <tr>
                
                <th class="w200">{$v.content}</th>
                <th ><?php echo $feedback_type[$v['type']]; ?></th>
                <th style='<?php echo $color[$v['status']]; ?>' ><?php echo $feedback_status[$v['status']]; ?></th>
                <th>{$v.remark}</th>
                <th>
                  <?php if($v['img']){  foreach($v['img'] as $vo){ ?>
                    <a target="_blank" href="{$vo}">
                        <img src="{$vo}" width="96" height="96">
                    </a>                    
                  <?php } } ?>
                </th>
                <th>{$v.ex_name} {$v.ex_num}</th>
                <th >{$v.create_time}  <P><a href="{:url('AdminOrder/index',array('order_id'=>$v['order_id']))}">订单详情</a></P></th>
                <th>
                    <a  class="pear-btn pear-btn-primary pear-btn-sm " href="javascript://" data-type="show_url" data-url="{:url('item',array('id'=>$v['id']))}" data-title="修改状态" data-width="500"  data-height="500"  >修改状态</a>
                </th>
            </tr>
            <?php }  }?>
        </tbody>
    </table>
       {$data.page|raw}
      </div>
</div>

