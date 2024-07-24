
<div class='x-body'>
    <div  >
        
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('send')}">发放优惠券</a>
    </xblock>
    
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text" name="id" value="{$in.id}"  placeholder="系统编号"  class="layui-input">
            <input type="text" name="name" value="{$in.name}"  placeholder="名称"  class="layui-input">
           <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户编号"  class="layui-input">
            <?php $is = lang('is'); ?>
            <div class="layui-input-inline">
              <select name="status">
                <option value="">状态</option>
                <?php foreach($is as $k=>$v){ ?>
                <option value="{$k}" <?php if($in['status']==$k&& $in['status']!=''){echo 'selected=""';} ?>>{$v.s}</option>
                <?php } ?>
              </select>
            </div>
           <div class="layui-input-inline">
                <select name="range">
                  <option value="">使用范围</option>
                  <?php foreach($coupon_range as $k=>$v){ ?>
                  <option value="{$k}" <?php if($in['range']==$k&& $in['range']!=''){echo 'selected=""';} ?>>{$v}</option>
                  <?php } ?>
                </select>
              </div>
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>    
        
        <div class='table-container'>

          
             
            
            <table class="layui-table ">
                <thead>
        <tr>
            <?php if($is_del==1){ ?>
             <th><div class="btn-group">
                <button type="button" class="btn" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</button>
                <button type="button" class="btn" onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</button>
              </div></th>
              <?php } ?>
             <th>系统编号</th>
<th>名称</th>
<th>使用门槛</th>
<th>面值</th>
<th>有效期</th>
<th>创建时间</th>
<th>使用范围</th>
<th>领取方式</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
         <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
        {foreach name='data.list' item='v' key='k' }
          <tr>
                <?php if($is_del==1){ ?><th style=" width: 100px;"><input type="checkbox" value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th> <?php } ?>
              <th><?php echo $v['id']; ?></th>
<th><?php echo $v['name']; ?></th>
<th><?php echo $v['base_money']; ?>元</th>
<th><?php echo $v['money']; ?>元</th>
<th>
    <p><?php echo $v['end']; ?></p>
</th>
<th><?php echo $v['time']; ?></th>
<th>
    <p><?php echo $coupon_range[$v['range']]; ?></p>
    <p><?php echo $v['range']==2?$v['goods_id']:''; ?><?php echo $v['range']==3?$v['category_id']:''; ?></p>
</th>
<th><?php echo $coupon_source[$v['source']]; ?></th>

                <th>
                     <?php if($is_edit==1){ ?>
                    <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>">编辑</a>
                     <?php } ?>
                      <?php if($is_del==1){ ?>
                    <a class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                     <?php } ?>
                </th>
            </tr>     
         {/foreach}
         <?php if($is_del==1){ ?>
         <tr>
             <td>
                 <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm">删除</button>
             </td>
         </tr>
         <?php } ?>
         </form>
    </tbody>
            </table>
            {$data.page|raw}

        </div>
    </div>
</div>