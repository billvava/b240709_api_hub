<style>
#preview[data-info]:after {
    content: attr(data-info);
    position: absolute;
    top: 2px;
    left: 2px;
    background: #e0e0e0b8;
    padding: 0px 6px;
    font-family: arial;
    font-size: 11px;
    height: 20px;
    line-height: 20px;
    color: #676767;
    border-radius: 1px;
    letter-spacing: 0.5px;
    white-space: nowrap;
    cursor: pointer;
    opacity: 0;
    transition: opacity ease 250ms;
}
#preview[data-info]:hover:after {
    opacity: 1;
}
.layui-layer-prompt textarea.layui-layer-input {
    width: 850px;
    height: 300px;
}
</style>

<script>
    $(function () {
        $('body').css('maxWidth', '100%');
        $(window).scroll(function () {
            if ($(window).scrollTop() > 275) {
                if ($('#box').find('div.layui-form-item').length > 3) {
                    $('#preview').addClass('scroll');
                }
            } else {
                $('#preview').removeClass('scroll');
            }
        });
        window.onbeforeunload = function() { 
            return "确认离开当前页面吗？未保存的数据可能会丢失";
        }
    })
</script>
<div class="x-body">
    <blockquote class="layui-elem-quote">
    基于 <a href="https://github.com/kkokk/poster" target='_blank'>https://github.com/kkokk/poster</a> 库，使用前先安装并引入。<br/>
    点击<a class='x-red' href="https://github.com/kkokk/poster#%E4%BD%BF%E7%94%A8%E8%AF%B4%E6%98%8E" target='_blank'>查看参数</a>说明<br/><br/>

    <span class='x-red'>* 该系统已自带无需重新安装 如若从新安装记得删除类库下的字体包<span style='color: #2D8CF0;'>（vendor/kkokk/poster/src/style）</span> 改用系统自带字体包</span>
    
    </blockquote>
    <blockquote class="layui-elem-quote">
        <a href="javascript:;" id='imgbtn'>添加图片</a> | 
        <a href="javascript:;" id='textbtn'>添加文字</a> | 
        <a href="javascript:;" id='qrbtn'>添加二维码</a>
        <span class="fr"><a href="JavaScript:;" class='layui-btn layui-btn-sm' id='sub'>点击预览</a></span>
        <span class="fr" style='margin-right: 10px;' id='storageBtnBox'></span>
        <span class="fr" style='margin-right: 10px;'><a href="JavaScript:;" class='layui-btn layui-btn-sm' id='import'>导入代码</a></span>
    </blockquote>
    <form id='form'>
        <fieldset class="layui-elem-field">
            <legend>效果预览</legend>
            <div class="layui-field-box">
                <div class="table-container layui-col-md7" id='box'>
                    <div class="layui-form-item">
                        <legend>画布</legend>
                        <div class="layui-inline">
                            <label class="layui-form-label">宽</label>
                            <div class="layui-input-inline">
                                <input type="text" name="buildIm[w]" class="layui-input">
                            </div>
                            <div class="layui-word-aux mt5">画布宽</div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">高</label>
                            <div class="layui-input-inline">
                                <input type="text" name="buildIm[h]" class="layui-input">
                            </div>
                            <div class="layui-word-aux mt5">画布高</div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">颜色</label>
                            <div class="layui-input-inline">
                                <input type="text" name="buildIm[rgba]" class="layui-input">
                            </div>
                            <div class="layui-word-aux mt5">颜色<a href="https://tool.lu/color" target='_blank'>rbga</a>，如：[51, 51, 51, 1]</div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">透明</label>
                            <div class="layui-input-inline">
                                <input type="text" name="buildIm[alpha]" class="layui-input">
                            </div>
                            <div class="layui-word-aux mt5">是否透明 0|1</div>
                        </div>
                    </div>
                </div>
                <div class="table-container layui-col-md5" id='preview' data-info="坐标: 0x0"></div>
            </div>
        </fieldset>
    </form>

    <fieldset class="layui-elem-field layui-col-md7">
        <legend>生成代码</legend>
        <div class="layui-field-box" id='code'></div>
    </fieldset>
    
</div>
<script>
    $(function () {

        var imgIndex = 0;
        var textIndex = 0;
        var qrIndex = 0;

        var previewStorage = localStorage.getItem('preview');
        if (previewStorage) {
            $('#box').html(previewStorage);

            var imgIndexStorage = localStorage.getItem('imgIndex');
            if (imgIndexStorage) {
                imgIndex = imgIndexStorage;
            }
            var textIndexStorage = localStorage.getItem('textIndex');
            if (textIndexStorage) {
                textIndex = textIndexStorage;
            }
            var qrIndexStorage = localStorage.getItem('qrIndex');
            if (qrIndexStorage) {
                qrIndex = qrIndexStorage;
            }
            $('#storageBtnBox').html("<a href='JavaScript:;' style='background: #f56c6c !important;' class='layui-btn layui-btn-sm' onclick='clearStorage()'>清除缓存</a>");
            preview();
        }

        $('#imgbtn').click(function () {
            buildImage();
        })

        $('#textbtn').click(function () {
            buildText();
        })

        $('#qrbtn').click(function () {
            buildQr();
        })

        $('#sub').click(function () {
            preview();
        })

        $('#form').on('change', function () {
            preview();
        })

        $('input').on('change', function () {
            var val = $(this).val();
            $(this).attr({'value': val});
        })

        function buildIm(w='',h='',rgba='',alpha='') {
            $("input[name='buildIm[w]']").val(w);
            $("input[name='buildIm[h]']").val(h);
            $("input[name='buildIm[rgba]']").val(rgba);
            $("input[name='buildIm[alpha]']").val(alpha);
        }

        function buildImage(src='',dst_x=0,dst_y=0,src_x=0,src_y=0,src_w='',src_h='',alpha='',type='') {
            var html = '<div class="layui-form-item" id="imgBox' + imgIndex + '">';
                html += '<legend>图片 <a href="javascript:;" onclick="del(\'imgBox' + imgIndex + '\')">删除</a></legend>';
                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片地址</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][src]" value="' + src + '" class="layui-input imgSrc">';
                    html += '<div class="layui-word-aux mt5">路径，支持网络图片（带http或https）</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">目标x轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][dst_x]"  value="' + dst_x + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">目标x轴 特殊值 center 居中 支持百分比20% 支持自定义 支持正负</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">目标y轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][dst_y]" value="' + dst_y + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">目标y轴 特殊值 center 居中 支持百分比20% 支持自定义 支持正负</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片x轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][src_x]" value="' + src_x + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片y轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][src_y]" value="' + src_y + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片自定义宽</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][src_w]" value="' + src_w + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片自定义高</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][src_h]" value="' + src_h + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">是否透明</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][alpha]" value="' + alpha + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">是否透明 0|1</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片变形类型</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildImage[' + imgIndex + '][type]" value="' + type + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">图片变形类型 normal 正常形状 circle 圆形</div>';
                    html += '</div>';
                    html += '</div>';
                html += '</div>';
            
            $('#box').append(html);
            imgIndex++;
            localStorage.setItem('imgIndex', imgIndex);
            localStorage.setItem('preview', $('#box').html());
        }

        function buildText(content='',dst_x=0,dst_y=0,font=28,rgba='51, 51, 51, 1',max_w='',font_family='static/admin/font/aliheiti.ttf') {
            var html = '<div class="layui-form-item" id="textBox' + textIndex + '">';
                html += '<legend>文字 <a href="javascript:;" onclick="del(\'textBox' + textIndex + '\')">删除</a></legend>';
                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">文字内容</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][content]" value="' + content + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">目标x轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][dst_x]" value="' + dst_x + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">目标x轴 特殊值 center 居中 支持百分比20% 支持自定义 支持正负</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">目标y轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][dst_y]" value="' + dst_y + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">目标y轴 特殊值 center 居中 支持百分比20% 支持自定义 支持正负</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">字体大小</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][font]" value="' + font + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">文字颜色</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][rgba]" value="[' + rgba + ']" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">颜色<a href="https://tool.lu/color" target="_blank">rbga</a>，如：[51, 51, 51, 1]</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">最大换行宽度</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][max_w]" value="' + max_w + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">字体</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildText[' + textIndex + '][font_family]" value="' + font_family + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5" style="color: red !important">* 必填 根目录位于index.php</div>';
                    html += '</div>';
                    html += '</div>';
                html += '</div>';
            
            $('#box').append(html);
            textIndex++;
            localStorage.setItem('textIndex', textIndex);
            localStorage.setItem('preview', $('#box').html());
        }

        function buildQr(text='',dst_x=0,dst_y=0,src_x=0,src_y=0,size='',margin='') {
            var html = '<div class="layui-form-item" id="qrBox' + qrIndex + '">';
                html += '<legend>二维码 <a href="javascript:;" onclick="del(\'qrBox' + qrIndex + '\')">删除</a></legend>';
                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">内容</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][text]" value="' + text + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">二维码包含的内容，可以是链接、文字、json字符串等等</div>';
                    html += '</div>';
                    html += '</div>';
                    
                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">目标位置x</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][dst_x]" value="' + dst_x + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">特殊值 center 居中 支持百分比20% 支持自定义 支持正负</div>';
                    html += '</div>';
                    html += '</div>';
                    
                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">目标位置y</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][dst_y]" value="' + dst_y + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">特殊值 center 居中 支持百分比20% 支持自定义 支持正负</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片x轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][src_x]" value="' + src_x + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">图片y轴</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][src_y]" value="' + src_y + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5"></div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">大小</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][size]" value="' + size + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">控制生成图片的大小，默认为4</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="layui-inline">';
                    html += '<label class="layui-form-label">白边大小</label>';
                    html += '<div class="layui-input-inline">';
                    html += '<input type="text" name="buildQr[' + qrIndex + '][margin]" value="' + margin + '" class="layui-input">';
                    html += '<div class="layui-word-aux mt5">控制生成二维码的空白区域大小</div>';
                    html += '</div>';
                    html += '</div>';
                html += '</div>';
            
            $('#box').append(html);
            qrIndex++;
            localStorage.setItem('qrIndex', qrIndex);
            localStorage.setItem('preview', $('#box').html());
        }

        //导入代码
        $('#import').click(function () {
            layer.prompt({
                formType: 2,
                title: '请输入代码',
                maxlength: 10000,
                area: ['900px', '450px']
            }, function(value, index, elem){
                layer.close(index);
                var content = value.split("\n");
                content.forEach(function(code) {
                    ret_buildIm = /.+(buildIm\(.*\));/;
                    ret_buildImage = /.+(buildImage\(.*\));/;
                    ret_buildText = /.+(buildText\(.*\));/;
                    ret_buildQr = /.+(buildQr\(.*\));/;
                    if((Im = ret_buildIm.exec(code))!=null){
                        console.log(Im);
                        eval(Im[1])
                    }
                    if((Image = ret_buildImage.exec(code))!=null){
                        eval(Image[1])
                    }
                    if((Text = ret_buildText.exec(code))!=null){
                        eval(Text[1])
                    }
                    if((Qr = ret_buildQr.exec(code))!=null){
                        eval(Qr[1])
                    }
                });
                preview();
            });
        })
    })

    //坐标
    $("#preview").on('mousemove', function(e){
        var px = e.pageX - $(this).offset().left-20;
        var py = e.pageY - $(this).offset().top;
        if(px<0){px=0}
        var pw = $(this).width();
        var ph = $(this).height();
        var imgw = $("input[name='buildIm[w]']").val();
        var imgh = $("input[name='buildIm[h]']").val();
        var x = Math.ceil(imgw/pw*px);
        var y = Math.ceil(imgh/ph*py);
        $(this).attr({'data-info': $(this).attr('data-info').split(':')[0]+': '+x+'x'+y});
    })

    function del (id) {
        $('#' + id).remove();
        // 加入缓存
        localStorage.setItem('preview', $('#box').html());
    }

    function preview () {
        // 加入缓存
        localStorage.setItem('preview', $('#box').html());

        layer.load(1);
        var data = $("#form").serialize();
        // 预览
        ajax('{:url("preview")}', data, function (res) {
            layer.closeAll();
            $('#preview').html('<img src="' + res.data.url + '">');
        });
        // 生成代码
        ajax('{:url("build")}', data, function (res) {
            layer.closeAll();
            $('#code').html(res.data);
        });
    }

    function clearStorage () {
        // 清除缓存
        localStorage.removeItem('imgIndex');
        localStorage.removeItem('qrIndex');
        localStorage.removeItem('textIndex');
        localStorage.removeItem('preview');
        alert('清除成功，刷新页面后现在的输入将被重置');
    }
</script>
<style>
#preview{padding: 0 20px; text-align: center;}
#preview img{width: 100%;}
.layui-field-box{overflow: hidden;}
.layui-form-item{ background: #eee;}
.layui-elem-field legend{ margin: 0; border-bottom: 0; padding: 0 20px 10px; }
.layui-form-item{ padding: 20px 0; }
.scroll{ position: fixed; top: 0; left: 58%; overflow: auto; }
</style>