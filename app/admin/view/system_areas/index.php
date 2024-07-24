<div class="x-body">
<!--    <div class="layui-row">
       <a class="pear-btn pear-btn-primary " href="{:url('item')}">新增</a>
    </div>-->

    <?php $a = array(1=>'省',2=>'市',3=>'区域'); ?>

    <div class="layui-row mt5">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text"   name='id' value="{$in.id}"  placeholder="系统编号"  class="layui-input ">
            <input type="text"   name='pid' value="{$in.pid}"  placeholder="上级ID"  class="layui-input ">
            <input type="text"   name='name' value="{$in.name}"  placeholder="名称"  class="layui-input ">
            <div class="layui-input-inline">
                <select name="level">
                    <option value="">等级</option>
                    <?php foreach($a as $k=>$v){ ?>
                        <option value="{$k}" <?php if($in['level']==$k&& $in['level']!=''){echo 'selected=""';} ?>>{$v}</option>
                    <?php } ?>
                </select>
            </div>
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary  pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <table class="layui-table" >
   
    <thead>
        <tr>
             <th>
                 <a type="button" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                <a type="button"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</a>
              </div></th>
             <th>系统编号</th>
<th>上级ID</th>
<th>名称</th>
<th>简称</th>
<th>等级</th>
<th>排序</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php $a = array(1=>'省',2=>'市',3=>'区域'); ?>
         <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
        {foreach name='data.list' item='v' key='k' }
          <tr class="layui-form">
                <th style=" width: 100px;"><input type="checkbox" value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]"/></th>
              <th><?php echo $v['id']; ?></th>
<th><?php echo $v['pid']; ?></th>
<th><?php echo $v['name']; ?></th>

<th> <?php echo fast_input(array(
                                        'key'=>'id',
                                         'keyid'=>$v['id'],
                                         'field'=>'simple',
                                        'url'=>url('set_val'),
                                        'val'=>$v['simple'],
                                        'class'=>'w100'
                                    ));
                  
?></th>

<th><?php echo $a[$v['level']]; ?></th>
<th><?php echo $v['sort']; ?></th>

                <th>
                    <?php if($v['level']!=3){ ?>
                    <a  class="pear-btn pear-btn-primary  " href="<?php echo url('index',array('pid'=>$v['id']) ); ?>">子区域</a>
                    <?php } ?>
                     <a class="pear-btn  pear-btn-danger" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                </th>
            </tr>     
         {/foreach}
         <tr>
             <td colspan="100">
                 <button type="submit" class="pear-btn  pear-btn-danger">删除</button>
             </td>
         </tr>
         </form>
    </tbody>
</table>
              {$data.page|raw}
</div>

<script type="text/javascript">
function check(){
    var r=window.confirm("确定删除吗？");
    return r;
}
</script>
