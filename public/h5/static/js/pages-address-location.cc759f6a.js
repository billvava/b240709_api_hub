(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-address-location"],{"0407":function(t,e,n){"use strict";n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){}));var a=function(){var t=this.$createElement,e=this._self._c||t;return e("v-uni-view",{staticClass:"u-safe-bottom",class:[!this.isNvue&&"u-safe-area-inset-bottom"],style:[this.style]})},r=[]},"20b3":function(t,e,n){"use strict";n.r(e);var a=n("0407"),r=n("97ef");for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("d819");var o=n("828b"),s=Object(o["a"])(r["default"],a["b"],a["c"],!1,null,"eca591a4",null,!1,a["a"],void 0);e["default"]=s.exports},28133:function(t,e,n){"use strict";n.r(e);var a=n("35e3"),r=n.n(a);for(var i in a)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return a[t]}))}(i);e["default"]=r.a},"2a92":function(t,e,n){var a=n("c2e1");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=n("967d").default;r("6f023ab6",a,!0,{sourceMap:!1,shadowMode:!1})},"35e3":function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={name:"search",props:{placeholder:{type:String,default:"输入关键字搜索"},mall:{type:Boolean,default:!1},btnSearch:{type:Boolean,default:!1}},data:function(){return{static:getApp().globalData.cdn,searchText:""}},methods:{changeVal:function(t){this.searchText=t},handleChange:function(t){this.searchText=t,this.$emit("change",t)},confirm:function(t){this.$emit("confirm",this.searchText)},handleSearch:function(){this.$emit("search",this.searchText)}}};e.default=a},"3c8b":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return a}));var a={uInput:n("1dc9").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"search",class:{mall:t.mall}},[n("v-uni-image",{attrs:{src:t.static+"common/icon-search.png",mode:"scaleToFill"}}),n("u-input",{attrs:{border:"none",placeholder:t.placeholder,value:t.searchText},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.handleChange.apply(void 0,arguments)},confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.confirm.apply(void 0,arguments)}}}),t.btnSearch?n("v-uni-view",{staticClass:"btn-search",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleSearch.apply(void 0,arguments)}}},[t._v("搜索")]):t._e()],1)},i=[]},"467b":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return a}));var a={uIcon:n("5245").default,search:n("cb43").default,uSafeBottom:n("20b3").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",[n("v-uni-view",{staticClass:"header"},[n("v-uni-view",{staticClass:"select-address"},[t._v("南宁市"),n("u-icon",{attrs:{color:"#333",size:"16",name:"arrow-down-fill"}})],1),n("search",{attrs:{placeholder:"输入您的地址"}})],1),n("v-uni-view",{staticClass:"current-location"},[n("v-uni-view",{staticClass:"title"},[t._v("当前定位")]),n("v-uni-view",{staticClass:"location"},[n("v-uni-image",{attrs:{src:t.static+"address/icon-location.png",mode:"scaleToFill"}}),t._v("良庆区绿地中心8栋")],1),n("v-uni-view",{staticClass:"btn-relocation"},[n("v-uni-image",{attrs:{src:t.static+"address/icon-pointer.png",mode:"scaleToFill"}}),t._v("重新定位")],1)],1),n("v-uni-view",{staticClass:"nearby-container"},[n("v-uni-view",{staticClass:"title"},[t._v("附近地址")]),n("v-uni-scroll-view",{staticClass:"nearby-list",style:{height:t.height},attrs:{"scroll-y":!0}},[t._l(10,(function(e){return n("v-uni-view",{key:e,staticClass:"item"},[n("v-uni-image",{attrs:{src:t.static+"common/icon-location.png",mode:"scaleToFill"}}),n("v-uni-view",{staticClass:"name"},[t._v("利江公寓")]),n("v-uni-view",{staticClass:"address"},[t._v("南宁市五象新区绿地中心8号楼28")]),n("v-uni-view",{staticClass:"distance"},[t._v("876m")])],1)})),n("u-safe-bottom")],2)],1)],1)},i=[]},5647:function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;e.default={props:{}}},6836:function(t,e,n){"use strict";var a=n("2a92"),r=n.n(a);r.a},"683b":function(t,e,n){"use strict";n("6a54");var a=n("f5bd").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=a(n("92e3")),i={data:function(){return{height:"100vh",static:getApp().globalData.cdn}},onLoad:function(){var t=this;setTimeout((function(){return(0,r.default)(".nearby-list").then((function(e){return t.height="calc(100vh - ".concat(e.top,"px)")}))}),0)}};e.default=i},8200:function(t,e,n){var a=n("c86c");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.search[data-v-4bac3b9b]{flex:1;display:flex;align-items:center;height:%?72?%;border-radius:%?36?%;background:#f6f6f6;padding-right:%?120?%;box-sizing:border-box;position:relative}.search.mall[data-v-4bac3b9b]{background:#fff;box-shadow:0 0 0 %?2?% #639dff inset}.search uni-image[data-v-4bac3b9b]{width:%?48?%;height:%?48?%;margin:0 %?16?% 0 %?20?%}.search .btn-search[data-v-4bac3b9b]{display:flex;align-items:center;justify-content:center;width:%?100?%;height:%?56?%;color:#fff;border-radius:%?30?%;background:linear-gradient(90deg,#2c7cf8,#00acff);position:absolute;right:%?8?%;top:%?8?%}',""]),t.exports=e},8870:function(t,e,n){"use strict";n.r(e);var a=n("467b"),r=n("aa3f");for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("6836");var o=n("828b"),s=Object(o["a"])(r["default"],a["b"],a["c"],!1,null,"4bd24283",null,!1,a["a"],void 0);e["default"]=s.exports},"92e3":function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("bf0f"),n("5c47");e.default=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(!t)return Promise.reject("element selector required.");var n=uni.createSelectorQuery();return e&&n.in(e),new Promise((function(a,r){n.select(t).boundingClientRect((function(n){console.log("selector[".concat(t,"] query boundingClientRect:"),n,e),a(n)})).exec()}))}},"97ef":function(t,e,n){"use strict";n.r(e);var a=n("a564"),r=n.n(a);for(var i in a)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return a[t]}))}(i);e["default"]=r.a},a564:function(t,e,n){"use strict";n("6a54");var a=n("f5bd").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=a(n("5647")),i={name:"u-safe-bottom",mixins:[uni.$u.mpMixin,uni.$u.mixin,r.default],data:function(){return{safeAreaBottomHeight:0,isNvue:!1}},computed:{style:function(){return uni.$u.deepMerge({},uni.$u.addStyle(this.customStyle))}},mounted:function(){}};e.default=i},aa3f:function(t,e,n){"use strict";n.r(e);var a=n("683b"),r=n.n(a);for(var i in a)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return a[t]}))}(i);e["default"]=r.a},c2e1:function(t,e,n){var a=n("c86c");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.header[data-v-4bd24283]{display:flex;align-items:center;background:#fff;padding-bottom:%?42?%}.header .select-address[data-v-4bd24283]{flex:none;display:flex;align-items:center;justify-content:center;width:%?192?%}.header .select-address .u-icon[data-v-4bd24283]{margin:%?10?% 0 0 %?16?%}.header[data-v-4bd24283] .search{width:%?534?%}.current-location[data-v-4bd24283]{display:flex;align-items:center;justify-content:space-between;height:%?108?%;background:#fff;margin-bottom:%?20?%;padding:%?34?% %?24?% 0 %?20?%;position:relative}.current-location .title[data-v-4bd24283]{line-height:%?34?%;font-size:%?28?%;color:#999;position:absolute;left:%?24?%;top:0}.current-location .location[data-v-4bd24283]{display:flex;align-items:center;font-size:%?34?%;color:#333}.current-location .location uni-image[data-v-4bd24283]{width:%?32?%;height:%?32?%;margin-right:%?10?%}.current-location .btn-relocation[data-v-4bd24283]{font-size:%?28?%;color:#1a72de}.current-location .btn-relocation uni-image[data-v-4bd24283]{width:%?32?%;height:%?32?%;margin-right:%?12?%}.nearby-container[data-v-4bd24283]{background:#fff}.nearby-container .title[data-v-4bd24283]{line-height:%?34?%;font-size:%?28?%;color:#999;padding:%?40?% %?24?% %?6?%}.nearby-list .item[data-v-4bd24283]{border-bottom:%?2?% solid #e6e6e6;margin:0 %?24?% %?14?%;padding:%?40?% 0 %?34?% %?56?%;position:relative}.nearby-list .item uni-image[data-v-4bd24283]{width:%?32?%;height:%?32?%;position:absolute;left:%?4?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.nearby-list .item .name[data-v-4bd24283]{width:90%;height:%?42?%;line-height:%?42?%;font-size:%?34?%;color:#333;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;margin-bottom:%?18?%}.nearby-list .item .address[data-v-4bd24283]{width:90%;height:%?30?%;line-height:%?30?%;color:#999;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}.nearby-list .item .distance[data-v-4bd24283]{white-space:nowrap;color:#999;position:absolute;right:%?8?%;top:%?48?%}',""]),t.exports=e},cb43:function(t,e,n){"use strict";n.r(e);var a=n("3c8b"),r=n("28133");for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("fd85");var o=n("828b"),s=Object(o["a"])(r["default"],a["b"],a["c"],!1,null,"4bac3b9b",null,!1,a["a"],void 0);e["default"]=s.exports},d819:function(t,e,n){"use strict";var a=n("eecb"),r=n.n(a);r.a},eb86:function(t,e,n){var a=n("c86c");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-safe-bottom[data-v-eca591a4]{width:100%}',""]),t.exports=e},eecb:function(t,e,n){var a=n("eb86");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=n("967d").default;r("800fe394",a,!0,{sourceMap:!1,shadowMode:!1})},f502:function(t,e,n){var a=n("8200");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=n("967d").default;r("53cb821e",a,!0,{sourceMap:!1,shadowMode:!1})},fd85:function(t,e,n){"use strict";var a=n("f502"),r=n.n(a);r.a}}]);