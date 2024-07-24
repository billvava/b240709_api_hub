<div class="x-body">
    <?php $message_type = lang('message_type'); ?>
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <div class="layui-input-inline">
                <select name="type">
                  <option value="">状态</option>
                  <?php foreach($message_type as $k=>$v){ ?>
                  <option value="{$k}"  <?php if($in['type']!='' && $in['type']==$k){echo 'selected=""';} ?>>{$v}</option>
                  <?php } ?>
                </select>
              </div>
              <input type="text"   name='name' value="{$in.name}"  placeholder="客户姓名"  class="layui-input ">
            <input type="text"   name='start_datetime' value="{$in.start_datetime}" readonly=""  placeholder="开始时间"  class="layui-input jeDateTime">
            <input type="text"   name='end_datetime' value="{$in.end_datetime}"  readonly="" placeholder="截至时间"  class="layui-input jeDateTime">
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary  pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <div class="table-container">
<table class="layui-table"  >
    <thead>
        <tr>
            <th>ID</th>
            <th>客户姓名</th>
            <th>邮箱</th>
            <th>客户电话</th>
            <th>提交时间</th>
            <th>内容</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php $color = lang('color'); ?>
        {foreach name="list" item="v"}
            <tr>
               <td>{$v.id}</td>
               <td>{$v.name}</td>
               <td>{$v.email}</td>
               <td>{$v.tel }</td>
               <td><?php echo date('Y-m-d H:i:s',$v['time']);  ?></td>
                   <td>{$v.content}</td>
                   <td style="<?php echo $color[$v['status']]; ?>"><?php echo $v['status']==1?'已处理':'未处理'; ?></td>
               <td>
                  <div class="dropdown">
                    <button class="pear-btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                      操作
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li>
                          <a  href="{:url('status',array('id'=>$v['id'],'status'=>0,))}">未处理</a>
                      </li>
                      <li>
                         <a  href="{:url('status',array('id'=>$v['id'],'status'=>1,))}">已处理</a>
                      </li>
                    </ul>
                  </div>
               </td>
           </tr>
       {/foreach}
       
        
    </tbody>
</table>
    </div>
       {$show|raw}
    
</div>
