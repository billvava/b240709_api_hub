(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-cashout_channel-index"],{"1ffc":function(t,a,n){var e=n("c86c");a=e(!1),a.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.card-wrap[data-v-1d3373c1]{padding:%?30?%}.pro-tit[data-v-1d3373c1]{margin-bottom:%?20?%;display:flex;justify-content:center;align-items:center;height:%?60?%}.pro-tit-em[data-v-1d3373c1]{width:%?70?%;height:1px;background:#e6e6e6}.pro-tit-view[data-v-1d3373c1]{margin:0 %?40?%;font-size:%?24?%;color:#333}.card-li[data-v-1d3373c1]{margin-bottom:%?20?%;padding:%?30?%;display:flex;justify-content:space-between;align-items:center;border-radius:%?15?%}.card-name[data-v-1d3373c1]{margin-bottom:%?20?%;font-size:%?32?%;color:#fff}.carder[data-v-1d3373c1]{font-size:%?24?%;color:#fff}.del-btn[data-v-1d3373c1]{display:flex;align-items:center;color:#fff}.del_icon[data-v-1d3373c1]{width:%?30?%;height:%?32?%;margin-left:%?10?%}.card-del-p[data-v-1d3373c1]{font-size:%?24?%;color:#fff}.del-btn .iconfont[data-v-1d3373c1]{font-size:20px}.creat[data-v-1d3373c1]{padding:0 %?20?%;border-radius:%?15?%;background:#fff}.creat-item[data-v-1d3373c1]{display:flex;justify-content:space-between;align-items:center;height:%?120?%;border-bottom:1px #e6e6e6 solid}.creat-item-p[data-v-1d3373c1]{font-size:%?28?%;color:#333}.creat-item-in[data-v-1d3373c1]{width:%?450?%;font-size:%?28?%;text-align:right}.creat-btn-wrap[data-v-1d3373c1]{padding:%?60?% %?30?%}.creat-btn[data-v-1d3373c1]{width:100%;height:%?80?%;border-radius:%?15?%;background:#1a72de;text-align:center;line-height:%?80?%;font-size:%?32?%;color:#fff}',""]),t.exports=a},2698:function(t,a,n){var e=n("1ffc");e.__esModule&&(e=e.default),"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var i=n("967d").default;i("6d58aae6",e,!0,{sourceMap:!1,shadowMode:!1})},"7ff3":function(t,a,n){"use strict";n.r(a);var e=n("b18b"),i=n.n(e);for(var r in e)["default"].indexOf(r)<0&&function(t){n.d(a,t,(function(){return e[t]}))}(r);a["default"]=i.a},abc8:function(t,a,n){"use strict";n.r(a);var e=n("b297"),i=n("7ff3");for(var r in i)["default"].indexOf(r)<0&&function(t){n.d(a,t,(function(){return i[t]}))}(r);n("e926");var c=n("828b"),d=Object(c["a"])(i["default"],e["b"],e["c"],!1,null,"1d3373c1",null,!1,e["a"],void 0);a["default"]=d.exports},b18b:function(t,a,n){"use strict";n("6a54"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0,n("5c47"),n("af8f"),n("c223");var e={data:function(){return{com_cdn:getApp().globalData.com_cdn,list:[],is_load:1,page:1,load_other:1,data:null}},onLoad:function(){},onReachBottom:function(){},onShow:function(){this.search()},methods:{del:function(t){var a=this;a.util.ajax("/mallapi/Cashout/channel_del",{id:t},(function(t){a.search()}))},search:function(){var t=this;t.page=1,t.list=[],t.is_load=1,t.load_data()},load_data:function(){var t=this;0!=t.is_load&&t.util.ajax("/mallapi/Cashout/channel_list",{page:t.page},(function(a){1==t.load_other&&(t.data=a.data,t.load_other=0),a.data.count<=0?t.is_load=0:(t.list=t.list.concat(a.data.list),t.page=t.page+1)}))}}};a.default=e},b297:function(t,a,n){"use strict";n.d(a,"b",(function(){return e})),n.d(a,"c",(function(){return i})),n.d(a,"a",(function(){}));var e=function(){var t=this,a=t.$createElement,n=t._self._c||a;return t.data?n("v-uni-view",[n("v-uni-view",{staticClass:"card-wrap"},[n("v-uni-view",{staticClass:"card-viewst"},[n("v-uni-view",{staticClass:"card-ul"},[t._l(t.list,(function(a,e){return[n("v-uni-view",{staticClass:"card-li",style:"background:"+a.bg,on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.tourl("./item?id="+a.id)}}},[n("v-uni-view",{staticClass:"card-left"},[n("v-uni-view",{staticClass:"card-name"},[t._v("【"+t._s(a.cate_str)+"】"+t._s(a.name))]),n("v-uni-view",{staticClass:"carder"},[t._v(t._s(a.realname)+" "+t._s(a.num))])],1),n("v-uni-view",{staticClass:"del-btn",on:{click:function(n){n.stopPropagation(),arguments[0]=n=t.$handleEvent(n),t.del(a.id)}}},[n("v-uni-view",{staticClass:"card-del-p"},[t._v("删除")]),n("v-uni-image",{staticClass:"del_icon",attrs:{src:t.com_cdn+"del2.png"}})],1)],1)]})),t.list.length<=0?n("msg"):t._e()],2)],1),n("v-uni-navigator",{staticClass:"creat-btn-wrap",attrs:{url:"./item"}},[n("v-uni-view",{staticClass:"creat-btn"},[t._v("去添加渠道信息")])],1)],1),n("foot")],1):t._e()},i=[]},e926:function(t,a,n){"use strict";var e=n("2698"),i=n.n(e);i.a}}]);