(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-login-login_h5"],{"302b":function(n,t,i){"use strict";i.r(t);var a=i("6233"),e=i("64a6");for(var r in e)["default"].indexOf(r)<0&&function(n){i.d(t,n,(function(){return e[n]}))}(r);i("6050");var o=i("828b"),s=Object(o["a"])(e["default"],a["b"],a["c"],!1,null,"7e06ade6",null,!1,a["a"],void 0);t["default"]=s.exports},6050:function(n,t,i){"use strict";var a=i("ea3d"),e=i.n(a);e.a},6233:function(n,t,i){"use strict";i.d(t,"b",(function(){return a})),i.d(t,"c",(function(){return e})),i.d(t,"a",(function(){}));var a=function(){var n=this,t=n.$createElement,i=n._self._c||t;return i("v-uni-view",{staticClass:"bg"},[i("v-uni-view",{staticStyle:{height:"200upx"}}),i("v-uni-view",{staticClass:"box"},[i("v-uni-view",{staticClass:"box"},[i("v-uni-view",{staticClass:"form"},[i("v-uni-image",{attrs:{src:"/static/icon/userName.png",mode:""}}),i("v-uni-input",{attrs:{type:"text",placeholder:"用户名"},model:{value:n.form.username,callback:function(t){n.$set(n.form,"username",t)},expression:"form.username"}})],1),i("v-uni-view",{staticClass:"form"},[i("v-uni-image",{attrs:{src:"/static/icon/password.png",mode:""}}),i("v-uni-input",{attrs:{type:"password",placeholder:"密码"},model:{value:n.form.pwd,callback:function(t){n.$set(n.form,"pwd",t)},expression:"form.pwd"}})],1)],1),i("v-uni-view",{staticClass:"submit",on:{click:function(t){arguments[0]=t=n.$handleEvent(t),n.sub.apply(void 0,arguments)}}},[n._v("登录")]),i("v-uni-view",{staticClass:"submit1",on:{click:function(t){arguments[0]=t=n.$handleEvent(t),n.tourl("/pages/login/register")}}},[n._v("注册")]),i("v-uni-view",{staticClass:"submit1",on:{click:function(t){arguments[0]=t=n.$handleEvent(t),n.tzhuan("http://www.xiaoguoshangcheng.com/down")}}},[n._v("APP下载")])],1)],1)},e=[]},"64a6":function(n,t,i){"use strict";i.r(t);var a=i("cc42"),e=i.n(a);for(var r in a)["default"].indexOf(r)<0&&function(n){i.d(t,n,(function(){return a[n]}))}(r);t["default"]=e.a},cc42:function(n,t,i){"use strict";i("6a54");var a=i("f5bd").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;a(i("74ed"));var e={data:function(){return{static:getApp().globalData.cdn,current:0,form:{username:"",pwd:""}}},onLoad:function(n){this.signUp.invitation_code=n.invitation_code},onUnload:function(){},methods:{change:function(n){this.current=n},sub:function(){this.util.ajax("/mallapi/login/pwd_login",this.form,(function(n){uni.setStorageSync("token",n.data.token),uni.navigateTo({url:"/pages/index/index"})}))},toForgot:function(){uni.navigateTo({url:"/pages/login/forgot"})},tzhuan:function(n){window.location.href=n},reg:function(){this.util.ajax("/mallapi/login/register",this.signUp,(function(n){uni.switchTab({url:"/pages/login/login_h5"})}))}}};t.default=e},ea3d:function(n,t,i){var a=i("fef4");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var e=i("967d").default;e("62f5d294",a,!0,{sourceMap:!1,shadowMode:!1})},fef4:function(n,t,i){var a=i("c86c");t=a(!1),t.push([n.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.submit1[data-v-7e06ade6]{border:1px #000 solid;padding:%?20?%;border-radius:%?50?%;color:#000;margin:25px %?50?%;background:linear-gradient(90deg,#fff 0,#fff)}.bg[data-v-7e06ade6]{background:linear-gradient(314deg,#20599c,#3b2893 49%,#402195);min-height:100vh;text-align:center}.bg .logo[data-v-7e06ade6]{width:%?200?%;height:%?200?%;margin-top:%?100?%}.box[data-v-7e06ade6]{background-color:#fff;padding:0 %?30?% %?20?%;margin:%?30?% %?30?% %?10?%;border-radius:%?30?%}.form[data-v-7e06ade6]{display:flex;border-bottom:%?1?% solid #c8c8c8;padding:%?20?% 0}.form uni-image[data-v-7e06ade6]{height:%?40?%;width:%?40?%;margin-top:%?14?%;margin-right:%?20?%}.submit[data-v-7e06ade6]{padding:%?20?%;border-radius:%?50?%;color:#fff;margin:0 %?50?%;background:linear-gradient(90deg,#54fdba 0,#28bd83)}.forgot[data-v-7e06ade6]{margin-top:5px;margin-left:150px;width:150px;text-align:right;padding:%?20?%;font-size:%?24?%}',""]),n.exports=t}}]);