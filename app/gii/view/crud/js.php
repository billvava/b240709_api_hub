<script>
$(function(){
    $("#tableName").blur( function () { 
         var t  = $("#tableName").val();
         $("#cName").val(t);
         $("#modelName").val(t);
         $("#viewName").val(t);
         $("#tableNameMsg").text('');
        $.post('check_crud',{name:'tableName',val:t},function(data){
              $("#last_edit,#index_edit").html("");
            if(data.status==0){
                $("#tableNameMsg").text(data.info);
            }else{
               $("#tableNameMsg").text('');
               $("#sort_rule").val(data.data.sort);

               // for(var i in data.data.fast_fields){
               //     var item = data.data.fast_fields[i];
               //     $("#last_edit").append("<label> <input type='checkbox'  name='fast_fields[]' value='"+item.field+" '  >"+item.name+" </label>&nbsp;");
               // }

                $("#last_edit").html(data.data.fast_html);
               
               for(var i in data.data.all_fields){
                   var gitem = data.data.all_fields[i];
                   $("#index_edit").append("<label> <input type='checkbox' checked='' lay-ignore  name='index_fields[]' value='"+gitem.field+" '  >"+gitem.name+" </label>&nbsp;");
               }
               layui.form.render();
               $("#field_form").html(data.data.sel_html);
                select_load();
               searchtype_load();
            }

        },'json');
    });
    $("#moduleName").blur( function () { 
         $("#moduleNameMsg").text('');
        $.post(root+'/crud/check_crud',{name:'moduleName',val:$("#moduleName").val()},function(data){
            if(data.status==0){
                $("#moduleNameMsg").text(data.info);
            }else{
                 $("#moduleNameMsg").text('');
            }

        },'json');
    });
    //复杂表单
    $("#fzbd_type_btn").click(function(){
        var t = $(this);
        var v = $('#fzbd_type').val();
        if(v){
            var l = $("."+v).length;
            $.post('<?php echo url('fzbd_btn'); ?>',{type:v,tb:$('#tableName').val(),index:l},function(data){
                $("#fzbd_type_div").append(data);
                layui.form.render();
            });
        }
    })


    $('#field_form').on('change','.select_x',function () {
        var ff=$(this).attr('ff');
        if($(this).val()=='selec'){
            $('#select_'+ff).show();
        }else{
            $('#select_'+ff).hide();
        }

    });

    $('#last_edit').on('change','.searchtype',function () {
        var ff=$(this).attr('ff');
        if($(this).val()=='selec'){
            $('#searchbox_'+ff).show();
        }else{
            $('#searchbox_'+ff).hide();
        }

    });

    function select_load(){
        $('.select_x').each(function (){
            var ff=$(this).attr('ff');
            if($(this).val()=='selec'){
                $('#select_'+ff).show();
            }else{
                $('#select_'+ff).hide();
            }
        })
    }

    function searchtype_load(){
        $('.searchtype').each(function (){
            var ff=$(this).attr('ff');
            if($(this).val()=='selec'){
                $('#searchbox_'+ff).show();
            }else{
                $('#searchbox_'+ff).hide();
            }
        })
    }

})
function change_app(rand){
    var v = $(".select_model_app"+rand).val();
    $.post('<?php echo url('load_model_file'); ?>',{app:v},function(data){
        $(".select_model_file"+rand).html(data);
        layui.form.render();
    });
}
</script>