<div class='x-body'>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>数据备份</legend>
    </fieldset>
    <xblock>
        <form class="layui-form layui-col-md12 x-so" action="{:url('bak')}" method="post" target="iframe_crud">
            <button class="pear-btn pear-btn-primary  pear-btn-sm mt5" type="submit">重新备份</button>
            <input type="hidden" name="flag" value="bak" />
        </form>
        <span class="x-a mt5">重新备份会覆盖旧备份数据，中途请勿关闭浏览器。</span>
    </xblock>
    <iframe style=" width: 100%; height: 200px;box-shadow: none;outline: none;border:1px solid #dddddd;" id="iframe_crud" name="iframe_crud" src="" border="0"></iframe>
    <table class="layui-table " >
        <thead>
            <tr>
                <th>备份文件</th>
                <th>备份时间</th>
                <th>文件大小</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($filenames as $v){ ?>
            <tr>
                <td>
                    <a target="_blank" href="{$v.file}">{$v.file}</a>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i:s',fileatime(".".$v['file'])) ; ?>
                </td>
                <td>
                    <?php echo byte_format($v['size']); ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>