(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-invite-invite"],{"1a37":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){}));var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.data?n("v-uni-view",{staticClass:"bg"},[n("v-uni-view",{staticClass:"box"},[n("v-uni-view",{staticStyle:{"margin-bottom":"30upx"}},[t._v("邀请码")]),n("v-uni-view",[t._v(t._s(t.data.invitation_code))]),n("v-uni-view",{staticStyle:{"text-align":"center","margin-bottom":"20upx"}},[n("v-uni-image",{attrs:{src:t.data.img_url,mode:"widthFix"}}),n("v-uni-input",{staticStyle:{overflow:"hidden"},attrs:{border:"true",type:"text",disabled:"true"},model:{value:t.data.link,callback:function(e){t.$set(t.data,"link",e)},expression:"data.link"}})],1),n("v-uni-button",{staticClass:"copy-btn",staticStyle:{"background-color":"#2b38bb",color:"#fff",width:"70%","margin-bottom":"20upx","margin-top":"30upx"},attrs:{"data-clipboard-text":t.data.link},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.alert()}}},[t._v("复制")])],1)],1):t._e()},o=[]},"2e78":function(t,e,n){(function(t){var r,o,i,a=n("bdbb").default;n("4626"),n("01a2"),n("e39c"),n("bf0f"),n("844d"),n("18f7"),n("de6c"),n("7a76"),n("c9b5"),n("6a54"),n("8a8d"),n("6a88"),n("7996"),n("926e"),n("2797"),n("fd3c"),n("ab80"),n("aa9c"),n("f7a5"),
/*!
 * clipboard.js v2.0.11
 * https://clipboardjs.com/
 *
 * Licensed MIT © Zeno Rocha
 */
function(n,c){"object"===a(e)&&"object"===a(t)?t.exports=c():(o=[],r=c,i="function"===typeof r?r.apply(e,o):r,void 0===i||(t.exports=i))}(0,(function(){return function(){var t={686:function(t,e,n){"use strict";n.d(e,{default:function(){return E}});var r=n(279),o=n.n(r),i=n(370),a=n.n(i),c=n(817),u=n.n(c);function f(t){try{return document.execCommand(t)}catch(e){return!1}}var l=function(t){var e=u()(t);return f("cut"),e};var s=function(t,e){var n=function(t){var e="rtl"===document.documentElement.getAttribute("dir"),n=document.createElement("textarea");n.style.fontSize="12pt",n.style.border="0",n.style.padding="0",n.style.margin="0",n.style.position="absolute",n.style[e?"right":"left"]="-9999px";var r=window.pageYOffset||document.documentElement.scrollTop;return n.style.top="".concat(r,"px"),n.setAttribute("readonly",""),n.value=t,n}(t);e.container.appendChild(n);var r=u()(n);return f("copy"),n.remove(),r},d=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{container:document.body},n="";return"string"===typeof t?n=s(t,e):t instanceof HTMLInputElement&&!["text","search","url","tel","password"].includes(null===t||void 0===t?void 0:t.type)?n=s(t.value,e):(n=u()(t),f("copy")),n};function p(t){return p="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},p(t)}var v=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},e=t.action,n=void 0===e?"copy":e,r=t.container,o=t.target,i=t.text;if("copy"!==n&&"cut"!==n)throw new Error('Invalid "action" value, use either "copy" or "cut"');if(void 0!==o){if(!o||"object"!==p(o)||1!==o.nodeType)throw new Error('Invalid "target" value, use a valid Element');if("copy"===n&&o.hasAttribute("disabled"))throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');if("cut"===n&&(o.hasAttribute("readonly")||o.hasAttribute("disabled")))throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes')}return i?d(i,{container:r}):o?"cut"===n?l(o):d(o,{container:r}):void 0};function y(t){return y="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},y(t)}function b(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function h(t,e){return h=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},h(t,e)}function m(t){var e=function(){if("undefined"===typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"===typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,r=x(t);if(e){var o=x(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return g(this,n)}}function g(t,e){return!e||"object"!==y(e)&&"function"!==typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function x(t){return x=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},x(t)}function w(t,e){var n="data-clipboard-".concat(t);if(e.hasAttribute(n))return e.getAttribute(n)}var S=function(t){(function(t,e){if("function"!==typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&h(t,e)})(n,t);var e=m(n);function n(t,r){var o;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,n),o=e.call(this),o.resolveOptions(r),o.listenClick(t),o}return function(t,e,n){e&&b(t.prototype,e),n&&b(t,n)}(n,[{key:"resolveOptions",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.action="function"===typeof t.action?t.action:this.defaultAction,this.target="function"===typeof t.target?t.target:this.defaultTarget,this.text="function"===typeof t.text?t.text:this.defaultText,this.container="object"===y(t.container)?t.container:document.body}},{key:"listenClick",value:function(t){var e=this;this.listener=a()(t,"click",(function(t){return e.onClick(t)}))}},{key:"onClick",value:function(t){var e=t.delegateTarget||t.currentTarget,n=this.action(e)||"copy",r=v({action:n,container:this.container,target:this.target(e),text:this.text(e)});this.emit(r?"success":"error",{action:n,text:r,trigger:e,clearSelection:function(){e&&e.focus(),window.getSelection().removeAllRanges()}})}},{key:"defaultAction",value:function(t){return w("action",t)}},{key:"defaultTarget",value:function(t){var e=w("target",t);if(e)return document.querySelector(e)}},{key:"defaultText",value:function(t){return w("text",t)}},{key:"destroy",value:function(){this.listener.destroy()}}],[{key:"copy",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{container:document.body};return d(t,e)}},{key:"cut",value:function(t){return l(t)}},{key:"isSupported",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:["copy","cut"],e="string"===typeof t?[t]:t,n=!!document.queryCommandSupported;return e.forEach((function(t){n=n&&!!document.queryCommandSupported(t)})),n}}]),n}(o()),E=S},828:function(t){if("undefined"!==typeof Element&&!Element.prototype.matches){var e=Element.prototype;e.matches=e.matchesSelector||e.mozMatchesSelector||e.msMatchesSelector||e.oMatchesSelector||e.webkitMatchesSelector}t.exports=function(t,e){while(t&&9!==t.nodeType){if("function"===typeof t.matches&&t.matches(e))return t;t=t.parentNode}}},438:function(t,e,n){var r=n(828);function o(t,e,n,r,o){var a=i.apply(this,arguments);return t.addEventListener(n,a,o),{destroy:function(){t.removeEventListener(n,a,o)}}}function i(t,e,n,o){return function(n){n.delegateTarget=r(n.target,e),n.delegateTarget&&o.call(t,n)}}t.exports=function(t,e,n,r,i){return"function"===typeof t.addEventListener?o.apply(null,arguments):"function"===typeof n?o.bind(null,document).apply(null,arguments):("string"===typeof t&&(t=document.querySelectorAll(t)),Array.prototype.map.call(t,(function(t){return o(t,e,n,r,i)})))}},879:function(t,e){e.node=function(t){return void 0!==t&&t instanceof HTMLElement&&1===t.nodeType},e.nodeList=function(t){var n=Object.prototype.toString.call(t);return void 0!==t&&("[object NodeList]"===n||"[object HTMLCollection]"===n)&&"length"in t&&(0===t.length||e.node(t[0]))},e.string=function(t){return"string"===typeof t||t instanceof String},e.fn=function(t){var e=Object.prototype.toString.call(t);return"[object Function]"===e}},370:function(t,e,n){var r=n(879),o=n(438);t.exports=function(t,e,n){if(!t&&!e&&!n)throw new Error("Missing required arguments");if(!r.string(e))throw new TypeError("Second argument must be a String");if(!r.fn(n))throw new TypeError("Third argument must be a Function");if(r.node(t))return function(t,e,n){return t.addEventListener(e,n),{destroy:function(){t.removeEventListener(e,n)}}}(t,e,n);if(r.nodeList(t))return function(t,e,n){return Array.prototype.forEach.call(t,(function(t){t.addEventListener(e,n)})),{destroy:function(){Array.prototype.forEach.call(t,(function(t){t.removeEventListener(e,n)}))}}}(t,e,n);if(r.string(t))return function(t,e,n){return o(document.body,t,e,n)}(t,e,n);throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")}},817:function(t){t.exports=function(t){var e;if("SELECT"===t.nodeName)t.focus(),e=t.value;else if("INPUT"===t.nodeName||"TEXTAREA"===t.nodeName){var n=t.hasAttribute("readonly");n||t.setAttribute("readonly",""),t.select(),t.setSelectionRange(0,t.value.length),n||t.removeAttribute("readonly"),e=t.value}else{t.hasAttribute("contenteditable")&&t.focus();var r=window.getSelection(),o=document.createRange();o.selectNodeContents(t),r.removeAllRanges(),r.addRange(o),e=r.toString()}return e}},279:function(t){function e(){}e.prototype={on:function(t,e,n){var r=this.e||(this.e={});return(r[t]||(r[t]=[])).push({fn:e,ctx:n}),this},once:function(t,e,n){var r=this;function o(){r.off(t,o),e.apply(n,arguments)}return o._=e,this.on(t,o,n)},emit:function(t){var e=[].slice.call(arguments,1),n=((this.e||(this.e={}))[t]||[]).slice(),r=0,o=n.length;for(r;r<o;r++)n[r].fn.apply(n[r].ctx,e);return this},off:function(t,e){var n=this.e||(this.e={}),r=n[t],o=[];if(r&&e)for(var i=0,a=r.length;i<a;i++)r[i].fn!==e&&r[i].fn._!==e&&o.push(r[i]);return o.length?n[t]=o:delete n[t],this}},t.exports=e,t.exports.TinyEmitter=e}},e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={exports:{}};return t[r](o,o.exports,n),o.exports}return function(){n.n=function(t){var e=t&&t.__esModule?function(){return t["default"]}:function(){return t};return n.d(e,{a:e}),e}}(),function(){n.d=function(t,e){for(var r in e)n.o(e,r)&&!n.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})}}(),function(){n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)}}(),n(686)}().default}))}).call(this,n("dc84")(t))},"2ec5":function(t,e,n){"use strict";t.exports=function(t,e){return e||(e={}),t=t&&t.__esModule?t.default:t,"string"!==typeof t?t:(/^['"].*['"]$/.test(t)&&(t=t.slice(1,-1)),e.hash&&(t+=e.hash),/["'() \t\n]/.test(t)||e.needQuotes?'"'.concat(t.replace(/"/g,'\\"').replace(/\n/g,"\\n"),'"'):t)}},"42b3":function(t,e,n){t.exports=n.p+"static/image/invite.png"},5072:function(t,e,n){"use strict";n.r(e);var r=n("77c7"),o=n.n(r);for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);e["default"]=o.a},5277:function(t,e,n){var r=n("b05a");r.__esModule&&(r=r.default),"string"===typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);var o=n("967d").default;o("386b08b3",r,!0,{sourceMap:!1,shadowMode:!1})},"77c7":function(t,e,n){"use strict";n("6a54");var r=n("f5bd").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=r(n("2e78")),i=getApp(),a={data:function(){return{cdn:i.globalData.cdn,com_cdn:i.globalData.com_cdn,data:[],invitation_code:""}},components:{},props:{},mounted:function(){new o.default(".copy-btn")},onLoad:function(t){this.load_data()},onReachBottom:function(){},methods:{alert:function(){this.util.show_msg("已复制")},load_data:function(){var t=this;t.util.ajax("/mallapi/user/invite",{},(function(e){t.data=e.data,t.invitation_code=e.data.invitation_code}))}}};e.default=a},"9df6":function(t,e,n){"use strict";var r=n("5277"),o=n.n(r);o.a},b05a:function(t,e,n){var r=n("c86c"),o=n("2ec5"),i=n("42b3");e=r(!1);var a=o(i);e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* COOL-UNI 颜色变量 */\r\n/* 颜色变量 */\r\n/* 颜色变量 */\r\n/* 默认颜色 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.bg[data-v-22f07d97]{background:url('+a+") no-repeat top;background-size:100%;min-height:100vh}.box[data-v-22f07d97]{border-radius:%?20?%;background-color:#fff;margin:%?30?% %?30?%;position:absolute;bottom:%?50?%;width:calc(100% - %?60?%);padding:%?30?%}.box > uni-view[data-v-22f07d97]:nth-child(1){text-align:center;font-size:%?36?%}.box > uni-view[data-v-22f07d97]:nth-child(2){text-align:center;font-size:%?50?%;color:#00f;font-weight:700}.box > uni-view[data-v-22f07d97]:nth-child(4){text-align:center;background-color:#2b38bb;margin:%?20?% %?100?%;padding:%?30?% 0;color:#fff;border-radius:%?10?%}",""]),t.exports=e},b885:function(t,e,n){"use strict";n.r(e);var r=n("1a37"),o=n("5072");for(var i in o)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return o[t]}))}(i);n("9df6");var a=n("828b"),c=Object(a["a"])(o["default"],r["b"],r["c"],!1,null,"22f07d97",null,!1,r["a"],void 0);e["default"]=c.exports}}]);