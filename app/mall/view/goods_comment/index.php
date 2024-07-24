
<div class='x-body' >


     <?php if($is_add==1){ ?>     
    <xblock>
         <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">新增</a>
    </xblock>
     <?php } ?>        
     <?php if($is_search==1){ ?>  
     <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}"  method="get" >
            <input type='text' name='id' value='<?php echo input('get.id'); ?>'  placeholder='系统编号'  class='layui-input'>
<input type='text' name='order_id' value='<?php echo input('get.order_id'); ?>'  placeholder='订单编号'  class='layui-input'>
<input type='text' name='goods_id' value='<?php echo input('get.goods_id'); ?>'  placeholder='商品编号'  class='layui-input'>
<input type='text' name='content' value='<?php echo input('get.content'); ?>'  placeholder='内容'  class='layui-input'>
<input type='text' name='user_id' value='<?php echo input('get.user_id'); ?>'  placeholder='用户编号'  class='layui-input'>
<input type='text' name='status' value='<?php echo input('get.status'); ?>'  placeholder='状态'  class='layui-input'>

            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
            <input type="hidden" value="1" name="p" />
        </form>
    </div>
     <?php } ?>    
     
    
    <div class="table-container">
        <table class="layui-table">
   
    <thead>
        <tr>
            <?php if($is_del==1){ ?>
             <th>
                 <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</a>
             </th>
              <?php } ?>
             <th>系统编号</th>
<th>订单编号</th>
<th>商品编号</th>
<th>内容</th>
<th>时间</th>
<th>类型</th>
<th>用户</th>
<th>状态</th>
<th class="w200">星级</th>

<th>排序</th>

            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['list'] as $v){ ?>
          <tr  class="layui-form" >
                <?php if($is_del==1){ ?><th style=" width: 100px;"><input type="checkbox" lay-ignore  value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
              <th><?php echo $v['id']; ?></th>
<th><?php echo $v['order_id']; ?></th>
<th><?php echo $v['goods_id']; ?> {$v.name}</th>
<th><?php echo $v['content']; ?></th>
<th><?php echo $v['time']; ?></th>
<th>
<p><?php echo $comment_type[$v['type']]; ?></p>
<?php if($v['type']==2){ ?>
<p>虚拟昵称：<?php echo $v['nickname']; ?></p>
<?php } ?>
</th>
<th><?php echo $v['user_id']; ?> {$v.username}</th>

<th><?php echo fast_check(array(
                      'key'=>'id',
                       'keyid'=>$v['id'],
                       'field'=>'status',
                      'url'=>url('set_val'),
                      'txt'=>"显示|显示",
                        'check'=>$v['status']
                  )); ?></th>
<th class="w200">
    <div id="rate-{$v.id}"></div>
</th>

<th>
<?php echo fast_input(array(
                      'key'=>'id',
                       'keyid'=>$v['id'],
                       'field'=>'sort',
                      'url'=>url('set_val'),
                      'val'=>$v['sort'],
                      'class'=>'w50'
                  )); ?>
</th>
                <th>
                     <?php if($is_edit==1){ ?>
                    <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>">查看</a>
                     <?php } ?>
                   
                </th>
            </tr>     
        <?php } ?>
         <?php if($is_del==1){ ?>
         <tr>
             <td colspan="100">
                   <a  href="javascript://" onclick="forever_del()"    class="pear-btn pear-btn-primary pear-btn-sm ">删除</a>
                   <a href="javascript://" onclick="all_hand('status',1)"  class="pear-btn pear-btn-primary pear-btn-sm ">显示</a>
                  <a  href="javascript://" onclick="all_hand('status',0)" class="pear-btn pear-btn-primary pear-btn-sm ">隐藏</a>
             </td>
         </tr>
         <?php } ?>
    </tbody>
</table>
              {$data.page|raw}
    </div>          
</div>
<script type="text/javascript">
function all_hand(field,val){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    ajax('{:url('mul_set')}',{ '<?php echo $pk; ?>':ids ,field:field,val:val} );
}
function get_ids(){
    var ids = [];
    $("input:checkbox:checked").each(function() {
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
        ajax('{:url('delete')}',{"<?php echo $pk; ?>":ids } );
    }
}

$(function(){
     layui.use(['rate'],
        function() {
            rate = layui.rate;
            <?php foreach($data['list'] as $v){ ?>
            rate.render({
                elem: '#rate-{$v.id}'
                ,value: {$v.star}
                ,readonly: true
              });
            <?php  } ?>
    });
})
</script>
