(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-address-item"],{"06bf":function(e,t,a){"use strict";a("6a54"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n={data:function(){return{com_cdn:getApp().globalData.com_cdn,id:"",is_addr:"",info:"",is_default:!0,address_text:"请选择地区",region_data:[],province_name:"",city_name:"",country_name:"",is_load:1,data:null,province:"",city:"",country:"",h5_popup:!1,country_type:"mp",lat:"",lng:"",reference:"",reference_address:""}},onLoad:function(e){2==e.is_addr&&uni.removeStorageSync("create_order_url"),e.id&&(this.id=e.id),this.edit_info()},methods:{open_address:function(){var e=this;wx.chooseLocation({success:function(t){console.log(t),e.setData({reference:t.name,reference_address:t.address,lat:t.latitude,lng:t.longitude})},fail:function(e){}})},edit_info:function(){var e=this,t={id:e.id};e.util.ajax("/mallapi/address/info",t,(function(t){e.data=t.data,e.id>0?(e.address_text=t.data.address_text,e.province=e.data.info.province,e.city=e.data.info.city,e.country=e.data.info.country,e.is_default=t.data.info.is_default,e.lat=t.data.info.lat,e.lng=t.data.info.lng,e.reference=t.data.info.reference,e.reference_address=t.data.info.reference_address):e.is_default=!0}))},region_change:function(e){console.log("a",e.detail.value);var t=e.detail.value,a=this;a.province_name=t[0].text,a.city_name=t[1].text,a.country_name=t[2].text,a.address_text=t[0].text+"-"+t[1].text+"-"+t[2].text,a.province=t[0].value,a.city=t[1].value,a.country=t[2].value},set_default:function(){this.is_default=!this.is_default},sub:function(e){var t=this,a=e.detail.value;t.country?(a.is_default=1==t.is_default?1:0,a.is_wxapp=1,a.flag="save",a.province=t.province,a.city=t.city,a.country=t.country,t.util.ajax("/mallapi/address/item",a,(function(e){var t=getCurrentPages();t[t.length-2];uni.navigateBack({delta:1})}))):t.util.show_msg("请选择区域")}}};t.default=n},"4ec5":function(e,t,a){"use strict";a.r(t);var n=a("e764"),i=a("af67");for(var r in i)["default"].indexOf(r)<0&&function(e){a.d(t,e,(function(){return i[e]}))}(r);a("803b");var d=a("828b"),o=Object(d["a"])(i["default"],n["b"],n["c"],!1,null,"0b0ee64c",null,!1,n["a"],void 0);t["default"]=o.exports},"5ccaa":function(e,t,a){var n=a("c86c");t=n(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-0b0ee64c]{color:#333;background-color:#f1f2f7}body.?%PAGE?%[data-v-0b0ee64c]{background-color:#f1f2f7}uni-view[data-v-0b0ee64c]{box-sizing:border-box}.container[data-v-0b0ee64c]{padding:0 3%;box-sizing:border-box}.flex-center[data-v-0b0ee64c]{display:flex;align-items:center}.ellipsis[data-v-0b0ee64c]{text-overflow:ellipsis;overflow:hidden;white-space:nowrap}.many-colum[data-v-0b0ee64c]{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;text-overflow:ellipsis;overflow:hidden}.line[data-v-0b0ee64c]{height:%?20?%;background:#f7f7f7}.success-icon[data-v-0b0ee64c]{width:%?37?%;height:%?37?%}.edit-item[data-v-0b0ee64c]{position:relative;padding:%?35?% %?30?%;background-color:#fff;font-size:%?28?%}.edit-item[data-v-0b0ee64c]::after{content:"";position:absolute;bottom:0;left:%?30?%;height:1px;width:100%;background-color:#d8d7d6;opacity:.6}.edit-item uni-text[data-v-0b0ee64c]{font-weight:700;color:#000;width:20%}.edit-item uni-input[data-v-0b0ee64c]{flex-grow:1}.default[data-v-0b0ee64c]{display:flex;align-items:center;font-size:%?24?%;color:#636161;margin-left:auto}.default .select-icon[data-v-0b0ee64c]{margin-right:%?11?%}.btn[data-v-0b0ee64c]{position:fixed;bottom:%?41?%;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%);width:%?695?%;height:%?80?%;line-height:%?80?%;text-align:center;color:#fff;font-size:%?28?%;background:#1a72de;background-blend-mode:normal,normal;border-radius:%?40?%}',""]),e.exports=t},"803b":function(e,t,a){"use strict";var n=a("edd6"),i=a.n(n);i.a},af67:function(e,t,a){"use strict";a.r(t);var n=a("06bf"),i=a.n(n);for(var r in n)["default"].indexOf(r)<0&&function(e){a.d(t,e,(function(){return n[e]}))}(r);t["default"]=i.a},e764:function(e,t,a){"use strict";a.d(t,"b",(function(){return i})),a.d(t,"c",(function(){return r})),a.d(t,"a",(function(){return n}));var n={uniDataPicker:a("0ec1").default},i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return e.data?a("v-uni-view",[a("v-uni-form",{on:{submit:function(t){arguments[0]=t=e.$handleEvent(t),e.sub.apply(void 0,arguments)}}},[a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.data.info.id,name:"id"}}),a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.province,name:"province"}}),a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.city,name:"city"}}),a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.country,name:"country"}}),a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.lat,name:"lat"}}),a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.lng,name:"lng"}}),a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:e.reference_address,name:"reference_address"}}),a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[e._v("收货人")]),a("v-uni-input",{attrs:{name:"name",value:e.data.info.name,placeholder:"输入姓名"}})],1),a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[e._v("手机号码")]),a("v-uni-input",{attrs:{name:"tel",type:"number",value:e.data.info.tel,placeholder:"请输入手机号"}})],1),a("uni-data-picker",{attrs:{localdata:e.data.city_json,value:e.country,"popup-title":"选择区域"},on:{change:function(t){arguments[0]=t=e.$handleEvent(t),e.region_change.apply(void 0,arguments)},nodeclick:function(t){arguments[0]=t=e.$handleEvent(t),e.onnodeclick.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[e._v("所属区域")]),a("v-uni-input",{attrs:{value:e.address_text,placeholder:"选择区域",disabled:"disabled"}})],1)],1),a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[e._v("详细地址")]),a("v-uni-input",{attrs:{name:"address",value:e.data.info.address,placeholder:"请输入"}})],1),a("v-uni-view",{staticClass:"line"}),a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[e._v("设为默认")]),a("v-uni-view",{staticClass:"default",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.set_default.apply(void 0,arguments)}}},[1==e.is_default?a("v-uni-image",{staticClass:"margin-left-20",staticStyle:{width:"36rpx",height:"36rpx"},attrs:{src:e.com_cdn+"dot2.png",mode:""}}):e._e(),0==e.is_default?a("v-uni-image",{staticClass:"margin-left-20",staticStyle:{width:"36rpx",height:"36rpx"},attrs:{src:e.com_cdn+"dot1.png",mode:""}}):e._e()],1)],1),a("v-uni-button",{staticClass:"btn",attrs:{"form-type":"submit"}},[e._v("保存")])],1)],1):e._e()},r=[]},edd6:function(e,t,a){var n=a("5ccaa");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var i=a("967d").default;i("1bb30c51",n,!0,{sourceMap:!1,shadowMode:!1})}}]);