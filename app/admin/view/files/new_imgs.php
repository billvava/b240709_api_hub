
<style>
    .upload-image-div{
        height: 80px;
        width: 80px;
        float: left;
        opacity: 1;
        position: relative;
        border:1px dashed #a0a0a0;
        background-repeat: no-repeat;
        background-position: 50% 35%;
        margin: 4px;
        text-align: center;
    }
    .upload-image-a{
        cursor: pointer;
        position: absolute;
        z-index: 100;
        top: 58px;
        right: -10%;
        width: 100px;
        height: 20px;
        font-size: 8px;
        line-height: 16px;
        text-align: center;
        border-radius: 10px;
        color: #4e8bff;
    }
    .upload-image-a:hover
    {
        color: #0641cb;
    }
    .upload-video-div{
        height: 80px;
        width: 80px;
        float: left;
        opacity: 1;
        position: relative;
        border:1px dashed #a0a0a0;
        background-repeat: no-repeat;
        background-position: 50% 35%;
        margin: 4px;
        text-align: center;
    }
    .upload-video-a{
        cursor: pointer;
        position: absolute;
        z-index: 100;
        top: 58px;
        right: -10%;
        width: 100px;
        height: 20px;
        font-size: 8px;
        line-height: 16px;
        text-align: center;
        border-radius: 10px;
        color: #4e8bff;
    }
    .upload-video-a:hover
    {
        color: #0641cb;
    }


    .like-layui-collapse {
        border-width: 1px;
        border-style: dashed;
        border-radius: 2px;
        border-color: #c4c4c4;
    }
    .like-layui-colla-title {
        position: relative;
        height: 42px;
        line-height: 42px;
        padding: 0 15px 0 35px;
        color: #333;
        background-color: #ffffff;
        cursor: pointer;
        font-size: 14px;
        overflow: hidden;
    }
    .like-layui-colla-title:hover {
        color: #2ba1fc;
    }


    .like-layui-form-label{
        margin: 20px;
        padding-left: 5px;
        border-left: solid #2ba1fc 8px;
        text-align: left;
        width: 100px
    }
    .like-layui-elem-quote {
        margin-bottom: 10px;
        padding: 15px;
        line-height: 22px;
        border-left: 5px solid #2ba1fc;
        border-radius: 0 2px 2px 0;
        background-color: #f2f2f2;
    }

    .layui-form-select dl dd.layui-this {
        background-color: #1E9FFF;
    }

    .layui-form-onswitch {
        border-color: #1E9FFF;
        background-color: #1E9FFF;
    }

    .layui-laypage .layui-laypage-curr .layui-laypage-em {
        position: absolute;
        left: -1px;
        top: -1px;
        padding: 1px;
        width: 100%;
        height: 100%;
        background-color: #1E9FFF;
    }

    .info_footer {
        text-align: center;
        bottom: 20px;
        left: 40%;
        font-size:14px;
        color:rgba(112,112,112,1);
        font-weight:400;
        margin-bottom: 12px;
    }
    .layui-icon-file:before {
        content: "\e623";
    }

    .add-border{
        border: 2px solid #1E9FFF;
    }

    .white-border{
        border: 2px solid #ffffff;
    }

    .img-box{
        margin-top: 10px
    }


    .img-border{
        width: 130px;
        height: 130px;
        box-sizing: border-box;
        padding: 5px;
    }

    .img-lists{
        margin-bottom: 15px
    }

    .used-btn{
        padding: 10px
    }

</style>
<div class="img-box" >
    <div style="padding:10px; overflow: hidden">
<!--        <button id="del"  type="button"  class="pear-btn pear-btn-sm pear-btn-danger" style="float: right; margin-right: 10px;"><i class="layui-icon">&#xe640;</i>删除图片
        </button>-->
        <button id="upload" type="button" class="pear-btn pear-btn-sm pear-btn-normal" style="float: right; margin-right: 10px;"><i class="layui-icon">&#xe67c;</i>上传图片
        </button>

        <button id="used"  type="button"  class="pear-btn pear-btn-sm" style="float: left; margin-left: 10px;"><i class="layui-icon">&#xe605;</i>使用选中图片
        </button>
    </div>
    <!-- COS大文件上传 -->
<!--    <div style="padding:10px; overflow: hidden">-->
<!--        <button id="submitBtn" type="button" class="pear-btn pear-btn-sm pear-btn-normal" style="float: right; margin-right: 10px;">确定上传</button>-->
<!--        <input id="fileSelector" type="file" value="选择文件" style="float: right; margin-right: 10px;">-->
<!--    </div>-->

    <!-- OSS大文件上传 -->
<!--    <div style="padding:10px; overflow: hidden">-->
<!--    <div style="height: 35px;">-->
<!--        <button id="postfiles" type="button" class="pear-btn pear-btn-sm pear-btn-normal" style="float: right; margin-right: 10px;">确定上传</button>-->
<!--        <button id="selectfiles" type="button" class="pear-btn pear-btn-sm pear-btn-normal" style="float: right; margin-right: 10px;">选择文件</button>-->
<!--    </div>-->
<!--     <div id="ossfile" style=" float: right;height: 35px;">-->
<!--        </div>-->
<!--    </div>-->

    <div style="color: #a0a0a0"></div>

    <div class="layui-card-body">
        <div class="file-body layui-form">
            <ul id="lists" class="layui-row fm_body layui-col-space10 img-lists">
                {foreach name='images' item='v'}
                <?php
                $show_img = get_img_url($v['file'],$v['source'],['type'=>'resize','width'=>100]);
                if($v['type'] == 'video'){
                    $show_img = get_img_url($v['file'],'video_thumb');
                }
                ?>
                <li style="display:inline-block">
                    <div align="center" class="white-border img-border">
                        <img data-id="{$v.id}" data-type="{$v.type}"  file-src='{$v.file}'  real-src="<?php echo  get_img_url($v['file'],$v['source']); ?>" src="<?php echo $show_img; ?>" width="100" height="100">
                        <p class="layui-elip" style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;width: 100px">{$v.name}</p>
                    </div>
                </li>
                {/foreach}

            </ul>
            <?php if(!$images){ ?>
                <p style=" text-align: center;">暂无数据</p>
            <?php } ?>

            <hr class="layui-bg-gray">

            <div class="layui-col-sm10">
                <div id="page">{$show|raw}</div>
            </div>

            <!-- <div class="layui-col-sm2 used-btn">
                <button id="used" class="pear-btn pear-btn-sm pear-btn-normal">使用选中图片</button>
            </div> -->
        </div>
    </div>
</div>
{include file="files/new_imgs_js" /}
{include file="files/cos_js" /}
{include file="files/oss_js" /}

</body>
