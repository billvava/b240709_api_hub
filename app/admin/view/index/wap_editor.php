<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>编辑器</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <style>
        body {
            display: block;
            margin: 0;
        }
        .vuefor {
            display: block;
            background: #F8F8F8;
            margin-bottom: 5px;
            
        }

        .fleft {
            float: left;
        }

        .fright {
            float: right;
        }

        .clear {
            clear: both;
        }

        /* 工具条 */
        .editor-box .tool-box {
            width: 100%;
            text-align: center;
            display: inline-block;
            background: #e6e6e6;
        }

        .editor-box .tool-item {
            font-size: 12px;
            float: left;
            width: 30px;
            height: 30px;
            line-height: 30px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 2px;
            background-color: #fff;
            cursor: pointer;
        }

        .editor-box .tool-item.active {
            color: #fff;
            background-color: #19be6b;
        }

        /* 文本 */
        .editor-box .editor-item .content .text-box {
            display: block;
            text-align: center;
        }

        /* 文本工具条 */
        .editor-box .editor-item .content .text-box .head {
            display: block;
            width: 100%;
        }

        .editor-box .editor-item .content .text-box .head div {
            font-size: 16px;
            float: left;
            width: 30px;
            height: 30px;
            line-height: 30px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 15px;
            background-color: #fff;
            cursor: pointer;
        }

        .editor-box .editor-item .content .text-box .input {
            text-align: left;
            clear: both;
            display: block !important;
            vertical-align: middle;
            /* 加了可实现纯textarea功能，不加则像富文本一样可以写入css样式和HTML标签 */
            user-modify: read-write-plaintext-only;
            -webkit-user-modify: read-write-plaintext-only;
            /* 必须加，不然移动端有些浏览器无光标 */
            user-select: auto;
            -webkit-user-select: auto;
            background-color: #fff;
            padding: 5px 0;
        }

        .editor-box .editor-item .content .text-box .input:empty:before {
            content: attr(placeholder);
            color: #e6e6e6;
        }

        .editor-box .editor-item .content .text-box .foot {
            height: 30px;
            line-height: 30px;

        }

        .editor-box .editor-item .content .text-box .foot .color-item {
            display: inline-table;
            margin: 2px;
            vertical-align: middle;
        }

        .editor-box .editor-item .content .text-box .foot .color-item span {
            width: 20px !important;
            height: 20px !important;
            border-radius: 20px;
            padding: 4px;
            border: 1px solid #999;
            display: block;
            cursor: pointer;
        }

        .editor-box .editor-item .content .text-box .foot .color-item span:empty:before {
            content: "\200b";
        }

        /* 分割线 */
        .editor-box .editor-item .content .line-box {
            display: block;
            text-align: center;
        }

        .editor-box .editor-item .content .line-box hr {
            height: 1px;
            margin: 10px 0;
            border: 0;
            clear: both;
            background-color: #909399;
        }

        /* 图片 */
        .editor-box .editor-item .content .img-box {
            display: block;
            text-align: center;
        }

        .editor-box .editor-item .content .img-box img {
            width: 100%;
        }
        /* 保存 */
        .editor-box .submit-box{
            margin:10px auto;
            text-align: center;
            background: #e6e6e6;
            padding:10px;
        }
        .editor-box .submit-box button{
            height: 30px;
            line-height: 30px;
            color:#fff;
            background-color: #19be6b;
            border:1px solid #999;
            border-radius: 3px;
        }
        @media only screen and (min-width: 1029px){
            body {
                display: block;
                margin: 0 auto;
            }
            .editor-box{
                width:480px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="editor-box vue-container">
        <div class="vuefor" v-for="(item,index) in editorData">
            <div class="tool-box">
                <div class="fleft">
                    <label class="tool-item " :class="{'active' : (item.length !=0 && item.mytype==1) }" v-on:click.stop="itemAdd(index,1)">
                        <div class="text">文字</div>
                    </label>
                    <label class="tool-item " :class="{'active' : (item.length !=0 && item.mytype==2) }" v-on:click.stop="itemAdd(index,2)">
                        <div class="text">图片</div>
                    </label>
<!--                    <label class="tool-item " :class="{'active' : (item.length !=0 && item.mytype==3) }" v-on:click.stop="itemAdd(index,3)">
                        <div class="text">分割</div>
                    </label>-->
                </div>
                <div class="fright">
                    <label class="tool-item " v-on:click.stop="itemUp(index)">
                        <div class="text">↑</div>
                    </label>
                    <label class="tool-item " v-on:click.stop="itemDown(index)">
                        <div class="text">↓</div>
                    </label>
                    <label class="tool-item " v-on:click.stop="itemDel(index)">
                        <div class="text">X</div>
                    </label>
                </div>
            </div>
            <div class="editor-item" v-if="item.length !=0">
                <div class="content" v-if="item.mytype==1">
                    <!--文字类型的输入框-->
                    <div class="text-box">
                        <div class="head">
                            <div title="加粗" v-on:click.stop="fontWeight(index)">B</div>
                            <div title="放大字体" v-on:click.stop="fontInc(index)" v-bind:class="{ 'head-btn': true}">+</div>
                            <div title="缩小字体" v-on:click.stop="fontDec(index)" v-bind:class="{ 'head-btn': true}">-</div>
                            <div title="删除线" v-on:click.stop="fontDel(index)" v-bind:class="{ 'head-btn': true}" style="text-decoration: line-through;">A</div>
                            <div title="下划线" v-on:click.stop="fontLine(index)" v-bind:class="{ 'head-btn': true}" style="text-decoration: underline;">A</div>
                            <div title="居中" v-on:click.stop="fontAlign(index)" v-bind:class="{ 'head-btn': true}">≡</div>
                        </div>
                        <div class="input clear" contenteditable=true v-bind:style="initStyle(index)" placeholder="请输入" v-html="item.content"
                             @blur="item.content=$event.target.innerText"></div>
                        <div class="foot">
                            <div class="color-list fright">
                                <div class="color-item" v-for="color in colors">
                                    <span v-on:click.stop="fontSetColor(index,color)" v-bind:style="initFontColor(color)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content" v-if="item.mytype==2" style="">
                    <!--图片-->
                    <div class="img-box">
                        <img v-if="item.default==1" v-bind:src="item.content" alt="" />
                        <img class="loading" v-if="item.default==0" src="data:image/png;base64,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" @click="changeCoverImg($event,index)"
                             alt="" />
                        <input
                             type="file"
                             :ref="`fileInput${index}`"
                             accept="image/*"
                             @change="getFile($event,index)"
                             style="display: none">
                    </div>
                </div>
                <div class="content" v-if="item.mytype==3">
                    <!--分割线-->
                    <div class="line-box">
                        <hr>
                    </div>
                </div>
                <div class="clear" style=""></div>
            </div>
        </div>
        <div class='submit-box'><button v-on:click.stop="submit" style="    width: 100%;"> 确定 </button></div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="__LIB__/localResizeIMG/mobileFix.mini.js" type="text/javascript"></script>
<script src="__LIB__/localResizeIMG/exif.js" type="text/javascript"></script>
<script src="__LIB__/localResizeIMG/lrz.js" type="text/javascript"></script>
<script src="__LIB__/jquery.min.js" type="text/javascript"></script>
<script>
    var pageData = {
        editorData: [
            {"mytype": 2, "content": "", "default": 0}
           
        ]
        , colors: [
            "#2979ff",
            "#fa3534",
            "#19be6b",
            "#ff9900",
            "#303133",
            "#909399",
            "#c0c4cc",
        ]
    };

    //初始化vue
    var vmMenu = new Vue({
        el: '.vue-container',
        data: pageData,
        methods: {
            initStyle: function (index) {
                var stylestr = "";
                var data = pageData.editorData[index].style;
                Object.keys(data).map(function (v, i, a) {
                    stylestr += v.replace('_', '-') + ':' + data[v] + ';'
                })
                return stylestr;
            },
            //字体背景的颜色
            initFontColor: function (color) {
                return "background-color:" + color;
            },
            //加粗
            fontWeight: function (index) {
                let num = parseInt(pageData.editorData[index].style.font_weight);
                pageData.editorData[index].style.font_weight = (800 == num ? 400 : 800);
            },
            //字体加大
            fontInc: function (index) {
                let num = parseInt(pageData.editorData[index].style.font_size) + 1
                pageData.editorData[index].style.font_size = num + 'px';
            },
            //字体减小
            fontDec: function (index) {
                let num = parseInt(pageData.editorData[index].style.font_size) - 1
                pageData.editorData[index].style.font_size = num + 'px';
            },
            //删除线
            fontDel: function (index) {
                let val = pageData.editorData[index].style.text_decoration || '';
                pageData.editorData[index].style.text_decoration = (val == 'line-through' ? '' : 'line-through');
            },
            //下划线
            fontLine: function (index) {
                let val = pageData.editorData[index].style.text_decoration || '';
                pageData.editorData[index].style.text_decoration = (val == 'underline' ? '' : 'underline');
            },
            //居中显示
            fontAlign: function (index) {
                let val = pageData.editorData[index].style.text_align || 'left';
                let align = ''
                switch (val) {
                    case 'left':
                        align = 'center'
                        break;
                    case 'center':
                        align = 'right'
                        break;
                    default:
                        align = 'left'
                        break;
                }
                pageData.editorData[index].style.text_align = align;
            },
            //选择字体颜色
            fontSetColor: function (index, color) {
                pageData.editorData[index].style.color = color;
            },
            //上升模块
            itemUp: function (index) {
                if (index > 0) {
                    var itemData = pageData.editorData[index];
                    pageData.editorData.splice(index, 1);
                    pageData.editorData.splice(index - 1, 0, itemData);

                }
            },
            //下降模块
            itemDown: function (index) {
                if (index + 1 < pageData.editorData.length) {
                    var itemData = pageData.editorData[index];
                    pageData.editorData.splice(index, 1);
                    pageData.editorData.splice(index + 1, 0, itemData);

                }
            },
            //删除模块
            itemDel: function (index) {
                pageData.editorData.splice(index, 1);
            },
            //添加一个新的模块
            itemAdd: function (index, type, groupid) {
                var itemData = null;
                switch (type) {
                    case 1:
                        itemData = {
                            mytype: 1,
                            content: "示例文字",
                            style: {
                                'font_size': '18px', 'font_weight': '', 'text_decoration': '', 'text_align': 'center', 'color': '#303133'
                            }
                        };
                        break;
                    case 2:
                        itemData = {
                            mytype: 2,
                            content: "",
                            default: 0
                        };
                        break;
                    case 3:
                        itemData = {
                            mytype: 3,
                            content: ""
                        };
                        break;
                    default:
                        alert('暂不支持');
                        break;
                }
                if (itemData) {
                    if (groupid) itemData.groupid = groupid;
                    pageData.editorData.splice(index, 0, itemData);
                }
            },
            //上传图片
            changeCoverImg: function (event, index) {
                this.$refs[`fileInput${index}`][0].click()
            },
            getFile: function (e,index) {
                console.log(e,index)
                var file = e.target.files[0]
                   lrz(file, {width: 600}, function(results) {
                      $.post("<?php echo url('home/ajax/upload_img'); ?>",{val:results.base64},function(data){
                          pageData.editorData[index].content = data.data.url;
                           pageData.editorData[index].default = 1;
                      },'json');
                   });
//                var reader = new FileReader()
//                reader.readAsDataURlang(file)
//                reader.onload = function (e) {
//                    console.log(this.result);
//                    pageData.editorData[index].content = this.result;
//                    pageData.editorData[index].default = 1;
//                }
            },
            submit:function(){
                let data = JSON.stringify(pageData.editorData)
                console.log(data);
                <?php $func = input('func')?input('func'):'wap_editor';  echo "parent.".$func."(data);var index = parent.layer.getFrameIndex(window.name);parent.layer.close(index); ";  ?>
            },
            //一个用于阻止冒泡的事件
            stopclick: function () {},
        },
        //实例被调用后
        created: function () {
        }
    });
</script>
</html>