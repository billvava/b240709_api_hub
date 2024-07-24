<div class="x-body">
    <fieldset class="layui-elem-field layui-field-title" >
        <legend>系统信息</legend>
    </fieldset>
    <div class='panel-body'>
        <div class="table-container">
        <table class="layui-table">

            <tbody>
                {volist name="info" id="v"}
                <tr><td width="15%">{$key}</td><td>{$v|raw}</td></tr>
                {/volist}
            </tbody>
        </table>
            </div>
    </div>

</div>