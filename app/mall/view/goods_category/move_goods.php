<?php $html = (new \app\mall\model\GoodsCategory())->getAllOptionHtml($goodsInfo['category_id']); ?>
<div class="x-body">
    <form method="post">
        <div class="layui-row">
            <div class="layui-col-xs6 layui-col-md3">
                <p class="x-a">原商品分类：</p>
                <select name='old_id' onchange="change_category('old_id')" id="old_id" class="mt10" style=" width: 90%;"   >
                        <option value=''>请选择</option>
                         <?php echo $html; ?>
                </select>
            </div>
            <div class="layui-col-xs6 layui-col-md3">
                <p class="x-a">目标商品分类：</p>
                <select name='new_id'  onchange="change_category('new_id')" id="new_id" class="mt10" style=" width: 90%;"   >
                    <option value=''>请选择</option>
                     <?php echo $html; ?>
                  </select>
            </div>
        </div>
        <div class="layui-row mt10">
            <div class="layui-col-xs12">
                <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm">确定</button>
            </div>
        </div>
        </form>
</div>
<script type="text/javascript">
function change_category(id) {
    var t = $("#"+id);
    var v = t.find("option:selected").val();
    var category_id = t.find("option:selected").attr('category_id');
    if (t.find("option[pid=" + category_id + "]").length > 0) {
        layer.tips('不能选择有子类目的类目',t, {
            tips: [1, '#3595CC'],
            time: 2000
        });
        t.val("");
        return false;
    }
}
</script>