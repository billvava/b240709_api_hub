        <?php  $allcolor = lang('color');$put_status = lang('put_status');  ?>

<div class='x-body'>
    <form class="layui-form layui-col-md12 x-so" action="__SELF__">
           
             <div class="layui-input-inline">
                <select name="status">
                  <option value="">状态</option>
                <?php foreach($put_status as $k=>$v){ ?>
             <option value="{$k}" <?php if($in['status']==$k && $in['status']!=''){echo 'selected="selected"';} ?>>{$v}</option>
             <?php } ?>
                </select>
              </div>
              <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
         </form>
    
    <div class='table-container'>
       
        <!--搜索结束-->
<table class="layui-table">
    <thead>
        <tr>
            <th>用户名</th>
            <th>金额</th>
            <th>时间</th>
             <th>提现方式</th>
            <th>当前状态</th>
           
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        {foreach name="list" item="v"}
            <tr>
               <td>【{$v.user_id}】{$v.username}</td>
               <td>
                   <p>申请金额：{$v.money}</p>
                   <p>应付金额：{$v.real_total}</p>
                   <p>手续金额：{$v.plus_total}</p>
               </td>
                <td><?php echo $v['time'];  ?></td>
                 <td>
                     <p><?php echo $cashout_cate[$v['cate']]; ?></p>
                     {$v.pay_type} {$v.name}  {$v.zhifubao}  {$v.weixin}  {$v.yh_khh}  {$v.yh_num}</td>
               <td  style="<?php  echo $allcolor[$v['status']]; ?>"><?php echo $put_status[$v['status']]; ?></td>
               <td>
                   {if condition="$v.status eq '0'"}
                   <div class="btn-group btn-sm ">
                        <button class="btn dropdown-toggle btn-sm " type="button" id="dropdownMenu1" data-toggle="dropdown">
                          修改状态
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                                    <?php for($i=1;$i<=2;$i++){ ?>
                                    <li>
                                          <a href="javascript://" onclick="del('{:url('chuli',array('id'=>$v['id'],'status'=>$i))}','确定修改为：<?php echo $put_status[$i]; ?>吗？')"><?php echo $put_status[$i]; ?></a>
                                      </li>
                                       <li class="divider"></li>
                                    <?php } ?>
                        </ul>
                      </div>
                   {/if}
               </td>
           </tr>
       {/foreach}
       
        
    </tbody>
</table>
        {$show|raw}
    </div>
</div>
</div>
