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
   min-height: 50px;
   width: 100%;
   background: #b9b7b7;
}
#file{
    padding: 20px;
    margin-bottom:10px;
    width:90%;
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

</style>
<body >
    <div id="clipArea"><img style="width: 100%;    border: 1px solid #ccc; display: none;" /></div>
        <input type="file"   id="file" onchange="view(this)" accept="image/*" >
        <button id="clipBtn" type="button" onclick="sub()" style="">确定</button>
        
<script src="__LIB__/localResizeIMG/mobileFix.mini.js" type="text/javascript"></script>
<script src="__LIB__/localResizeIMG/exif.js" type="text/javascript"></script>
<script src="__LIB__/localResizeIMG/lrz.js" type="text/javascript"></script>
<div id="lrz_upload_marsk" style=" display: none; width: 100%; height: 100%; position: fixed; top: 0; left: 0;z-index: 999999; opacity: 0.7; background: url(__PUBLIC__/img/loading.gif) center center no-repeat #000;"></div>
<script>
var base64='';    
 var is_load = 1;
function sub(){
  if(base64==''){
      alert('还没上传图片');
      return;
  }
    if(is_load==0){
        return ;
    }
    is_load = 0;
     $("#lrz_upload_marsk").show();
    $.post("<?php echo url('home/ajax/upload_img'); ?>", {val: base64}, function(data) {
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
       $("#lrz_upload_marsk").hide();
    },'json');
}    

function view(obj){
     lrz(obj.files[0], {width: <?php echo $in['width']?$in['width']:'600'; ?>}, function(results) {
     $("#clipArea img").attr('src',results.base64);
     base64 = results.base64;
     $("#clipArea img").show();
    });
}
    
</script>
</body>
</html>
