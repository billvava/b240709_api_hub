(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-index"],{"1b8a":function(t,a,i){"use strict";i("6a54");var n=i("f5bd").default;Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var e=n(i("6c38")),r={components:{city:e.default},data:function(){return{static:getApp().globalData.cdn,data:null,daojishi:"",current:0,day:"",h:"",m:"",s:"",background:"none"}},onLoad:function(){this.load_data()},onUnload:function(){0},onPageScroll:function(t){var a=t.scrollTop;this.background=a>15?"rgba(255,255,255,1)":"none"},methods:{kaifa:function(){this.util.show_msg("待开发")},open_city:function(){console.log(1111),this.$refs.city.open()},sel_city:function(){this.load_data()},search_btn:function(t){uni.navigateTo({url:"/pages/goods/index2/index?name="+t})},goods_down:function(t){if(this.data.end_second<=0)return!1},load_data:function(){var t=this;t.util.ajax("/mallapi/index/index",{},(function(a){t.data=a.data}))},handleSwiperClick:function(t){var a=this.data.banner[t].link.link;a&&uni.navigateTo({url:a})}}};a.default=r},"34a0":function(t,a,i){var n=i("a4ba");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var e=i("967d").default;e("1b7be735",n,!0,{sourceMap:!1,shadowMode:!1})},3721:function(t,a,i){"use strict";i.d(a,"b",(function(){return e})),i.d(a,"c",(function(){return r})),i.d(a,"a",(function(){return n}));var n={customHeader:i("f37f").default,search:i("0a0e").default,uSwiper:i("e839").default},e=function(){var t=this,a=t.$createElement,i=t._self._c||a;return i("v-uni-view",[i("v-uni-image",{staticClass:"bg",attrs:{src:t.static+"mall/bg.jpg",mode:"scaleToFill"}}),i("custom-header",{attrs:{fixed:!0,gap:!0,background:t.background}}),i("v-uni-view",{staticStyle:{height:"40rpx",width:"100%"}}),i("search",{attrs:{mall:!0,"btn-search":!0},on:{confirm:function(a){arguments[0]=a=t.$handleEvent(a),t.search_btn.apply(void 0,arguments)},search:function(a){arguments[0]=a=t.$handleEvent(a),t.search_btn.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"banner"},[i("u-swiper",{attrs:{circular:!0,height:"296rpx",list:t.data.banner,"key-name":"img",autoplay:!0},on:{change:function(a){arguments[0]=a=t.$handleEvent(a),function(a){return t.current=a.current}.apply(void 0,arguments)},click:function(a){arguments[0]=a=t.$handleEvent(a),t.handleSwiperClick.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"indicator",attrs:{slot:"indicator"},slot:"indicator"},t._l(t.data.banner,(function(a,n){return i("v-uni-view",{key:n,staticClass:"dot",class:{active:n===t.current}})})),1)],1)],1),i("v-uni-view",{staticClass:"nav"},[t._l(t.data.category_list2,(function(a){return i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},attrs:{url:"/pages/goods/index2/index?category_id="+a.category_id}},[i("v-uni-image",{attrs:{src:a.thumb}}),i("v-uni-text",[t._v(t._s(a.name))])],1)})),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},attrs:{url:"/pages/ac/help/item?id=about"}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-4.png"}}),i("v-uni-text",[t._v("公告区")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-5.png"}}),i("v-uni-text",[t._v("代理商")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-5.png"}}),i("v-uni-text",[t._v("线下兑换")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-3.png"}}),i("v-uni-text",[t._v("广告区")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-5.png"}}),i("v-uni-text",[t._v("土特产区")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-5.png"}}),i("v-uni-text",[t._v("加盟")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-3.png"}}),i("v-uni-text",[t._v("生鲜超市")])],1),i("v-uni-navigator",{staticStyle:{"margin-left":"20upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.kaifa()}}},[i("v-uni-image",{attrs:{src:t.static+"mall/icon-nav-3.png"}}),i("v-uni-text",[t._v("海外区")])],1)],2),i("v-uni-image",{staticClass:"recommend-title",attrs:{src:t.static+"mall/title-recommend.png",mode:"scaleToFill"}}),i("v-uni-view",{staticClass:"recommends"},t._l(t.data.goods_list,(function(a){return i("v-uni-navigator",{attrs:{url:"/pages/goods/item/item?goods_id="+a.goods_id}},[i("v-uni-image",{staticClass:"pic",attrs:{src:a.thumb,mode:"scaleToFill"}}),i("v-uni-view",{staticClass:"title"},[1==a.is_new?i("v-uni-image",{attrs:{src:t.static+"mall/icon-offer.png",mode:"scaleToFill"}}):t._e(),t._v(t._s(a.name))],1),i("v-uni-view",{staticClass:"desc"},[t._v(t._s(a.small_title))]),i("v-uni-view",{staticClass:"price"},[t._v("¥"),i("v-uni-text",[t._v(t._s(a.min_price))]),i("v-uni-view",{staticClass:"original"},[t._v("¥"+t._s(a.min_market_price))])],1)],1)})),1),i("foot")],1)},r=[]},"52ec":function(t,a,i){"use strict";i.r(a);var n=i("3721"),e=i("f2fa");for(var r in e)["default"].indexOf(r)<0&&function(t){i.d(a,t,(function(){return e[t]}))}(r);i("cdac");var o=i("828b"),c=Object(o["a"])(e["default"],n["b"],n["c"],!1,null,"5cb392a5",null,!1,n["a"],void 0);a["default"]=c.exports},a4ba:function(t,a,i){var n=i("c86c");a=n(!1),a.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.bg[data-v-5cb392a5]{width:%?750?%;height:%?392?%;position:absolute;left:50%;top:0;-webkit-transform:translateX(-50%);transform:translateX(-50%)}.select-address[data-v-5cb392a5]{display:flex;align-items:center;position:absolute;left:%?24?%;top:0;bottom:0}.select-address .u-icon[data-v-5cb392a5]{margin:%?10?% 0 0 %?16?%}.search[data-v-5cb392a5]{width:%?702?%;margin:%?24?% auto %?30?%}.banner[data-v-5cb392a5]{width:%?702?%;height:%?296?%;border-radius:%?16?%;overflow:hidden;position:relative;margin:0 auto %?24?%}.banner .indicator[data-v-5cb392a5]{display:flex}.banner .indicator .dot[data-v-5cb392a5]{width:%?16?%;height:%?16?%;border-radius:%?16?%;background:hsla(0,0%,100%,.4);margin:0 %?8?%;transition:all .2s ease-in-out}.banner .indicator .dot.active[data-v-5cb392a5]{background:#fff}.nav[data-v-5cb392a5]{width:%?702?%;background:#fff;border-radius:%?16?%;padding:%?20?% 0;margin:0 auto %?24?%;overflow-x:auto;text-align:center}.nav uni-navigator[data-v-5cb392a5]{float:left;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;width:%?150?%;height:%?172?%;line-height:%?36?%;font-size:%?26?%;color:#333}.nav uni-navigator uni-image[data-v-5cb392a5]{width:%?100?%;height:%?100?%;margin-bottom:%?8?%}.seckill[data-v-5cb392a5]{width:%?706?%;height:%?434?%;background:url(http://xf01.cos.xinhu.wang/suoye/static/mall/bg-seckill.png) no-repeat;background-size:100%;margin:0 auto %?34?%}.seckill .title[data-v-5cb392a5]{display:flex;align-items:center;margin:0 %?24?%;padding:%?32?% 0 %?30?%;position:relative}.seckill .title uni-image[data-v-5cb392a5]{width:%?142?%;height:%?34?%;margin-right:%?18?%}.seckill .title .count-down[data-v-5cb392a5]{display:flex;align-items:center;width:%?194?%;height:%?36?%;color:#f03721;border-radius:%?4?%;border:%?2?% solid #f03721;padding-left:%?90?%;box-sizing:border-box;position:relative}.seckill .title .count-down uni-text[data-v-5cb392a5]{display:flex;align-items:center;justify-content:center;width:%?80?%;color:#fff;background:#f03721;position:absolute;left:0;top:0;bottom:0}.seckill .title uni-navigator[data-v-5cb392a5]{display:flex;align-items:center;color:#999;position:absolute;right:0;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.seckill .seckill-list[data-v-5cb392a5]{display:flex;overflow-x:auto;overflow-y:hidden;margin:0 %?24?%}.seckill .seckill-list uni-navigator[data-v-5cb392a5]{flex:none;display:flex;flex-direction:column;width:%?210?%;height:%?296?%;margin-right:%?12?%}.seckill .seckill-list uni-navigator[data-v-5cb392a5]:last-child{margin-right:0}.seckill .seckill-list uni-navigator uni-image[data-v-5cb392a5]{width:%?206?%;height:%?206?%;border-radius:%?16?%;background:#f1f5f9;margin-bottom:%?16?%}.seckill .seckill-list uni-navigator .name[data-v-5cb392a5]{height:%?30?%;color:#333;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;margin-bottom:%?6?%}.seckill .seckill-list uni-navigator .price[data-v-5cb392a5]{display:flex;align-items:center;height:%?38?%;font-size:%?32?%;color:#f03721}.seckill .seckill-list uni-navigator .price uni-text[data-v-5cb392a5]{font-size:%?24?%}.recommend-title[data-v-5cb392a5]{display:block;width:%?248?%;height:%?50?%;margin:%?34?% auto %?24?%}.recommends[data-v-5cb392a5]{display:grid;grid-template-columns:repeat(auto-fill,%?340?%);justify-content:space-between;width:%?702?%;margin:0 auto}.recommends uni-navigator[data-v-5cb392a5]{width:%?340?%;height:%?554?%;background:#fff;border-radius:%?16?%;margin-bottom:%?24?%}.recommends uni-navigator .pic[data-v-5cb392a5]{display:block;width:%?340?%;height:%?340?%;border-radius:%?16?% %?16?% 0 0;margin-bottom:%?16?%}.recommends uni-navigator .title[data-v-5cb392a5]{line-height:%?34?%;font-size:%?28?%;color:#333;padding:0 %?20?% %?20?%;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;text-overflow:ellipsis;overflow:hidden}.recommends uni-navigator .title uni-image[data-v-5cb392a5]{width:%?60?%;height:%?28?%}.recommends uni-navigator .desc[data-v-5cb392a5]{height:%?28?%;font-size:%?24?%;color:#999;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;margin:0 %?20?% %?24?%}.recommends uni-navigator .price[data-v-5cb392a5]{display:flex;align-items:center;height:%?38?%;font-size:%?24?%;color:#f03721;padding-left:%?20?%}.recommends uni-navigator .price uni-text[data-v-5cb392a5]{font-size:%?32?%;font-weight:700;padding:0 %?15?% 0 %?5?%}.recommends uni-navigator .price .original[data-v-5cb392a5]{color:#999;text-decoration:line-through}.cart[data-v-5cb392a5]{display:flex;flex-direction:column;align-items:center;justify-content:center;width:%?96?%;height:%?96?%;background:#fff;box-shadow:0 %?4?% %?12?% 0 hsla(0,0%,80.4%,.5);-webkit-backdrop-filter:blur(%?8?%);backdrop-filter:blur(%?8?%);border-radius:50%;position:fixed;right:0;bottom:%?166?%}.cart uni-image[data-v-5cb392a5]{width:%?40?%;height:%?40?%}.cart uni-image uni-text[data-v-5cb392a5]{line-height:14px;-webkit-transform:scale(.9) translateY(%?-5?%);transform:scale(.9) translateY(%?-5?%)}.cart uni-text[data-v-5cb392a5]{-webkit-transform:scale(.8);transform:scale(.8)}.cart .badge[data-v-5cb392a5]{min-width:%?32?%;height:%?32?%;line-height:%?32?%;text-align:center;color:#fff;background:#fe5253;border-radius:%?16?%;padding:0 %?10?%;position:absolute;right:%?4?%;top:%?4?%;-webkit-transform:scale(.8);transform:scale(.8)}',""]),t.exports=a},cdac:function(t,a,i){"use strict";var n=i("34a0"),e=i.n(n);e.a},f2fa:function(t,a,i){"use strict";i.r(a);var n=i("1b8a"),e=i.n(n);for(var r in n)["default"].indexOf(r)<0&&function(t){i.d(a,t,(function(){return n[t]}))}(r);a["default"]=e.a}}]);