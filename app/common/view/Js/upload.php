{__NOLAYOUT__}
<script type="text/javascript">
    var upload_id_<?php echo $field; ?> = new plupload.Uploader({
        runtimes: 'gears,html5,html4,silverlight,flash',
        browse_button: '<?php echo $field; ?>',
        url: "<?php echo $server_url; ?>",
        flash_swf_url:'__ROOT__/public/lib/plupload/Moxie.swf',
        silverlight_xap_url:  '__ROOT__/public/lib/plupload/Moxie.xap")',
        filters: {
            max_file_size: '1m',
            mime_types: [
                {title: "*", extensions: "<?php echo $ext; ?>"}
            ]
        },
        init: {
            PostInit: function() {
            },
            //选择图片后
            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    upload_id_<?php echo $field; ?>.start();
                    return false;
                });
            },
            //上传中
            UploadProgress: function(up, file) {
                $("#<?php echo $field; ?>_progress").text(file.percent + "%");
            },
            //异常处理
            Error: function(up, err) {
                alert(err.message);
            },
            //图片上传后
            FileUploaded: function(up, file, res) {
                var str = res.response;
                var json = eval("(" + str + ")");
                if (json.status == '0') {
                    alert(json.info);
                } else {
                    var fn_name = "<?php echo $field; ?>_success";
                    var url = json.info;
                    $("#<?php echo $field; ?>_input").val(json.data.file);
                    $("#<?php echo $field; ?>_img").attr('src',json.data.url);
                    if(isExitsFunction(fn_name)==true){
                        eval(""+fn_name+"(url)");
                    }

                }
            }

        }
    });
    upload_id_<?php echo $field; ?>.init();
</script>