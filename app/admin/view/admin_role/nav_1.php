

<div class="layui-fluid">
    <div class="layui-col-md12">
        <div class="layui-card">
        <div class="layui-card-header">{$info.name}</div>
        <div class="layui-card-body" >
           <div class="pear-btn-group">
                <button type="button" class="pear-btn pear-btn-primary " onclick="allSelectType('nav_id')">全选</button>
                <button type="button" class="pear-btn pear-btn-primary " onclick="invertSelectType('nav_id')">反选</button>
            </div>

            <form action="{:url('nav')}" method="post">
                <input value="{$info.role_id}" type="hidden" name="role_id" />
                {foreach name="alls" item="v"}
                <p>
                    <label style=" font-weight: normal"><input type="checkbox" name="nav_id[]" value="{$v.id}" <?php if(in_array($v['id'], $oneNode)){echo 'checked="checked"';} ?> >&nbsp;&nbsp;<?php echo str_repeat('----', $v['levs']); ?>{$v.name}</label>
                 </p>
                {/foreach}
                <tr>
                    <td colspan="3">
                        <button class="pear-btn pear-btn-primary " type="submit">保存</button>
                </tr>

            </form>
        </div>
        </div>
    </div>
</div>

