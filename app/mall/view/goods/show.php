


<div class='panel xf_panel ' style="margin: 10px;" >
    
    <div class='panel-body'>
        
        <ol class="breadcrumb">
            <li><a href="{:url('index')}">{$name}</a></li>
            <li><a href="<?php echo url('show',array($pk=>$info[$pk]) ); ?>"><?php echo $info[$pk]; ?></a></li>
            <li class="active">{$title}</li>
        </ol>
        
        <div style=" margin-bottom: 20px;">
            <a class="btn btn-default" href="<?php echo url('edit',array($pk=>$info[$pk]) ); ?>">编辑</a>
        <a class="btn btn-danger" href="<?php echo url('add',array($pk=>$info[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
        </div>

        <table id="w0" class="table table-striped table-bordered detail-view">
            <tbody>
                <?php $data=$model->attributeLabels(); ?>
                {foreach name='data' item='v' key='k'}
                <tr><th>{$v}</th><td><?php  echo $info[$k]; ?></td></tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

