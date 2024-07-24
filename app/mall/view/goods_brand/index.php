


<div class='x-body'>
    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('item')}">新增</a>
    </xblock>

  <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('index')}">
            <input type="text" name="goods_id" value="{$in.goods_id}"  placeholder="系统编号"  class="layui-input">
            <input type="text" name="name" value="{$in.name}"  placeholder="品牌名称"  class="layui-input">
           <input type="text" name="desc" value="{$in.desc}"  placeholder="品牌描述"  class="layui-input">
            
            
            <?php $is = lang('is'); ?>
            <div class="layui-input-inline">
              <select name="status">
                <option value="">状态</option>
                <?php foreach($is as $k=>$v){ ?>
                <option value="{$k}" <?php if($in['status']==$k&& $in['status']!=''){echo 'selected=""';} ?>>{$v.str}</option>
                <?php } ?>
              </select>
            </div>
            <input type="hidden"  name="p" value="1">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" ><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    
    
    <div class='table-container'>
 
        <table class="layui-table ">
    <thead>
        <tr>
             
             <th>系统编号</th>
<th>品牌名称</th>
<th>品牌描述</th>
<th>排序</th>
<th>首字母</th>
<th>logo</th>

<th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <form id="del_form"  action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
        <?php foreach($data['list'] as $k=>$v){ ?>
          <tr class="layui-form">
              <th><?php echo $v['brand_id']; ?></th>
<th><?php echo $v['name']; ?></th>
<th><?php echo $v['desc']; ?></th>
<th><?php echo $v['sort']; ?></th>
<th><?php echo $v['letter']; ?></th>
<th><?php  echo_img($v['thumb']); ?></th>

<th>
        <p><?php echo fast_check(array(
                      'key'=>'brand_id',
                       'keyid'=>$v['brand_id'],
                       'field'=>'status',
                      'url'=>url('set_val'),
                      'txt'=>"正常|正常",
                        'check'=>$v['status']
                  )); ?></p>
</th>


<th><a class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>">编辑</a>  <a  class="pear-btn pear-btn-primary pear-btn-sm" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                </th>
            </tr>     
        <?php } ?>
       
         </form>
    </tbody>
</table>
              {$data.page|raw}
    </div>
</div>
<script type="text/javascript">

function check(){
    var r=window.confirm("确定删除吗？");
    return r;
}
</script>

