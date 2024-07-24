


<div class='x-body' >
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回列表</a>
    </xblock>
    
    <div class="table-container">
        <table class="layui-table layui-form" >
   
    <thead>
        <tr>
             <th  class="w50">
                <input type="checkbox"  lay-filter="all_sel" data-name="goods_id" lay-skin="primary" />
             </th>
             <th class="w50">系统编号</th>
             <th>排序</th>
            <th>名称</th>
            <th>货号</th>
            <th>价格</th>
            <th>属性</th>
            <th>缩略图</th>
        </tr>
    </thead>
    <tbody>
         <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
             <?php $color = lang('color'); ?>
        {foreach name='data.list' item='v' key='k' }
          <tr>
              <th class="w50"><input type="checkbox" value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>" lay-skin="primary" /></th>
              <th class="w50"><?php echo $v['goods_id']; ?></th>
              <th class="w100">
                  <?php echo $v['sort']; ?>
                  </th>
<th> <?php echo $v['name']; ?></th>
<th><?php echo$v['wares_no']; ?></th>
<th>
        <p>售价：<?php echo $v['min_price']; ?></p>
        <p>成本价：<?php echo $v['min_price2']; ?></p>
        <p>划线价：<?php echo $v['min_market_price']; ?></p>
</th>
<th>
        <p><?php echo fast_check(array(
                      'key'=>'goods_id',
                       'keyid'=>$v['goods_id'],
                       'field'=>'status',
                      'url'=>url('set'),
                      'txt'=>"上架|下架",
                        'check'=>$v['status']
                  )); ?></p>
        <p class="mt5"><?php echo fast_check(array(
                      'key'=>'goods_id',
                       'keyid'=>$v['goods_id'],
                       'field'=>'is_recommend',
                      'url'=>url('set'),
                      'txt'=>"推荐",
                        'check'=>$v['is_recommend']
                  )); ?></p>
         <p class="mt5"><?php echo fast_check(array(
                      'key'=>'goods_id',
                       'keyid'=>$v['goods_id'],
                       'field'=>'is_new',
                      'url'=>url('set'),
                      'txt'=>"特价",
                        'check'=>$v['is_new']
                  )); ?></p>

</th>
<th><img src="<?php echo $v['thumb']; ?>" class="img_thumb" style=" width: 50px; height: 50px;" /></th>

            </tr>     
         {/foreach}
         <tr>
             <td colspan="10">
                <a href="javascript://" onclick="all_hand('status',1)"  class="pear-btn pear-btn-primary pear-btn-sm ">上架</a>
                <a  href="javascript://" onclick="all_hand('status',0)" class="pear-btn pear-btn-primary pear-btn-sm ">下架</a>
                <a  href="javascript://"  onclick="forever_del()"class="pear-btn pear-btn-primary pear-btn-sm pear-btn pear-btn-primary pear-btn-sm-danger">永久删除</a>
             </td>
         </tr>
         </form>
    </tbody>
</table>
              {$data.page|raw}
    </div>
</div>
{include file="goods/goods_js" /}

