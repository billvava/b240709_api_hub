<div class='x-body'>
     <fieldset class="layui-elem-field layui-field-title">
        <legend>批量发货</legend>
    </fieldset>
      <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回订单</a>
    </xblock>
    <div class="table-container">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>收货人</th>
                    <th>手机</th>
                    <th>地址</th>
                    <th>支付状态</th>
                    <th>配送方式</th>
                    <th>快递公司</th>
                    <th>快递单号</th>
            </thead>
            <tbody>
            <form action="{:url('send')}" method="post">
               <?php foreach($data as $v){ ?>
                <tr>
                    <th>{$v.ordernum}</th>
                    <th>{$v.linkman}</th>
                    <th>{$v.tel}</th>
                    <th>{$v.province} {$v.city} {$v.country} {$v.address}</th>
                     <th><?php echo $pay_type[$v['pay_type']]; ?> <?php echo $is_pay[$v['is_pay']]; ?></th>
                     <th>
                        <select class="form-control w100 " name="send_type[{$v.order_id}]" id="send_type{$v.order_id}" onchange="change_send_type({$v.order_id})">
                        <?php foreach($send_type as $k=>$vv){ ?>
                        <option value="{$k}">{$vv}</option>
                        <?php } ?>
                        </select>
                     </th>
                     <th>
                        <?php  $elist  =  (new \app\mall\model\ExpressCompany())->getList();  ?>
                        <select class="form-control w100 wuliu{$v.order_id}" name="express_key[{$v.order_id}]"  >
                            <option value="{$v.expresskey}">请选择</option>
                            {foreach name='elist' item='vv'}
                          <option value="{$vv.expresskey}">{$vv.expressname}</option>
                          {/foreach}
                        </select>
                         
                     </th>
                     <th>
                         <input type="text" class="form-control w100  wuliu{$v.order_id}" placeholder="请输入" name="express_code[{$v.order_id}]" />
                     </th>
                </tr>
               <?php } ?>
                <?php if(!$data){ ?>
                <tr >
                    <th colspan="100">暂无数据</th>
                </tr>
                <?php }else{ ?>
                <tr >
                    <th colspan="100"><button style=" float: right;" class="pear-btn pear-btn-primary pear-btn-sm" type="submit">确定</button></th>
                </tr>
                <?php } ?>
                </form>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
function change_send_type(id){
    var t = $("#send_type"+id);
    if(t.val()=='1'){
        $(".wuliu"+id).show();
    }else{
        $(".wuliu"+id).hide();
    }
}
</script>