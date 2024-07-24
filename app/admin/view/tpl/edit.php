<div class='col-md-12'>

<form id="myForm" action="{:url("edit",array("dosubmit"=>1))}"
	method="post">
<div id="pageCo">
<div class="manageForm">
<table class="table table-striped table-hover table-bordered">
	<tbody>
		<tr>
			<th width="10%">文件名称</th>
			<td><input type="text" class="form-control" name="info[filename]"
				value="{$data.filename}" size="60" /></td>
		</tr>
		
		<tr>

			<th>模板内容</th>
			<td><textarea style="width: 90%" rows="18" class="form-control"
				name="info[content]" id="content">{$content}</textarea> </td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<td><input type="hidden" name="info[path]"
				value="{$data.path}" /> <label class="pear-btn"><input
				type="submit" class="submit" value="提交保存" /></label> </td>
		</tr>
	</tfoot>
</table>
</div>
</div>
</form>
</div>