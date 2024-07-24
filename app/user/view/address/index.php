<div class='x-body'>
    
    {include file="address/top" /}
    <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text" name="user_id" value="{$in.user_id}"  placeholder="用户编号"  class="layui-input">
            <input type="text" name="name" value="{$in.name}"  placeholder="收货人姓名"  class="layui-input">
            <input type="text" name="tel" value="{$in.tel}"  placeholder="手机"  class="layui-input">
            <input type="text" name="address" value="{$in.address}"  placeholder="地址"  class="layui-input">
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    <div class='table-container' >
        <table class="layui-table ">
            <thead>
                <tr>
                    <th>系统编号</th>
                    <th>用户</th>
                    <th>收货人姓名</th>
                    <th>手机</th>
                    <th>省市</th>
                     <th>地址</th>
                    <th>默认</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                {foreach name="data.list" item="v"}
                    <tr>
                       <td>{$v.id}</td>
                       <td>【{$v.user_id}】{$v.username}</td>
                        <td>{$v.name}</td>
                       <td>{$v.tel}</td>
                       <td><?php echo $ho->getAreas($v['province']); ?> <?php echo $ho->getAreas($v['city']); ?> <?php echo $ho->getAreas($v['country']); ?></td>
                        <td>{$v.address}</td>
                       <td><?php if($v['is_default']==1){echo '默认';} ?> </td>
                       <td>
                           <a class="pear-btn pear-btn-primary" href="{:url('item',array('id'=>$v['id']))}">编辑</a>
                           <a class="pear-btn pear-btn-primary layui-btn-danger" onclick="del('{:url('del',array('id'=>$v['id']))}')">删除</a>
                        </td>
                   </tr>
               {/foreach}


            </tbody>
        </table>
               {$data.page|raw}
            </div>
        </div>
</div>