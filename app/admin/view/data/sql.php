<div class='panel'>
    <div class='panel-heading'>SQL命令执行表单 <small class="text-danger">提示：此处不支持drop操作，select语句只支持单条语句！</small>
    </div>
    <div class='panel-body'>
        <form class="form-horizontal" role="form" method="post" action='__SELF__'>

            <div class="form-group">
                <div class="col-md-10">
                    <textarea cols="60" rows="10" class='form-control' name="sqlquery">{$content}</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <input type="hidden" name='execute' value="execute" />
                    <button type="submit" class="pear-btn btn-primary">执行</button>
                    <button type="reset" class="pear-btn">清空</button>
                </div>
            </div>

        </form>
    </div>
</div>
<hr />
<div style='padding-left: 20px;'>
    <h4>执行结果：</h4>
    {$returnStr}
</div>