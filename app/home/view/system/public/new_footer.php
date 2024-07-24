<style>
/*底部菜单 start*/
.sell_footer { width: 100%; position: fixed; left: 0; bottom: 0; background: #fff;  z-index: 999;    border-top: 1px solid #E5E5E5;}
.sell_footer ul { overflow: hidden;}
.sell_footer li { width: 20%; float: left; text-align: center; }
.sell_footer a { display: block; padding: 5px 0; color: #444;}
.sell_footer a.act{  color: <?php echo $wap_shop_color['main']; ?>;}
.sell_footer a span { display: block; font-size: 12px; line-height: 18px;}
.sell_footer a i { font-size: 26px; line-height: 30px;}
/*底部菜单 end */
</style>
            <div style="width: 100%; height: 100px;"></div>
<?php $on_sell = C('on_sell'); ?>
<div class="sell_footer">
    <ul>
        <li <?php if($on_sell==0){ ?>style="width:25%;"<?php } ?>><a <?php if(is_home()){echo ' class="act"';} ?> href="{:url('/')}"><i class="fa fa-home"></i><span>首页</span></a></li>
        <li <?php if($on_sell==0){ ?>style="width:25%;"<?php } ?>><a  <?php if($cate['catid']==6 || $cate['pid']==6){echo ' class="act"';} ?> href="<?php echo $lib->get_list_url(6); ?>"><i class="fa fa-list-ul"></i><span>商品</span></a></li>
        <?php if($on_sell==1){ ?>
        <li ><a   <?php if(MODULE_NAME=='Sell'){echo ' class="act"';} ?> href="{:url('/sell')}"><i class="fa fa-sitemap"></i><span>分销中心</span></a></li>
        <?php } ?>
        
        <li <?php if($on_sell==0){ ?>style="width:25%;"<?php } ?>><a   <?php if(CONTROLLER_NAME=='Order'){echo ' class="act"';} ?> href="<?php echo url('/H/order/man'); ?>"><i class="fa fa-shopping-cart"></i><span>订单</span></a></li>
        <li <?php if($on_sell==0){ ?>style="width:25%;"<?php } ?>><a  <?php if(CONTROLLER_NAME=='My'){echo ' class="act"';} ?> href="<?php echo url('/H/my/index'); ?>"><i class="fa fa-user"></i><span>个人中心</span></a></li>
    </ul>
</div>
<script type="text/javascript">
$(function(){
    if($("link[href='__LIB__/sell/css/font-awesome.min.css']").length<=0){
        $("head").append('<link href="__LIB__/sell/css/font-awesome.min.css" rel="stylesheet">');
    }
})
</script>