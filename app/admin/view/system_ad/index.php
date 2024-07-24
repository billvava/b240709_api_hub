
<div class="x-body">

    {include file="system_ad/top" /}
    <div class="table-container">
        <table class="layui-table" >

            <thead>
                <tr>
                    <?php if($is_del==1){ ?>
                    <th>
                        <a href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)">全选</a> |
                        <a href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</a>
                    </th>
                    <?php } ?>
                    <th>编号</th>
                          <th>广告名称</th>
                     <th>标识</th>
                    <th>宽</th>
                    <th>高</th>
                    <th>备注</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
                    {foreach name='data.list' item='v' key='k' }
                    <tr>
                        <?php if($is_del==1){ ?>
                        <th style=" width: 100px;">
                            <input type="checkbox" value="<?php echo $v[$pk]; ?>" name="<?php echo $pk; ?>[]" />
                        </th>
                        <?php } ?>
                        <th>
                            <?php echo $v['id']; ?>
                        </th>
                         <th>
                            <?php echo $v['name']; ?>
                        </th>
                        <th>
                             <?php echo $v['key']; ?>
                        </th>
                        <th>
                            <?php echo fast_input(array(
                            'key'=>$pk,
                            'keyid'=>$v[$pk],
                            'field'=>'width',
                            'val'=>$v['width'],
                            )); ?>
                        </th>
                        <th>
                            <?php echo fast_input(array(
                            'key'=>$pk,
                            'keyid'=>$v[$pk],
                            'field'=>'height',
                            'val'=>$v['height'],
                            )); ?>
                        </th>
                        <th>
                            <?php echo fast_input(array(
                            'key'=>$pk,
                            'keyid'=>$v[$pk],
                            'field'=>'remark',
                            'val'=>$v['remark'],
                            )); ?>
                        </th>
                        <th>
                            <a class="pear-btn pear-btn-sm pear-btn-primary  " href='javascript://' onclick="show_url('<?php echo url('SystemAdImg/index',array('ad_id'=>$v[$pk]) ); ?>')">图片管理</a>
                            <?php if($is_edit==1){ ?>
                            <a class="pear-btn  pear-btn-sm  pear-btn-primary  " href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>">编辑</a>
                            <?php } ?>
                            <?php if($is_del==1){ ?>
                            <a class="pear-btn   pear-btn-sm  pear-btn-danger" href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                            <?php } ?>
                        </th>
                    </tr>
                    {/foreach}
                    <?php if($is_del==1){ ?>
                    <tr>
                        <td colspan="100">
                            <button type="submit" class="pear-btn  pear-btn-sm  pear-btn-danger">删除</button>
                        </td>
                    </tr>
                    <?php } ?>
                </form>
            </tbody>
        </table>
    </div>
    {$data.page|raw}
</div>


<script type="text/javascript">
    function check(){
        var r=window.confirm("确定删除吗？");
        return r;
    }
</script>
