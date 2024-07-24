


<div class='x-body' >
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">新增商品</a>
      
    </xblock>
    
    
    <div class="table-container">

        <table class="layui-table layui-form" >
   
    <thead>
        <tr>
            
             <th class="w50">系统编号</th>
             <th>排序</th>
<!--            <th>名称</th>-->
            <th>最低价格</th>
            <th>关联商品</th>
            <th>剩余名额</th>
            <th>时间</th>
            <th>成团数量</th>
            <th>销量</th>

            <th>属性</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
         <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
             <?php $color = lang('color'); ?>
        {foreach name='data.list' item='v' key='k' }
          <tr>
              <th class="w50"><?php echo $v['id']; ?></th>
              <th class="w100">
                  <?php echo fast_input(array(
                      'key'=>'id',
                       'keyid'=>$v['id'],
                       'field'=>'sort',
                      'url'=>url('set'),
                      'val'=>$v['sort'],
                      'class'=>'w50'
                  )); ?>
                  </th>
<!--<th> --><?php //echo fast_input(array(
//                      'key'=>'id',
//                       'keyid'=>$v['id'],
//                       'field'=>'name',
//                      'url'=>url('set'),
//                      'val'=>$v['name'],
//                      'class'=>'w100'
//                  )); ?><!--</th>-->
<th>{$v['min_price']}

</th>
<th>【{$v['goods_id']}】 <?php  echo \think\facade\Db::name('mall_goods')->where(array('goods_id'=>$v['goods_id']))->cache(true)->value('name'); ?>

</th>

<th>
    <p><?php echo $v['num']; ?></p>


</th>
 <th><?php echo $v['start']; ?> ~ <?php echo $v['end']; ?></th>

              <th>
       <?php echo $v['pt_num']; ?>
</th>
              <th>
                  <?php echo $v['sale_num']; ?>
              </th>

<th>
        <p><?php echo fast_check(array(
                      'key'=>'id',
                       'keyid'=>$v['id'],
                       'field'=>'status',
                      'url'=>url('set'),
                      'txt'=>"上架|下架",
                        'check'=>$v['status']
                  )); ?></p>
       

</th>
                <th>
                    <a href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>" class="pear-btn pear-btn-primary pear-btn-sm ">编辑</a>
                    <a href="<?php echo url('del',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');" class="pear-btn pear-btn-primary pear-btn-sm ">删除</a>
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
{include file="goods/goods_js" /}

