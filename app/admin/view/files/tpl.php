{__NOLAYOUT__}
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>模板库</title>
<style>
* {
	font-size: 12px;
	line-height: 1.5;
}
body {
	font-size: 12px;
	line-height: 1.5;
	color:#2D6A99;
}
a:link,a:visited{
	font-size: 12px;
	text-decoration:none;
	color:#2D6A99;
}
a:hover,a:active{
	font-size: 12px;
	text-decoration: underline;
	color:blue;
}
div, form, h1, h2, h3, h4, h5, h6 {
	margin: 0;
	padding:0;
}

.article {
	FONT-SIZE: 10pt;
LINE-HEIGHT: 160%；table-layout:fixed;
	word-break:break-all
}
.bn {
	color:#FFFFFF;
	font-size:0.1pt;
	line-height:50%
}
.contents {
	font-size:1pt;
	color:#F7F6F8
}
.nb {
	border: 1px solid #000000;
	height:18px
}
.coolbg {
	border-right: 2px solid #ACACAC;
	border-bottom: 2px solid #ACACAC;
	background-color: #E6E6E6
}
.ctfield {
	padding: 3px;
	line-height: 150%
}
.nndiv {
	width: 175px;
	height:20px;
	margin: 0px;
	padding: 0px;
	word-break: break-all;
	overflow: hidden;
}

.napisdiv {
	left:40;
	top:3;
	width:150px;
	height:100px;
	position:absolute;
	z-index:3;
	display:none;
}
</style>
</head>
<body>
<div>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
  <thead>
  <tr bgcolor="#CCCCCC" class='t_head'>
    <td class='linerow' ><strong>点击名称选择模板</strong></td>
    <td width="15%" align="center" class='linerow'><strong>文件大小</strong></td>
    <td width="30%" align="center" class='linerow'><strong>最后修改时间</strong></td>
  </tr>
  </thead> 
  <tbody>
  <tr>
    <td class='linerow' colspan='3'><a href='{:url("tpl",array("opener_id"=>$opener_id,'path'=>$parent_path))}'><img src="__STATIC__/admin/images/dir2.gif" border=0 width=16 height=16 align=absmiddle>上级目录</a> &nbsp; 当前目录:{$now_path}</td>
  </tr>
  {foreach name="data" item="v"}
  <tr>
    <td  class='linerow'>
     <?php if($v['isDir']){ ?>
    <a href='{:url("tpl",array("opener_id"=>$opener_id,'path'=>'/'.$v['path']))}'><img src="__STATIC__/admin/images/dir.gif" border=0 width=16 height=16 align=absmiddle>{$v.filename}</a>
     <?php }else{ ?>

    <a href="#" title="点击选择" rel="{$v.path}">{$v.filename}</a>

    <?php } ?>
    </td>
    <td align="center"  class='linerow'><?php if(!$v['isDir']){ ?> <?php echo byte_format($v["size"]); ?> <?php } ?>&nbsp;</td>
    <td align="center"  class='linerow'><?php if(!$v['isDir']){ ?> <?php echo date('Y-m-d H:i:s',$v["mtime"]); ?>  <?php } ?>&nbsp;</td>
  </tr>
 {/foreach}
  </tbody>
  <tfoot> 
<!--
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
-->
  </tfoot>         
</table>
</div>
<script src="__LIB__/jquery.min.js" type="text/javascript"></script>
<script src="__LIB__/layer/layer.js" type="text/javascript"></script>
<script>
$(function(){
  $("a[title='点击选择']").bind('click',function(){
    var main=$(window.parent.document),
        targetID='#'+'{$opener_id}',
        url=$(this).attr('rel');
        var index = parent.layer.getFrameIndex(window.name); 
        parent.layer.close(index); 
        console.log(targetID);
        main.find(targetID).val(url);
    return false;
  });

  $("tr:even").not('.t_head').css("background-color", "#efefef");
});
</script>
</body>
</html>