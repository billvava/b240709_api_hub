
<script type="text/javascript" src="__LIB__/template.js"></script>
<script type="text/html" id="tpl">
    <li style="display:inline-block">
        <div align="center" class="white-border img-border">
            <img data-id="<%:=data.id%>" file-src="<%:=data.file%>" real-src="<%:=data.url%>" src="<%:=data.url%>" width="100" height="100">
            <p class="layui-elip" style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;width: 100px"><%:=data.name%></p>
        </div>
    </li>
</script>
<script>
    var  sel_num  = {$selectNum};
    $(function () {
        /**
         * 点击图片
         *
         * @return  [type]  [return description]
         */
        $(document).on('click', 'img', function () {
            if ($(this).parent().hasClass('add-border')){
                $(this).parent().removeClass('add-border');
                $(this).parent().addClass('white-border');
                $(this).parents('li').removeClass('act');
            } else {

                if(sel_num == 1){
                    $("#lists div").addClass('white-border');
                    $("#lists div").removeClass('add-border');
                    $("#lists li").removeClass('act');
                }

                $(this).parent().addClass('add-border');
                $(this).parent().removeClass('white-border');
                $(this).parents('li').addClass('act');
            }
        });

        /**
         * 上传图片
         *
         * @return  [type]  [return description]
         */
        var main = $(window.parent.document);
        var rand_name = get_rand_name();
        eval("var " + rand_name);
        layui.use('upload', function(){
            var upload = layui.upload;
            //执行实例
            rand_name  = upload.render({
                accept:'images',
                exts:'jpg|gif|png|bmp|jpeg|ico',
                elem: '#upload' //绑定元素
                ,url: root + admin_name + "/Upload/upload_img?flag=1&filename=<?php echo $in['filename']; ?>" //上传接口
                ,multiple: true
                ,done: function(res){
                    //上传完毕回调
                    if(res.status=='0'){
                        layer.alert(res.info);
                    }else{
                        if (res.status == '0') {
                            layer.msg(res.info);
                        } else {
                            $.post('{:url('new_imgs_save')}', {
                                source: res.data.source,
                                file: res.data.file,
                                type: res.data.type,
                                name: '',
                            }, function (res2) {
                                res.data.id = res2.data.id;
                                res.data.name= '新文件';
                                var render = template($("#tpl").html(), {
                                    data:res.data
                                });
                                $("#lists").prepend(render);
                            }, 'json')
                        }

                    }

                }
                ,error: function(){
                    //请求异常回调
                    layer.alert('上传失败!');
                }
            });
        });


        /**
         * 删除图片
         *
         * @return  [type]  [return description]
         */
        $('#del').click(function () {
            var length = 0;
            var selected = [];
            $('#lists li.act').each(function (index, element) {
                selected[index] = $(element).find('img').data('id');
                length++;
            })
            if (length <= 0) {
                layer.msg('请选择图片');
                return false;
            }
            var confirmbox = layer.confirm('确定删除这' + length + '张图片吗', {
                yes: function () {
                    $.post('new_imgs_del', {images: selected}, function (res) {
                        $('#lists li.act').remove();
                        layer.close(confirmbox);
                    }, 'json')
                }
            });
        })

        /**
         * 选中图片
         *
         * @return  [type]  [return description]
         */
        $('#used').click(function () {
            var img = '';
            var length = $('#lists li.act').length;
            var sel_num  = {$selectNum};

            var sel_img_src = [];
            var sel_img_file = [];
            $('#lists li.act').each(function (index, element) {
                var src = $(element).find('img').attr('real-src');
                var file_src = $(element).find('img').attr('file-src');
                sel_img_src.push(src);
                sel_img_file.push(file_src);
                img += '<div  ondblclick="$(this).remove();" onmouseover="mouseover(this)" onmouseout="mouseout(this)" class=\'img_item_box\' style="position: relative;overflow:hidden; width: 100px; height: 100px; float: left;  margin: 0 15px 15px 0;">';
                img += '<div class="imgMark">双击删除</div>';
                img += '<a class="imgBoxA" href="javascript:;"><img src="' + src + '"  style="width:100px;height:100px;"></a>';
                img += '<input type="hidden" name="{$field}';
                if ({$selectNum} > 1) {
                    img += '[]';
                }
                img += '" value="' + file_src + '" />';
                img += '</div>';
            })
            if (length <= 0) {
                layer.msg('请选择图片');
                return false;
            }
            if (length > sel_num) {
                layer.msg('最多只能选择' + {$selectNum} + '张图片!');
                return false;
            }
            // 回调关闭弹窗并赋值
            var main = $(window.parent.document);
            var checkSelected = main.find("#{$field}_imgbox .img_item_box").length;
            var cal_count = (checkSelected + length);
            if (sel_num != 1) {
                if (cal_count > sel_num) {
                    layer.msg('【{$name}】只允许上传' + {$selectNum} + '张图片');
                    return false;
                }
            }
            <?php if($in['callback']){ ?>
            <?php echo "parent.".$in['callback']."('".$in['field']."',sel_img_src,sel_img_file);parent.layer.closeAll();return;"; ?>;
            <?php } ?>

            if(sel_num == 1){
                main.find("#{$field}_imgbox").html(img);
            }else{
                main.find("#{$field}_imgbox").append(img);
            }
            <?php if($in['callback_rela']){ ?>
            <?php echo "parent.".$in['callback_rela']."('".$in['field']."',sel_img_src,sel_img_file);"; ?>;
            <?php } ?>
            parent.layer.closeAll();
        })
    })
    /**
     * 生成一个随机数
     * @returns {String}
     */
    function get_rand_name() {
        return 'a' + Date.parse(new Date()) + Math.floor((Math.random()) * 100).toString();
    }
</script>