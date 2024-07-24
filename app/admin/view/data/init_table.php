<div class="panel m10">
    <div class="panel-heading">
        请注意核对数据库！！！
    </div>
    <div class="panel-body">
        <div class="alert alert-danger">
            <h1>当前数据库：
                <?php echo $c['database']; ?>
            </h1>
        </div>
        <div class="alert alert-danger">
            <h1>当前网站：
                <?php echo C('title'); ?>
            </h1>
        </div>
        <button class="pear-btn btn-success" onclick="javascript:$('.board').show();" type="button">确定</button>
    </div>
</div>
<div class="board panel m10" style="display: none">
    <div class="panel-heading">
        <strong>清空表 truncate table</strong>
    </div>

    <div class="panel-body">
        <div class="board-list">
            <div class="board-item" style=" line-height: 35px;border-bottom: solid 1px #ccc;">
                <span style="width: 200px;display: inline-block;">全部清空</span>
                <a class="pear-btn-sm btn" onclick="del('{:url('clear_t')}')" href="javascript://">清空</a>
            </div>
            {foreach name='table_list' item='v'}
            <div class="board-item" style=" line-height: 35px;border-bottom: solid 1px #ccc;">
                <span style="width: 200px;display: inline-block;">{$v}</span>
                <a class="pear-btn-sm btn" onclick="del('{:url('clear_t',array('t'=>$v))}')" href="javascript://">清空</a>
            </div>
            {/foreach}

        </div>
    </div>
</div>