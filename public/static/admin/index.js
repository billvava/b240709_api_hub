$(function() {
    //html加载事件

    //设置左边菜单的高度
    var ztree = $('.ztree').height();
    $('.ztree').height(ztree + 200);

    //快捷修改
    $(document).on('change', "input[data-type=setval],select[data-type=setval]", function() {
        var t = $(this);
        var key = t.data('key');
        var keyid = t.data('keyid');
        var url = t.data('url');
        var field = t.data('field');
        var v = t.val();
        $.post(url, {keyid: keyid, key: key, field: field, val: v}, function(data) {
            layer.tips(data.info, t, {
                tips: [1, '#3595CC'],
                time: 2000
            });
        }, 'json');
    });


});

/**
 * 切换字段状态
 * @param {type} url
 * @returns {undefined}
 */
function change_status(url, id) {
    var qiyong = arguments[2] ? arguments[2] : '启用';
    var jinyong = arguments[3] ? arguments[3] : '禁用';
    ajax(url, {id: id}, function(data) {
        layer.tips('切换成功。', '#field' + id, {
            tips: [1, '#3595CC'],
            time: 1000
        });
        if (data.data.status == '0') {
            $("#field" + id).html("<font style='color:red;'>" + jinyong + "</font>");
        } else {
            $("#field" + id).html("<font style='color:green;'>" + qiyong + "</font>");
        }
    });
}

/**
 * [change_display description]
 * @return {[type]} [description]
 */
function change_display(obj, id, url) {
    var type = $(obj).data('type');
    ajax(url, {'id': id, 'type': type}, function(data) {
        if (type == 1) {
            $(obj).data('type', '0');
            $('.is-hide-' + id).find('font').html('<font style="color:green;">显示</font>');
        } else {
            $(obj).data('type', '1');
            $('.is-hide-' + id).find('font').html('<font style="color:red;">隐藏</font>');
        }
    });
}





/**
 * 删除图片
 * @param {type} classname
 * @returns {undefined}
 */
function del_img(classname) {
    var src = $("." + classname).attr("src");
    $("input[value='" + src + "']:eq(0)").remove();
    $("." + classname).remove();
}



/**
 * 打开图片选择器
 * @param {type} field
 * @returns {undefined}
 */
function show_img(field, flag) {
    layer.open({
        type: 2,
        title: '站内选择',
        closeBtn: 1,
        shade: [0.3],
        shadeClose: true,
        area: ['700px', '550px'],
        maxmin: true,
        shift: 1,
        content: [root + admin_name + "/Files/images?field=" + field + "&flag=" + flag],
    });
}
/**
 * 打开图片上传器
 * @param {type} field
 * @returns {undefined}
 */
function upload_duo_img(field, catid) {
    layer.open({
        type: 2,
        title: '本地上传',
        closeBtn: 1,
        shade: [0.3],
        shadeClose: false,
        area: ['700px', '550px'],
        shift: 1,
        content: [root + admin_name + "/Upload/local_upload_img?field=" + field + "&catid=" + catid],
    });
}

/**
 * 发送url
 * @param {type} url
 * @returns {undefined}
 */
function send(url, msg) {
    layer.confirm(msg, function() {
        ajax(url, null);
    });
}

/**
 * 上传单图片
 */
function upload_img(field) {
    var rand_name = get_rand_name();
    eval("var " + rand_name);
    rand_name = new plupload.Uploader({
        runtimes: 'gears,html5,html4,silverlight,flash',
        browse_button: 'upload_' + field,
        url: root + admin_name + "/Upload/upload_img?flag=1",
        flash_swf_url: root + '/public/lib/plupload/Moxie.swf',
        silverlight_xap_url: root + '/public/lib/plupload/Moxie.xap")',
        filters: {
            max_file_size: '0',
            mime_types: [
                {title: "*", extensions: "*"}
            ]
        },
        init: {
            PostInit: function() {
            },
            //选择图片后
            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    rand_name.start();
                    return false;
                });
            },
            //上传中
            UploadProgress: function(up, file) {
                $("#" + field + "_process").text("已上传" + file.percent + "%");
            },
            //异常处理
            Error: function(up, err) {
                layer.msg(err.message);
            },
            //图片上传后
            FileUploaded: function(up, file, res) {
                var str = res.response;
                var json = eval("(" + str + ")");
                if (json.status == '0') {
                    layer.msg(json.info);
                } else {
                    $("#" + field + " input").val(json.data.file);
                }

            }


        }
    });

    rand_name.init();
}

/**
 * 生成一个随机数
 * @returns {String}
 */
function get_rand_name() {
    return 'a' + Date.parse(new Date()) + Math.floor((Math.random()) * 100).toString();
}
/**
 * 添加广告子集图片
 * @returns {undefined}
 */
function add_item() {
    var current = $("#current").val();
    ajax(root + admin_name + "/Ad/create_ad_item", {current: current}, function(data) {
        $("#footer_btn").before(data.data.html);
        $("#current").val(data.data.current);
        upload_img("big" + current);
        upload_img("small" + current);
        $('[data-toggle="tooltip"]').tooltip();
    })
}

/**
 * 预览文件
 * @returns {undefined}
 */
function showfile(field) {
    var url = $("input[name='" + field + "']").val();
    if (isNotEmpty(url) == false)
        layer.msg('文件为空');
    else
        openLink(root_path + url);
}


function isPositiveNum(s) {//是否为正整数 
    var re = /^[0-9]+$/;
    return re.test(s)
}

/**
 * 图片预览 
 * @param {type} name 
 * @returns {undefined}
 */
function tupianyulan(name) {
    var v = $("input[name=" + name + "]").val();
    if (isNotEmpty(v) == false) {
        msg('图片为空');
        return false;
    }
    v = root_path + v;
    layer.open({
        type: 1,
        area: ['400px', '400px'], //宽高
        shadeClose: true,
        title: '图片预览',
        maxmin: true,
        content: '<a href="' + v + '" target="_blank"><img src="' + v + '" /></a>'
    });
}
//裁剪上传
function open_clip_upload(width, height, func) {
    var url = root_path + '/h/upload/index?width=' + width + '&height=' + height + "&func=" + func;
    var width;
    var height;
    var content;
    if(is_mobile()==false){
        width =  '100%';
       height =  '100%';
       content = [url];
    }else{
         width = $("body").width() + 'px';
         height = ($(window).height()) + 'px';
           content = [url, 'no'];
    }
    
    layer.open({
        type: 2,
        shadeClose: false,
        title: '上传图片',
        area: [width, height],
        content: [url, 'no']
    });
}

//缩放上传
function open_zoom_upload(width, height, func) {
    var url = root_path + '/h/upload/zoom?width=' + width + '&height=' + height + "&func=" + func;
    var width;
    var height;
    var content;
    if(is_mobile()==false){
        width =  '50%';
       height =  '50%';
       content = [url];
    }else{
         width = $("body").width() + 'px';
         height = ($(window).height()) + 'px';
         content = [url, 'no'];
    }
    
    layer.open({
        type: 2,
        shadeClose: false,
        title: '上传图片',
        area: [width, height],
        content:url
    });
}

//判断PC还是WAP
function is_mobile() {
    var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
    var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
    if (!(bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM)) {
        return false;
    } else {
        return true;
    }
}
