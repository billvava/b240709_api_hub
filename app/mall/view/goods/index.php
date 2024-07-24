


<div class='x-body' >
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">新增商品</a>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('trash')}">回收站</a>
        <span class="x-a">排序越小的越在前面</span>
    </xblock>
   
    <div class="layui-row">

        <form class="layui-form"  action="{:url('index')}">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">类目</label>
                    <div class="layui-input-inline">
                        <select name="category_id">
                            <option value="">类目</option>
                            <?php echo (new \app\mall\model\GoodsCategory())->getAllOptionHtml($in['category_id']); ?>
                        </select>
                    </div>
                </div>

                <!-- <div class="layui-inline">
                    <label class="layui-form-label">供应商</label>
                    <div class="layui-input-inline">
                        <select name="shop_id">
                            <option value="">选择</option>
                            <?php  if(is_array($shop_list)){ foreach($shop_list as $k=>$v){ ?>
                                <option value="{$k}" <?php if($in['shop_id']==$k && $in['shop_id']!=''){echo 'selected=""';} ?>>{$v}</option>
                            <?php } } ?>
                        </select>
                    </div>
                </div> -->


                <?php $is = lang('is'); ?>

                <div class="layui-inline">
                    <label class="layui-form-label">上架状态</label>
                    <div class="layui-input-inline">
                    <select name="status">
                        <option value="">选择</option>
                        <?php foreach($is as $k=>$v){ ?>
                            <option value="{$k}" <?php if($in['status']==$k&& $in['status']!=''){echo 'selected=""';} ?>>{$v.name}</option>
                        <?php } ?>
                    </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">推荐</label>
                    <div class="layui-input-inline">
                        <select name="is_recommend">
                            <option value="">选择</option>
                            <?php foreach($is as $k=>$v){ ?>
                                <option value="{$k}" <?php if($in['is_recommend']==$k&& $in['is_recommend']!=''){echo 'selected=""';} ?>>{$v.name}</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="layui-inline">
                    <label class="layui-form-label">商品名称</label>
                    <div class="layui-input-inline">
                        <input type="text"   name='name' value="{$in.name}"   placeholder="商品名称"  class="layui-input ">
                    </div>
                </div>

                <div class="layui-inline">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <button class="pear-btn pear-btn-md pear-btn-primary" type="submit">
                            <i class="layui-icon layui-icon-search"></i>
                            搜索
                        </button>

                    </div>
                </div>
            </div>
        </form>


    </div>
    
    <div class="table-container">
        
        
        
        
        

        <table class="layui-table layui-form" >
   
    <thead>
        <tr>
             <th  class="w50">
                <input type="checkbox"  lay-filter="all_sel" data-name="goods_id" lay-skin="primary" />
             </th>
             <th class="w50">编号</th>
             <th>排序</th>
            <th>名称</th>
            <th>类目</th>
            <!-- <th>供应商</th> -->
            <th>价格</th>
<!--            <th>平台奖金</th>-->

            <th>存库</th>
            <th>属性</th>
            <th>缩略图</th>
            <th>操作</th>
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
                  <?php echo fast_input(array(
                      'key'=>'goods_id',
                       'keyid'=>$v['goods_id'],
                       'field'=>'sort',
                      'url'=>url('set'),
                      'val'=>$v['sort'],
                      'class'=>'w50'
                  )); ?>
                  </th>
<th> <?php echo $v['name']; ?></th>
              <th> <?php echo $cate_list[$v['category_id']]; ?></th>
              <!-- <th> <?php echo $shop_list[$v['shop_id']]; ?></th> -->


<th>
        <p>售价：<?php echo $v['min_price']; ?> <?php if($v['spec_type']==2){ ?><span class="layui-badge layui-bg-green">多规格</span><?php } ?></p>

    <p>成本价：<?php echo $v['min_price2']; ?></p>
    <p>划线价：<?php echo $v['min_market_price']; ?></p>

        <?php if($v['min_pt_price'] > 0){ ?>
        <p>拼团价：<?php echo $v['min_pt_price']; ?></p>
        <?php } ?>
</th>
<!--            <th><p>--><?php //echo $v['bonus']; ?><!--</p></th>-->

<th> <p class="mt5"><?php echo fast_check(array(
            'key'=>'goods_id',
            'keyid'=>$v['goods_id'],
            'field'=>'is_stock',
            'url'=>url('set'),
            'txt'=>"库存足",
            'check'=>$v['is_stock']
        )); ?></p></th>
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
    <p class="mt5"><?php echo fast_check(array(
            'key'=>'goods_id',
            'keyid'=>$v['goods_id'],
            'field'=>'is_down',
            'url'=>url('set'),
            'txt'=>"倒计时",
            'check'=>$v['is_down']
        )); ?></p>
        

</th>
<th><?php echo_img($v['thumb']); ?></th>

                <th>
                    <a href="<?php echo url('item',array($pk=>$v[$pk],'p'=>$in['p']) ); ?>" class="pear-btn pear-btn-primary pear-btn-sm ">编辑</a>
                </th>
            </tr>     
         {/foreach}
         <tr>
             <td colspan="10">
                <a href="javascript://" onclick="all_hand('status',1)"  >上架</a> | 
                <a  href="javascript://" onclick="all_hand('status',0)" >下架</a> | 
                <!--<a  href="javascript://" onclick="copy_goods()" >复制</a> |-->
                <a  href="javascript://" onclick="all_hand('is_recommend',1)"  >设为推荐</a> | 
                <a  href="javascript://" onclick="all_hand('is_recommend',0)" >取消推荐</a> | 
                <a  href="javascript://" onclick="all_hand('is_new',1)" >设为特价</a> | 
                <a  href="javascript://" onclick="all_hand('is_new',0)" >取消特价</a> | 
                <a  href="javascript://" onclick="all_hand('status',-1)"  >放入回收站</a> | 
                <a  href="javascript://" onclick="forever_del()"  >永久删除</a>
             </td>
         </tr>
         </form>
    </tbody>
</table>
              {$data.page|raw}
    </div>
</div>
{include file="goods/goods_js" /}

