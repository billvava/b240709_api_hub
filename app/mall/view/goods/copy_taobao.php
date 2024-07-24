<a class="pear-btn pear-btn-danger pear-btn-sm" id='copy' href="javascript:;">复制淘宝、天猫、1688、京东商品</a>
<div id='inputBox'>
    <div id="inputGroup">
        <input type="text" class='layui-input' id='inputValue'>
        <button class="pear-btn pear-btn-primary" id='getContents' type="button">提取信息</button>
    </div>
    <p>注意：受到淘宝店铺影响需要登陆才可以访问产品详情，可能会导致采集程序不稳定，都属于正常现象</p>
    <p>输入以http或https开头的淘宝、天猫、1688、京东的商品详情页网址，网址正确且商品信息存在时才能采集成功。</p>
</div>
<style>
    #inputBox{display: none; padding: 20px;}
    #inputBox p{margin-top: 10px; color: red;}
    #inputGroup{width: 100%; height: auto; overflow: hidden;}
    #inputGroup input{width: 80% !important; float: left; display: block; overflow: hidden; border-right: 0; border-top-right-radius: 0; border-bottom-right-radius: 0}
    #inputGroup button{width: 20% !important; float: left; display: block; overflow: hidden; border-left: 0; border-top-left-radius: 0; border-bottom-left-radius: 0}
</style>
<script>
    $(function () {
        $('#copy').click(function () {
            $('#inputValue').val('');
            layer.open({
                type: 1,
                area: ['50%'],
                title: '复制淘宝、天猫、1688、京东商品',
                content: $('#inputBox')
            });
        })

        $('#getContents').click(function () {
            var url = $('#inputValue').val();
            if (url == '') {
                layer.msg('请输入链接地址');
                return;
            }
            layer.load();
            ajax('{:url("CopyTaobao/get_request_contents")}', {'url': url}, function (res) {
                var data = res.data;
                $('input[name=name]').val(data.store_name);
                $('input[name=small_title]').val(data.store_info);
                $('input[name=unit]').val(data.unit_name);

                // 缩略图
                var thumb = '';
                thumb += '<div  ondblclick="$(this).remove();" onmouseover="mouseover(this)" onmouseout="mouseout(this)" class=\'img_item_box\' style="position: relative;overflow:hidden; width: 100px; height: 100px; float: left;  margin: 0 15px 15px 0;">';
                thumb += '<div class="imgMark">双击删除图片</div>';
                thumb += '<a class="imgBoxA" href="javascript:;"><img src="' + data.image + '"  style="width:100px;height:100px;"></a>';
                thumb += '<input type="hidden" name="thumb';
                thumb += '" value="' + data.image + '" />';
                thumb += '</div>';
                $('#thumb_imgbox').html(thumb);

                // 组图
                var images = '';
                for (var i = 0; i < data.slider_image.length; i++) {
                    images += '<div  ondblclick="$(this).remove();" onmouseover="mouseover(this)" onmouseout="mouseout(this)" class=\'img_item_box\' style="position: relative;overflow:hidden; width: 100px; height: 100px; float: left;  margin: 0 15px 15px 0;">';
                    images += '<div class="imgMark">双击删除图片</div>';
                    images += '<a class="imgBoxA" href="javascript:;"><img src="' + data.slider_image[i] + '"  style="width:100px;height:100px;"></a>';
                    images += '<input type="hidden" name="images[]';
                    images += '" value="' + data.slider_image[i] + '" />';
                    images += '</div>';
                }
                if (images != '') {
                    $('#images_imgbox').html(images);
                }

                editorcontent.html(data.description);
                layer.closeAll();
            }, function () {
                layer.closeAll();
            })
        })
    })
</script>