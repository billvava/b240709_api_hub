<div class="layui-collapse" lay-filter="component-panel" >
    <div class="layui-colla-item">
      <h2 class="layui-colla-title">订单筛选<i class="layui-icon layui-colla-icon"></i></h2>
      <div class="layui-colla-content layui-show">
        <div class="layui-row mt10"  style=" padding: 10px;">
                
            <div class="layui-row ">
                            <a class="pear-btn  pear-btn-sm  <?php if($in['status']==''){ echo "pear-btn-primary"; } ?>"  href="{:url('index')}">全部订单 <b>{$census.all_count}</b></a>
                            <?php foreach($order_status as $k=>$v){ ?>
                           <a class="pear-btn pear-btn-sm <?php if($in['status']!='' && $in['status'] == $k){ echo "pear-btn-primary";} ?>"  href="{:url('index',array('status'=>$k))}">{$v} <b><?php echo $census[$order_status_field[$k]]; ?></b></a>
                            <?php } ?>
                            <a class="pear-btn pear-btn-sm"  href="###" onclick="$('#order_form').toggle()">更多条件</a>
                        </div>   
            <form class="layui-form layui-col-md12 x-so" id="order_form" action="" style=" display: none;">
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>基础筛选</legend>
                        </fieldset>
                        <div class="layui-row">
                            <div class="layui-input-inline">
                                <select name="pay_status">
                                  <option value="">支付状态</option>
                                  <?php foreach ($is_pay as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['is_pay']==$k&& $in['is_pay']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>
                             <div class="layui-input-inline">
                                <select name="pay_type">
                                  <option value="">支付方式</option>
                                  <?php foreach ($pay_type as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['pay_type']==$k&& $in['pay_type']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>
                             <div class="layui-input-inline">
                                <select name="delivery_status">
                                  <option value="">发货状态</option>
                                  <?php foreach ($order_is_send as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['delivery_status']==$k&& $in['delivery_status']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>

                            <div class="layui-input-inline">
                                <select name="delivery_type">
                                  <option value="">发货方式</option>
                                  <?php foreach ($send_type as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['delivery_type']==$k&& $in['delivery_type']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>

                          


                            <div class="layui-input-inline">
                                <select name="type">
                                  <option value="">订单类型</option>
                                  <?php foreach ($order_type as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['type']==$k&& $in['type']!=''){echo 'selected=""';} ?> >{$v}</option>
                                  <?php  } ?>
                                </select>
                              </div>

                            <div class="layui-input-inline">
                                <select name="is_a_del">
                                  <option value="">后台显示</option>
                                  <?php foreach ($is as $k => $v) { ?>
                                      <option value="{$k}" <?php if($in['is_a_del']==$k&& $in['is_a_del']!=''){echo 'selected=""';} ?> >{$v.show}</option>
                                  <?php  } ?>
                                </select>
                              </div>

                        </div>
                         <div class="layui-row mt5">
                             <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户编号"  class="layui-input">
                            <input type="text" name="ordernum" value="{$in.ordernum}"  placeholder="订单编号"  class="layui-input">
                             <input type="text"   name='mul_time' value="{$in.mul_time}" readonly=""  id="mul_time"  placeholder="创建时间"  class="layui-input ">
                             <input type="text"   name='mul_pay_time' value="{$in.mul_pay_time}" readonly=""  id="mul_pay_time"  placeholder="支付时间"  class="layui-input ">
                            <input type="text"   name='username' value="{$in.username}"  placeholder="用户名"  class="layui-input ">
                            <input type="text"   name='admin_remark' value="{$in.admin_remark}"   placeholder="后台备注"  class="layui-input ">
                        </div>

                        <div class="layui-row mt5">
                           <input type="text"   name='province' value="{$in.province}"   placeholder="收货省份"  class="layui-input ">
                            <input type="text"   name='city' value="{$in.city}"   placeholder="收货城市"  class="layui-input ">
                            <input type="text"   name='p_ordernum' value="{$in.p_ordernum}"   placeholder="父级单号"  class="layui-input ">
                            <input type="text"   name='address' value="{$in.address}"   placeholder="收货地址"  class="layui-input ">
                            <input type="text"   name='linkman' value="{$in.linkman}"   placeholder="收货人"  class="layui-input ">
                            <input type="text"   name='tel' value="{$in.tel}"   placeholder="收货手机"  class="layui-input ">
                            <input type="hidden"  name="p" value="1">
                        </div>
              
                            <div id="xls_field_div" style=" display: none;">
                                <fieldset class="layui-elem-field layui-field-title">
                                    <legend>导出设置 <a  class="x-a" href="javascript://" onclick="all_sel()" >全选</a> | 
                <a class="x-a"  href="javascript://" onclick="re_sel()">反选</a></legend>
                                </fieldset>
                                <div class="layui-row mt5" id="xls_field_check">
                                     <?php $order_xls_field = explode(',', C('order_xls_field')) ;  foreach($xls_field as $k=>$v){ ?>
                                     <input type='checkbox' name='xls_field[]' lay-skin='primary' value='{$v.field}' title='{$v.name}' <?php if(in_array($v['field'], $order_xls_field)){ echo "checked=''";} ?> >
                                     <?php } ?>
                                </div>
                            </div>
                        <fieldset class="layui-elem-field layui-field-title">
                        </fieldset>
                          <div class="layui-row mt5">  
                           <button class="pear-btn pear-btn-primary pear-btn-sm " onclick="$('#order_form').attr('action','{:url('index')}');" type="submit">确认搜索</button>
                           <a class="pear-btn pear-btn-primary pear-btn-sm " href="{:url('index')}">重置搜索</a>
                           <button class="pear-btn pear-btn-primary pear-btn-sm " onclick="$('#order_form').attr('action','{:url('xls')}');" type="submit" >导出结果</button>
                           <button class="pear-btn pear-btn-primary pear-btn-sm " type="button" onclick="$('#xls_field_div').toggle()" >导出设置</button>
                          </div>

                    </form>
                  


            </div>
      </div>
    </div>
    
  </div>
