(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-baoming-jinhuoquantransfer"],{"23aa":function(t,a,e){"use strict";var n=e("e505"),i=e.n(n);i.a},"2d9c":function(t,a,e){"use strict";e.r(a);var n=e("30bf"),i=e.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){e.d(a,t,(function(){return n[t]}))}(o);a["default"]=i.a},"30bf":function(a,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;getApp();var i={data:function(){return{com_cdn:getApp().globalData.com_cdn,mobile:"",money:"",type:0,jihuoquan:0,num:""}},components:{},props:{},onLoad:function(){this.getData()},onReachBottom:function(){this.getData()},onShareAppMessage:function(){return t.util.share()},methods:{getData:function(){var t=this;t.util.ajax("/mallapi/user/index",{},(function(a){t.data=a.data,t.jihuoquan=a.data.finfo.jinhuoquan}))},sub:function(){var t=this;t.mobile?t.num<=0?t.util.show_msg("请输入转账数量"):t.util.ajax("/mallapi/user/jinhuoquantransfer",{num:t.num,mobile:t.mobile},(function(t){uni.reLaunch({url:"/pages/baoming/jinhuoquantransfer"})})):t.util.show_msg("请输入收款人手机号码")}}};e.default=i},"6e02":function(t,a,e){"use strict";e.d(a,"b",(function(){return n})),e.d(a,"c",(function(){return i})),e.d(a,"a",(function(){}));var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",[e("v-uni-view",{staticClass:"jifen"},[e("v-uni-image",{staticClass:"jifen-bg",attrs:{src:t.com_cdn+"user/jifen.jpg"}}),e("v-uni-view",{staticClass:"jifen-box"},[e("v-uni-view",{staticClass:"jifen-p"},[t._v("我的进货券")]),e("v-uni-view",{staticClass:"jifen-num"},[t._v(t._s(t.jihuoquan))])],1)],1),e("v-uni-form",{on:{submit:function(a){arguments[0]=a=t.$handleEvent(a),t.sub.apply(void 0,arguments)}}},[e("v-uni-view",{staticClass:"edit-item flex-center"},[e("v-uni-text",{staticStyle:{width:"200upx"}},[t._v("收款人手机号码")]),e("v-uni-input",{attrs:{name:"tel",type:"number",placeholder:"请输入收款人手机号码"},model:{value:t.mobile,callback:function(a){t.mobile=a},expression:"mobile"}})],1),e("v-uni-view",{staticClass:"line"}),e("v-uni-view",{staticClass:"edit-item flex-center"},[e("v-uni-text",[t._v("数量")]),e("v-uni-input",{attrs:{name:"num",type:"number",placeholder:"请输入转让数量"},model:{value:t.num,callback:function(a){t.num=a},expression:"num"}})],1),e("v-uni-view",{staticClass:"line"}),e("v-uni-button",{staticClass:"btn",staticStyle:{},attrs:{"form-type":"submit"}},[t._v("确定")])],1),e("v-uni-navigator",{staticClass:"tixian-btn2",attrs:{url:"/pages/user/log/log?flag=jinhuoquan"}},[t._v("明细")])],1)},i=[]},c3f8:function(t,a,e){"use strict";e.r(a);var n=e("6e02"),i=e("2d9c");for(var o in i)["default"].indexOf(o)<0&&function(t){e.d(a,t,(function(){return i[t]}))}(o);e("23aa");var f=e("828b"),d=Object(f["a"])(i["default"],n["b"],n["c"],!1,null,"0b42fdfa",null,!1,n["a"],void 0);a["default"]=d.exports},e505:function(t,a,e){var n=e("eaa9");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("967d").default;i("26ee4d19",n,!0,{sourceMap:!1,shadowMode:!1})},eaa9:function(t,a,e){var n=e("c86c");a=n(!1),a.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-0b42fdfa]{color:#333;background-color:#f1f2f7}body.?%PAGE?%[data-v-0b42fdfa]{background-color:#f1f2f7}uni-view[data-v-0b42fdfa]{box-sizing:border-box}.tixian-btn2.act[data-v-0b42fdfa]{background:#1a72de;color:#fff}.tixian-btn2[data-v-0b42fdfa]{width:100%;height:40px;border-radius:25px;text-align:center;line-height:40px;font-size:16px;color:#333;margin-top:%?120?%;background-color:#fff;border:solid 1px #e5e5e5}.jifen-num2[data-v-0b42fdfa]{width:%?180?%;height:%?60?%;line-height:%?60?%;font-size:%?36?%;text-align:center;background-color:#1a72de;color:#fff;border-radius:%?10?%;margin-top:%?20?%}.jifen[data-v-0b42fdfa]{width:100%;height:%?326?%;position:relative}.jifen-bg[data-v-0b42fdfa]{width:100%;height:%?326?%}.jifen-box[data-v-0b42fdfa]{width:100%;height:%?280?%;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;color:#333;position:absolute;top:0;left:0;z-index:9}.red[data-v-0b42fdfa]{color:red}.green[data-v-0b42fdfa]{color:green}.jifen-p[data-v-0b42fdfa]{font-size:%?30?%}.jifen-num[data-v-0b42fdfa]{font-size:%?70?%}.title[data-v-0b42fdfa]{padding:%?30?%;display:flex;justify-content:space-between;align-items:center}.title-h[data-v-0b42fdfa]{font-size:%?36?%;font-weight:700;color:#333}.title-p[data-v-0b42fdfa]{font-size:%?28?%;color:#999}.jifen-list[data-v-0b42fdfa]{margin-bottom:%?20?%;padding-left:%?30?%;background:#fff}.jifen-item[data-v-0b42fdfa]{padding:%?30?% 0;padding-right:%?30?%;display:flex;justify-content:space-between;border-bottom:1px #e6e6e6 solid}.jifen-item[data-v-0b42fdfa]:last-child{border-bottom:none}.jifen-img[data-v-0b42fdfa]{width:%?86?%;height:%?86?%}.jifen-image[data-v-0b42fdfa]{width:%?86?%;height:%?86?%}.jifen-info[data-v-0b42fdfa]{width:%?575?%}.jifen-info-top[data-v-0b42fdfa]{margin-bottom:%?30?%;display:flex;justify-content:space-between;font-size:%?30?%;color:#333}.jifen-info-p[data-v-0b42fdfa]{font-size:%?24?%;color:#333}.jifen-time[data-v-0b42fdfa]{font-size:%?24?%;color:#999}.container[data-v-0b42fdfa]{padding:0 3%;box-sizing:border-box}.flex-center[data-v-0b42fdfa]{display:flex;align-items:center}.ellipsis[data-v-0b42fdfa]{text-overflow:ellipsis;overflow:hidden;white-space:nowrap}.color0[data-v-0b42fdfa]{color:#000}.color1[data-v-0b42fdfa]{color:#00f}.color2[data-v-0b42fdfa]{color:red}.many-colum[data-v-0b42fdfa]{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;text-overflow:ellipsis;overflow:hidden}.line[data-v-0b42fdfa]{height:%?20?%;background:#f7f7f7}.success-icon[data-v-0b42fdfa]{width:%?37?%;height:%?37?%}.edit-item[data-v-0b42fdfa]{position:relative;padding:%?35?% %?30?%;background-color:#fff;font-size:%?28?%}.edit-item[data-v-0b42fdfa]::after{content:"";position:absolute;bottom:0;left:%?30?%;height:1px;width:100%;background-color:#d8d7d6;opacity:.6}.edit-item uni-text[data-v-0b42fdfa]{font-weight:700;color:#000;width:20%}.edit-item uni-input[data-v-0b42fdfa]{flex-grow:1}.default[data-v-0b42fdfa]{display:flex;align-items:center;font-size:%?24?%;color:#636161;margin-left:auto}.default .select-icon[data-v-0b42fdfa]{margin-right:%?11?%}.btn[data-v-0b42fdfa]{position:fixed;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%);width:%?695?%;height:%?80?%;line-height:%?80?%;text-align:center;color:#fff;font-size:%?28?%;background:#1a72de;background-blend-mode:normal,normal;border-radius:%?40?%}',""]),t.exports=a}}]);