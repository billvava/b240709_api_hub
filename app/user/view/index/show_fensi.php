<form action="__SELF__" method="get">
        <div class="col-md-10 xf_panel">
           <div class="input-group">
            
             <span class="input-group-btn">
              <button class="btn btn-default" type="button">昵称</button>
            </span>
            <input type="text" class="form-control" placeholder="" name="nickname" value="{$in.nickname}" >
            
              <span class="input-group-btn">
               <button class="btn btn-default" type="submit">搜索</button>
            </span>

             <input type="hidden"  name="p" value="1">
             <input type="hidden"  name="user_id" value="<?php echo $in['user_id']; ?>">
             <input type="hidden"  name="level" value="<?php echo $in['level']; ?>">
        </div> 
            
            
          
        </div>
       </form>
<table class="table table-striped table-hover table-bordered" >
    <thead>
        <tr>
            <th>编号</th>
            <th>用户名</th>
            <th>他的粉丝数量</th>
            <th>创建时间</th>
        </tr>
    </thead>
    <tbody>
        {foreach name="data.list" item="v"}
            <tr>
               <td>{$v.user_id}</td>
               <td>{$v.nickname}</td>
               <td>{$v.fensi_count}</td>
              <td><?php echo date('Y-m-d H:i:s',$v['create_time']);  ?></td>
            </tr>
       {/foreach}
    </tbody>
</table>
       {$show|raw}
   
