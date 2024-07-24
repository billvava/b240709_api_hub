<div class='col-md-12'>
<div class="panel-heading">
    <div class="pear-btn-group">
        <a  href="{:url('manage')}" type="button" class="pear-btn pear-btn-primary"><i class="icon icon-list-ul"></i> 管理模板</a>
    </div>
</div>
<div id="pageCo">
	<div class="listForm">
		<table  class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>文件名</th>
                            <th>描述</th>
                            <th>修改时间</th>
                            <th>操　作</th>
                        </tr>
                    </thead>
			
			<tbody> 
				<tr>
					<td colspan="5">当前模板位置：<b class="green"> {$themePath} {$folder}</b> <?php if(!empty($pfolder)){ ?><a href='{:url('manage',array('folder'=>$pfolder))}'><img src="__ADMIN__/images/dir2.gif" />上级目录</a><?php }else{ ?><a href='{:url('manage')}'><img src="__ADMIN__/images/dir2.gif" />上级目录</a><?php } ?></td>
				</tr>
		        {foreach name="data" item="v"}
                        <?php if($v['isDir']){ ?>
		      
		        <tr>				  
                            <td><img src="__ADMIN__/images/dir.gif" /> <a href="{:url('manage',array('folder'=>$folder.'/'.$v['filename']))}">{$v.filename}</a></td>
						  <td>文件夹</td>
						  <td>&nbsp;</td>
						  <td><a href="{:url('manage',array('folder'=>$folder.'/'.$v['filename']))}">展开</a></td>
						</tr>	
                        <?php }else{ ?>
		        <tr>				  
						  <td><img src="__ADMIN__/images/file.gif" /> {$v.filename}</td>
						  <td><span class="editable pointer" id="tpl_{$folder}/{$v.filename}"><?php if(!empty($v['note'])){ ?>{$v.note}<?php }else{ ?>暂无描述<?php } ?></span></td>
						  <td>{$v.mtime|date='Y-m-d H:i:s',###}</td>
						  <td><a href="javascript://" onclick="show_url('{:url('edit',array('filename'=>$folder.'/'.$v['filename']))}')">编辑</a> | <a href="javascript://" onClick="del('{:url('delete',array('filename'=>$folder.'/'.$v['filename']))}')">删除</a> </td>
						</tr>
		        <?php } ?>
			{/foreach}
			</tbody>
		</table>	
	</div>	
	<div class="pageList">
		{$data.pages}
		<ul class="clearit"></ul> 
	</div>
</div>

</div>