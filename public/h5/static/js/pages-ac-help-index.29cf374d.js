(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-ac-help-index"],{"0343":function(i,t,e){"use strict";e.r(t);var n=e("7a00"),o=e.n(n);for(var a in n)["default"].indexOf(a)<0&&function(i){e.d(t,i,(function(){return n[i]}))}(a);t["default"]=o.a},2768:function(i,t,e){var n=e("e6c2");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[i.i,n,""]]),n.locals&&(i.exports=n.locals);var o=e("967d").default;o("0a1fab18",n,!0,{sourceMap:!1,shadowMode:!1})},"2ae9":function(i,t,e){var n=e("40c9");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[i.i,n,""]]),n.locals&&(i.exports=n.locals);var o=e("967d").default;o("3d22ea18",n,!0,{sourceMap:!1,shadowMode:!1})},"35ee":function(i,t,e){"use strict";var n=e("2ae9"),o=e.n(n);o.a},"3df8":function(i,t,e){"use strict";e("6a54");var n=e("f5bd").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,e("aa9c"),e("4626"),e("5ac7"),e("5ef2");var o=n(e("d923")),a=n(e("926f")),c={name:"u-icon",data:function(){return{}},mixins:[uni.$u.mpMixin,uni.$u.mixin,a.default],computed:{uClasses:function(){var i=[];return i.push(this.customPrefix+"-"+this.name),this.color&&uni.$u.config.type.includes(this.color)&&i.push("u-icon__icon--"+this.color),i},iconStyle:function(){var i={};return i={fontSize:uni.$u.addUnit(this.size),lineHeight:uni.$u.addUnit(this.size),fontWeight:this.bold?"bold":"normal",top:uni.$u.addUnit(this.top)},this.color&&!uni.$u.config.type.includes(this.color)&&(i.color=this.color),i},isImg:function(){return-1!==this.name.indexOf("/")},imgStyle:function(){var i={};return i.width=this.width?uni.$u.addUnit(this.width):uni.$u.addUnit(this.size),i.height=this.height?uni.$u.addUnit(this.height):uni.$u.addUnit(this.size),i},icon:function(){return o.default["uicon-"+this.name]||this.name}},methods:{clickHandler:function(i){this.$emit("click",this.index),this.stop&&this.preventEvent(i)}}};t.default=c},"40c9":function(i,t,e){var n=e("c86c");t=n(!1),t.push([i.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.faq-item[data-v-4c15e004]{border-top:%?2?% solid #e6e6e6;margin-left:%?66?%}.faq-item.first[data-v-4c15e004]{border-top:none}.faq-item.show .title .arrow[data-v-4c15e004]{-webkit-transform:translateY(-50%) rotate(90deg);transform:translateY(-50%) rotate(90deg)}.faq-item.show .desc[data-v-4c15e004]{height:auto;margin-bottom:%?32?%}.faq-item .title[data-v-4c15e004]{line-height:%?42?%;font-size:%?30?%;color:#333;padding:%?32?% %?80?% %?32?% 0;position:relative}.faq-item .title .index[data-v-4c15e004]{line-height:%?50?%;font-size:%?36?%;font-weight:700;color:#69727e;position:absolute;left:%?-20?%;top:50%;-webkit-transform:translate(-100%,-50%);transform:translate(-100%,-50%)}.faq-item .title .index.top[data-v-4c15e004]{color:#ff5e0e}.faq-item .title .arrow[data-v-4c15e004]{position:absolute;right:%?30?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.faq-item .desc[data-v-4c15e004]{height:0;line-height:%?48?%;font-size:%?30?%;color:#999;margin-right:%?80?%;overflow:hidden}',""]),i.exports=t},"5ab9":function(i,t,e){"use strict";e.r(t);var n=e("b63a"),o=e("7ef0");for(var a in o)["default"].indexOf(a)<0&&function(i){e.d(t,i,(function(){return o[i]}))}(a);e("ae38");var c=e("828b"),r=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"6e9ebd02",null,!1,n["a"],void 0);t["default"]=r.exports},"5e2c":function(i,t,e){"use strict";e.d(t,"b",(function(){return o})),e.d(t,"c",(function(){return a})),e.d(t,"a",(function(){return n}));var n={uIcon:e("ed69").default},o=function(){var i=this,t=i.$createElement,e=i._self._c||t;return e("v-uni-view",{staticClass:"faq-item",class:{first:0===i.index,show:i.show}},[e("v-uni-view",{staticClass:"title",on:{click:function(t){arguments[0]=t=i.$handleEvent(t),i.show=!i.show}}},[e("v-uni-view",{staticClass:"index",class:{top:i.index<3}},[i._v(i._s(i.index+1))]),e("v-uni-view",{staticClass:"arrow"},[e("u-icon",{attrs:{name:"arrow-right",size:"32",color:"#999"}})],1),i._v(i._s(i.title))],1),e("v-uni-view",{staticClass:"desc"},[e("v-uni-rich-text",{attrs:{nodes:i.desc}},[e("span",{staticStyle:{opacity:"0"}},[i._v("0")])])],1)],1)},a=[]},"7a00":function(i,t,e){"use strict";e("6a54"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,e("64aa");var n={props:{index:{type:Number,required:!0},title:{type:String,default:""},desc:{type:String,default:""}},data:function(){return{show:!1,static:getApp().globalData.cdn}}};t.default=n},"7e7b":function(i,t,e){"use strict";e.r(t);var n=e("5e2c"),o=e("0343");for(var a in o)["default"].indexOf(a)<0&&function(i){e.d(t,i,(function(){return o[i]}))}(a);e("35ee");var c=e("828b"),r=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"4c15e004",null,!1,n["a"],void 0);t["default"]=r.exports},"7ef0":function(i,t,e){"use strict";e.r(t);var n=e("b8ab"),o=e.n(n);for(var a in n)["default"].indexOf(a)<0&&function(i){e.d(t,i,(function(){return n[i]}))}(a);t["default"]=o.a},"926f":function(i,t,e){"use strict";e("6a54"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,e("64aa");var n={props:{name:{type:String,default:uni.$u.props.icon.name},color:{type:String,default:uni.$u.props.icon.color},size:{type:[String,Number],default:uni.$u.props.icon.size},bold:{type:Boolean,default:uni.$u.props.icon.bold},index:{type:[String,Number],default:uni.$u.props.icon.index},hoverClass:{type:String,default:uni.$u.props.icon.hoverClass},customPrefix:{type:String,default:uni.$u.props.icon.customPrefix},label:{type:[String,Number],default:uni.$u.props.icon.label},labelPos:{type:String,default:uni.$u.props.icon.labelPos},labelSize:{type:[String,Number],default:uni.$u.props.icon.labelSize},labelColor:{type:String,default:uni.$u.props.icon.labelColor},space:{type:[String,Number],default:uni.$u.props.icon.space},imgMode:{type:String,default:uni.$u.props.icon.imgMode},width:{type:[String,Number],default:uni.$u.props.icon.width},height:{type:[String,Number],default:uni.$u.props.icon.height},top:{type:[String,Number],default:uni.$u.props.icon.top},stop:{type:Boolean,default:uni.$u.props.icon.stop}}};t.default=n},ae38:function(i,t,e){"use strict";var n=e("2768"),o=e.n(n);o.a},b63a:function(i,t,e){"use strict";e.d(t,"b",(function(){return n})),e.d(t,"c",(function(){return o})),e.d(t,"a",(function(){}));var n=function(){var i=this,t=i.$createElement,e=i._self._c||t;return e("v-uni-view",[e("v-uni-image",{staticClass:"bg",attrs:{src:i.static+"faq/bg-faq.png",mode:"widthFix"}}),e("v-uni-view",{staticClass:"faq-title"},[e("v-uni-text",[i._v("Hi～")]),e("v-uni-text",[i._v("有什么可以帮您！")]),e("v-uni-image",{attrs:{src:i.static+"faq/icon-faq.png",mode:"scaleToFill"}})],1),e("v-uni-view",{staticClass:"faq-content"},[e("v-uni-view",{staticClass:"title"},[i._v("猜你想问")]),e("v-uni-view",{staticClass:"tabs"},i._l(i.data.list,(function(t,n){return e("v-uni-view",{class:"item "+(i.cate_token==t.token?"active":""),on:{click:function(e){arguments[0]=e=i.$handleEvent(e),i.cate_token=t.token,i.search()}}},[i._v(i._s(t.name))])})),1),e("v-uni-view",{staticClass:"faq-list"},i._l(i.list,(function(t,n){return e("FaqItem",{key:i.n,attrs:{index:n,title:t.name,desc:t.html}})})),1)],1),e("v-uni-view",{staticClass:"operate-container"},[e("v-uni-view",{staticClass:"gap"}),e("v-uni-view",{staticClass:"content"},[e("v-uni-view",{staticClass:"btn cancel"},[e("v-uni-button",{staticClass:"kefu",attrs:{"open-type":"contact"}},[i._v("客服")]),e("v-uni-image",{attrs:{src:i.static+"faq/icon-btn-faq.png",mode:"scaleToFill"}}),i._v("联系客服")],1),e("v-uni-view",{staticClass:"btn submit",on:{click:function(t){arguments[0]=t=i.$handleEvent(t),i.tourl("/pages/ac/help/message")}}},[e("v-uni-image",{attrs:{src:i.static+"faq/icon-btn-feedback.png",mode:"scaleToFill"}}),i._v("反馈问题")],1)],1)],1)],1)},o=[]},b8ab:function(i,t,e){"use strict";e("6a54");var n=e("f5bd").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,e("5c47"),e("af8f"),e("c223");var o=n(e("7e7b")),a={components:{FaqItem:o.default},data:function(){return{com_cdn:getApp().globalData.com_cdn,is_scroll:!1,static:getApp().globalData.cdn,list:[],is_load:1,page:1,load_other:1,data:null,cate_token:"",dataSource:[{title:"如何在平台上下单开锁服务",desc:"如何在平台"},{title:"官方客服电话是多少",desc:"如何在平台上"},{title:"怎么找到适合自己的安装师傅",desc:"在首页-锁"},{title:"如何申请退款",desc:"如何在平台上下单"},{title:"关于平台锁匠资质问题",desc:"如何在平"}]}},onLoad:function(i){i.cate_token&&(this.cate_token=i.cate_token),this.load_cate()},methods:{change_show:function(i){this.list[i].show=!this.list[i].show},onPageScroll:function(i){i.scrollTop>0?this.is_scroll=!0:this.is_scroll=!1},load_cate:function(){var i=this;i.util.ajax("/comapi/Help/getCateList",{},(function(t){i.data=t.data,i.cate_token=t.data.list[0].token,i.search()}))},search:function(){var i=this;i.page=1,i.list=[],i.is_load=1,i.load_data()},load_data:function(){var i=this;0!=i.is_load&&i.util.ajax("/comapi/Help/load_list",{page:i.page,cate_token:i.cate_token},(function(t){1==i.load_other&&(i.load_other=0),t.data.count<=0?i.is_load=0:(i.list=i.list.concat(t.data.list),i.page=i.page+1)}))}}};t.default=a},bf68:function(i,t,e){"use strict";e.r(t);var n=e("3df8"),o=e.n(n);for(var a in n)["default"].indexOf(a)<0&&function(i){e.d(t,i,(function(){return n[i]}))}(a);t["default"]=o.a},d923:function(i,t,e){"use strict";e("6a54"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;t.default={"uicon-level":"","uicon-column-line":"","uicon-checkbox-mark":"","uicon-folder":"","uicon-movie":"","uicon-star-fill":"","uicon-star":"","uicon-phone-fill":"","uicon-phone":"","uicon-apple-fill":"","uicon-chrome-circle-fill":"","uicon-backspace":"","uicon-attach":"","uicon-cut":"","uicon-empty-car":"","uicon-empty-coupon":"","uicon-empty-address":"","uicon-empty-favor":"","uicon-empty-permission":"","uicon-empty-news":"","uicon-empty-search":"","uicon-github-circle-fill":"","uicon-rmb":"","uicon-person-delete-fill":"","uicon-reload":"","uicon-order":"","uicon-server-man":"","uicon-search":"","uicon-fingerprint":"","uicon-more-dot-fill":"","uicon-scan":"","uicon-share-square":"","uicon-map":"","uicon-map-fill":"","uicon-tags":"","uicon-tags-fill":"","uicon-bookmark-fill":"","uicon-bookmark":"","uicon-eye":"","uicon-eye-fill":"","uicon-mic":"","uicon-mic-off":"","uicon-calendar":"","uicon-calendar-fill":"","uicon-trash":"","uicon-trash-fill":"","uicon-play-left":"","uicon-play-right":"","uicon-minus":"","uicon-plus":"","uicon-info":"","uicon-info-circle":"","uicon-info-circle-fill":"","uicon-question":"","uicon-error":"","uicon-close":"","uicon-checkmark":"","uicon-android-circle-fill":"","uicon-android-fill":"","uicon-ie":"","uicon-IE-circle-fill":"","uicon-google":"","uicon-google-circle-fill":"","uicon-setting-fill":"","uicon-setting":"","uicon-minus-square-fill":"","uicon-plus-square-fill":"","uicon-heart":"","uicon-heart-fill":"","uicon-camera":"","uicon-camera-fill":"","uicon-more-circle":"","uicon-more-circle-fill":"","uicon-chat":"","uicon-chat-fill":"","uicon-bag-fill":"","uicon-bag":"","uicon-error-circle-fill":"","uicon-error-circle":"","uicon-close-circle":"","uicon-close-circle-fill":"","uicon-checkmark-circle":"","uicon-checkmark-circle-fill":"","uicon-question-circle-fill":"","uicon-question-circle":"","uicon-share":"","uicon-share-fill":"","uicon-shopping-cart":"","uicon-shopping-cart-fill":"","uicon-bell":"","uicon-bell-fill":"","uicon-list":"","uicon-list-dot":"","uicon-zhihu":"","uicon-zhihu-circle-fill":"","uicon-zhifubao":"","uicon-zhifubao-circle-fill":"","uicon-weixin-circle-fill":"","uicon-weixin-fill":"","uicon-twitter-circle-fill":"","uicon-twitter":"","uicon-taobao-circle-fill":"","uicon-taobao":"","uicon-weibo-circle-fill":"","uicon-weibo":"","uicon-qq-fill":"","uicon-qq-circle-fill":"","uicon-moments-circel-fill":"","uicon-moments":"","uicon-qzone":"","uicon-qzone-circle-fill":"","uicon-baidu-circle-fill":"","uicon-baidu":"","uicon-facebook-circle-fill":"","uicon-facebook":"","uicon-car":"","uicon-car-fill":"","uicon-warning-fill":"","uicon-warning":"","uicon-clock-fill":"","uicon-clock":"","uicon-edit-pen":"","uicon-edit-pen-fill":"","uicon-email":"","uicon-email-fill":"","uicon-minus-circle":"","uicon-minus-circle-fill":"","uicon-plus-circle":"","uicon-plus-circle-fill":"","uicon-file-text":"","uicon-file-text-fill":"","uicon-pushpin":"","uicon-pushpin-fill":"","uicon-grid":"","uicon-grid-fill":"","uicon-play-circle":"","uicon-play-circle-fill":"","uicon-pause-circle-fill":"","uicon-pause":"","uicon-pause-circle":"","uicon-eye-off":"","uicon-eye-off-outline":"","uicon-gift-fill":"","uicon-gift":"","uicon-rmb-circle-fill":"","uicon-rmb-circle":"","uicon-kefu-ermai":"","uicon-server-fill":"","uicon-coupon-fill":"","uicon-coupon":"","uicon-integral":"","uicon-integral-fill":"","uicon-home-fill":"","uicon-home":"","uicon-hourglass-half-fill":"","uicon-hourglass":"","uicon-account":"","uicon-plus-people-fill":"","uicon-minus-people-fill":"","uicon-account-fill":"","uicon-thumb-down-fill":"","uicon-thumb-down":"","uicon-thumb-up":"","uicon-thumb-up-fill":"","uicon-lock-fill":"","uicon-lock-open":"","uicon-lock-opened-fill":"","uicon-lock":"","uicon-red-packet-fill":"","uicon-photo-fill":"","uicon-photo":"","uicon-volume-off-fill":"","uicon-volume-off":"","uicon-volume-fill":"","uicon-volume":"","uicon-red-packet":"","uicon-download":"","uicon-arrow-up-fill":"","uicon-arrow-down-fill":"","uicon-play-left-fill":"","uicon-play-right-fill":"","uicon-rewind-left-fill":"","uicon-rewind-right-fill":"","uicon-arrow-downward":"","uicon-arrow-leftward":"","uicon-arrow-rightward":"","uicon-arrow-upward":"","uicon-arrow-down":"","uicon-arrow-right":"","uicon-arrow-left":"","uicon-arrow-up":"","uicon-skip-back-left":"","uicon-skip-forward-right":"","uicon-rewind-right":"","uicon-rewind-left":"","uicon-arrow-right-double":"","uicon-arrow-left-double":"","uicon-wifi-off":"","uicon-wifi":"","uicon-empty-data":"","uicon-empty-history":"","uicon-empty-list":"","uicon-empty-page":"","uicon-empty-order":"","uicon-man":"","uicon-woman":"","uicon-man-add":"","uicon-man-add-fill":"","uicon-man-delete":"","uicon-man-delete-fill":"","uicon-zh":"","uicon-en":""}},e65f:function(i,t,e){var n=e("c86c");t=n(!1),t.push([i.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-view[data-v-59765974], uni-scroll-view[data-v-59765974], uni-swiper-item[data-v-59765974]{display:flex;flex-direction:column;flex-shrink:0;flex-grow:0;flex-basis:auto;align-items:stretch;align-content:flex-start}@font-face{font-family:uicon-iconfont;src:url(https://at.alicdn.com/t/font_2225171_8kdcwk4po24.ttf) format("truetype")}.u-icon[data-v-59765974]{display:flex;align-items:center}.u-icon--left[data-v-59765974]{flex-direction:row-reverse;align-items:center}.u-icon--right[data-v-59765974]{flex-direction:row;align-items:center}.u-icon--top[data-v-59765974]{flex-direction:column-reverse;justify-content:center}.u-icon--bottom[data-v-59765974]{flex-direction:column;justify-content:center}.u-icon__icon[data-v-59765974]{font-family:uicon-iconfont;position:relative;display:flex;flex-direction:row;align-items:center}.u-icon__icon--primary[data-v-59765974]{color:#3c9cff}.u-icon__icon--success[data-v-59765974]{color:#5ac725}.u-icon__icon--error[data-v-59765974]{color:#f56c6c}.u-icon__icon--warning[data-v-59765974]{color:#f9ae3d}.u-icon__icon--info[data-v-59765974]{color:#909399}.u-icon__img[data-v-59765974]{height:auto;will-change:transform}.u-icon__label[data-v-59765974]{line-height:1}',""]),i.exports=t},e6c2:function(i,t,e){var n=e("c86c");t=n(!1),t.push([i.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.faq-item[data-v-6e9ebd02]{border-top:%?2?% solid #e6e6e6;margin-left:%?66?%}.faq-item.first[data-v-6e9ebd02]{border-top:none}.faq-item.show .title .arrow[data-v-6e9ebd02]{-webkit-transform:translateY(-50%) rotate(90deg);transform:translateY(-50%) rotate(90deg)}.faq-item.show .desc[data-v-6e9ebd02]{height:auto;margin-bottom:%?32?%}.faq-item .title[data-v-6e9ebd02]{line-height:%?42?%;font-size:%?30?%;color:#333;padding:%?32?% %?80?% %?32?% 0;position:relative}.faq-item .title .index[data-v-6e9ebd02]{line-height:%?50?%;font-size:%?36?%;font-weight:700;color:#69727e;position:absolute;left:%?-20?%;top:50%;-webkit-transform:translate(-100%,-50%);transform:translate(-100%,-50%)}.faq-item .title .index.top[data-v-6e9ebd02]{color:#ff5e0e}.faq-item .title .arrow[data-v-6e9ebd02]{position:absolute;right:%?30?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.faq-item .desc[data-v-6e9ebd02]{height:0;line-height:%?48?%;font-size:%?30?%;color:#999;margin-right:%?80?%;overflow:hidden}.kefu[data-v-6e9ebd02]{position:absolute;width:%?360?%;height:%?100?%;opacity:0}.bg[data-v-6e9ebd02]{width:%?750?%;position:absolute;left:50%;top:0;-webkit-transform:translateX(-50%);transform:translateX(-50%)}.faq-title[data-v-6e9ebd02]{display:flex;flex-direction:column;line-height:%?66?%;font-size:%?48?%;font-weight:700;color:#333;padding:%?60?%;position:relative}.faq-title uni-image[data-v-6e9ebd02]{width:%?244?%;height:%?188?%;position:absolute;right:%?50?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.faq-content[data-v-6e9ebd02]{background:#fff;border-radius:%?16?%;margin:0 %?24?% %?30?%;padding-bottom:%?16?%;position:relative}.faq-content .title[data-v-6e9ebd02]{line-height:%?50?%;font-size:%?36?%;font-weight:700;color:#333;padding:%?32?% 0 %?38?% %?30?%}.faq-content .tabs[data-v-6e9ebd02]{display:flex;flex-wrap:wrap;padding:0 %?20?% %?20?%}.faq-content .tabs .item[data-v-6e9ebd02]{display:flex;align-items:center;height:%?56?%;color:#999;background:#fff;border-radius:%?28?%;border:%?2?% solid #999;padding:0 %?20?%;margin:0 %?10?% %?10?%}.faq-content .tabs .item.active[data-v-6e9ebd02]{color:#fff;background:linear-gradient(90deg,#2c7cf8,#00acff);border:none}.operate-container .gap[data-v-6e9ebd02]{height:%?136?%;padding-bottom:0;padding-bottom:constant(safe-area-inset-bottom);padding-bottom:env(safe-area-inset-bottom)}.operate-container .content[data-v-6e9ebd02]{display:flex;height:%?136?%;align-items:center;justify-content:space-between;background:#fff;box-shadow:0 %?4?% %?12?% 0 rgba(0,0,0,.2);padding:0 %?30?% 0 %?20?%;padding-bottom:0;padding-bottom:constant(safe-area-inset-bottom);padding-bottom:env(safe-area-inset-bottom);position:fixed;left:0;bottom:0;right:0;z-index:10}.operate-container .content .btn[data-v-6e9ebd02]{flex:1;display:flex;align-items:center;justify-content:center;height:%?96?%;font-size:%?30?%;font-weight:700;position:relative}.operate-container .content .btn[data-v-6e9ebd02]:first-child::before{display:none}.operate-container .content .btn[data-v-6e9ebd02]::before{content:"";width:%?2?%;height:%?40?%;background:#d8d8d8;opacity:.5;position:absolute;left:0;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.operate-container .content .btn uni-image[data-v-6e9ebd02]{width:%?36?%;height:%?36?%;margin-right:%?4?%}',""]),i.exports=t},ed69:function(i,t,e){"use strict";e.r(t);var n=e("f820"),o=e("bf68");for(var a in o)["default"].indexOf(a)<0&&function(i){e.d(t,i,(function(){return o[i]}))}(a);e("ff14");var c=e("828b"),r=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"59765974",null,!1,n["a"],void 0);t["default"]=r.exports},f2c4:function(i,t,e){var n=e("e65f");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[i.i,n,""]]),n.locals&&(i.exports=n.locals);var o=e("967d").default;o("0b8a49ee",n,!0,{sourceMap:!1,shadowMode:!1})},f820:function(i,t,e){"use strict";e.d(t,"b",(function(){return n})),e.d(t,"c",(function(){return o})),e.d(t,"a",(function(){}));var n=function(){var i=this,t=i.$createElement,e=i._self._c||t;return e("v-uni-view",{staticClass:"u-icon",class:["u-icon--"+i.labelPos],on:{click:function(t){arguments[0]=t=i.$handleEvent(t),i.clickHandler.apply(void 0,arguments)}}},[i.isImg?e("v-uni-image",{staticClass:"u-icon__img",style:[i.imgStyle,i.$u.addStyle(i.customStyle)],attrs:{src:i.name,mode:i.imgMode}}):e("v-uni-text",{staticClass:"u-icon__icon",class:i.uClasses,style:[i.iconStyle,i.$u.addStyle(i.customStyle)],attrs:{"hover-class":i.hoverClass}},[i._v(i._s(i.icon))]),""!==i.label?e("v-uni-text",{staticClass:"u-icon__label",style:{color:i.labelColor,fontSize:i.$u.addUnit(i.labelSize),marginLeft:"right"==i.labelPos?i.$u.addUnit(i.space):0,marginTop:"bottom"==i.labelPos?i.$u.addUnit(i.space):0,marginRight:"left"==i.labelPos?i.$u.addUnit(i.space):0,marginBottom:"top"==i.labelPos?i.$u.addUnit(i.space):0}},[i._v(i._s(i.label))]):i._e()],1)},o=[]},ff14:function(i,t,e){"use strict";var n=e("f2c4"),o=e.n(n);o.a}}]);