<div class="x-body">
    <div class="table-container">

        <table class="layui-table " >
            <thead>
            <tr>
                <th>用户编号</th>
                <th>级别</th>
                <th>昵称</th>
                <th>层级</th>

            </tr>
            </thead>
            <tbody>
            {foreach name="list"  key="k" item="v"}
            <tr>
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $v['rank_name_text']; ?></td>
                <td><?php echo $v['nickname'.$i]; ?></td>
                <td>第<?php echo $k + 1; ?>级</td>
            </tr>
            {/foreach}

            </tbody>
        </table>
    </div>
</div>
   
