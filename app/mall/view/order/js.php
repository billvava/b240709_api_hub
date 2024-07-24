
<div id="call" style="display: none; width: 500px;">
    <div class="x-body">
  <div class="modal-body">
    <form  method="post" action="{:url('send')}">
        <select class="form-control" name="send_type" id="send_type" onchange="change_send_type()">
            <?php foreach($send_type as $k=>$v){ ?>
          <option value="{$k}">{$v}</option>
            <?php } ?>
      </select>
        <div id="send_type_div1">
        <?php  $elist  =  (new \app\mall\model\ExpressCompany())->getList();  ?>
      <select class="form-control mt10" name="express_key"  >
          <option value="">请选择快递（选填）</option>
          {foreach name='elist' item='v'}
        <option value="{$v.expresskey}">{$v.expressname}</option>
        {/foreach}
      </select>
       <input value="" placeholder="请输入快递单号（选填）"   class="form-control mt10" name='express_code' />
       </div>
      <input type="hidden" name="order_id" id='order_send_order_id' value="">
      <button class="pear-btn pear-btn-primary pear-btn-sm mt10" onclick="$(this).attr('disable',true)" type="submit">确定</button>
    </form>
  </div>
        
</div>

</div>

<script type="text/javascript">
function change_send_type(){
   var v =  $("#send_type").val();
   if(v=='1'){
       $("#send_type_div1").show();
   }else{
       $("#send_type_div1").hide();
   }
}

</script>



<div id="tuikdiv" style="display: none; width: 500px;">
  <div class="x-body">
  <div class="modal-body">
      <form id="tk_form" method="post" action="{:url('refund')}">
        <p>订单号<b id="tk_ordernum"></b></p>
        <p>退款：<span class="refund_money_txt"></span></p>
      <input type="hidden" name="order_id" id='tk_order_id' value="">
      <input type="hidden" name="refund_money" id='refund_money' value="">
      <button onclick="quedingtk()" class="pear-btn pear-btn-primary pear-btn-sm mt10" style="margin-top: 10px;" onclick="$(this).attr('disable',true)" type="submit">确定</button>
    </form>
  </div>
</div>
</div>





<div id="set_total" style="display: none; width: 500px;">
  <div class="x-body">
    <div class="modal-body">
        <p>注意：修改价格后，会重新生成订单编号</p>
        <form id="set_price_form" method="post" action="{:url('set_total')}">
        <input value="" placeholder="请输入新的订单价格，最小0.01元"  class="layui-input mt10" name='total' />
        <input type="hidden" name="order_id" id='set_price_order_id' value="">
        <button  class="btn btn-large btn-block btn-default mt10" style="margin-top: 10px;" onclick="$(this).attr('disable',true)" type="submit">确定</button>
      </form>
    </div>
  </div>
</div>


<div id="up_is_pay" style="display: none; width: 500px;">
  <div class="x-body">
    <div class="modal-body">
        <form method="post" action="{:url('set_pay')}">
        <select class="form-control"  name="pay_type">
            <option value="">请选择付款方式</option>
                <?php foreach($pay_type as $k=>$v){ ?>
              <option value="{$k}">{$v}</option>
                <?php } ?>
        </select>
        <input type="hidden" name="order_id" id='up_is_pay_order_id' value="">
        <button  class="btn btn-large btn-block btn-default mt10"  onclick="$(this).attr('disable',true)" type="submit">确定</button>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

function order_is_pay(order_id){
     $("#up_is_pay_order_id").val(order_id);
     layer.open({
            type: 1,
            title:'设置订单为已付款',
            closeBtn: 1, //不显示关闭按钮
            shift: 2,
            area: '500px,300px',
            shadeClose: true, //开启遮罩关闭
            content:$("#up_is_pay")
        });
}


function set_total(order_id){
    $("#set_price_order_id").val(order_id);
     layer.open({
            type: 1,
            title:'修改订单价格',
            closeBtn: 1, //不显示关闭按钮
            shift: 2,
            area: '500px,300px',
            shadeClose: true, //开启遮罩关闭
            content:$("#set_total")
        });
}


function handle(obj){
    var t= $(obj);
    var bindtap = t.data('bindtap');
    var order_id = t.data('order_id');
    if(bindtap == 'send'){
        order_send(order_id);
        return;
    }
    load();
    ajax("{:url('up_status')}",{order_id:order_id,bindtap:bindtap},function(){
        location.reload();
    })
}
    
    
    
//设置备注
function setremark(order_id,remark){
    layer.prompt({title: '设置备注', formType: 2,value:remark,maxlength: 2500 }, function(text){
            ajax('{:url('set_val')}',{field:'admin_remark',key:'order_id',keyid:order_id,val:text},function(data){
                close();
                $(".admin_remark"+order_id).text('备注：'+text);
            });
       });
}    

/**
 * 退款
 */
function tuik(ordernum,order_id,total){
    $("#tk_order_id").val(order_id);
     $("#refund_money").val(total);
     $("#tk_ordernum").text(ordernum);
     $(".refund_money_txt").text(total);
     layer.open({
            type: 1,
            title:'真的要退款吗？',
            closeBtn: 1, //不显示关闭按钮
            shift: 2,
            area: '500px,300px',
            shadeClose: true, //开启遮罩关闭
            content:$("#tuikdiv")
        });
}
//发货
function order_send(order_id){
    $("#order_send_order_id").val(order_id);
    layer.open({
        type: 1,
        title:'设置发货信息',
        closeBtn: 1, //不显示关闭按钮
        shift: 2,
        area: '500px,300px',
        shadeClose: true, //开启遮罩关闭
        content:$("#call")
    });
}


function all_hand(field,val){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
   
    ajax('{:url('mul_set')}',{ '<?php echo $pk; ?>':ids ,field:field,val:val},function(data){
        if(data.data=='refresh'){
            location.reload();
        }
    } );
}
function get_ids(){
    var ids = [];
    $(".list-form input:checkbox:checked").each(function() {
        ids.push($(this).val());
    });
    if(ids.length <= 0){
        
        return null;
    }else{
        return ids;
    }
}
function forever_del(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var r=window.confirm("确定删除吗？");
    if(r){
        ajax('{:url('forever_del')}',{"<?php echo $pk; ?>":ids },function(){
            location.reload()
        },function(){
            location.reload();
        });
    }
}

function mul_finsh(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var r=window.confirm("确定执行确认操作吗？");
    if(r){
        ajax('{:url('finish')}',{"<?php echo $pk; ?>":ids },function(){
            location.reload()
        },function(){
            location.reload();
        });
    }
}

function mul_close(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var r=window.confirm("确定执行关闭操作吗？");
    if(r){
        ajax('{:url('close')}',{"<?php echo $pk; ?>":ids },function(){
            location.reload();
        },function(){
            location.reload();
        });
    }
}

function mul_p(){
   var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var i = ids.join(',');
    show_url('{:url('print_info')}?order_ids='+i);
}
//全选
function all_sel(){
    $("input[name='xls_field[]']").prop('checked',true);
    layui.use('form', function(){
        var form = layui.form;
        form.render('checkbox');
    });
}
//反选
function re_sel(){
    $("input[name='xls_field[]']").each(function(){
        var t = $(this);
        t.prop('checked',!t.is(":checked"));
    });
    layui.use('form', function(){
        var form = layui.form;
        form.render('checkbox');
    });
}

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
