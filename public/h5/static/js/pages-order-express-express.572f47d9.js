(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-express-express"],{1520:function(t,a,e){"use strict";e.d(a,"b",(function(){return n})),e.d(a,"c",(function(){return i})),e.d(a,"a",(function(){}));var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",t._l(t.data.list,(function(a,n){return e("v-uni-view",{key:n,staticClass:"area"},[e("v-uni-view",{staticClass:"company"},[e("v-uni-view",{staticClass:"text"},[t._v(t._s(a.express_name)+" "+t._s(a.express_code))]),e("v-uni-view",{staticClass:"copy-btn",attrs:{"data-text":a.express_name+" "+a.express_name},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.copyText.apply(void 0,arguments)}}},[t._v("复制")])],1),e("v-uni-view",{staticClass:"progress"},[t._l(a.data,(function(a,n){return[e("v-uni-view",{key:n+"_0",class:"item "+(0==n?"ed":"")},[e("v-uni-view",{staticClass:"date"},[e("v-uni-view",[t._v(t._s(a.m1))]),e("v-uni-view",[t._v(t._s(a.m2))])],1),e("v-uni-view",{staticClass:"bar"},[e("v-uni-view",{staticClass:"point"}),e("v-uni-view",{staticClass:"line"})],1),e("v-uni-view",{staticClass:"event"},[t._v(t._s(a.content))])],1)]}))],2)],1)})),1)},i=[]},"699c":function(t,a,e){var n=e("c86c");a=n(!1),a.push([t.i,".area[data-v-db7ad694]{padding:0 %?30?%;background-color:#fff}.company[data-v-db7ad694]{display:flex;justify-content:space-between;align-items:center;padding:%?30?% 0;border-bottom:%?2?% solid #e8e8e8}.company .text[data-v-db7ad694]{font-size:%?28?%}.company .copy-btn[data-v-db7ad694]{padding:.2em 1em;background-color:#ddd;border-radius:1em;font-size:%?26?%;color:#7e7e7e}.progress[data-v-db7ad694]{padding:%?30?% 0}.item[data-v-db7ad694]{display:flex;color:#bbb}.item .date[data-v-db7ad694]{display:flex;flex-flow:column nowrap;align-items:center;width:%?64?%;font-size:%?22?%}.item .bar[data-v-db7ad694]{position:relative;display:flex;justify-content:center;width:%?60?%}.bar .point[data-v-db7ad694]{position:absolute;z-index:1;top:%?20?%;width:%?18?%;height:%?18?%;border-radius:%?30?%;background-color:#bbb}.bar .line[data-v-db7ad694]{width:%?2?%;background-color:#e8e8e8;-webkit-transform:translateY(%?28?%);transform:translateY(%?28?%)}.item:last-of-type .line[data-v-db7ad694]{display:none}.item .event[data-v-db7ad694]{flex:1;padding-top:%?10?%;padding-bottom:%?60?%;font-size:%?28?%}\r\n\r\n/* **已完成** */.item.ed[data-v-db7ad694]{color:#222}.item.ed .point[data-v-db7ad694]{background-color:#f9604e}\r\n\r\n/* ********* */\r\n\r\n/* **进行中** */.item.ing[data-v-db7ad694]{color:#222}.item.ing .point[data-v-db7ad694]{background-color:#f9604e;box-shadow:0 0 0 %?4?% rgba(249,95,78,.2);-webkit-transform:scale(1.4);transform:scale(1.4)}\r\n\r\n/* ********* */",""]),t.exports=a},"765c":function(t,a,e){"use strict";e.r(a);var n=e("fc74"),i=e.n(n);for(var d in n)["default"].indexOf(d)<0&&function(t){e.d(a,t,(function(){return n[t]}))}(d);a["default"]=i.a},9824:function(t,a,e){var n=e("699c");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("967d").default;i("0cad9003",n,!0,{sourceMap:!1,shadowMode:!1})},b0f8:function(t,a,e){"use strict";e.r(a);var n=e("1520"),i=e("765c");for(var d in i)["default"].indexOf(d)<0&&function(t){e.d(a,t,(function(){return i[t]}))}(d);e("e63e");var o=e("828b"),s=Object(o["a"])(i["default"],n["b"],n["c"],!1,null,"db7ad694",null,!1,n["a"],void 0);a["default"]=s.exports},e63e:function(t,a,e){"use strict";var n=e("9824"),i=e.n(n);i.a},fc74:function(t,a,e){"use strict";e("6a54"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;getApp();var n={data:function(){return{data:""}},components:{},props:{},onLoad:function(t){var a={ordernum:t.ordernum},e=this;e.util.ajax("/mallapi/order/send_item",a,(function(t){e.setData({data:t.data})}))},onShareAppMessage:function(){},methods:{copyText:function(t){uni.setClipboardData({data:t.currentTarget.dataset.text,success:function(t){uni.getClipboardData({success:function(t){uni.showToast({title:"复制单号成功"})}})}})}}};a.default=n}}]);