<style>
    .items a{ cursor: pointer;}

</style>
<?php   $defaultValue = $model->defaultValue(); ?>
<form class="layui-form"  action="{:url('item')}" method="post">

    <!--  【id】 start  !-->
    <input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
    <!--  【id】 end  !-->
    <!--  【name】 start  !-->
    <?php echo form_input(array('field'=>'name','fname'=>'名称','defaultvalue'=>$info?$info['name']:'',)); ?>

    <?php echo form_input(array('field'=>'remark','fname'=>'备注','defaultvalue'=>$info?$info['remark']:'','msg'=>'例如：仅限XX使用')); ?>


    <!--  【name】 end  !-->
    <!--  【money】 start  !-->
    <?php echo form_input(array('field'=>'money','fname'=>'面值','defaultvalue'=>$info?$info['money']:'','msg'=>'面值只能是数值，0.01-10000，限2位小数')); ?>
    <!--  【money】 end  !-->
    <!--  【base_money】 start  !-->
    <!--  【base_money】 end  !-->

    <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
        <label class="layui-form-label">使用门槛</label>
        <div class="layui-input-block">
            <div>
                <input type="radio" name="is_base_money" <?php if(!$info || $info['base_money']==0){echo "checked=''";} ?> value="0" title="无门槛" >

                <input type="radio" name="is_base_money" <?php if($info['base_money']>0){echo "checked=''";} ?>  value="1" title="满" ><input type="text" value="{$info.base_money}" name="base_money" class=" w100 layui-input inline"> <span style="font-size: 14px;" class="  inline">元可用</span>
            </div>
            <div class="x-a mt5"></div>
        </div>
    </div>

    <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
        <label class="layui-form-label">有效期</label>
        <div class="layui-input-block">
            <div>
                <p><input type="radio" name="end_type" value="1" <?php if($info['end_type']==1){echo "checked=''";} ?>  title="日期范围" ><input type="text" placeholder="截至此时到期" readonly="" class=" layui-input jeDateTime w200 inline" name="end" value="{$info.end}"  ></p>

                <p><input type="radio" name="end_type" value="2" <?php if(!$info || $info['end_type']==2){echo "checked=''";} ?>  title="固定天数" ><input type="text" placeholder="领取后到期天数" class=" layui-input  w200 inline" name="day"  value="{$info.day}"  onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"   ></p>
            </div>
            <div class="x-a mt5"></div>
        </div>
    </div>


    <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
        <label class="layui-form-label">领取机制</label>
        <div class="layui-input-block">
            <div>
                <?php foreach($coupon_type as $k=>$v){ ?>
                    <input type="radio" name="type" value="{$k}" <?php if($info['type']==$k  || (!$info && $k==1)  ){echo "checked=''"; } ?>  title="{$v}" >
                <?php } ?>
            </div>
            <div class="x-a mt5"></div>
        </div>
    </div>

    <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
        <label class="layui-form-label">使用范围</label>
        <div class="layui-input-block">
            <div>
                <?php foreach($coupon_range as $k=>$v){ ?>
                    <input type="radio" name="range" value="{$k}"  lay-filter="range" <?php if($info['range']==$k || (!$info && $k==1)  ){echo "checked=''"; } ?>  title="{$v}" >
                <?php } ?>

            </div>
            <div class="x-a mt5"></div>
        </div>
    </div>

    <div id="goods_id_div"  style=" display: none">
        <input type="hidden" value="{$info.goods_id}" name="goods_id" id="goods_id" />
        <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
            <label class="layui-form-label">选择商品</label>
            <div class="layui-input-block">
                <div>
                    <select name="" id="goodscid" lay-ignore  class="inline w200">
                        <option value="">请输入商品名称</option>
                    </select>
                    <a class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="add_goods()"> + 确定</a>
                </div>
                <div class="x-a mt5"></div>
            </div>
        </div>

        <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <div>
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <td>系统编号</td>
                            <td>商品</td>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="x-a mt5"></div>
            </div>
        </div>
    </div>
    <div id="category_id_div" style=" display: none">
        <?php $cs = (new \app\mall\model\GoodsCategory())->getChild() ?>
        <input type="hidden" value="{$info.category_id}" name="category_id" id="category_id" />
        <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
            <label class="layui-form-label">选择分类</label>
            <div class="layui-input-block">
                <div>
                    <select name="" id="category_id_select" lay-ignore  class="inline" style=" width: 400px;">
                        <option value="">选择</option>
                        <?php foreach($cs   as $v){ ?>
                            <option value="{$v.category_id}">{$v.name}</option>
                        <?php } ?>
                    </select>
                    <a class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="add_cate()">确定</a>
                </div>
                <div class="x-a mt5"></div>
            </div>
        </div>
        <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_cate_id">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <div>
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <td>系统编号</td>
                            <td>分类</td>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="x-a mt5"></div>
            </div>
        </div>
    </div>

    <?php echo submit(); ?>
</form>

<script type="text/html" id="goods_tpl">
    <tr class="goods_id_<%:=goods_id%>">
        <td><%:=goods_id%></td>
        <td><%:=name%></td>
        <th><a class="x-a" href="javascript://" onclick="del_goods_id('<%:=goods_id%>')">删除</a></th>
    </tr>
</script>
<script type="text/html" id="cate_tpl">
    <tr>
        <td><%:=category_id%></td>
        <td><%:=name%></td>
    </tr>
</script>
<script src="__LIB__/template.js" type="text/javascript"></script>
<script src="__LIB__/select2/js/select2.full.min.js" type="text/javascript"></script>
<link href="__LIB__/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    function del_goods_id(goods_id){
        $(".goods_id_"+goods_id).remove();
        var v =  $("#goods_id").val();
        var arr = v.split(',');
        var new_arr = [];
        for(var i in arr){
            if(goods_id != arr[i]){
                new_arr.push(arr[i]);
            }
        }
        $("#goods_id").val( new_arr.join(','));

    }

    function add_cate(){
        var t = $("#category_id_select");
        var res= t.select2("data");
        var arr = [];
        $("#contentshow_cate_id tbody").html('');
        for(var i in res){
            var item = res[i];
            if(item.id!=''){
                arr.push(item.id);
                var render = template($("#cate_tpl").html(), {
                    category_id:item.id,
                    name:item.text,
                });
                $("#contentshow_cate_id tbody").append(render);
            }
        }
        $("#category_id").val( arr.join(','));
    }

    function add_goods(){
        var t = $("#goodscid");
        var res= t.select2("data")[0];
        if(res.id!=''){
            var r = up_goods_id(res.id);
            if(r==true){
                var render = template($("#goods_tpl").html(), {
                    goods_id:res.id,
                    name:res.text,
                });
                $("#contentshow_goods_id tbody").append(render);
            }else{
                layer.alert('已经添加这个商品了');
            }
        }
    }
    function up_goods_id(id){
        var v =  $("#goods_id").val();
        var arr = v.split(',');
        var flag = true;
        for(var i in arr){
            if(id == arr[i]){
                flag = false;
                break;
            }
        }
        if(flag==true){
            arr.push(id);
            $("#goods_id").val( arr.join(','));
        }
        return flag;
    }
    $(function(){
        layui.use(['form','element'],
            function() {
                form = layui.form;
                form.on('radio(range)', function(e){
                    var t = $(this);
                    var v = t.val();
                    $("#category_id_div,#goods_id_div").hide();
                    if(v==2){
                        $("#goods_id_div").show();
                    }else if(v==3){
                        $("#category_id_div").show();
                    }
                });
                <?php if($info){ ?>
                layui.$('[name="range"]:eq(<?php echo $info['range']-1; ?>)').next('.layui-form-radio').click();
                <?php  foreach(explode(',', $info['category_id'])  as $v){  ?>
                var render = template($("#cate_tpl").html(), {
                    category_id:'{$v}',
                    name:'{$v}',
                });
                $("#contentshow_cate_id tbody").append(render);
                <?php } ?>
                <?php  foreach(explode(',', $info['goods_id'])  as $v){  ?>
                var render = template($("#goods_tpl").html(), {
                    goods_id:'{$v}',
                    name:'{$v}',
                });
                $("#contentshow_goods_id tbody").append(render);
                <?php } ?>
                <?php } ?>
            });

//    $('input[name=range][value=2]').next('.layui-form-radio').click();


    })

    function formatRepo(repo){return repo.text}
    function formatRepoSelection(repo){return repo.text}

    $("#goodscid").select2({
        language: 'zh-CN',
        ajax: {
            url: "{:url('getgoods')}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    name: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    $("#category_id_select").select2({
        multiple: true
    });
</script>