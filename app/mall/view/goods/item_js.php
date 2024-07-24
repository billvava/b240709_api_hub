
<script type="text/javascript">
    //注意：选项卡 依赖 element 模块，否则无法进行功能性操作
    var dragstart = 0;
    var swop_element_ed = -1;
    var create_table_by_spec = null;
    var spec_table_data = [];
    var spec_value_temp_id_number = 0;
    var spec_thumb_element;

    function in_array(search,array){
        for(var i in array){
            if(array[i]==search){
                return true;
            }
        }
        return false;
    }
    $(function(){
        layui.use(['element','form'], function(){
            var form = layui.form;
            
             //switch的切换事件
            form.on('select(category_id)', function(e){
                change_category();
            });
            
            //统一规格与多规格切换事件
            function switchSpecType(value)
            {
                var goods_spec_project = $('#goods-spec-project');
                if (value == 2) {
                    $('#add-spec').parent().show();
                    if (goods_spec_project.children().length > 0) {
                        goods_spec_project.parent().show();
                        $('#more-spec-lists').show();
                    }
                    $('#one-spec-lists').hide();
                } else {
                    $('#add-spec').parent().hide();
                    goods_spec_project.parent().hide();
                    $('#one-spec-lists').show();
                    $('#more-spec-lists').hide();
                }
            }
            form.on('radio(spec-type)', function (data) {
                switchSpecType(data.value);
            });

            function getImageWidth(url, callback) {
                var img = new Image();
                img.src = url;
                if (img.complete) {
                    callback(img.width, img.height);
                } else {
                    img.onload = function () {
                        callback(img.width, img.height);
                    }
                }
            }

            function uniq(array){
                var temp = [];
                for(var i = 0; i < array.length; i++){
                    if(temp.indexOf(array[i]) == -1){
                        temp.push(array[i]);
                    }
                }
                return temp;
            }
            //判断字符串是否为空格
            function isEmptyString(str) {
                str = str.replace(/(^\s*)|(\s*$)/g, "");
                if (str.length == 0) {
                    return true;
                }
                return false;
            }
            //数组去重
            function unique(arr){
                var hash=[];
                for (var i = 0; i < arr.length; i++) {
                    if(hash.indexOf(arr[i])==-1){
                        hash.push(arr[i]);
                    }
                }
                return hash;
            }

            //元素交换
            function swop(first, second) {
                html = first.html();
                first.html(second.html());
                second.html(html);
            }
            //笛卡尔积生成
            function cartesianProduct(arr) {
                if (arr.length < 2) return arr[0] || [];
                return [].reduce.call(arr, function (col, set) {
                    let res = [];
                    col.forEach(c => {
                        set.forEach(s => {
                            let t = [].concat(Array.isArray(c) ? c : [c]);
                            t.push(s);
                            res.push(t);
                        })
                    });
                    return res;
                });
            }


            //元素交换
            function swop(first, second) {
                html = first.html();
                first.html(second.html());
                second.html(html);
            }
            //数组去重
            function unique(arr){
                var hash=[];
                for (var i = 0; i < arr.length; i++) {
                    if(hash.indexOf(arr[i])==-1){
                        hash.push(arr[i]);
                    }
                }
                return hash;
            }


            //转换数组格式
            function array_column(data,key) {
                var result = [];
                for(var i in data){
                    result[data[i][key]] = data[i];
                }
                return result;
            }

            function array_unique(arr){
                for(var i=0; i<arr.length; i++){
                    for(var j=i+1; j<arr.length; j++){
                        if(arr[i]==arr[j]){
                            arr.splice(j,1);
                            j--;
                        }
                    }
                }
                return arr;
            }


            //添加规格项目
            function addSpec(value){
                value = value===undefined?'':value;
                var element_spec = $('#goods-spec-project');
                var count = $('.goods-spec').size();
                if (count > 2) {
                    layer.msg('最多添加3个规格项目');
                    return;
                }
                var template_spec = $('#template-spec').html();
                element_spec.append(template_spec.replace('{value}',value));
                $('#goods-spec-project').parent().show();
                form.render('checkbox');
            }
            $('#add-spec').click(function () {
                addSpec();
            });

            //显示或隐藏规格项目删除按钮绑定
            $(document).on('mouseenter', '.goods-spec', function () {
                $(this).find('.goods-spec-del-x').show();
            });
            $(document).on('mouseleave', '.goods-spec', function () {
                $(this).find('.goods-spec-del-x').hide();
            });

            //删除规格项目绑定
            $(document).on('click', '.goods-spec-del-x', function () {
                $(this).parent().remove();
                var goods_spec_project = $('#goods-spec-project');
                if (goods_spec_project.children().length == 0) {
                    goods_spec_project.parent().hide();
                }
            });
           

            //添加或删除规格项目后续操作
            function specValueLater(){
                $('.add-spec-value').each(function(){
                    add_spec_value = $(this);
                    var spec_values = '';
                    add_spec_value.parent().parent().find('.goods-spec-value-input').each(function () {
                        spec_values += $(this).val() + ',';
                    });
                    add_spec_value.parent().find('.spec_values').val(spec_values.substring(0, spec_values.lastIndexOf(',')));

                    var spec_value_ids = '';
                    add_spec_value.parent().parent().find('.goods-sepc-value-id-input').each(function () {
                        spec_value_ids += $(this).val() + ',';
                    });
                    add_spec_value.parent().find('.spec_value_ids').val(spec_value_ids.substring(0, spec_value_ids.lastIndexOf(',')));
                    triggerCreateTableBySepc();
                });
            }

            //添加规格项
            function addSpecvalue(add_spec_value,spec,spec_id){
                var template_spec_value = $('#template-spec-value').html();
                var template_spec_value_html = template_spec_value.replace('{spec_value_temp_id}', spec_value_temp_id_number--);
                template_spec_value_html = template_spec_value_html.replace('{spec_value_id}',spec_id);
                template_spec_value_html = template_spec_value_html.replace('{spec_value}', spec)
                add_spec_value.parent().before(template_spec_value_html);
                var div = add_spec_value.parent().parent().parent().parent();
                var status = div.find('.batch-spec-image-switch').first().is(':checked');
                if(status){
                    div.find('.batch-spec-image').show();
                }else{
                    div.find('.batch-spec-image').hide();
                }
            }
            form.on('switch(batch-spec-image-switch)', function(data){
                var status = data.elem.checked;
                $('.batch-spec-image-switch').prop("checked",false);
                if(status) {
                    $('.batch-spec-image').hide();
                    $(this).parent().parent().parent().parent().find('.batch-spec-image').show();
                    $(this).prop("checked",true);
                }else{
                    $(this).parent().parent().parent().parent().find('.batch-spec-image').hide();
                }
                form.render('checkbox');
            });

            //批量添加规格项绑定
            $(document).on('click', '.add-spec-value', function () {
                var add_spec_value = $(this);
                layer.prompt({title: '输入规格值，多个请换行', formType: 2}, function (text, index) {
                    layer.close(index);
                    var specs = text.split('\n');
                    for (var i in specs) {
                        specs[i] = specs[i].trim();
                    }
                    specs = unique(specs);
                    var added_specs = [];
                    add_spec_value.parent().parent().find('.goods-spec-value-input').each(function () {
                        added_specs.push($(this).val().trim());
                    });
                    for (var i in specs) {
                        var spec = specs[i].trim();
                        if (spec == '' || in_array(spec, added_specs)) {
                            //已存或为空的不添加
                            continue;
                        }
                        addSpecvalue(add_spec_value,spec,0);
                    }
                    specValueLater();
                });
            });


            //删除规格项
            $(document).on('click', '.goods-spec-value-del-x', function () {
                var add_spec_value = $(this).parent().parent().find('.add-spec-value').first();
                $(this).parent().remove();
                specValueLater();
            });
            $(document).on('mouseenter', '.goods-spec-img-div', function () {
                $(this).find('.goods-spec-img-del-x').show();
            });
            $(document).on('mouseleave', '.goods-spec-img-div', function () {
                $(this).find('.goods-spec-img-del-x').hide();
            });
            $(document).on('mouseenter', '.goods-spec-img-div', function () {
                $(this).find('.goods-one-spec-img-del-x').show();
            });
            $(document).on('mouseleave', '.goods-spec-img-div', function () {
                $(this).find('.goods-one-spec-img-del-x').hide();
            });
            $(document).on('click', '.goods-spec-img-del-x', function () {
                var key = 'spec_image[]' + $(this).parent().parent().parent().attr('spec-value-temp-ids');
                $(this).parent().html('<input type="hidden" name="spec_image[]"><img src="__STATIC__/admin/images/upload.png" class="goods-spec-img-add">');
                spec_table_data[key] = '';
            });
            $(document).on('click', '.goods-one-spec-img-del-x', function () {
                $(this).parent().html('<input type="hidden" name="one_spec_image"><img src="__STATIC__/admin/images/upload.png" class="goods-one-spec-img-add">');
            });
            
            

             //批量填充规格图片
            $(document).on('click', '.batch-spec-image', function () {
                var t = $(this);
                spec_thumb_element = t;
                layer.open({
                    type: 2,
                    title: '图片选择器',
                    closeBtn: 1,
                    shade: [0.3],
                    shadeClose: true,
                    area: ['90%', '90%'],
                    maxmin: true,
                    shift: 1,
                    content: [ "<?php echo url(ADMIN_URL.'/Files/new_imgs',array('name'=>'规格图片','callback'=>'goods_spec_image','field'=>'goods_spec_image')); ?>"],
                });
            });
            //多规格充规格图片
            $(document).on('click', '.goods-spec-img-add', function () {
                var t = $(this);
                spec_thumb_element = t;
                layer.open({
                    type: 2,
                    title: '图片选择器',
                    closeBtn: 1,
                    shade: [0.3],
                    shadeClose: true,
                    area: ['90%', '90%'],
                    maxmin: true,
                    shift: 1,
                    content: [ "<?php echo url(ADMIN_URL.'/Files/new_imgs',array('name'=>'规格图片','callback'=>'goods_spec_image','field'=>'goods_duo_spec_image')); ?>"],
                });
            });
            
            //单规格充规格图片
            $(document).on('click', '.goods-one-spec-img-add', function () {
                var t = $(this);
                spec_thumb_element = t;
                layer.open({
                    type: 2,
                    title: '图片选择器',
                    closeBtn: 1,
                    shade: [0.3],
                    shadeClose: true,
                    area: ['90%', '90%'],
                    maxmin: true,
                    shift: 1,
                    content: [ "<?php echo url(ADMIN_URL.'/Files/new_imgs',array('name'=>'规格图片','callback'=>'goods_spec_image','field'=>'goods_one_spec_image')); ?>"],
                });
            });
            
            
          
            

            //批量填充
            $('.batch-spec-content').click(function(){
                var title = $(this).text();
                var input_name = $(this).attr('input-name');
                layer.prompt({
                    formType: 3
                    ,title: '批量填写'+title
                },function(value, index, elem){
                    $('input[name="'+input_name+'[]"]').val(value);
                    //保存值到本地
                    $('#more-spec-lists-table input').each(function(){
                        var key = $(this).attr('name') + $(this).parent().parent().attr('spec-value-temp-ids');
                        spec_table_data[key] = $(this).val();
                    });
                    layer.close(index);
                });
            });

            //显示或隐藏规格项删除按钮
            $(document).on('mouseenter', '.goods-spec-value', function () {
                $(this).find('.goods-spec-value-del-x').show();
            });
            $(document).on('mouseleave', '.goods-spec-value', function () {
                $(this).find('.goods-spec-value-del-x').hide();
            });
            
            //规格生成表格
            createTableBySepc = function () {
                if ($('.goods-spec').size() <= 0) {
                    $('#more-spec-lists').hide();
                    return;
                }
                $('#more-spec-lists').show();
                var table_title = [];
                var table_data = [];
                var spec_value_temp_arr = [];
                var i = 0;
                var th_html = $('#template-spec-table-th').html();
                var tr_html = $('#template-spec-table-tr').html();

                //遍历规格项目
                $('.goods-spec').each(function () {
                    var spec_name = $(this).find('.spec_name').first().val();
                    if (isEmptyString(spec_name)) {
                        return true;
                    }
                    table_title[i] = spec_name;
                    table_data[i] = [];
                    spec_value_temp_arr[i] = [];
                    var j = 0;
                    $(this).find('.goods-spec-value .goods-spec-value-input').each(function () {
                        var spec_value = $(this).val();
                        var spec_value_temp_id = $(this).attr('spec-value-temp-id');
                        if (isEmptyString(spec_value)) {
                            return true;
                        }
                        table_data[i][j] = spec_value;
                        spec_value_temp_arr[i][j] = spec_value_temp_id;
                        j++;
                    });
                    i++;
                });

                table_html = '';

                //表格头部组装
                spec_th_html = '';
                for (var i in table_title) {
                    spec_th_html += '<th>' + table_title[i] + '</th>';
                }
                table_html = th_html.replace('{spec_th}', spec_th_html);

                spec_value_temp_arr = cartesianProduct(spec_value_temp_arr);
                table_data = cartesianProduct(table_data);
                for (var i in table_data) {
                    var spec_tr_html = '';
                    var tr_name_arr = [];
                    var specs = '';
                    if (Array.isArray(table_data[i])) {
                        //根据规格创建tr的id
                        var spec_value_temp_ids = '';
                        for(var j in spec_value_temp_arr[i]){
                            spec_value_temp_ids += spec_value_temp_arr[i][j]+',';
                        }
                        spec_value_temp_ids = spec_value_temp_ids.substring(0, spec_value_temp_ids.lastIndexOf(','));
                        spec_tr_html += '<tr spec-value-temp-ids="'+spec_value_temp_ids+'">';

                        for (var j in table_data[i]) {
                            spec_tr_html += '<td>' + table_data[i][j] + '</td>';
                            tr_name_arr[j] = table_data[i][j];
                            specs += table_data[i][j].replace(',', '') + ',';
                        }
                    } else {
                        var spec_value_temp_ids = spec_value_temp_arr[i];
                        spec_tr_html = '<tr spec-value-temp-ids="'+spec_value_temp_ids+'">';
                        spec_tr_html += '<td>' + table_data[i] + '</td>';
                        specs += table_data[i].replace(',', '') + ',';
                    }
                    specs = specs.substring(0, specs.lastIndexOf(','));
                    spec_tr_html += '<td style="display: none"><input type="hidden" name="spec_value_str[]" value="' + specs + '"><input type="hidden" name="item_id[]" value=""></td>';
                    table_html += tr_html.replace('{spec_td}', spec_tr_html);

                }
                $('#more-spec-lists-table').html(table_html);
                setTableValue();
            }
            //触发规格生成表格
            function triggerCreateTableBySepc() {
                clearTimeout(create_table_by_spec);
                create_table_by_spec = setTimeout(createTableBySepc, 1000);
            }

            //各种触发生成规格事件
            triggerCreateTableBySepc();
            $('#add-spec').click(function () {
                triggerCreateTableBySepc();
            });
            $(document).on('click', '.goods-spec-del-x', function () {
                triggerCreateTableBySepc();
            });
            $(document).on('click', '.add-spec-value', function () {
                triggerCreateTableBySepc();
            });
            $(document).on('click', '.goods-spec-value-del-x', function () {
                triggerCreateTableBySepc();
            });
            $(document).on('input', '.goods-spec input', function () {
                triggerCreateTableBySepc();
                specValueLater();
            });

            //规格数据本地保存
            $(document).on('input', '#more-spec-lists-table input', function () {
                var key = $(this).attr('name') + $(this).parent().parent().attr('spec-value-temp-ids');
                spec_table_data[key] = $(this).val();
            });




         

        //------------------------编辑页面----------------------------------

        var goods_info = <?php echo json_encode($specInfo); ?>;
        
        var spec_type = '{$goodsInfo.spec_type}';
        $("input[name=spec_type][value="+ spec_type +"]").prop('checked',"true");   //商品规格
        form.render();
        switchSpecType(spec_type);
        if(spec_type == 1){
            var template_goods_image = $('#template-goods-image').html();
            if(goods_info['item'][0]['image']){
                $('.goods-one-spec-img-add').parent().html('<input name="one_spec_image" type="hidden" value="' + goods_info['item'][0]['image'] + '"><a class="goods-one-spec-img-del-x">x</a><img class="goods-spec-img" src="' + goods_info['item'][0]['image'] + '">');
            }
            <?php   foreach ($goods_spec_field as $v) { ?>
           $('input[name="one_<?php echo $v['field']; ?>"]').val(goods_info['item'][0]['<?php echo $v['field']; ?>']);
           <?php } ?>
        }
        if(spec_type == 2) {
            for(var i in goods_info['spec']){
                addSpec(goods_info['spec'][i]['name']);
                var spes_values = goods_info['spec'][i]['values'];
                for(var j in  spes_values){
                    addSpecvalue($('.add-spec-value').eq(i),spes_values[j]['value'],spes_values[j]['id']);
                }

            }
            for(var i in goods_info['spec']){
                $('input[name="spec_id[]"]').eq(i).val(goods_info['spec'][i]['id']);
            }
            specValueLater();
            createTableBySepc();
            for(var i in goods_info['item']){
                $('#more-spec-lists-table tbody tr').each(function() {
                    var spec_value_str = $(this).find('input[name="spec_value_str[]"]').first().val();
                    if(spec_value_str == goods_info['item'][i]['spec_value_str']){
                        spec_value_temp_ids = $(this).attr('spec-value-temp-ids');
                        spec_table_data["spec_image[]"+spec_value_temp_ids] = goods_info['item'][i]['image'];
                         <?php   foreach ($goods_spec_field as $v) { if($v['field'] == 'spec_image'){ continue; }  ?>
                         spec_table_data["<?php echo $v['field']; ?>[]"+spec_value_temp_ids] = goods_info['item'][i]['<?php echo $v['field']; ?>'];            
                        <?php } ?>
                        spec_table_data["item_id[]"+spec_value_temp_ids] = goods_info['item'][i]['id'];
                        return false;
                    }
                });


            }
            setTableValue();
        }
        form.render();
            //------------------------编辑页面----------------------------------

            


        });


        //加载属性组
        get_attr_data();
    })
    
    //动态渲染已保存的值
    function setTableValue() {
        $('#more-spec-lists-table').find('input').each(function () {
            var key = $(this).attr('name') + $(this).parent().parent().attr('spec-value-temp-ids');
            if(spec_table_data[key]!== undefined){
                $(this).val(spec_table_data[key]);
            }
        });
        $('.goods-spec-img-div').each(function(){
            var key = $(this).parent().parent().attr('spec-value-temp-ids');
            if(spec_table_data["spec_image[]"+key]){
                $(this).html('<input name="spec_image[]" type="hidden" value="' + spec_table_data["spec_image[]"+key] + '"><a class="goods-spec-img-del-x">x</a><img class="goods-spec-img" src="' + spec_table_data["spec_image[]"+key] + '">');
            }
        });
    }
    //操作保存
    function save(){
        load();
        var html = ue_content.getContent();
        html = encodeURIComponent(html);
        ajax('{:url('Goods/item')}',$('#goods_from').serialize()+"&content="+html,function(data){
            
        });
    }

     //接收图片
    function goods_spec_image(field,src,file){
        var temp_id = spec_thumb_element.prev().attr('spec-value-temp-id');
        var uri = src[0];
        if(field == 'goods_one_spec_image'){
            spec_thumb_element.hide();
            var key = spec_thumb_element.parent().parent().parent().attr('spec-value-temp-ids');
            spec_table_data["spec_image[]"+key] = uri;//保存图片地址
            spec_thumb_element.parent().html('<input name="one_spec_image" type="hidden" value="' + uri + '"><a class="goods-one-spec-img-del-x">x</a><img class="goods-spec-img" src="' +uri + '">');
        }
        if(field == 'goods_duo_spec_image'){
             spec_thumb_element.hide();
            var key = spec_thumb_element.parent().parent().parent().attr('spec-value-temp-ids');
            spec_table_data["spec_image[]"+key] = uri;//保存图片地址
            spec_thumb_element.parent().html('<input name="spec_image[]" type="hidden" value="' +uri + '"><a class="goods-spec-img-del-x">x</a><img class="goods-spec-img" src="' + uri + '">');
        }
        
        if(field == 'goods_spec_image'){
            $('input[name="spec_image[]"]').each(function(){
                var temp_ids = $(this).parent().parent().parent().attr('spec-value-temp-ids');
                temp_ids_arr = temp_ids.split(',');
                var key = $(this).attr('name') + temp_ids;
                console.log(temp_ids_arr);
                console.log(temp_id);
                if(in_array(temp_id,temp_ids_arr)) {
                    spec_table_data[key] = uri;
                }
            });
            setTableValue();
        }
       
    }  
    
    //获取子属性
    function get_attr_data(){
        var v = $("#attr_id").val();
        $("#is_up_attr").val(0);
        if(isNotEmpty(v)==false){
            $("#attr_child_div").html("");
        }else{
            $.post('{:url('get_attr_data')}',{attr_id:v,goods_id:$("#goods_id").val()},function(data){
                $("#attr_child_div").html(data);
                $("#is_up_attr").val(1);
            });
        }
    }
    
     //选择栏目
    function change_category() {
        var t = $("#category_id");
        var v = t.find("option:selected").val();
        var category_id = t.find("option:selected").attr('category_id');
        if (t.find("option[pid=" + category_id + "]").length > 0) {
            layer.alert('不能选择有子类目的类目');
            t.val("");
            form.render('select');
            return false;
        }
    }
</script>