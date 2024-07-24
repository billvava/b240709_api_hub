<div class='panel m10'>
    <div class='panel-heading'>表的修复和优化</div>
    <div class='panel-body'>
        <form class="form-horizontal" method="post" action='{:url(' opimize')}'>
            <br />
            <fieldset>
                <div class="form-actions">
                    请选择要操作的表:{$selStr}
                    <br />
                    <input type="hidden" name='execute' value="execute" />
                    <input type='hidden' name='dopost' value='viewinfo' />
                    <button type="submit" class="pear-btn btn-primary" onClick="this.form.dopost.value='opimize';">优化选中表</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" class="pear-btn btn-primary" onClick="this.form.dopost.value='repair';">修复选中表</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" class="pear-btn btn-primary" onClick="this.form.dopost.value='viewinfo';">查看表结构</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <br />
                    <br />
                    <button type="submit" class="pear-btn btn-success" onClick="this.form.dopost.value='opimizeAll';">优化全部表</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" class="pear-btn btn-success" onClick="this.form.dopost.value='repairAll';">修复全部表</button>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

            </fieldset>
        </form>
        <hr />
        <div style='padding-left: 20px;'>
            <h4>执行结果：</h4>
            {$returnStr}
        </div>
    </div>
</div>
</div>