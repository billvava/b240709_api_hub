<div class="x-body">
    
<fieldset class="layui-elem-field layui-field-title">
  <legend>订单【{$in.ordernum}】日志</legend>
</fieldset> 
<ul class="layui-timeline">
     <?php foreach($data as $v){ ?>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h3 class="layui-timeline-title"><?php echo $order_log_cate[$v['cate']]; ?> {$v.time}</h3>
      <p>
        {$v.msg}
      </p>
    </div>
  </li>
    <?php } ?>
  
 
</ul>  

</div>