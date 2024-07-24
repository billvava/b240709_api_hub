<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>
<!--  【id】 end  !-->
<!--  【group_name】 start  !-->
<?php echo form_input(array('field'=>'group_name','fname'=>'数据组名称','defaultvalue'=>$info?$info['group_name']:'','col'=>4)); ?>
<!--  【group_name】 end  !-->
<!--  【group_info】 start  !-->
<!--  【group_info】 end  !-->
<!--  【group_key】 start  !-->
<?php echo form_input(array('field'=>'group_key','fname'=>'组合数据key','defaultvalue'=>$info?$info['group_key']:'','col'=>4)); ?>

<?php echo form_input(array('field'=>'group_info','fname'=>'提示','defaultvalue'=>$info?$info['group_info']:'','col'=>4)); ?>

        <!--  【group_key】 end  !-->
<!--  【fields】 start  !-->
<!--  【sort】 start  !-->
<?php  form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'0','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【create_time】 start  !-->
        <div id="apps">

            <div class="layui-form-item  layui-col-xs10 layui-col-md12" id="contentshow_name">
                <label class="layui-form-label">数据项</label>
                <div class="layui-input-block">

                    <div id="app">

                        <div class="table-container">
                            <table class="layui-table"  >

                                <thead>
                                <tr>
                                    <th>类型</th>
                                    <th>字段名称</th>
                                    <th>字段key</th>
                                    <th>选择项</th>
                                    <th>配置</th>
                                    <th>操作 <a href="javascript://" @click="add">添加</a></th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr class="layui-form"  v-for="(item, index) in list">
                                    <th>
                                        <select name="list[type][]" v-model="item.type" lay-ignore style="width: 100px;">
                                            <option :value="index2" v-for="(item2, index2) in type_list">{{item2}}</option>
                                        </select>
                                    </th>
                                    <th>
                                       <input  name="list[name][]" type="text" placeholder="必填" class="layui-input" v-model="item.name" >
                                    </th>
                                    <th>
                                        <input  name="list[field][]" type="text"  placeholder="必填"  class="layui-input" v-model="item.field" >
                                    </th>
                                    <th>
                                        <input name="list[param][]"  type="text"  class="layui-input"  v-model="item.param" >
                                    </th>
                                    <th>
                                        <input  name="list[props][]" type="text"  class="layui-input" v-model="item.props" >
                                    </th>
                                    <th>
                                        <a  href="javascript://" @click="del(index)">删除</a>
                                    </th>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
            </div>
        </div>
<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>
<script type="text/javascript" src="/static/lib/vue.min.js"></script>
<script type="text/javascript" >

  $(function(){
        var vm = new Vue({
        el:'#apps',
        data:{
            list:<?php  echo $info ? json_encode($info['fields']) :json_encode([]); ?>,
            type_list:<?php echo json_encode($type_list,323); ?>,
        },

        mounted:function(){
            var t = this;
        },
        watch:{


        },
        methods: {
            add:function(){
              var t = this;
              t.list.push({
                  'name':'',
                  'field':'',
                  'type':'input',
                  'param':'',
                  'props':''

              })
            },
            del:function (index){
                var t = this;
                t.list.splice(index,1);
            },

        },




        });
    })



</script>

