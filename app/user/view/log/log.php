<div class='x-body'>
        <!--搜索开始-->
       
         <form class="layui-form layui-col-md12 x-so" action="__SELF__">
            <input type="text" name="start_datetime" value="{$in.start_datetime}" readonly=""  placeholder="开始时间"  class="layui-input">
            <input type="text" name="end_datetime" value="{$in.end_datetime}" readonly=""  placeholder="截止时间"  class="layui-input">
            <input type="text" name="ordernum" value="{$in.ordernum}"  placeholder="订单编号"  class="layui-input">
            <input type="text" name="msg" value="{$in.msg}"  placeholder="说明"  class="layui-input">
            <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户ID"  class="layui-input">
            
             <div class="layui-input-inline">
                <select name="type">
                  <option value="">类型</option>
                <?php foreach($user_log_type as $k=>$v){ ?>
             <option value="{$k}" <?php if($in['type']==$k){echo 'selected="selected"';} ?>>{$v}</option>
             <?php } ?>
                </select>
              </div>
             <div class="layui-input-inline">
                <select name="cate">
                  <option value="">分类</option>
                <?php foreach($cate as $k=>$v){ ?>
             <option value="{$k}" <?php if($in['cate']==$k){echo 'selected="selected"';} ?>>{$v}</option>
             <?php } ?>
                </select>
              </div>
              <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
         </form>
        
         <form class="layui-form layui-col-md12 x-so" action="{:url('out_xls')}" target="_blank">
                          <input type="hidden" name="table" value="<?php echo request()->action(); ?>" >

            <input type="text" name="start_datetime" value="{$in.start_datetime}" readonly=""  placeholder="开始时间"  class="layui-input">
            <input type="text" name="end_datetime" value="{$in.end_datetime}" readonly=""  placeholder="截止时间"  class="layui-input">
            <input type="text" name="ordernum" value="{$in.ordernum}"  placeholder="订单编号"  class="layui-input">
            <input type="text" name="msg" value="{$in.msg}"  placeholder="说明"  class="layui-input">
            <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户ID"  class="layui-input">
            
             <div class="layui-input-inline">
                <select name="type">
                  <option value="">类型</option>
                <?php foreach($user_log_type as $k=>$v){ ?>
             <option value="{$k}" <?php if($in['type']==$k){echo 'selected="selected"';} ?>>{$v}</option>
             <?php } ?>
                </select>
              </div>
             <div class="layui-input-inline">
                <select name="cate">
                  <option value="">分类</option>
                <?php foreach($cate as $k=>$v){ ?>
             <option value="{$k}" <?php if($in['cate']==$k){echo 'selected="selected"';} ?>>{$v}</option>
             <?php } ?>
                </select>
              </div>
              <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" >导出</button>
         </form>
        
        
        
        <!--搜索结束-->
        <div class='table-container'>
<table class="layui-table ">
    <thead>
        <tr>
            <th>系统编号</th>
            <th>用户ID</th>
            <th>用户名</th>
            <th>时间</th>
            <th>额度</th>
            <th>本次结余</th>
            <th>类型</th>
            <th>订单号</th>
            <th>说明</th>
             <th>分类</th>
              <th>后台ID</th>
              <!--<th>操作</th>-->
        </tr>
    </thead>
    <tbody>
        {foreach name="data.list" item="v"}
            <tr>
               <td>{$v.id}</td>
               <td>{$v.user_id}</td>
               <td>{$v.username}</td>
              <td>{$v.time}</td>
               <td>{$v.total}</td>
               <td>{$v.current_total}</td>
               <td><?php echo $user_log_type[$v['type']];  ?></td>
               <td>{$v.ordernum}</td>
               <td>{$v.msg}</td>
               <td><?php echo $cate[$v['cate']]; ?></td>
                <td>{$v.admin_id}</td>
                <!--<td><a href="javascript://" onclick="del('<?php echo url('cacel',array('table'=>app()->request->action(),'id'=>$v['id'])); ?>','该操作会删除此条记录，并将相应的额度减回或者加回，可能会导致负数，操作后不可复原，请谨慎操作。')">回撤</a></td>-->
           </tr>
       {/foreach}
       
        
    </tbody>
</table>
       {$data.page|raw}
       
    </div>
</div>
