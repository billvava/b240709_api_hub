
<script type="text/javascript">
function all_hand(field,val){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    ajax('{:url('dataset')}',{goods_ids:ids ,field:field,val:val} );
}
function get_ids(){
    var ids = [];
    $("input:checkbox[name='goods_id']:checked").each(function() {
        ids.push($(this).val());
    });
    if(ids.length <= 0){
        
        return null;
    }else{
        return ids;
    }
}
function forever_del(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var r=window.confirm("确定删除吗？");
    if(r){
        ajax('{:url('forever_del')}',{goods_ids:ids },function(){
            location.reload();
        });
    }
}
function copy_goods(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var r=window.confirm("复制之后需要重新录入规格的价格，确定？");
    if(r){
        ajax('{:url('copy')}',{goods_ids:ids },function(){
            location.reload();
        });
    }
    
}
</script>