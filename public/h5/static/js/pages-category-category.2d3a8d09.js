(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-category-category"],{28133:function(t,e,n){"use strict";n.r(e);var a=n("35e3"),r=n.n(a);for(var i in a)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return a[t]}))}(i);e["default"]=r.a},"30f7":function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")},n("7a76"),n("c9b5")},"35e3":function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={name:"search",props:{placeholder:{type:String,default:"输入关键字搜索"},mall:{type:Boolean,default:!1},btnSearch:{type:Boolean,default:!1}},data:function(){return{static:getApp().globalData.cdn,searchText:""}},methods:{changeVal:function(t){this.searchText=t},handleChange:function(t){this.searchText=t,this.$emit("change",t)},confirm:function(t){this.$emit("confirm",this.searchText)},handleSearch:function(){this.$emit("search",this.searchText)}}};e.default=a},"3c8b":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return a}));var a={uInput:n("1dc9").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"search",class:{mall:t.mall}},[n("v-uni-image",{attrs:{src:t.static+"common/icon-search.png",mode:"scaleToFill"}}),n("u-input",{attrs:{border:"none",placeholder:t.placeholder,value:t.searchText},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.handleChange.apply(void 0,arguments)},confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.confirm.apply(void 0,arguments)}}}),t.btnSearch?n("v-uni-view",{staticClass:"btn-search",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleSearch.apply(void 0,arguments)}}},[t._v("搜索")]):t._e()],1)},i=[]},4733:function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){if(Array.isArray(t))return(0,a.default)(t)};var a=function(t){return t&&t.__esModule?t:{default:t}}(n("8d0b"))},"47c8":function(t,e,n){"use strict";n("6a54");var a=n("f5bd").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=a(n("b7c7"));n("c223"),n("fd3c"),n("5c47"),n("dc69"),n("aa77"),n("bf0f");var i=a(n("92e3")),c={data:function(){return{static:getApp().globalData.cdn,height:"100vh",currentNav:"",currentNavLock:!1,currentCategoryId:"",subTitlesRect:[],categories:[]}},onLoad:function(){var t=this;setTimeout((function(){(0,i.default)(".category-list").then((function(e){var n=uni.getSystemInfoSync(),a=n.windowTop;t.height="calc(100vh - ".concat(a,"px - ").concat(e.top,"px)")}))}),0),this.categories=["智能门锁","玻璃门锁玻璃门锁","家用门锁","保险箱","别墅锁","电车锁"].map((function(t,e){return{name:t,id:"cate-".concat(e),children:(0,r.default)(SubCategories)}})),this.currentNav=this.categories[0].id,setTimeout((function(){uni.createSelectorQuery().selectAll(".sub-title").fields({id:!0,rect:!0}).exec((function(e){t.subTitlesRect=e[0].reverse()}))}),300)},methods:{handleSelectSubCategory:function(t){this.currentCategoryId=t.id,this.currentNav=t.id,this.currentNavLock=!0},handleCategoryListScroll:function(t){var e=t.currentTarget.offsetTop+t.detail.scrollTop,n=this.subTitlesRect.find((function(t){return t.top<=e}));n&&(this.currentNavLock&&this.currentNav!==n.id?(this.currentNav=this.currentCategoryId,this.currentNavLock=!1):this.currentNav=n.id)}}};e.default=c},8200:function(t,e,n){var a=n("c86c");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.search[data-v-4bac3b9b]{flex:1;display:flex;align-items:center;height:%?72?%;border-radius:%?36?%;background:#f6f6f6;padding-right:%?120?%;box-sizing:border-box;position:relative}.search.mall[data-v-4bac3b9b]{background:#fff;box-shadow:0 0 0 %?2?% #639dff inset}.search uni-image[data-v-4bac3b9b]{width:%?48?%;height:%?48?%;margin:0 %?16?% 0 %?20?%}.search .btn-search[data-v-4bac3b9b]{display:flex;align-items:center;justify-content:center;width:%?100?%;height:%?56?%;color:#fff;border-radius:%?30?%;background:linear-gradient(90deg,#2c7cf8,#00acff);position:absolute;right:%?8?%;top:%?8?%}',""]),t.exports=e},"8c74":function(t,e,n){var a=n("f445");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=n("967d").default;r("4615839c",a,!0,{sourceMap:!1,shadowMode:!1})},"92e3":function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("bf0f"),n("5c47");e.default=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(!t)return Promise.reject("element selector required.");var n=uni.createSelectorQuery();return e&&n.in(e),new Promise((function(a,r){n.select(t).boundingClientRect((function(n){console.log("selector[".concat(t,"] query boundingClientRect:"),n,e),a(n)})).exec()}))}},"99c8":function(t,e,n){"use strict";n.r(e);var a=n("9a9b"),r=n("ee34");for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("c06a");var c=n("828b"),o=Object(c["a"])(r["default"],a["b"],a["c"],!1,null,"735dff82",null,!1,a["a"],void 0);e["default"]=o.exports},"9a9b":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return a}));var a={search:n("cb43").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",[n("v-uni-view",{staticClass:"search-content"},[n("search")],1),n("v-uni-view",{staticClass:"category-container"},[n("v-uni-view",{staticClass:"nav"},t._l(t.categories,(function(e){return n("v-uni-view",{key:e.id,staticClass:"item",class:{active:t.currentNav===e.id},on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.handleSelectSubCategory(e)}}},[n("v-uni-text",[t._v(t._s(e.name))]),n("v-uni-image",{attrs:{src:t.static+"category/bg-nav-arrow.png",mode:"scaleToFill"}}),n("v-uni-image",{attrs:{src:t.static+"category/bg-nav-arrow.png",mode:"scaleToFill"}})],1)})),1),n("v-uni-scroll-view",{staticClass:"category-list",style:{height:t.height},attrs:{"scroll-y":!0,"scroll-with-animation":!0,"enable-back-to-top":!0,"scroll-into-view":t.currentCategoryId},on:{scroll:function(e){arguments[0]=e=t.$handleEvent(e),t.handleCategoryListScroll.apply(void 0,arguments)}}},[t._l(t.categories,(function(e){return[n("v-uni-view",{key:e.id,staticClass:"sub-title",attrs:{id:e.id}},[t._v(t._s(e.name))]),n("v-uni-view",{staticClass:"sub-categories"},t._l(e.children,(function(e){return n("v-uni-navigator",{key:e.id,attrs:{url:"/pages/catetory-details/category-details"}},[n("v-uni-image",{attrs:{src:e.pic,mode:"aspectFit"}}),n("v-uni-text",[t._v(t._s(e.name))])],1)})),1)]}))],2)],1)],1)},i=[]},b7c7:function(t,e,n){"use strict";n("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){return(0,a.default)(t)||(0,r.default)(t)||(0,i.default)(t)||(0,c.default)()};var a=o(n("4733")),r=o(n("d14d")),i=o(n("5d6b")),c=o(n("30f7"));function o(t){return t&&t.__esModule?t:{default:t}}},c06a:function(t,e,n){"use strict";var a=n("8c74"),r=n.n(a);r.a},cb43:function(t,e,n){"use strict";n.r(e);var a=n("3c8b"),r=n("28133");for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("fd85");var c=n("828b"),o=Object(c["a"])(r["default"],a["b"],a["c"],!1,null,"4bac3b9b",null,!1,a["a"],void 0);e["default"]=o.exports},ee34:function(t,e,n){"use strict";n.r(e);var a=n("47c8"),r=n.n(a);for(var i in a)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return a[t]}))}(i);e["default"]=r.a},f445:function(t,e,n){var a=n("c86c");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.search-content[data-v-735dff82]{padding:%?24?% %?24?% %?32?%;background:#fff}.category-container[data-v-735dff82]{display:flex}.category-container .nav[data-v-735dff82]{flex:none;width:%?160?%}.category-container .nav .item[data-v-735dff82]{display:flex;align-items:center;height:%?96?%;padding:0 %?15?% 0 %?20?%;box-sizing:border-box;position:relative}.category-container .nav .item.active[data-v-735dff82]{color:#1a72de;background:#fff}.category-container .nav .item.active[data-v-735dff82]::before{top:0;bottom:0}.category-container .nav .item.active uni-image[data-v-735dff82]{display:block}.category-container .nav .item[data-v-735dff82]::before{content:"";width:%?4?%;background:#1a72de;border-radius:%?4?%;position:absolute;left:0;top:50%;bottom:50%;transition:all .3s}.category-container .nav .item uni-text[data-v-735dff82]{display:-webkit-box;text-overflow:ellipsis;overflow:hidden;-webkit-line-clamp:2;-webkit-box-orient:vertical}.category-container .nav .item uni-image[data-v-735dff82]{display:none;width:%?15?%;height:%?15?%;position:absolute;right:0}.category-container .nav .item uni-image[data-v-735dff82]:first-of-type{top:%?-15?%}.category-container .nav .item uni-image[data-v-735dff82]:last-of-type{bottom:%?-15?%;-webkit-transform:scaleY(-1);transform:scaleY(-1)}.category-container .category-list[data-v-735dff82]{background:#fff}.category-container .category-list .sub-title[data-v-735dff82]{line-height:%?96?%;font-size:%?32?%;color:#333;padding-left:%?22?%}.category-container .category-list .sub-categories[data-v-735dff82]{display:flex;flex-wrap:wrap}.category-container .category-list .sub-categories uni-navigator[data-v-735dff82]{display:flex;align-items:center;flex-direction:column;width:%?140?%;height:%?172?%;padding-top:%?12?%;box-sizing:border-box;margin:0 %?28?% %?36?%}.category-container .category-list .sub-categories uni-navigator uni-image[data-v-735dff82]{width:%?100?%;height:%?100?%;margin-bottom:%?4?%}.category-container .category-list .sub-categories uni-navigator uni-text[data-v-735dff82]{width:80%;line-height:%?36?%;text-align:center;font-size:%?26?%;color:#333;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}',""]),t.exports=e},f502:function(t,e,n){var a=n("8200");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=n("967d").default;r("53cb821e",a,!0,{sourceMap:!1,shadowMode:!1})},fd85:function(t,e,n){"use strict";var a=n("f502"),r=n.n(a);r.a}}]);