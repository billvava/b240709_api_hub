

<style>
    .category_list{position:  relative;}
    .category_list input{ border: #fff 1px solid; color: #555555;padding-left: 5px; outline: none; margin-left: 5px;}
    .category_list:hover input{ border: #ccc 1px solid; }
    .category_list input:focus{ border: #ccc 1px solid; }
    .show_switch{ top: 3px;position:  absolute; cursor: pointer;}
</style>
<div class='x-body'  >
     
    <blockquote class="layui-elem-quote">
        注意：删除操作会将该栏目的子类目一并删除
    </blockquote>
    <div class='panel-body'>
        <div>
            <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">新增类目</a>
            <a class="pear-btn pear-btn-primary pear-btn-sm" href="javascript://" onclick="<?php foreach($data as $v){ echo "hide_pid(".$v['category_id'].",'hide');"; } ?>">收起</a>
            <a class="pear-btn pear-btn-primary pear-btn-sm" style=" margin-right: 10px;"  href="javascript://" onclick="hide_pid(0,'show')">展开</a>
        </div>
       <div class="table-container">
        <table class="layui-table" >
    <thead>
        <tr>
           <th>编号</th>
             <th>类目</th>
<th>图</th>
<th>商品数量</th>
<th >排序</th>
<th >状态</th>
<th>设置</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    
            <?php  function categoryTreeToHtml($data){   $shifou = array(0=>'否',1=>'是');
                foreach($data as $v){ ?>
                    <tr  class="category_list pid<?php echo $v['pid'];  ?> layui-form" data-pid="<?php echo $v['pid'];  ?>" data-id="<?php echo $v['category_id'];  ?>">
                        <th>
                           {$v.category_id}
                        </th>
                        <th>
                            <div  style=" position: relative;" >
                             <?php if($v['children']){ ?>
                            <span class=" iconfont icon-unfold show_switch" pid="<?php echo $v['category_id'];  ?>" style="left: <?php echo ($v['lev']-1)*17; ?>px;"></span>
                            <?php } ?> 
                            <?php echo str_repeat ("&nbsp;&nbsp;&nbsp;&nbsp;" , $v['lev']-1); ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;<input  data-key='category_id'  data-keyid='{$v['category_id']}'  data-field='name'  data-url='{:url('set_val')}' value='{$v['name']}' type='text' data-type='setval'  class="common_inp"  />
                            </div>
                        </th>
                        <th><?php echo_img($v['thumb'], 1); ?></th>
                        <th><?php if(empty($v['children'])){  echo $v['goods_num']; }else{echo '--';} ?></th>
                        <th style="width:50px;"><input class="layui-input" type="text" data-key='category_id'  data-keyid='{$v['category_id']}' data-field='sort'  data-url='{:url('set_val')}'  data-type='setval' value="<?php echo $v['sort']; ?>" /></th>
                        
                         <th class="w100">
                             <p><?php echo fast_check(array(
                                'key'=>'category_id',
                                 'keyid'=>$v['category_id'],
                                 'field'=>'is_show',
                                'txt'=>"显示|显示",
                                  'check'=>$v['is_show']
                            )); ?></p>
                             <p class="mt5"> <?php echo fast_check(array(
                                'key'=>'category_id',
                                 'keyid'=>$v['category_id'],
                                 'field'=>'is_nav',
                                'txt'=>"导航栏|导航栏",
                                  'check'=>$v['is_nav']
                            )); ?></p>
                        </th>
                        <th>
                            <a href='javascript://' class="x-a" onclick="del('<?php echo url('item',array('pid'=>$v['category_id'])); ?>','新增子类目会将上级类目中的商品移动到新的子类目中，确定操作？')"  >新增子类</a>
                            | <a href='javascript://' class="x-a" onclick="show_url('{:url('move_goods')}','转移商品')"  >转移商品</a>
                        </th>
                        <th>
                             <a class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('item',array('category_id'=>$v['category_id']) ); ?>" >编辑</a>
                            <a class="pear-btn pear-btn-primary pear-btn-sm " href="<?php echo url('delete',array('category_id'=>$v['category_id']) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                        </th>
                    </tr>
                    <?php if(!empty($v['children'])){  categoryTreeToHtml($v['children']);  } ?>
                     
                  <?php } ?>
            
            <?php } ?>
            <?php categoryTreeToHtml($data); ?>
    </tbody>
</table>
           {$data.page|raw}
       </div>
           
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".show_switch").click(function(){
        var t = $(this);
        var pid = t.attr('pid');
        if(t.hasClass('icon-caret-down')){
            hide_pid(pid,'hide');
            t.removeClass('icon-caret-down');
            t.addClass('icon-plus');
        }else{
            hide_pid(pid,'show');
            t.addClass('icon-caret-down');
            t.removeClass('icon-plus');
        }
    });

  
})

function hide_pid(pid,type){
    $(".pid"+pid).each(function(){
        var tt = $(this);
        if(type=='hide'){
            tt.hide();
            tt.find(".show_switch").removeClass('icon-caret-down').addClass('icon-plus');;
        }else{
            tt.show();
            tt.find(".show_switch").addClass('icon-caret-down').removeClass('icon-plus');
        }
        var data_id = tt.data('id');
        hide_pid(data_id,type);
    })
}


</script>

