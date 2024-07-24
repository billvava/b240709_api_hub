<div class='x-body'>
     
     
    <div class="table-container">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>发放状态</th>
                    <th>一级用户</th>
                    <th>一级佣金</th>
                    <th>二级用户</th>
                    <th>二级佣金</th>
                    <th>三级用户</th>
                    <th>三级佣金</th>
            </thead>
            <tbody>
                <tr>
                    <th><?php echo $info['status']==1?'已发放':'未发放'; ?></th>
                    <th><?php if($info['pid1'] ){ ?>【{$info.pid1}】{$info.name1}<?php } ?></th>
                    <th>{$info.bro1}</th>
                   <th><?php if($info['pid2']){ ?>【{$info.pid2}】{$info.name2}<?php } ?></th>
                    <th>{$info.bro2}</th>
                    <th><?php if($info['pid3']){ ?>【{$info.pid3}】{$info.name3}<?php } ?></th>
                    <th>{$info.bro3}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>