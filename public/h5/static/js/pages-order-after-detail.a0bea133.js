(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-after-detail"],{"0828":function(t,a,e){var i=e("71b6");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("967d").default;n("fbc9f646",i,!0,{sourceMap:!1,shadowMode:!1})},"12a8":function(t,a,e){"use strict";var i=e("0828"),n=e.n(i);n.a},7137:function(t,a,e){"use strict";e.r(a);var i=e("7f60"),n=e.n(i);for(var s in i)["default"].indexOf(s)<0&&function(t){e.d(a,t,(function(){return i[t]}))}(s);a["default"]=n.a},"71b6":function(t,a,e){var i=e("c86c");a=i(!1),a.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* pages/user/afterSales/detail/index.wxss */uni-page-body[data-v-0b2a36f8]{background-color:#f5f5f5}body.?%PAGE?%[data-v-0b2a36f8]{background-color:#f5f5f5}.header_cv[data-v-0b2a36f8]{background-color:#fff;padding-top:%?80?%;padding-bottom:%?60?%}.title_tv[data-v-0b2a36f8]{display:flex;justify-content:center;align-items:center;font-size:%?36?%;color:#333}.title_tv uni-image[data-v-0b2a36f8]{width:%?160?%;height:%?160?%;margin-right:%?10?%}.header_cv .tt[data-v-0b2a36f8]{margin-top:%?40?%;font-size:%?32?%;color:#333;text-align:center}.conteView[data-v-0b2a36f8]{padding:0 %?24?%;margin-top:%?20?%}.conte_cv[data-v-0b2a36f8]{padding:%?24?%;background-color:#fff;border-radius:%?12?%;margin-bottom:%?20?%}.order_ct[data-v-0b2a36f8]{font-size:%?28?%;color:#666;height:%?64?%;display:flex;justify-content:space-between;align-items:center}.order_ct .name[data-v-0b2a36f8]{width:%?180?%}.order_ct .rt[data-v-0b2a36f8]{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.conte_cv .title[data-v-0b2a36f8]{font-size:%?32?%;color:#333;margin-bottom:%?28?%}.conte_cv2 .rt[data-v-0b2a36f8]{text-align:right}',""]),t.exports=a},"72e2":function(t,a,e){"use strict";e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return n})),e.d(a,"a",(function(){}));var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:" "},[e("v-uni-view",{staticClass:"conteView"},[e("v-uni-view",{staticClass:"conte_cv"},[e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("当前进度")]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(t.data.status_text))])],1),e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("商品名称")]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(t.data.name))])],1),e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("申请金额")]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(t.data.refund_price))])],1),e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("售后单号")]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(t.data.sn))])],1),e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("申请金额")]),e("v-uni-view",{staticClass:"rt"},[t._v("¥"+t._s(t.data.refund_price))])],1),e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("申请原因")]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(t.data.refund_reason))])],1),e("v-uni-view",{staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v("申请说明")]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(t.data.refund_remark))])],1)],1),e("v-uni-view",{staticClass:"conte_cv conte_cv2"},[e("v-uni-view",{staticClass:"title"},[t._v("申请记录")]),t._l(t.data.log,(function(a,i){return e("v-uni-view",{key:i,staticClass:"order_ct"},[e("v-uni-view",{staticClass:"name"},[t._v(t._s(a.content))]),e("v-uni-view",{staticClass:"rt"},[t._v(t._s(a.create_time))])],1)}))],2)],1)],1)},n=[]},"7f60":function(t,a,e){"use strict";e("6a54"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={data:function(){return{id:0,data:""}},components:{},props:{},onLoad:function(t){this.setData({id:t.id}),this.getData()},onShareAppMessage:function(){return util.share()},methods:{getData:function(){var t=this;t.util.ajax("/mallapi/AfterSale/item",{id:t.id},(function(a){t.setData({data:a.data})}))}}};a.default=i},beb1:function(t,a,e){"use strict";e.r(a);var i=e("72e2"),n=e("7137");for(var s in n)["default"].indexOf(s)<0&&function(t){e.d(a,t,(function(){return n[t]}))}(s);e("12a8");var r=e("828b"),c=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"0b2a36f8",null,!1,i["a"],void 0);a["default"]=c.exports}}]);