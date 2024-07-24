/**
 * 询问框
 * @returns {undefined}
 * 使用示例：
 *  warning('确定刷新页面吗？',function(){location.reload();})
 */
function warning() {
    var title = arguments[0] ? arguments[0] : '确定执行此操作吗？';
    var yes = arguments[1] ? arguments[1] : function() {
        close;
    };
    var no = arguments[2] ? arguments[2] : function() {
        close;
    };
    var end = arguments[3] ? arguments[3] : function() {
        close;
    };
    layer.confirm(title, {
        btn: ['确定', '取消'],
        yes: function(index, layero) {
           (yes && typeof (yes) == "function") && yes(index,layero);
        },
        cancel:function(index, layero) {
           (cancel && typeof (cancel) == "function") && cancel(index,layero);
        },
        end:function(index, layero) {
           (end && typeof (end) == "function") && end(index,layero);
        },
    });

}
/*
 ajax请求方法，默认为Post方法,json数据类型返回 {'success','success msg',[url:'http://www.baidu.com']} 
 如果存在返回的数据存在msg参数的话，则会在函数执行后页面自动弹出该msg.
 如果存在返回的数据存在url参数的话，则会在函数执行后页面自动跳转到该url.
 url:请求的路径
 data:发送的数据
 success:'status'为'success'回调函数 function(data){}
 error:'status'为'error'回调函数 function(data){}
 */
function ajax(url, post_data) {
    var success = arguments[2] ? arguments[2] : null;
    var error = arguments[3] ? arguments[3] : null;
    $.post(url, post_data,
            function(data) {
                if (data.status == "1") {
                    if (isNotEmpty(data.info) == true) {
                        layer.msg(data.info, {shade: 0.3, time: 1000}, function() {
                            (success && typeof (success) == "function") && success(data);

                            if (isNotEmpty(data.url) == true)
                                location.href = encodeURI(data.url);
                        });
                    } else {

                        (success && typeof (success) == "function") && success(data);
                        if (isNotEmpty(data.url) == true)
                            location.href = encodeURI(data.url);
                    }
                } else if (data.status == "0") {
                    if (isNotEmpty(data.info) == true) {
                        layer.msg(data.info, {shade: 0.3, time: 3000}, function() {
                            (error && typeof (error) == "function") && error(data);
                            if (isNotEmpty(data.url) == true)
                                location.href = encodeURI(data.url);
                        });
                    } else {
                        (error && typeof (error) == "function") && error(data);
                        if (isNotEmpty(data.url) == true)
                            location.href = encodeURI(data.url);
                    }
                }
            }, "json"
            );

}
/**
 * 判断是否为空
 * @param {type} val
 * @returns {Boolean}
 */
function isNotEmpty(val) {
    if (typeof (val) == 'undefined') {
        return false;
    }
    if (val != '' && val != null && val != undefined)
        return true;
    else
        return false;
}
/**
 * 返回一个数字
 * @param {type} num
 * @returns {undefined}
 */
function toNumber(str) {
    if(isNotEmpty(str)==false){
        return 0;
    }
    if(typeof(str)=='number'){
        return str;
    }else{
       var d = parseFloat(str);
       return d.toFixed(2);
    }



}

/**
 * 确认框，并跳转
 * @param {type} url 跳转的地址
 * @returns void
 */
function del(url) {
    var msg = arguments[1] ? arguments[1] : '确定执行该操作吗？';
    layer.confirm(msg, function() {
        location.href = url;
    });
}
//以新窗口打开链接
function openLink(url)
{
    window.open(url);
}
/**
 * checkbox的反选
 * @param {type} name
 * @returns {undefined}
 */
function invertSelectType(name) {
    var ids = $("input[name='" + name + "[]']");
    for (var i = 0; i < ids.length; i++)
    {
        if (ids[i].checked == true)
        {
            ids[i].checked = "";

        } else {
            ids[i].checked = "checked";

        }

    }

}
/**
 * 全选
 * @param {type} name
 * @returns {undefined}
 */
function allSelectType(name)
{

    var ids = $("input[name='" + name + "[]']");
    for (var i = 0; i < ids.length; i++)
    {

        ids[i].checked = "checked";

    }

}

/**
 * 打开链接
 * @param {type} url
 * @returns {undefined}
 */
function show_url(url) {
     var title = arguments[1] ? arguments[1] : '&nbsp;';
    layer.open({
        type: 2,
        title:title,
        closeBtn: 1,
        shade: [0.3],
        shadeClose: true,
        area: [($("body").width() - 100) + 'px', ($(window).height() - 100) + 'px'],
        maxmin: true,
        shift: 1,
        content: [url]
    });
}

/**
 * 判断是否为数字
 * @param {type} val
 * @returns {Boolean}
 */
function is_number(val) {
    if (!isNaN(val)) {
        return true;
    } else {
        return false;
    }
}
//是否为正整数 
function isPositiveNum(s) {
    var re = /^[0-9]+$/;
    return re.test(s)
}



//获取name的值
function get_name_val(name) {
    var thisval;
    var type = arguments[1] ? arguments[1] : 'text';
    if (type == 'text') {
        thisval = $.trim($("input[name=" + name + "]").val());
    }
    else if (type == 'check') {
        thisval = $.trim($("input[name=" + name + "]:checked").val());
    }
    else if (type == 'select') {
        thisval = $.trim($("select[name=" + name + "]").val());
    }
    return thisval;

}

//验证手机
function checkMobile(name) {
    var myreg = /^(((1[0-9][0-9]{1}))+\d{8})$/;
    var mobile = $.trim($("input[name=" + name + "]").val());
    if (!myreg.test(mobile))
    {
        layer.msg("手机号码格式有误");
        throw "手机号码格式有误";
    }

}
//验证邮箱
function check_email(name) {
    var strEmail = $.trim($("input[name=" + name + "]").val());
    var emailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/;
    if (emailReg.test(strEmail)) {

    } else {
        layer.msg("邮箱格式有误");
        throw "邮箱格式有误";
    }
}


//是否相等
function is_eq(var1, var2, msg) {
    if ($.trim($("input[name=" + var1 + "]").val()) != $.trim($("input[name=" + var2 + "]").val())) {
        layer.msg(msg);
        throw msg;
    }

}


//清空表单
function clearForm(id) {
    var objId = document.getElementById(id);
    if (objId == undefined) {
        return;
    }
    for (var i = 0; i < objId.elements.length; i++) {
        if (objId.elements[i].type == "text") {
            objId.elements[i].value = "";
        }
        else if (objId.elements[i].type == "password") {
            objId.elements[i].value = "";
        }
        else if (objId.elements[i].type == "radio") {
            objId.elements[i].checked = false;
        }
        else if (objId.elements[i].type == "checkbox") {
            objId.elements[i].checked = false;
        }
        else if (objId.elements[i].type == "select-one") {
            objId.elements[i].options[0].selected = true;
        }
        else if (objId.elements[i].type == "select-multiple") {
            for (var j = 0; j < objId.elements[i].options.length; j++) {
                objId.elements[i].options[j].selected = false;
            }
        }
        else if (objId.elements[i].type == "textarea") {
            objId.elements[i].value = "";
        }

    }
}

/**
 *显示信息 
 * @param {type} msg
 * @returns {undefined}
 */
function msg(msg) {
    layer.msg(msg, {shade: [0.3, 'black']});
}
/**
 * 加载框
 * @returns {undefined}
 */
function load() {
    var msg = arguments[0] ? arguments[0] : '正在处理中...';
    layer.msg(msg, {icon: 16, time: 10, shade: [0.2, '#393D49']});
}



function mload() {
    return layer.open({
        type: 2,
        shadeClose: false,
        time: 10
    });
}
/**
 * 时间戳转格式
 * @param {type} nS
 * @returns {String}
 */
function format_date(time) {
    var times = parseInt(time + '000');
    var d = new Date(times);
    var year = d.getFullYear();
    var month = (d.getMonth() + 1);
    var day = d.getDate();
    if (month < 10) {
        month = '0' + month;
    }
    ;
    if (day < 10) {
        day = '0' + day;
    }
    ;

    return year + '-' + month + '-' + day;
}

//移动版的layer  ajax请求
function a(url, post_data) {

    var success = arguments[2] ? arguments[2] : null;
    var error = arguments[3] ? arguments[3] : null;
    $.post(url, post_data,
            function(data) {

                if (data.status == "1") {
                    if (isNotEmpty(data.info) == true) {
                        layer.open({
                            content: data.info,
                            shadeClose: false,
                            end: function() {
                                (success && typeof (success) == "function") && success(data);
                                close();
                                if (isNotEmpty(data.url) == true)
                                    location.href = data.url;
                            },
                            time: 2
                        });

                    } else {
                        (success && typeof (success) == "function") && success(data);
                        close();
                        if (isNotEmpty(data.url) == true)
                            location.href = data.url;
                    }
                } else if (data.status == "0") {
                    if (isNotEmpty(data.info) == true) {
                        layer.open({
                            content: data.info,
                            shadeClose: false,
                            end: function() {
                                (error && typeof (error) == "function") && error(data);
                                close();
                                if (isNotEmpty(data.url) == true)
                                    location.href = data.url;
                            },
                            time: 3
                        });
                    } else {
                        (error && typeof (error) == "function") && error(data);
                        close();
                        if (isNotEmpty(data.url) == true)
                            location.href = data.url;
                    }
                }
            }, "json"
            );

}

function close() {
    layer.closeAll();
}
/**
 * 
 * @param {type} verifyURL
 * @returns {Boolean}验证码
 */
function change_code(verifyURL) {
    $("#code").attr("src", verifyURL + '?cc=' + Math.random());
    return false;
}

//获取get参数
function getQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return r[2]; return '';
}

//判断方法是否存在
function isExitsFunction(funcName) {
    try {
        if (typeof (eval(funcName)) == "function") {
            return true;
        }
    } catch (e) {
    }
    return false;
}

function open_cut_upload(width,height,func){
    var url = root+'/home/upload/cut?width='+width+'&height='+height+"&func="+func;
    layer.open({
        type: 2, 
         shadeClose: false,
         title:'上传图片',
        area: [($("body").width()) + 'px', ($(window).height() ) + 'px'],
        content: [url,'no'] 
      }); 
}
function open_zoom_upload(width,height,func){
    var url = root+'/home/upload/zoom?width='+width+'&height='+height+"&func="+func;
    layer.open({
        type: 2, 
         shadeClose: false,
         title:'上传图片',
        area: [($("body").width()) + 'px', ($(window).height() ) + 'px'],
        content:url
      }); 
}