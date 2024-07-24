{__NOLAYOUT__}
<!DOCTYPE html>
<html>
<head>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="blue">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>上传</title>
<script src="__LIB__/jquery.min.js" type="text/javascript"></script>
</head>
<style>
    body{ maigin:0;padding:0;}
#clipArea {
    <?php if(is_mobile()){echo ' height: 300px;';}else{echo ' height: 300px;';  } ?>
   
}
#file{
    padding: 20px;
    margin-bottom:10px;
    width:100%;
    border:1px solid #ccc;
}
#clipBtn {
    width: 100%;
    margin: 0;
    height: 40px;
    background: #60c53e;
    border: none;
    color: #FFF;
    font-size: 16px;
}
#view {
	margin: 0 auto;
	width: 200px;
	height: 200px;
}
</style>
<body >
<article class="zzsc-container">
        <div id="clipArea"></div>
        <input type="file" id="file" accept="image/*" >
        <button id="clipBtn" style=""  type="button" >确定</button>
        <div id="view"></div>
</article>
<script src="__LIB__/clip/iscroll-zoom.js" type="text/javascript"></script>
<script src="__LIB__/clip/hammer.js" type="text/javascript"></script>
<script src="__LIB__/clip/jquery.photoClip.js" type="text/javascript"></script>
<script>
    var is_load = 1;
$(function(){
        
    $("#clipArea").photoClip({
            width: '<?php echo $in['width']?$in['width']:100; ?>',
            height: '<?php echo $in['height']?$in['height']:100; ?>',
            file: "#file",
            ok: "#clipBtn",
            loadStart: function() {
                    console.log("照片读取中");
            },
            loadComplete: function() {
                    console.log("照片读取完成");
            },
            clipFinish: function(dataURL) {
                 var main=$(window.parent.document);
                    if(is_load==0){
                        return ;
                    }
                    is_load = 0;
                    $.post("<?php echo url('home/ajax/upload_img'); ?>",{val:dataURL},function(data){
                        is_load = 1;
                       if(data.status=='1'){
                            
                <?php if($in['func']){ ?>
                <?php echo "parent.".$in['func']."(";?>data.data.file<?php echo ",";?>data.data.url<?php echo ")"; ?>;
                <?php } ?>     
                             var index = parent.layer.getFrameIndex(window.name); 
                             parent.layer.close(index);   
                       }else{
                           alert(data.info);
                       }
                      
                    },'json');
                   
            }
    });
})
</script>
</body>
</html>
