(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-baoming-type4"],{1364:function(t,e,a){"use strict";a("6a54"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{com_cdn:getApp().globalData.com_cdn,address_text:"请选择地区",region_data:[],form_data:{name:"",tel:"",address_text:"请选择地区",agent_name:"请选择代理角色"},data:null,province:"",city:"",country:"",lat:"",lng:"",reference:"",reference_address:"",list:[{type:2,name:"市级代理"},{type:3,name:"县级代理"},{type:4,name:"店家"}]}},onLoad:function(t){2==t.is_addr&&uni.removeStorageSync("create_order_url"),t.id&&(this.id=t.id),this.edit_info()},methods:{bindPickerChange:function(t){var e=this,a=t.detail.value;e.type=e.list[a].type,e.form_data.agent_name=e.list[a].name},edit_info:function(){var t=this;t.util.ajax("/mallapi/address/getShopInfo",{},(function(e){t.data=e.data,e.data.info&&(t.form_data=e.data.info,t.type=t.form_data.type,t.country=t.form_data.country,t.city=t.form_data.city,t.province=t.form_data.province)}))},region_change:function(t){var e=t.detail.value,a=this;a.province_name=e[0].text,a.city_name=e[1].text,a.country_name=e[2].text,a.form_data.address_text=e[0].text+"-"+e[1].text+"-"+e[2].text,console.log("地区",a.address_text),a.province=e[0].value,a.city=e[1].value,a.country=e[2].value},sub:function(t){var e=this;e.form_data.name?e.form_data.tel?e.form_data?e.type?(e.form_data.country=e.country,e.form_data.province=e.province,e.form_data.city=e.city,e.form_data.type=e.type,console.log("要提交的数据",e.form_data),e.util.ajax("/mallapi/address/addShop",e.form_data,(function(t){var e=getCurrentPages();e[e.length-2];uni.navigateBack({delta:1})}))):e.util.show_msg("请选择代理类型"):e.util.show_msg("请选择区域"):e.util.show_msg("手机号码不能为空"):e.util.show_msg("姓名不能为空")},navigateBack:function(){var t=getCurrentPages();t[t.length-2];uni.navigateBack({delta:1})}}};e.default=n},"20a3":function(t,e,a){"use strict";var n=a("26d5"),i=a.n(n);i.a},"26d5":function(t,e,a){var n=a("f584");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("967d").default;i("5fc6ef21",n,!0,{sourceMap:!1,shadowMode:!1})},"7f1a":function(t,e,a){"use strict";a.r(e);var n=a("1364"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},b2d8:function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var n={uniDataPicker:a("003c").default},i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return t.data?a("v-uni-view",[a("v-uni-form",{on:{submit:function(e){arguments[0]=e=t.$handleEvent(e),t.sub.apply(void 0,arguments)}}},[a("v-uni-input",{staticStyle:{display:"none"},attrs:{type:"text",value:t.form_data.id,name:"id"}}),a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[t._v("姓名")]),a("v-uni-input",{attrs:{name:"name",placeholder:"输入姓名"},model:{value:t.form_data.name,callback:function(e){t.$set(t.form_data,"name",e)},expression:"form_data.name"}})],1),a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[t._v("手机号码")]),a("v-uni-input",{attrs:{name:"tel",type:"number",placeholder:"请输入手机号"},model:{value:t.form_data.tel,callback:function(e){t.$set(t.form_data,"tel",e)},expression:"form_data.tel"}})],1),a("uni-data-picker",{attrs:{localdata:t.data.city_json,"popup-title":"选择区域"},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.region_change.apply(void 0,arguments)}},model:{value:t.country,callback:function(e){t.country=e},expression:"country"}},[a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[t._v("所属区域")]),a("v-uni-input",{attrs:{value:t.form_data.address_text,placeholder:"选择区域",disabled:"disabled"}})],1)],1),a("v-uni-picker",{staticClass:"choose_btn",attrs:{mode:"selector",range:t.list,"range-key":"name"},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.bindPickerChange.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",{staticStyle:{color:"#000000","font-size":"30upx",width:"28%"}},[t._v("选择代理")]),a("v-uni-text",{staticClass:"cuIcon-triangledownfill text-gray",staticStyle:{"margin-left":"8upx",width:"100%",float:"left"}},[t._v(t._s(t.form_data.agent_name))])],1)],1),a("v-uni-view",{staticClass:"line"}),t.form_data.state>=0?a("v-uni-view",{staticClass:"edit-item flex-center"},[a("v-uni-text",[t._v("审核状态")]),a("v-uni-view",{class:2==t.form_data.state?"color2":"color1"},[t._v(t._s(t.form_data.state_text))])],1):t._e(),a("v-uni-view",{staticClass:"line"}),t.form_data.state?t._e():a("v-uni-button",{staticClass:"btn",attrs:{"form-type":"submit"}},[t._v("确定")]),2==t.form_data.state?a("v-uni-button",{staticClass:"btn",attrs:{"form-type":"submit"}},[t._v("重新提交")]):t._e(),1==t.form_data.state||0==t.form_data.state?a("v-uni-button",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.navigateBack()}}},[t._v("返回")]):t._e()],1)],1):t._e()},o=[]},eb78:function(t,e,a){"use strict";a.r(e);var n=a("b2d8"),i=a("7f1a");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("20a3");var d=a("828b"),r=Object(d["a"])(i["default"],n["b"],n["c"],!1,null,"c29d6072",null,!1,n["a"],void 0);e["default"]=r.exports},f584:function(t,e,a){var n=a("c86c");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-c29d6072]{color:#333;background-color:#f1f2f7}body.?%PAGE?%[data-v-c29d6072]{background-color:#f1f2f7}uni-view[data-v-c29d6072]{box-sizing:border-box}.tixian-btn2.act[data-v-c29d6072]{background:#1a72de;color:#fff}.tixian-btn2[data-v-c29d6072]{width:100%;height:40px;border-radius:25px;text-align:center;line-height:40px;font-size:16px;color:#333;margin-top:%?120?%;background-color:#fff;border:solid 1px #e5e5e5}.jifen-num2[data-v-c29d6072]{width:%?180?%;height:%?60?%;line-height:%?60?%;font-size:%?36?%;text-align:center;background-color:#1a72de;color:#fff;border-radius:%?10?%;margin-top:%?20?%}.jifen[data-v-c29d6072]{width:100%;height:%?326?%;position:relative}.jifen-bg[data-v-c29d6072]{width:100%;height:%?326?%}.jifen-box[data-v-c29d6072]{width:100%;height:%?280?%;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;color:#333;position:absolute;top:0;left:0;z-index:9}.red[data-v-c29d6072]{color:red}.green[data-v-c29d6072]{color:green}.jifen-p[data-v-c29d6072]{font-size:%?30?%}.jifen-num[data-v-c29d6072]{font-size:%?70?%}.title[data-v-c29d6072]{padding:%?30?%;display:flex;justify-content:space-between;align-items:center}.title-h[data-v-c29d6072]{font-size:%?36?%;font-weight:700;color:#333}.title-p[data-v-c29d6072]{font-size:%?28?%;color:#999}.jifen-list[data-v-c29d6072]{margin-bottom:%?20?%;padding-left:%?30?%;background:#fff}.jifen-item[data-v-c29d6072]{padding:%?30?% 0;padding-right:%?30?%;display:flex;justify-content:space-between;border-bottom:1px #e6e6e6 solid}.jifen-item[data-v-c29d6072]:last-child{border-bottom:none}.jifen-img[data-v-c29d6072]{width:%?86?%;height:%?86?%}.jifen-image[data-v-c29d6072]{width:%?86?%;height:%?86?%}.jifen-info[data-v-c29d6072]{width:%?575?%}.jifen-info-top[data-v-c29d6072]{margin-bottom:%?30?%;display:flex;justify-content:space-between;font-size:%?30?%;color:#333}.jifen-info-p[data-v-c29d6072]{font-size:%?24?%;color:#333}.jifen-time[data-v-c29d6072]{font-size:%?24?%;color:#999}.container[data-v-c29d6072]{padding:0 3%;box-sizing:border-box}.flex-center[data-v-c29d6072]{display:flex;align-items:center}.ellipsis[data-v-c29d6072]{text-overflow:ellipsis;overflow:hidden;white-space:nowrap}.color0[data-v-c29d6072]{color:#000}.color1[data-v-c29d6072]{color:#00f}.color2[data-v-c29d6072]{color:red}.many-colum[data-v-c29d6072]{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;text-overflow:ellipsis;overflow:hidden}.line[data-v-c29d6072]{height:%?20?%;background:#f7f7f7}.success-icon[data-v-c29d6072]{width:%?37?%;height:%?37?%}.edit-item[data-v-c29d6072]{position:relative;padding:%?35?% %?30?%;background-color:#fff;font-size:%?28?%}.edit-item[data-v-c29d6072]::after{content:"";position:absolute;bottom:0;left:%?30?%;height:1px;width:100%;background-color:#d8d7d6;opacity:.6}.edit-item uni-text[data-v-c29d6072]{font-weight:700;color:#000;width:20%}.edit-item uni-input[data-v-c29d6072]{flex-grow:1}.default[data-v-c29d6072]{display:flex;align-items:center;font-size:%?24?%;color:#636161;margin-left:auto}.default .select-icon[data-v-c29d6072]{margin-right:%?11?%}.btn[data-v-c29d6072]{position:fixed;bottom:%?41?%;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%);width:%?695?%;height:%?80?%;line-height:%?80?%;text-align:center;color:#fff;font-size:%?28?%;background:#1a72de;background-blend-mode:normal,normal;border-radius:%?40?%}',""]),t.exports=e}}]);