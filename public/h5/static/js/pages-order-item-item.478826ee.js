(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-item-item"],{1688:function(t,n,e){"use strict";e("6a54");var a=e("f5bd").default;Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0,e("bf0f");var i=a(e("2634")),o=a(e("2fdc")),s={title:"提示",btn:["cancel","confirm"],btnText:["取消","确认"],content:"",onCancel:function(){},onConfirm:function(){}},c={name:"common-tips",data:function(){return{visible:!1,title:"",content:"",btns:null,btnText:null,onCancel:null,onConfirm:null}},methods:{handleBtnClick:function(t){var n=this;return(0,o.default)((0,i.default)().mark((function e(){return(0,i.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:if("cancel"!==t||!n.onCancel){e.next=7;break}if(!(n.onCancel instanceof Promise)){e.next=6;break}return e.next=4,n.onCancel();case 4:e.next=7;break;case 6:n.onCancel();case 7:if("confirm"!==t||!n.onConfirm){e.next=14;break}if(!(n.onConfirm instanceof Promise)){e.next=13;break}return e.next=11,n.onConfirm();case 11:e.next=14;break;case 13:n.onConfirm();case 14:n.$emit(t),n.close();case 16:case"end":return e.stop()}}),e)})))()},show:function(t){var n=Object.assign({},s,t),e=n.title,a=n.content,i=n.btn,o=n.btnText,c=n.onCancel,r=n.onConfirm;this.title=e,this.content=a,this.btns=i,this.btnText=o,this.onCancel=c,this.onConfirm=r,this.btnText[1]||this.$set(this.btnText,1,s.btnText[1]),this.visible=!0},close:function(){this.visible=!1}}};n.default=c},"21d1":function(t,n,e){"use strict";e.r(n);var a=e("befb"),i=e("fdd1");for(var o in i)["default"].indexOf(o)<0&&function(t){e.d(n,t,(function(){return i[t]}))}(o);e("2cc9");var s=e("828b"),c=Object(s["a"])(i["default"],a["b"],a["c"],!1,null,"5caef648",null,!1,a["a"],void 0);n["default"]=c.exports},"2cc9":function(t,n,e){"use strict";var a=e("bed0"),i=e.n(a);i.a},3396:function(t,n,e){"use strict";e.d(n,"b",(function(){return i})),e.d(n,"c",(function(){return o})),e.d(n,"a",(function(){return a}));var a={uPopup:e("d30b").default},i=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("u-popup",{attrs:{show:t.visible,mode:"center",round:"12rpx","close-on-click-overlay":!0},on:{close:function(n){arguments[0]=n=t.$handleEvent(n),t.close.apply(void 0,arguments)}}},[e("v-uni-view",{staticClass:"common-tips"},[e("v-uni-view",{staticClass:"title"},[t._v(t._s(t.title))]),e("v-uni-view",{staticClass:"content"},[t._v(t._s(t.content))]),e("v-uni-view",{staticClass:"btn-content"},t._l(t.btns,(function(n,a){return e("v-uni-view",{staticClass:"btn",class:n,on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleBtnClick(n)}}},[t._v(t._s(t.btnText[a]))])})),1)],1)],1)},o=[]},4997:function(t,n,e){"use strict";e("6a54");var a=e("f5bd").default;Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0,e("5c47"),e("af8f");var i=a(e("33a7")),o=getApp(),s=null,c={data:function(){return{com_cdn:o.globalData.com_cdn,is_scroll:!1,buBottom:o.globalData.buBottom,statusBarHeight:o.globalData.statusBarHeight,isIphoneX:o.globalData.isIphoneX,topStyle:!0,data:null,static:getApp().globalData.cdn,ordernum:""}},components:{},props:{},onLoad:function(t){this.ordernum=t.ordernum,this.search()},onUnload:function(){null!==s&&(console.log("清除定时器"),clearInterval(s))},methods:{copyText:function(t){uni.setClipboardData({data:t,success:function(t){uni.getClipboardData({success:function(t){uni.showToast({title:"复制成功"})}})}})},countDown:function(){var t=this;null!==s&&(console.log("清除定时器"),clearInterval(s)),s=setInterval((function(){var n=t.data.info;if(n.auto_close_second>0){var e=0,a=0,i=0,o=n.auto_close_second-1;if(o>=0){if(e=Math.floor(o/3600)<10?"0"+Math.floor(o/3600):Math.floor(o/3600),a=Math.floor(o/60%60)<10?"0"+Math.floor(o/60%60):Math.floor(o/60%60),i=Math.floor(o%60)<10?"0"+Math.floor(o%60):Math.floor(o%60),e>=24){var s=Math.floor(e/24);e-=24*s,e<10&&(e="0"+e)}t.data.info.auto_close_second_str="，剩余 "+a+":"+i,t.data.info.auto_close_second=o}}else 0==n.auto_close_second&&t.util.ajax("/mallapi/auto/close_order",{},(function(n){t.search()}))}),1e3)},search:function(){var t=this,n={ordernum:t.ordernum};t.util.ajax("/mallapi/order/item",n,(function(n){t.setData({data:n.data}),t.countDown()}))},apply_install:function(t){i.default.apply_install(t)},call:function(t){uni.makePhoneCall({phoneNumber:t.currentTarget.dataset.tel})},comment:function(t){i.default.comment(t)},item:function(t){i.default.item(t)},feed:function(t){i.default.feed(t)},express:function(t){i.default.express(t)},pay:function(t){i.default.pay(t)},finish:function(t){var n=this;i.default.finish(t,(function(){n.search()}))},close:function(t){var n=this;i.default.close(t,(function(){n.search()}))},extend:function(t){var n=this;i.default.extend(ordernum,(function(){n.search()}))}}};n.default=c},"7a25":function(t,n,e){var a=e("fc29");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=e("967d").default;i("0e62487d",a,!0,{sourceMap:!1,shadowMode:!1})},a0bc:function(t,n,e){"use strict";e.r(n);var a=e("1688"),i=e.n(a);for(var o in a)["default"].indexOf(o)<0&&function(t){e.d(n,t,(function(){return a[t]}))}(o);n["default"]=i.a},a175:function(t,n,e){"use strict";var a=e("7a25"),i=e.n(a);i.a},bed0:function(t,n,e){var a=e("ed55");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=e("967d").default;i("a60f9568",a,!0,{sourceMap:!1,shadowMode:!1})},befb:function(t,n,e){"use strict";e.d(n,"b",(function(){return i})),e.d(n,"c",(function(){return o})),e.d(n,"a",(function(){return a}));var a={commonTips:e("f087").default},i=function(){var t=this,n=t.$createElement,e=t._self._c||n;return t.data?e("v-uni-view",[e("v-uni-view",{staticClass:"status"},[e("v-uni-view",{staticClass:"title"},[t._v(t._s(t.data.status_data.status)+" "+t._s(t.data.info.auto_close_second_str))]),e("v-uni-view",{staticClass:"desc"},[t._v(t._s(t.data.status_data.info))])],1),1==t.data.info.delivery_type?e("v-uni-view",{staticClass:"address",attrs:{url:"/pages/address/delivery"}},[e("v-uni-image",{attrs:{src:t.static+"checkout/icon-address.png",mode:"scaleToFill"}}),e("v-uni-view",{staticClass:"details"},[t._v(t._s(t.data.info.province)+" "+t._s(t.data.info.city)+" "+t._s(t.data.info.country)+t._s(t.data.info.address))]),e("v-uni-view",{staticClass:"user-info"},[e("v-uni-text",[t._v(t._s(t.data.info.linkman))]),e("v-uni-text",[t._v(t._s(t.data.info.tel))])],1)],1):t._e(),e("v-uni-view",{staticClass:"commodity-info"},[e("v-uni-view",{staticClass:"shop"},[e("v-uni-image",{attrs:{src:t.data.info.shop_thumb,mode:"scaleToFill"}}),e("v-uni-text",[t._v(t._s(t.data.info.shop_name))])],1),t._l(t.data.goods_list,(function(n,a){return e("v-uni-view",{staticClass:"commodity"},[e("v-uni-view",{staticClass:"details"},[e("v-uni-image",{staticClass:"pic",attrs:{src:n.thumb,mode:"aspectFit"}}),e("v-uni-view",{staticClass:"info"},[e("v-uni-view",{staticClass:"name"},[t._v(t._s(n.name))]),n.spec_str?e("v-uni-view",{staticClass:"specs"},[t._v(t._s(n.spec_str))]):t._e()],1),e("v-uni-view",{staticClass:"params"},[e("v-uni-view",{staticClass:"price"},[e("v-uni-text",[t._v("￥")]),t._v(t._s(n.unit_price))],1),e("v-uni-view",{staticClass:"count"},[t._v("x"+t._s(n.num))])],1)],1),1==t.data.app_btn.after_res?e("v-uni-view",{staticClass:"btn-content"},[e("v-uni-navigator",{staticClass:"btn-refund",attrs:{url:"/pages/order/after/index"}},[t._v("售后进度")])],1):t._e(),1==t.data.app_btn.after_apply?e("v-uni-view",{staticClass:"btn-content"},[e("v-uni-navigator",{staticClass:"btn-refund",attrs:{url:"/pages/order/after/apply?ordernum="+t.data.info.ordernum}},[t._v("申请售后")])],1):t._e()],1)})),e("v-uni-view",{staticClass:"subtotal"},[t.data.info.delivery_total>0?e("v-uni-view",{staticClass:"item"},[t._v("运费"),e("v-uni-view",{staticClass:"details"},[t._v("¥"+t._s(t.data.info.delivery_total?t.data.info.delivery_total:0))])],1):t._e(),e("v-uni-view",{staticClass:"item"},[t._v("商品优惠"),e("v-uni-view",{staticClass:"details"},[t._v("- ¥"+t._s(t.data.info.discount_total))])],1),e("v-uni-view",{staticClass:"item"},[t._v("商品总价"),e("v-uni-view",{staticClass:"details"},[t._v("¥"+t._s(t.data.info.goods_total))])],1),e("v-uni-view",{staticClass:"item"},[t._v("实付款"),e("v-uni-view",{staticClass:"details price"},[e("v-uni-text",[t._v("¥")]),t._v(t._s(t.data.info.total))],1)],1)],1)],2),1==t.data.info.pay_status&&4==t.data.info.pay_type?e("v-uni-view",{staticClass:"payments"},[t._v("支付方式"),e("v-uni-view",{staticClass:"details"},[e("v-uni-image",{attrs:{src:t.static+"checkout/icon-payment-wx.png",mode:"scaleToFill"}}),e("v-uni-text",[t._v("微信支付")])],1)],1):t._e(),e("v-uni-view",{staticClass:"order-info"},[e("v-uni-view",{staticClass:"title"},[t._v("订单信息")]),e("v-uni-view",{staticClass:"item"},[e("v-uni-view",{staticClass:"label"},[t._v("订单编号")]),e("v-uni-view",{staticClass:"details"},[t._v(t._s(t.data.info.ordernum)),e("v-uni-text",{on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.copyText(t.data.info.ordernum)}}},[t._v("｜复制")])],1)],1),e("v-uni-view",{staticClass:"item"},[e("v-uni-view",{staticClass:"label"},[t._v("下单时间")]),e("v-uni-view",{staticClass:"details"},[t._v(t._s(t.data.info.create_time))])],1),1==t.data.info.pay_status?e("v-uni-view",{staticClass:"item"},[e("v-uni-view",{staticClass:"label"},[t._v("交易时间")]),e("v-uni-view",{staticClass:"details"},[t._v(t._s(t.data.info.pay_time))])],1):t._e()],1),e("v-uni-view",{staticClass:"operate-container"},[e("v-uni-view",{staticClass:"gap"}),e("v-uni-view",{staticClass:"content"},[e("v-uni-view",{staticClass:"link"},[e("v-uni-image",{attrs:{src:t.static+"order/icon-customer-service.png",mode:"scaleToFill"}}),e("v-uni-text",[t._v("联系客服")]),e("v-uni-button",{staticClass:"kefu",attrs:{"open-type":"contact"}},[t._v("客服")])],1),e("v-uni-view",{staticClass:"btn-content"},[1==t.data.app_btn.close?e("v-uni-view",{staticClass:"btn cancel",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.close(t.data.info.ordernum)}}},[t._v("取消订单")]):t._e(),1==t.data.app_btn.pay?e("v-uni-view",{staticClass:"btn confirm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.pay(t.data.info.ordernum)}}},[t._v("去支付")]):t._e(),1==t.data.app_btn.delivery?e("v-uni-view",{staticClass:"btn confirm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.express(t.data.info.ordernum)}}},[t._v("查看物流")]):t._e(),1==t.data.app_btn.comment?e("v-uni-view",{staticClass:"btn confirm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.comment(t.data.info.ordernum)}}},[t._v("去评价")]):t._e(),1==t.data.app_btn.finish?e("v-uni-view",{staticClass:"btn confirm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.finish(t.data.info.ordernum)}}},[t._v("确认收货")]):t._e(),1==t.data.app_btn.apply_install?e("v-uni-view",{staticClass:"btn confirm",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.apply_install(t.data.info.ordernum)}}},[t._v("申请安装")]):t._e()],1)],1)],1),e("common-tips",{ref:"tips"})],1):t._e()},o=[]},ed55:function(t,n,e){var a=e("c86c");n=a(!1),n.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-5caef648]{background:#f6f6f6 linear-gradient(180deg,#2b7fff,#68a4ff %?300?%,rgba(237,240,242,0) %?400?%) repeat-x}body.?%PAGE?%[data-v-5caef648]{background:#f6f6f6 linear-gradient(180deg,#2b7fff,#68a4ff %?300?%,rgba(237,240,242,0) %?400?%) repeat-x}.kefu[data-v-5caef648]{position:absolute;width:%?100?%;height:%?150?%;left:0;top:0;opacity:0}.status[data-v-5caef648]{color:#fff;padding:%?20?% %?24?% %?38?%}.status .title[data-v-5caef648]{line-height:%?50?%;font-size:%?36?%;font-weight:700;margin-bottom:%?14?%}.status .desc[data-v-5caef648]{line-height:18px;font-size:13px}.address[data-v-5caef648]{background:#fff;border-radius:%?16?%;margin:0 %?24?% %?24?%;padding:%?28?% %?100?% %?36?% %?114?%;position:relative}.address uni-image[data-v-5caef648]{width:%?60?%;height:%?60?%;position:absolute;left:%?24?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.address[data-v-5caef648] .u-icon{position:absolute;right:%?30?%;top:%?54?%}.address .details[data-v-5caef648]{line-height:%?38?%;font-size:%?32?%;color:#333;margin-bottom:%?36?%}.address .user-info[data-v-5caef648]{line-height:%?34?%;font-size:%?28?%;color:#999}.address .user-info uni-text[data-v-5caef648]:first-child{margin-right:%?18?%}.commodity-info[data-v-5caef648]{background:#fff;border-radius:%?16?%;padding-bottom:%?34?%;margin:0 %?24?% %?24?%}.commodity-info .shop[data-v-5caef648]{display:flex;align-items:center;height:%?92?%;border-bottom:%?2?% solid #e6e6e6;padding-left:%?24?%;margin-bottom:%?32?%}.commodity-info .shop uni-image[data-v-5caef648]{width:%?60?%;height:%?60?%;margin-right:%?10?%}.commodity-info .commodity .details[data-v-5caef648]{display:flex;height:%?160?%;border-radius:%?16?%;box-sizing:border-box;padding:%?4?% %?24?% %?14?% %?180?%;margin-bottom:%?22?%;position:relative}.commodity-info .commodity .details uni-image[data-v-5caef648]{flex:none;width:%?160?%;height:%?160?%;border-radius:%?16?%;position:absolute;left:0;top:0}.commodity-info .commodity .details .info[data-v-5caef648]{flex:1;display:flex;flex-direction:column;justify-content:space-between}.commodity-info .commodity .details .info .name[data-v-5caef648]{line-height:%?36?%;font-size:%?28?%;font-weight:700;color:#333;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;text-overflow:ellipsis;overflow:hidden}.commodity-info .commodity .details .info .specs[data-v-5caef648]{line-height:%?34?%;color:#999}.commodity-info .commodity .details .params[data-v-5caef648]{display:flex;flex-direction:column;justify-content:space-between;align-items:flex-end;padding-left:%?30?%}.commodity-info .commodity .details .params .price[data-v-5caef648]{line-height:%?36?%;font-weight:700;font-size:%?28?%;color:#333}.commodity-info .commodity .details .params .price uni-text[data-v-5caef648]{font-size:%?24?%}.commodity-info .commodity .details .params .count[data-v-5caef648]{line-height:%?34?%;color:#999}.commodity-info .commodity .btn-content[data-v-5caef648]{display:flex;justify-content:flex-end;margin-bottom:%?58?%}.commodity-info .commodity .btn-content .btn-refund[data-v-5caef648]{display:flex;align-items:center;justify-content:center;width:%?180?%;height:%?64?%;font-size:%?28?%;color:#999;border:%?2?% solid #999;border-radius:%?32?%;margin-left:%?26?%}.commodity-info .subtotal[data-v-5caef648]{margin:0 %?24?%}.commodity-info .subtotal .item[data-v-5caef648]{display:flex;align-items:center;justify-content:space-between;height:%?58?%;font-size:%?28?%;color:#333;margin-bottom:%?24?%}.commodity-info .subtotal .item .details[data-v-5caef648]{font-weight:700}.commodity-info .subtotal .item .details.price[data-v-5caef648]{color:#f03721;font-size:%?42?%}.commodity-info .subtotal .item .details.price uni-text[data-v-5caef648]{font-size:%?22?%}.payments[data-v-5caef648]{display:flex;align-items:center;justify-content:space-between;font-size:%?28?%;color:#333;background:#fff;border-radius:%?16?%;padding:%?28?% %?24?%;margin:0 %?24?% %?24?%}.payments .details[data-v-5caef648]{display:flex;align-items:center;font-weight:700}.payments .details uni-image[data-v-5caef648]{width:%?50?%;height:%?50?%;margin-right:%?10?%}.order-info[data-v-5caef648]{background:#fff;border-radius:%?16?%;padding:%?36?% %?30?% %?24?%;margin:0 %?24?% %?24?%}.order-info .title[data-v-5caef648]{line-height:%?44?%;font-size:%?32?%;font-weight:700;color:#333;margin-bottom:%?36?%}.order-info .item[data-v-5caef648]{display:flex;align-items:center;justify-content:space-between;font-size:%?28?%;margin-bottom:%?30?%}.order-info .item .label[data-v-5caef648]{width:%?190?%;color:#333}.order-info .item .details[data-v-5caef648]{color:#999}.order-info .item .details uni-text[data-v-5caef648]{color:#333}.operate-container .gap[data-v-5caef648]{height:%?136?%;padding-bottom:0;padding-bottom:constant(safe-area-inset-bottom);padding-bottom:env(safe-area-inset-bottom)}.operate-container .content[data-v-5caef648]{display:flex;height:%?136?%;align-items:center;justify-content:space-between;background:#fff;box-shadow:0 %?4?% %?12?% 0 rgba(0,0,0,.2);padding:0 %?30?% 0 %?20?%;padding-bottom:0;padding-bottom:constant(safe-area-inset-bottom);padding-bottom:env(safe-area-inset-bottom);position:fixed;left:0;bottom:0;right:0;z-index:10}.operate-container .content .link[data-v-5caef648]{flex:none;display:flex;flex-direction:column;align-items:center;height:%?80?%;white-space:nowrap}.operate-container .content .link uni-image[data-v-5caef648]{width:%?48?%;height:%?48?%}.operate-container .content .link uni-text[data-v-5caef648]{line-height:%?30?%;color:#4e585f}.operate-container .content .btn-content[data-v-5caef648]{flex:1;display:flex;justify-content:space-between;margin-left:%?46?%}.operate-container .content .btn-content .btn[data-v-5caef648]{display:flex;align-items:center;justify-content:center;width:%?151?%;height:%?70?%;font-size:%?32?%;border-radius:%?20?%;padding:0 %?9?%}.operate-container .content .btn-content .btn.cancel[data-v-5caef648]{color:#999;border:%?2?% solid #999}.operate-container .content .btn-content .btn.confirm[data-v-5caef648]{color:#fff;background:linear-gradient(90deg,#2c7cf8,#00acff)}',""]),t.exports=n},f087:function(t,n,e){"use strict";e.r(n);var a=e("3396"),i=e("a0bc");for(var o in i)["default"].indexOf(o)<0&&function(t){e.d(n,t,(function(){return i[t]}))}(o);e("a175");var s=e("828b"),c=Object(s["a"])(i["default"],a["b"],a["c"],!1,null,"26923b66",null,!1,a["a"],void 0);n["default"]=c.exports},fc29:function(t,n,e){var a=e("c86c");n=a(!1),n.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.common-tips[data-v-26923b66]{display:flex;flex-direction:column;align-items:center;width:%?574?%;padding-top:%?40?%}.common-tips .title[data-v-26923b66]{line-height:%?48?%;font-size:%?36?%;color:rgba(0,0,0,.85)}.common-tips .content[data-v-26923b66]{line-height:%?46?%;font-size:%?30?%;text-align:center;color:rgba(0,0,0,.65);white-space:pre-wrap;padding:%?20?% %?50?% %?30?%}.common-tips .btn-content[data-v-26923b66]{display:flex;width:100%;border-top:%?1?% solid #d8d8d8}.common-tips .btn-content .btn[data-v-26923b66]{width:50%;height:%?96?%;line-height:%?96?%;text-align:center;font-size:%?32?%}.common-tips .btn-content .btn.cancel[data-v-26923b66]{color:rgba(0,0,0,.65)}.common-tips .btn-content .btn.confirm[data-v-26923b66]{color:#1a72de}.common-tips .btn-content .btn[data-v-26923b66]:first-child{border-right:%?1?% solid #d8d8d8}.common-tips .btn-content .btn[data-v-26923b66]:only-child{width:100%;border-right:0}',""]),t.exports=n},fdd1:function(t,n,e){"use strict";e.r(n);var a=e("4997"),i=e.n(a);for(var o in a)["default"].indexOf(o)<0&&function(t){e.d(n,t,(function(){return a[t]}))}(o);n["default"]=i.a}}]);