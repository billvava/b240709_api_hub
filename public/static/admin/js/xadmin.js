// Array.prototype.remove = function(val) { 
//     var index = this.indexOf(val); 
//     if (index > -1) { 
//         return  this.splice(index, 1); 
//     }

// };
$(function () {
   
    $(document).on('click', "*[data-toggle=dropdown]", function(e) {
        var t = $(this);
        t.siblings(".dropdown-menu").toggle();
        e.stopPropagation();
    });
    $(document).on('click', ".dropdown-menu a", function(e) {
        var t = $(this);
        t.parents(".dropdown-menu").hide();
        e.stopPropagation();
    });
    $(document).on('click', "html", function() {
        var t = $(this);
        $(".dropdown-menu").hide();
    });
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
    $(document).on('click', "*[data-type=show_url]", function(e) {
        var t = $(this);
        layer.open({
            type: 2,
            title:t.data('title'),
            closeBtn: 1,
            shade: [0.3],
            shadeClose: true,
            area: [ t.data('width') + 'px', t.data('height') + 'px'],
            maxmin: true,
            shift: 1,
            content: [ t.data('url') ]
        });
    });
    
    
    layui.use(['form','element'],
    function() {
        layer = layui.layer;
        element = layui.element;
        form = layui.form;
        
        //switch的切换事件
        form.on('switch(setval)', function(e){
            var t = $(this);
            var key = t.data('key');
            var keyid = t.data('keyid');
            var url = t.data('url');
            var field = t.data('field');
            var v = this.checked ? '1' : '0';
            $.post(url, {keyid: keyid, key: key, field: field, val: v}, function(data) {
                layer.tips(data.info, e.othis, {
                    tips: [1,'#3595CC'],
                    time: 2000
                });
            }, 'json');
        });
        
        form.on('checkbox(all_sel)', function(e){
            var t = $(this);
            var name = t.data('name');
            if(t.is(":checked")){
                $("input[name='"+name+"']").each(function () {
                    this.checked = true;
                });
            }else{
                $("input[name='"+name+"']").each(function () {
                    this.checked = false;
                });
            }
             form.render('checkbox');
        });
        

        //tab 右键事件
        $(".layui-tab-title").on('contextmenu', 'li', function(event) {
            var tab_left = $(this).position().left;
            var left = $(this).position().top;
            this_index = $(this).attr('lay-id');
            $('#tab_right').css({'left':tab_left+50}).show();
            $('#tab_show').show();
            return false;
        });

        $('.page-content,#tab_show,.container,.left-nav').click(function(event) {
            $('#tab_right').hide();
            $('#tab_show').hide();
        });


      

    });

    


     
     
    
})

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
        content: [root + '/' + admin_name + "/Upload/local_upload_img?field=" + field + "&catid=" + catid],
    });
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
 * 新打开图片选择器
 * @param {type} field
 * @returns {undefined}
 */
function new_img(name, field, selectNum) {
    var callback_rela = arguments[3] ? arguments[3] : '';
    var filename = arguments[4] ? arguments[4] : '';
    layer.open({
        type: 2,
        title: '图片选择器',
        closeBtn: 1,
        shade: [0.3],
        shadeClose: true,
        area: ['90%', '90%'],
        maxmin: true,
        shift: 1,
        content: [root + admin_name + "/Files/new_imgs?name=" + name + "&field=" + field + "&selectNum=" + selectNum + '&callback_rela='+callback_rela+'&filename='+filename],
    });
}


//打开选择商品
function select_goods(field){
    layer.open({
        type: 2,
        title: '商品选择',
        closeBtn: 1,
        shade: [0.3],
        shadeClose: true,
        area: ['90%', '90%'],
        maxmin: true,
        shift: 1,
        content: [ root + "/mall/Com/select_goods?callback=select_goods_callback&field="+field],
    });
}

//接收回调
function select_goods_callback(field,goods_id,items){
    $("#"+field).val(goods_id);
     var render = template($("#"+field+"_tpl").html(), {
        items: items
    });
    $("#"+field+"_list").html(render).show();
}

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