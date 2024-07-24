<div class="x-body">
    <form method="post">
        <input type="hidden" name="id" value="{$info.id}" />
    <table class="layui-table">
        <tr>
            <td>状态</td>
            <td>
                <select name="status" class="layui-select">
                    <option value="">请选择</option>
                    <?php foreach ($feedback_status as $k => $v) { ?>
                        <option value="{$k}" <?php if($info['status']==$k&& $info){echo 'selected=""';} ?> >{$v}</option>
                    <?php  } ?>
                  </select>
                
            </td>
        </tr>
        <tr>
            <td>备注</td>
            <td>
                <textarea name="remark" class="layui-textarea">{$info.remark}</textarea>
                
            </td>
        </tr>
         <tr>
            <td></td>
            <td>
                <button class="pear-btn pear-btn-primary pear-btn-sm " type="submit">确认</button>
                
            </td>
        </tr>
    </table>
    </form>
</div>