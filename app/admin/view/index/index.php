{__NOLAYOUT__}
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>{:C('title')}</title>
		<!-- 依 赖 样 式 -->
                 <link href="//at.alicdn.com/t/font_1108287_v6oxqcv9bcs.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="__STATIC__/pearadmin/component/pear/css/pear.css" />
		<!-- 加 载 样 式-->
		<!--<link rel="stylesheet" href="__STATIC__/pearadmin/admin/css/load.css" />-->
		<!-- 布 局 样 式 -->
		<link rel="stylesheet" href="__STATIC__/pearadmin/admin/css/admin.css" />
		<!-- 主 题 更 换 -->
		<style id="pearadmin-bg-color"></style>
		<!-- 头 部 结 束 -->
	</head>
	<!-- 结 构 代 码 -->
	<body class="layui-layout-body pear-admin">
		<!-- 布 局 框 架 -->
		<div class="layui-layout layui-layout-admin">
			<div class="layui-header">
				<!-- 顶 部 左 侧 功 能 -->
				<ul class="layui-nav layui-layout-left">
					<li class="collaspe layui-nav-item"><a href="#" class="layui-icon layui-icon-shrink-right"></a></li>
					<li class="refresh layui-nav-item"><a href="#" class="layui-icon layui-icon-refresh-1" loading=600></a></li>
				</ul>
				<!-- 顶 部 右 侧 菜 单 -->
				<div id="control" class="layui-layout-control"></div>
				<ul class="layui-nav layui-layout-right">
					<li class="layui-nav-item layui-hide-xs"><a href="#" title="清除缓存"  class="clear_cache layui-icon layui-icon-fonts-clear"></a></li>
                                        <li class="layui-nav-item layui-hide-xs"><a href="/" target="_blank" class="layui-icon layui-icon-website"></a></li>
					<li class="layui-nav-item">
						<!-- 头 像 -->
						<a href="javascript:;">
                            <img src="/img/adminHeader.jpg" class="layui-nav-img">
							{$adminInfo.username}
						</a>
						<!-- 功 能 菜 单 -->
						<dl class="layui-nav-child">
                                                     <dd><a href="javascript://" class="clear_cache">清除缓存</a></dd>
                                                    <dd><a href="javascript://" user-menu-url="{:url('login/update_pwd')}" user-menu-id="5555" user-menu-title="修改密码">修改密码</a></dd>
							<dd><a href="{:url('login/logout')}">注销登录</a></dd>
						</dl>
					</li>
					<!-- 主 题 配 置 -->
					<li class="layui-nav-item setting"><a href="#" class="layui-icon layui-icon-more-vertical"></a></li>
				</ul>
			</div>
			<!-- 侧 边 区 域 -->
			<div class="layui-side layui-bg-black">
				<!-- 顶 部 图 标 -->
				<div class="layui-logo">
					<!-- 图 表 -->
					<img class="logo"></img>
					<!-- 标 题 -->
					<span class="title"></span>
				</div>
				<!-- 侧 边 菜 单 -->
				<div class="layui-side-scroll">
					<div id="sideMenu"></div>
				</div>
			</div>
			<!-- 视 图 页 面 -->
			<div class="layui-body">
				<!-- 内 容 页 面 -->
				<div id="content"></div>
			</div>
		</div>
		<!-- 遮 盖 层 -->
		<div class="pear-cover"></div>
		<!-- 移 动 端 便 捷 操 作 -->
		<div class="pear-collasped-pe collaspe"><a href="#" class="layui-icon layui-icon-shrink-right"></a></div>
		<!-- 加 载 动 画-->
		<div class="loader-main">
			<div class="loader"></div>
		</div>
		<!-- 依 赖 脚 本 -->
		<script src="__STATIC__/pearadmin/component/layui/layui.js"></script>
		<script src="__STATIC__/pearadmin/component/pear/pear.js?v=1"></script>
		<!-- 框 架 初 始 化 -->
		<script>
			layui.use(['admin','jquery',"convert","notice"], function() {
				var admin = layui.admin,
					$ = layui.jquery,
					convert = layui.convert;
				
				var image = new Image();
				image.src = "{:lang('empty_header')}";
				// image.onload = function(){
				// 	$(".layui-nav-img").attr("src", convert.imageToBase64(image));
				// }
                admin.setConfigType("json");

				// 重新设置配置文件读取路径，默认为同级目录下的 pear.config.json
//				admin.setConfigPath("__STATIC__/pearadmin/config/pear.config.json");
				admin.setConfigPath("<?php echo url('get_json'); ?>");
				// Admin 框架初始化
				admin.render();
                                
                                $(function(){
                                    $(".clear_cache").click(function(){
                                        $.post('<?php echo url('Cache/clear_all'); ?>',{},function(res){
                                            layui.notice.success(res.info);
                                        },'json')
                                    });
                                })
			})
		</script>
	</body>
</html>
