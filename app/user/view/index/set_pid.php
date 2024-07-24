<div class="x-body">
     <?php
    echo form_start(array('url'=>url('set_pid')));
    ?>
    <div class='layui-form-item  layui-col-xs8 layui-col-md8' >
            <label class='layui-form-label'>会员ID</label>
            <div class='layui-input-block'>
                <div><input type='text'  name="user_id1" value="" onchange="getname('user_id1')"  class='layui-input' required></div>
                <div class='x-a mt5' id="user_id1"></div>    
            </div>
        </div>
    
     <div class='layui-form-item  layui-col-xs8 layui-col-md8' >
            <label class='layui-form-label'>上级ID</label>
            <div class='layui-input-block'>
                <div><input type='text'  name="user_id2" value="" onchange="getname('user_id2')"  class='layui-input' required></div>
                <div class='x-a mt5' id="user_id2"></div>    
            </div>
        </div>
    <?php
    echo submit();
    echo form_end();
    ?>
</div>

<script type="text/javascript">
function getname(id){
    var v = $('input[name='+id+']').val();
    ajax('{:url('getname')}',{id:v},function(data){
        $("#"+id).text(data.data);
    });
}
</script>