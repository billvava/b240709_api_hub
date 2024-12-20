<!DOCTYPE html>
<html>
  <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>{$data.title}</title>
<script src="__LIB__/jquery.min.js" type="text/javascript"></script>
<style>
    @charset "utf-8";
html {
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
	line-height: 1.6
}
li{ list-style: none;}
body {
	-webkit-touch-callout: none;
	font-family: -apple-system-font, "Helvetica Neue", "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", sans-serif;
	background-color: #f3f3f3;
	line-height: inherit
}
body.rich_media_empty_extra {
	background-color: #fff
}
body.rich_media_empty_extra .rich_media_area_primary:before {
	display: none
}
h1, h2, h3, h4, h5, h6 {
	font-weight: 400;
	font-size: 16px
}
* {
	margin: 0;
	padding: 0
}
a {
	color: #607fa6;
	text-decoration: none
}
.rich_media_inner {
	font-size: 16px;
	word-wrap: break-word;
	-webkit-hyphens: auto;
	-ms-hyphens: auto;
	hyphens: auto
}
.rich_media_area_primary {
	position: relative;
	padding: 20px 15px 15px;
	background-color: #fff
}
.rich_media_area_primary:before {
	content: " ";
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 1px;
	border-top: 1px solid #e5e5e5;
	-webkit-transform-origin: 0 0;
	transform-origin: 0 0;
	-webkit-transform: scaleY(0.5);
	transform: scaleY(0.5);
	top: auto;
	bottom: -2px
}
.rich_media_area_primary .original_img_wrp {
	display: inline-block;
	font-size: 0
}
.rich_media_area_primary .original_img_wrp .tips_global {
	display: block;
	margin-top: .5em;
	font-size: 14px;
	text-align: right;
	width: auto;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	word-wrap: normal
}
.rich_media_area_extra {
	padding: 0 15px 0
}
.rich_media_title {
	margin-bottom: 10px;
	line-height: 1.4;
	font-weight: 400;
	font-size: 24px
}
.rich_media_meta_list {
	margin-bottom: 18px;
	line-height: 20px;
	font-size: 0
}
.rich_media_meta_list em {
	font-style: normal
}
.rich_media_meta {
	display: inline-block;
	vertical-align: middle;
	margin-right: 8px;
	margin-bottom: 10px;
	font-size: 16px
}
.meta_original_tag {
	display: inline-block;
	vertical-align: middle;
	padding: 1px .5em;
	border: 1px solid #9e9e9e;
	color: #8c8c8c;
	border-top-left-radius: 20% 50%;
	-moz-border-radius-topleft: 20% 50%;
	-webkit-border-top-left-radius: 20% 50%;
	border-top-right-radius: 20% 50%;
	-moz-border-radius-topright: 20% 50%;
	-webkit-border-top-right-radius: 20% 50%;
	border-bottom-left-radius: 20% 50%;
	-moz-border-radius-bottomleft: 20% 50%;
	-webkit-border-bottom-left-radius: 20% 50%;
	border-bottom-right-radius: 20% 50%;
	-moz-border-radius-bottomright: 20% 50%;
	-webkit-border-bottom-right-radius: 20% 50%;
	font-size: 15px;
	line-height: 1.1
}
.meta_enterprise_tag img {
	width: 30px;
	height: 30px!important;
	display: block;
	position: relative;
	margin-top: -3px;
	border: 0
}
.rich_media_meta_text {
	color: #8c8c8c
}
span.rich_media_meta_nickname {
	display: none
}
.rich_media_thumb_wrp {
	margin-bottom: 6px
}
.rich_media_thumb_wrp .original_img_wrp {
	display: block
}
.rich_media_thumb {
	display: block;
	width: 100%
}
.rich_media_content {
	overflow: hidden;
	color: #3e3e3e
}
.rich_media_content * {
	max-width: 100%!important;
	box-sizing: border-box!important;
	-webkit-box-sizing: border-box!important;
	word-wrap: break-word!important
}
.rich_media_content p {
	clear: both;
	min-height: 1em;
	white-space: pre-wrap
}
.rich_media_content em {
	font-style: italic
}
.rich_media_content fieldset {
	min-width: 0
}
.rich_media_content .list-paddingleft-2 {
	padding-left: 30px
}
.rich_media_content blockquote {
	margin: 0;
	padding-left: 10px;
	border-left: 3px solid #dbdbdb
}
img {
	height: auto!important
}
@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2) {
.mm_appmsg .rich_media_inner, .mm_appmsg .rich_media_meta, .mm_appmsg .discuss_list, .mm_appmsg .rich_media_extra, .mm_appmsg .title_tips .tips {
	font-size: 17px
}
.mm_appmsg .meta_original_tag {
	font-size: 15px
}
}
@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3) {
.mm_appmsg .rich_media_title {
	font-size: 25px
}
}
@media screen and (min-width:1024px) {
.rich_media {
	width: 740px;
	margin-left: auto;
	margin-right: auto
}
.rich_media_inner {
	padding: 20px
}
body {
	background-color: #fff
}
}
@media screen and (min-width:1025px) {
body {
	font-family: "Helvetica Neue", Helvetica, "Hiragino Sans GB", "Microsoft YaHei", Arial, sans-serif
}
.rich_media {
	position: relative
}
.rich_media_inner {
	background-color: #fff;
	padding-bottom: 100px
}
}
.radius_avatar {
	display: inline-block;
	background-color: #fff;
	padding: 3px;
	border-radius: 50%;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
	overflow: hidden;
	vertical-align: middle
}
.radius_avatar img {
	display: block;
	width: 100%;
	height: 100%;
	border-radius: 50%;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
	background-color: #eee
}
.cell {
	padding: .8em 0;
	display: block;
	position: relative
}
.cell_hd, .cell_bd, .cell_ft {
	display: table-cell;
	vertical-align: middle;
	word-wrap: break-word;
	word-break: break-all;
	white-space: nowrap
}
.cell_primary {
	width: 2000px;
	white-space: normal
}
.flex_cell {
	padding: 10px 0;
	display: -webkit-box;
	display: -webkit-flex;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	-ms-flex-align: center;
	align-items: center
}
.flex_cell_primary {
	width: 100%;
	-webkit-box-flex: 1;
	-webkit-flex: 1;
	-ms-flex: 1;
	box-flex: 1;
	flex: 1
}
.original_tool_area {
	display: block;
	padding: .75em 1em 0;
	-webkit-tap-highlight-color: rgba(0,0,0,0);
	color: #3e3e3e;
	border: 1px solid #eaeaea;
	margin: 20px 0
}
.original_tool_area .tips_global {
	position: relative;
	padding-bottom: .5em;
	font-size: 15px
}
.original_tool_area .tips_global:after {
	content: " ";
	position: absolute;
	left: 0;
	bottom: 0;
	right: 0;
	height: 1px;
	border-bottom: 1px solid #dbdbdb;
	-webkit-transform-origin: 0 100%;
	transform-origin: 0 100%;
	-webkit-transform: scaleY(0.5);
	transform: scaleY(0.5)
}
.original_tool_area .radius_avatar {
	width: 27px;
	height: 27px;
	padding: 0;
	margin-right: .5em
}
.original_tool_area .radius_avatar img {
	height: 100%!important
}
.original_tool_area .flex_cell_bd {
	width: auto;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	word-wrap: normal
}
.original_tool_area .flex_cell_ft {
	font-size: 14px;
	color: #8c8c8c;
	padding-left: 1em;
	white-space: nowrap
}
.original_tool_area .icon_access:after {
	content: " ";
	display: inline-block;
	height: 8px;
	width: 8px;
	border-width: 1px 1px 0 0;
	border-color: #cbcad0;
	border-style: solid;
	transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
	-ms-transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
	-webkit-transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
	position: relative;
	top: -2px;
	top: -1px
}
.weui_loading {
	width: 20px;
	height: 20px;
	display: inline-block;
	vertical-align: middle;
	-webkit-animation: weuiLoading 1s steps(12, end) infinite;
	animation: weuiLoading 1s steps(12, end) infinite;
	background: transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;
	-webkit-background-size: 100%;
	background-size: 100%
}
@-webkit-keyframes weuiLoading {
0% {
-webkit-transform:rotate3d(0, 0, 1, 0deg)
}
100% {
-webkit-transform:rotate3d(0, 0, 1, 360deg)
}
}
@keyframes weuiLoading {
0% {
-webkit-transform:rotate3d(0, 0, 1, 0deg)
}
100% {
-webkit-transform:rotate3d(0, 0, 1, 360deg)
}
}
.gif_img_wrp {
	display: inline-block;
	font-size: 0;
	position: relative;
	font-weight: 400;
	font-style: normal;
	text-indent: 0;
	text-shadow: none 1px 1px rgba(0,0,0,0.5)
}
.gif_img_wrp img {
	vertical-align: top
}
.gif_img_tips {
	background: rgba(0,0,0,0.6)!important;
filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr='#99000000', endcolorstr = '#99000000');
	border-top-left-radius: 1.2em 50%;
	-moz-border-radius-topleft: 1.2em 50%;
	-webkit-border-top-left-radius: 1.2em 50%;
	border-top-right-radius: 1.2em 50%;
	-moz-border-radius-topright: 1.2em 50%;
	-webkit-border-top-right-radius: 1.2em 50%;
	border-bottom-left-radius: 1.2em 50%;
	-moz-border-radius-bottomleft: 1.2em 50%;
	-webkit-border-bottom-left-radius: 1.2em 50%;
	border-bottom-right-radius: 1.2em 50%;
	-moz-border-radius-bottomright: 1.2em 50%;
	-webkit-border-bottom-right-radius: 1.2em 50%;
	line-height: 2.3;
	font-size: 11px;
	color: #fff;
	text-align: center;
	position: absolute;
	bottom: 10px;
	left: 10px;
	min-width: 65px
}
.gif_img_tips.loading {
	min-width: 75px
}
.gif_img_tips i {
	vertical-align: middle;
	margin: -0.2em .73em 0 -2px
}
.gif_img_play_arrow {
	display: inline-block;
	width: 0;
	height: 0;
	border-width: 8px;
	border-style: dashed;
	border-color: transparent;
	border-right-width: 0;
	border-left-color: #fff;
	border-left-style: solid;
	border-width: 5px 0 5px 8px
}
.gif_img_loading {
	width: 14px;
	height: 14px
}
i.gif_img_loading {
	margin-left: -4px
}
.rich_media_global_msg {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	padding: 1em 35px 1em 15px;
	z-index: 1;
	background-color: #c6e0f8;
	color: #8c8c8c;
	font-size: 13px
}
.rich_media_global_msg .icon_closed {
	position: absolute;
	right: 15px;
	top: 50%;
	margin-top: -5px;
	line-height: 300px;
	overflow: hidden;
	-webkit-tap-highlight-color: rgba(0,0,0,0);
	width: 11px;
	height: 11px;
	vertical-align: middle;
	display: inline-block;
	-webkit-background-size: 100% auto;
	background-size: 100% auto
}
.rich_media_global_msg .icon_closed:active {
	background-position: 0 -17px
}
.preview_appmsg .rich_media_title {
	margin-top: 1.9em
}
@media screen and (min-width:1024px) {
.rich_media_global_msg {
	position: relative;
	margin: 0 20px
}
.preview_appmsg .rich_media_title {
	margin-top: 0
}
}

</style>


    </head>
    <body id="activity-detail" class="zh_CN mm_appmsg" ontouchstart="">
      

    <div id="js_article" class="rich_media">
        
        <div id="js_top_ad_area" class="top_banner">
 
        </div>
                

        <div class="rich_media_inner">
                        <div id="page-content">
                <div id="img-content" class="rich_media_area_primary">
                    <h2 class="rich_media_title" id="activity-name">
                         {$data.title} 
                    </h2>
                    <div class="rich_media_meta_list">
                    <em id="post-date" class="rich_media_meta rich_media_meta_text">{$data.create_time|date='Y-m-d'}</em>

                      <a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" href="{:C('wapurl')}" id="post-user">{:C('title')}</a>

                        
                    </div>
                    
                    
                    
                                        
                                        
            </div>

                            <div class="rich_media_content " id="js_content" style="padding: 10px;">      {$data.content}</div>

               
            </div>
          

        </div>
    </div>




    </body>
</html>
 
