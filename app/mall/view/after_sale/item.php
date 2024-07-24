<style>
    .div-flex {
        display: flex;
        align-items: center;
        justify-content: left;
    }
    .width-160 {
        width: 200px;
    }
    .layui-table th {
        text-align: center;
    }
    .table-margin{
        margin-left: 50px;
        margin-right: 50px;
        text-align: center;
    }
    .image{
        height:80px;
        width: 80px;
    }

    .mt50{
        margin-left: 50px;
    }

</style>
<div class="layui-card-body" >
    <!--基本信息-->
    <div class="layui-form" lay-filter="layuiadmin-form-after-sale" id="layuiadmin-form-after-sale" >
        <input type="hidden" class="after_sale_id" name="after_sale_id" value="{$info.id}">

        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>售后信息</legend>
            </fieldset>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">售后单号:</label>
            <div class="width-160">{$info.sn}</div>
            <label class="layui-form-label ">申请时间:</label>
            <div class="width-160">{$info.create_time}</div>
            <label class="layui-form-label ">退款方式:</label>
            <div class="width-160 refund_type_text">{$info.refund_type_text}</div>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">退款金额:</label>
            <div class="width-160 refund_price_text">￥{$info.refund_price}  <a href="javascript://" onclick="update_refund_price()">修改</a></div>
            <label class="layui-form-label ">退款原因:</label>
            <div class="width-160 ">{$info.refund_reason}</div>
            <label class="layui-form-label ">退款说明:</label>
            <div class="width-160">{$info.refund_remark}</div>
        </div>
        <?php $color = lang('color'); ?>
        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">申请状态:</label>
            <div class="width-160" style="<?php echo $color[$info['status']]; ?>">{$info.status_text}</div>
            <label class="layui-form-label ">售后说明:</label>
            <div class="width-160">{$info.admin_remark}</div>
        </div>

        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>订单信息</legend>
            </fieldset>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">订单编号:</label>
            <div class="width-160">{$info.ordernum}</div>
            <label class="layui-form-label ">订单金额:</label>
            <div class="width-160">￥{$info.total}</div>
            <label class="layui-form-label ">付款方式:</label>
            <div class="width-160 pay_way_text">{$info.pay_type_str}</div>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">配送方式:</label>
            <div class="width-160">物流配送</div>
            <label class="layui-form-label ">订单状态:</label>
            <div class="width-160">{$info.order_status}</div>
        </div>

        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>会员信息</legend>
            </fieldset>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">会员编号:</label>
            <div class="width-160">{$info.user.id}</div>
            <label class="layui-form-label ">会员昵称:</label>
            <div class="width-160">{$info.user.nickname}</div>
            <label class="layui-form-label ">手机号码:</label>
            <div class="width-160">{$info.user.tel}</div>
        </div>

      

        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>退款商品</legend>
            </fieldset>
        </div>

        <div class="layui-form-item table-margin">
            <table class="layui-table">
                <colgroup>
                    <col width="250">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                </colgroup>
                <thead>
                <tr>
                    <th>商品信息</th>
                    <th>价格(元)</th>
                    <th>数量</th>
                    <th>小计(元)</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($info['order_goods']?:[] as $k=>$goods){ ?>
                <tr>
                    <td>
                        <div style="text-align: left">
                            <div class="layui-col-md3">
                                <img src="{$goods.thumb}" class="image-show image" >
                            </div>
                            <div class="layui-col-md9">
                                <p style="margin-top: 10px">{$goods.name}</p>
                                <br>
                                <p>{$goods.spec_str}</p>
                            </div>
                        </div>
                    </td>
                    <td>￥{$goods.unit_price}</td>
                    <td>{$goods.num}</td>
                    <td>{$goods.total_price}</td>
                </tr>
                    <?php } ?>
                <tr>
                    <td colspan="3"></td>
                    <td style="text-align: left;">
                        <div>退款金额:<span style="color: red">￥{$info.refund_price}</span> </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php if($info['status']>3 && $info['invoice_no']&& $info['refund_type']==1){ ?>
        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>收货信息</legend>
            </fieldset>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">快递公司:</label>
            <div class="width-160">{$info.express_name}</div>
            <label class="layui-form-label ">快递单号:</label>
            <div class="width-160">{$info.invoice_no}</div>
            <label class="layui-form-label ">快递说明:</label>
            <div class="width-160">{$info.express_remark}</div>
        </div>

        <div class="layui-form-item div-flex">
            <label class="layui-form-label ">退货地址:</label>
            <div class="width-160 refund_address_text">--</div>
            <label class="layui-form-label ">入库方式:</label>
            <div class="width-160"></div>
            <label class="layui-form-label ">收货时间:</label>
            <div class="width-160">{$info.confirm_take_time}</div>
        </div>
        <?php } ?>

        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>售后操作</legend>
            </fieldset>
        </div>

        <div class="layui-form-item div-flex ">
            <div class="layui-input-block ">

                 <?php if($info['status']==0){ ?>
<!--                <button type="button" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="agree">商家同意</button>-->
                <button type="button" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="agree">确定退款</button>



                     <button type="button" class="layui-btn layui-btn-sm layui-btn-danger width_160 " id="refuse">商家拒绝</button>
                 <?php } ?>


               <?php if($info['status']==3){ ?>
                <button type="button" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="take_goods">商家收货</button>
                <button type="button" class="layui-btn layui-btn-sm layui-btn-danger width_160 " id="refuse_goods">拒绝收货</button>
                <?php } ?>

                <?php if($info['status']==5){ ?>
                <button type="button" class="layui-btn layui-btn-sm layui-btn-normal width_160 " id="confirm">确认退款</button>
                   <?php } ?>

                <div class="layui-card">
                    <div class="layui-card-header">温馨提示</div>
                    <div class="layui-card-body">
                        退款仅是订单退款而已，其他优惠券不会变动，需要退回这些请到订单列表《关闭订单》
                    </div>
                </div>

            </div>
        </div>
        
        <div class="layui-form-item">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>售后日志</legend>
            </fieldset>
        </div>

        <div class="layui-form-item div-flex">
            <div class="layui-input-block ">
            <ul class="layui-timeline">
                <?php foreach($info['log'] as $vo){ ?>
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                        <h3 class="layui-timeline-title">{$vo.content}</h3>
                        <p>{$vo.create_time}</p>
                    </div>
                </li>
                <?php } ?>
            </ul>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
function update_refund_price(){
    
     layer.prompt({title: '输入新的金额', formType: 0}, function(pass, index){
        layer.close(index);
        ajax('{:url("update_refund_price")}',{id:'<?php echo $info['id']; ?>',refund_price:pass},function(res){
            location.reload();
        });

      });
}    
    
$(function(){
    //商家同意
        $('#agree').click(function () {
            layer.confirm(
                "提示：商家同意后，请前往订单列表手动退款回会员账户。"+"<br/>"+
                "退款金额：<?php echo $info['refund_price']; ?><span style='color: red'></span>", {
                btn: ['确认','取消'] 
            }, function(){
                ajax('{:url("agree")}',{id:'<?php echo $info['id']; ?>'},function(res){
                    location.reload();
                });
               
            });
        });

        //商家拒绝
        $('#refuse').click(function () {
            layer.confirm(
                "提示：请与会员协商后确认拒绝申请，会员可再次发起退款。"+"<br/>"+
                "拒绝原因："+"<textarea id='remark' name='remark' class='layui-textarea' type='text' style='height:100px'></textarea>", {
                    btn: ['确认','取消'] //按钮
                }, function(){
                    var remark = $('#remark').val();
                    if (remark == null || remark == undefined || remark == ''){
                        layer.msg('请填写拒绝原因!');
                        return false;
                    }
                    ajax('{:url("refuse")}',{id:'<?php echo $info['id']; ?>',remark:remark},function(res){
                        location.reload();
                    });
                    return false;
                });
        });


        //商家收货
        $('#take_goods').click(function () {
            layer.confirm(
                "提示：商家同意后，退款将自动原路退回会员账户。"+"<br/>"+
                "退款金额："+ "<span style='color: red'><?php echo $info['refund_price']; ?></span>"+ "<br/>"+
                "退货地址：<?php echo $info['refund_address']; ?>", {
                    btn: ['确认','取消'] //按钮
                }, function(){
                    ajax('{:url("takeGoods")}',{id:'<?php echo $info['id']; ?>'},function(res){
                        location.reload();
                    });
                   
                });
        });



        //商家拒绝收货
        $('#refuse_goods').click(function () {
            layer.confirm(
                "提示：请与会员协商后确认拒绝申请，会员可再次发起退款。"+"<br/>"+
                "退款金额："+ "<span style='color: red'><?php echo $info['refund_price']; ?></span>"+ "<br/>"+
                "拒绝原因："+"<textarea id='remark' name='remark' class='layui-textarea' type='text' style='height:100px'></textarea>", {
                    btn: ['确认','取消'] //按钮
                }, function(){
                    var remark = $('#remark').val();
                    if (remark == null || remark == undefined || remark == ''){
                        layer.msg('请填写拒绝原因!');
                        return false;
                    }
                     ajax('{:url("refuseGoods")}',{id:'<?php echo $info['id']; ?>','remark':remark},function(res){
                        location.reload();
                    });
                  
                    return false;
                });
        });



        //确认退款
        $('#confirm').click(function () {
            layer.confirm(
                "提示：商家确认退款后，退款将自动原路退回会员账户。"+"<br/>"+
                "退款金额："+ "<span style='color: red'><?php echo $info['refund_price']; ?></span>",  {
                    btn: ['确认','取消'] //按钮
                }, function(){
                      ajax('{:url("confirm")}',{id:'<?php echo $info['id']; ?>'},function(res){
                        location.reload();
                    });
                });
        });
    
})
</script>