<div class="layui-fluid">
    <div class="layui-col-md12">
        <div class="layui-card">
        <div class="layui-card-header">{$info.name}</div>
        <div class="layui-card-body">
           <form class="form-horizontal" role="form" action='{:url('auth')}' method='post'>
                   <div class="layui-col-md12">
                        <div class="layui-card">
                          <div class="layui-card-body">
                            <div class="layui-collapse" lay-accordion="">
                                
                                 <?php foreach($alls as  $v){ ?>
                              <div class="layui-colla-item">
                                <h2 class="layui-colla-title">
                                    <label>
                                        <input  type='checkbox' class="lev1"  name="node_id[]" <?php if(in_array($v['id'], $oneNode)){echo 'checked=""';} ?>  value='{$v.id}' />
                                    {$v.title} {$v.name}
                                    </label>
                                    <i class="layui-icon layui-colla-icon"></i>
                                </h2>
                                <div class="layui-colla-content layui-show" pid='{$v.id}'>
                                     <?php foreach($v['child'] as  $vv){ ?>
                                        <p>
                                            <label class="label"><input  type='checkbox'   name="node_id[]" value='{$vv.id}' <?php if(in_array($vv['id'], $oneNode)){echo 'checked=""';} ?>  class="lev2"  />{$vv.title} {$vv.name}</label>
                                            <div class="lev3_div"  pid='{$vv.id}'>
                                           {foreach name='vv.child' item='vvv'}
                                           <label class="label" style="font-weight:normal; margin-left: 5px;" ><input  name="node_id[]"  type="checkbox" value="{$vvv.id}" <?php if(in_array($vvv['id'], $oneNode)){echo 'checked=""';} ?>  />{$vvv.title} {$vvv.name}</label>
                                           {/foreach}
                                           </div>
                                       </p>
                                       <?php  } ?>
                                </div>
                              </div>
                                 <?php } ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    <input type="hidden" value="{$info.role_id}" name="role_id" />
               

                    <div class="form-group">
                       <label class="col-md-2 control-label"></label>
                       <div class="col-md-8">
                           <input type="submit" class="pear-btn btn-success" value="保存" />
                       </div>
                   </div>


            </form> 
        </div>
        </div>
    </div>
</div>









<script type="text/javascript">
$(function(){
    $(".lev1").change(function(){
        var t = $(this);
        var pid = t.val();
        if(t.is(":checked")){
          $(".layui-colla-content[pid="+pid+"] input").prop('checked',true);
        }else{
            $(".layui-colla-content[pid="+pid+"] input").prop('checked',false);
        }
    });
    
    $(".lev2").change(function(){
        var t = $(this);
        var pid = t.val();
        if(t.is(":checked")){
          $(".lev3_div[pid="+pid+"] input").prop('checked',true);
        }else{
            $(".lev3_div[pid="+pid+"] input").prop('checked',false);
        }
    });
    
    
    
})
</script>