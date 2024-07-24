/**
 * 询问框
 * @returns {undefined}
 * 使用示例：
 *  warning('确定刷新页面吗？',function(){location.reload();})
 */
function warning(){
  var title = arguments[0] ? arguments[0] : '确定执行此操作吗？';
  var yes = arguments[1] ? arguments[1] :  function(){close;};
  var no = arguments[2] ? arguments[2] : function(){close;};
  var end = arguments[3] ? arguments[3] : function(){close;};
  layer.open({
    content: title
    ,btn: ['确定', '取消']
    ,yes: function(index){
        (yes && typeof (yes) == "function") && yes(index);
    },no:function(index){
        (no && typeof (no) == "function") && no(index);
    },end:function(index){
         (end && typeof (end) == "function") && end(index);
    }
  });
}

/*
 * 
 * @param {type} url
 * @param {type} post_data
 * @returns {undefined}
 * 移动版layer
 */

//移动版的layer  ajax请求
function ajax(url, post_data) {
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
                                
                                if (isNotEmpty(data.url) == true)
                                    location.href = data.url;
                            },
                            time: 2
                        });

                    } else {
                        (success && typeof (success) == "function") && success(data);
                     
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
                              
                                if (isNotEmpty(data.url) == true)
                                    location.href = data.url;
                            },
                            time: 3
                        });
                    } else {
                        (error && typeof (error) == "function") && error(data);
                     
                        if (isNotEmpty(data.url) == true)
                            location.href = data.url;
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

/**
 *显示信息 
 * @param {type} msg
 * @returns {undefined}
 */
function msg(msg) {
    layer.open({
        content: msg
        ,btn: '确定'
      });
}
/**
 * 加载框
 * @returns {undefined}
 */
function load() {
    
    var msg = arguments[0] ? arguments[0] : '正在处理中...';
    layer.open({
        type: 2
        ,content: msg,
         shadeClose: false,
         time:10
      });
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


/**
 * 返回一个数字
 * @param {type} num
 * @returns {undefined}
 */
function toNumber(num) {
    num = $.trim(num);
    if (isNotEmpty(num) == false) {
        num = 0.00;
    }
    var re = /^[0-9]+(.[0-9]{2})?$/;
    if (!re.test(num)) {
        num = 0.00;
    }
    if (typeof (num) != 'number') {
        return parseFloat(num);
    } else {
        return (num);
    }



}

/**
 * 关闭Layer
 * @returns {undefined}
 */
function close() {
    layer.closeAll();
}
/**
 * 
 * @param {type} verifyURL
 * @returns {Boolean}验证码
 */
function change_code(id,verifyURL) {
    $("#"+id).attr("src", verifyURL + '?cc=' + Math.random());
    return false;
}


