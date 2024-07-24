<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-tab layui-tab-card" lay-filter="tab-all">
            <div class="layui-tab-item layui-show">
                <div class="layui-card">
                    <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                        <form method="get" action="<?php echo url(app()->request->action()); ?>">
                        <div class="layui-form-item type">
                            <div class="layui-inline">
                                <label class="layui-form-label">商品名称:</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" placeholder="请输入关键词" value="{$in.name}" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-inline">
                                <label class="layui-form-label">商品分类:</label>
                                <div class="layui-input-block">
                                    <select name="category_id"  placeholder="请选择商品分类" >
                                        <option value="0">全部</option>
                                        <?php if($category_list){  foreach($category_list as $val){ ?>
                                        <option value="{$val.category_id}" <?php if($in['category_id']==$val['category_id']){ echo "selected=''";} ?>>{$val.html}{$val.name}</option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="callback" value="{$in.callback}" >
                            <input type="hidden" name="field" value="{$in.field}" >
                            <div class="layui-inline">
                                <button class="pear-btn pear-btn-primary" type="submit"  >查询</button>
                            </div>
                        </div>
                            
                        </form>
                    </div>
                    <div class="layui-card-body">
                       <div class='table-container'>
                <table class="layui-table">

                    <thead>
                        <tr>
                             <th></th>
                             <th>编号</th>
                <th>名称</th>
                <th>价格</th>
                        </tr>
                    </thead>
                    <tbody>
                         <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
                        {foreach name='data.list' item='v' key='k' }
                        <tr  class="layui-form" >
                            <th style=" width: 100px;"><input type="radio"  onchange="sel_radio('<?php echo $v['goods_id']; ?>')" lay-ignore  value="<?php echo $v['goods_id']; ?>" name="goods_id" /></th>
                              <th><?php echo $v['goods_id']; ?></th>
                              <th><?php echo_img($v['thumb']) ; ?><span style="margin: 10px"></span> <?php echo $v['name']; ?></th>
                <th><?php echo $v['min_price']; ?></th>
              
                            </tr>     
                         {/foreach}
                         <?php if(!$data['list']){ ?>
                         <tr  class="layui-form" >
                                
                             <th colspan="100" style=" text-align: center;">暂无数据
                                </th>
                            </tr>   
                         <?php }else{ ?>
                            <tr  class="layui-form" >
                             <th colspan="100">
                                 <button class="pear-btn pear-btn-primary" type="button" onclick="sub()"  >确定选择</button>
                                </th>
                            </tr> 
                         <?php } ?>
                         </form>
                    </tbody>
                </table>
                              {$data.page|raw}
                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="select-submit" class="select-submit" id="select-submit" value="确认">
    </div>
</div>
<style>
    .layui-table-cell {
        height: auto;
    }
</style>
<script>
var goods_id = null;    
function sel_radio(g){
     goods_id = g;
}    
    
function sub(){
    if(goods_id == null){
        layer.msg('请选择');
        return;
    }
    ajax('{:url('get_item')}',{goods_id:goods_id},function(data){
        var goods_id = data.data.goods_id;
        var items = data.data.items;
        var info = data.data.info;
        <?php if($in['callback']){ ?>
        <?php echo "parent.".$in['callback']."('".$in['field']."',goods_id,items,info);parent.layer.closeAll();return;"; ?>;
        <?php } ?> 
        
    });
}
</script>