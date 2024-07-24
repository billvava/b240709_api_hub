<div class="x-body">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>{$ad_info.name}</legend>
    </fieldset>
    <xblock>
        <a class="pear-btn pear-btn-sm pear-btn-primary " href="{:url('item',array('ad_id'=>$in['ad_id']))}">
            <i class="layui-icon layui-icon-add-1"></i> 新增
        </a>
    </xblock>

    <table class="layui-table " >

        <thead>
            <tr>
                <?php if($is_del==1){ ?>
                <th>
                    <a href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)">全选</a> |
                    <a href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); if(t.is(':checked')){ t.prop('checked',false); }else{  t.prop('checked',true); } })">反选</a>
                </th>
                <?php } ?>
                <th>id</th>
                <th>名称</th>
                <th>排序</th>
                <th>大图</th>
                <th>小图</th>
                <th>文字</th>
                <th>链接</th>
                 <th>状态</th> 
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <form id="del_form" action="<?php echo url('delete'); ?>" method="post" onsubmit="return check()">
                {foreach name='data.list' item='v' key='k' }
                <tr class="layui-form">
                    <?php if($is_del==1){ ?>
                    <th style=" width: 100px;">
                        <input type="checkbox" value="<?php echo $v[$pk]; ?>" lay-ignore  name="<?php echo $pk; ?>[]" />
                    </th>
                    <?php } ?>
                    <th>
                        <?php echo $v['id']; ?>
                    </th>
                    <th>
                        <?php echo fast_input(array(
                        'key'=>$pk,
                        'keyid'=>$v[$pk],
                        'field'=>'name',
                        'val'=>$v['name'],
                        )); ?>
                    </th>
                    <th>
                        <?php echo fast_input(array(
                        'key'=>$pk,
                        'keyid'=>$v[$pk],
                        'field'=>'sort',
                        'val'=>$v['sort'],
                        )); ?>
                    </th>

                    <th>
                        <?php if($v['big']){ ?>
                        <a class="big" href="<?php echo get_img_url($v['big']); ?>" target="_blank">显示图片</a>
                        <?php } ?>
                    </th>
                    <th>
                        <?php if($v['small']){ ?>
                        <a class="small" href="<?php echo get_img_url($v['small']); ?>" target="_blank">显示图片</a>
                        <?php } ?>
                    </th>
                    <th>
                        <?php echo fast_input(array(
                        'key'=>$pk,
                        'keyid'=>$v[$pk],
                        'field'=>'txt',
                        'val'=>$v['txt'],
                        )); ?>
                    </th>
                    <th>
                        <?php echo $v['link']; ?>
                    </th>
                    <th>
                        <?php echo fast_check(array(
                        'key'=>$pk,
                        'keyid'=>$v[$pk],
                        'field'=>'status',
                        'check'=>$v['status'],
                          'txt'=>'显示'
                        )); ?>
                    </th>
                    <th>
                        <?php if($is_edit==1){ ?>
                        <a  href="<?php echo url('item',array($pk=>$v[$pk]) ); ?>">编辑</a> | 
                        <?php } ?>
                        <a  href="<?php echo url('copy',array($pk=>$v[$pk]) ); ?>">复制</a> | 
                        <?php if($is_del==1){ ?>
                        <a href="<?php echo url('delete',array($pk=>$v[$pk]) ); ?>" onclick="return window.confirm('此操作不可撤销，你确定要继续？');">删除</a>
                        <?php } ?>
                    </th>
                </tr>
                {/foreach}
                <?php if($is_del==1){ ?>
                <tr>
                    <td colspan="100">
                        
                        <button type="submit" class="pear-btn pear-btn-sm  pear-btn-danger">删除</button>
                    </td>
                </tr>
                <?php } ?>
            </form>
        </tbody>
    </table>
    {$data.page|raw}
</div>
<script type="text/javascript">
    $(function(){
        $('.big,.small').hover(function(e) {
            var t = $(this);
            var href = t.attr('href');
            var html = "<img src='" + href + "' style='' />";
            layer.tips(html, t, {
                tips: [1, '#fff'],
                time: 10000
            });
        }, function() {
            close();
        });
    
    })
    function check(){
        var r=window.confirm("确定删除吗？");
        return r;
    }
</script>
