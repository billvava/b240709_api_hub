<div class="x-body">
    <div class="layui-row">
       <a class="pear-btn  pear-btn-danger" href="{:url('delall')}"  onclick="return window.confirm('此操作不可撤销，你确定要继续？');">一键清空</a>
    </div>
    
    <table class="layui-table" >
   
    <thead>
        <tr>
             <th>文件名</th>
             <th>文件大小</th>
             <th>更新时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
        <?php 
        $start = ($page-1)*$pagenum;
        $end = ($page)*$pagenum;
        for( $j = $start; $j < $end && $j<$i;$j++){ $v= $data['list'][$j]; ?>
          <tr>
              <th> <?php echo $v['filename']; ?></th>
              <th> <?php echo $v['filesize']; ?></th>
              <th> <?php echo $v['filemtime']; ?></th>
              
                <th><a href="javascript://" onclick="show_url('<?php echo url('item',array('file'=>$v['file']) ); ?>')">查看具体信息</a> | <a href="<?php echo url('download',array('file'=>$v['file']) ); ?>" >下载</a> | <a href="<?php echo url('delete',array('file'=>$v['file']) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                </th>
            </tr>
        <?php } ?>
          </form>
    </tbody>
</table>
              {$data.page|raw}
</div>

<script type="text/javascript">
function check(){
    var r=confirm("确定删除吗？");
    return r;
}
</script>
