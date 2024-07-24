{__NOLAYOUT__}
{foreach name='as' item='v'}
<div class='layui-form-item  layui-col-xs8 layui-col-md8'  >
    <label class='layui-form-label'>{$v.name}</label>
    <div class='layui-input-block'>
        <?php   $newRsK = $newRsK?:[];  $default = in_array($v['field_id'],$newRsK)? ($newRs[$v['field_id']]['val']?$newRs[$v['field_id']]['val']:$newRs[$v['field_id']]): $v['default']; ?>
        <div>
        <?php if($v['type']==1){ ?> 
        <input type='text'    value="<?php echo $default;?>"     name="attr_field[{$v.field_id}]"  class='layui-input'>
        <?php }elseif($v['type']==2){  $param = preg_split('/\r\n/',$v['param']);   ?>
            <select  name="attr_field[{$v.field_id}]" class="w100" >
                <option  value="">请选择</option>
                {foreach name='param' item='pv'}
                <option  value="{$pv}" <?php if($pv==$default){echo "selected=''";} ?>>{$pv}</option>
                {/foreach}
            </select>
         <?php }elseif($v['type']==3){  
                
                $param = preg_split('/\r\n/',$v['param']); 
                $create_num = 1;
                $newCreate = array();
                if(is_array($default)){
                    foreach($default as $dv){
                        $newCreate[] = $dv['val'];
                    }
                }else if($default){
                    
                    $default =  explode('@', $default);
                    foreach($default as $dv){
                        $newCreate[] = $dv;
                    }
                }else{
                    $newCreate[] = '';
                }
                foreach($newCreate as $nv){
                
            ?>
            <select  name="attr_field[{$v.field_id}][]" class="w100"  >
                <option  value="">请选择</option>
                {foreach name='param' item='pv'}
                <option  value="{$pv}"  <?php if($pv==$nv){echo "selected=''";} ?>>{$pv}</option>
                {/foreach}
            </select>
                <?php } ?>
            <a href="###" style=" margin-left: 5px;" class="attr_field_add{$v.field_id}" onclick="$('.attr_field_add{$v.field_id}').before($(this).siblings('select:eq(0)').clone())">+</a>
            <?php }elseif($v['type']==4){   ?>
            <textarea  class="form-control"  name="attr_field[{$v.field_id}]"><?php echo $default;?></textarea>
            <?php } ?>
            </div>
        <div class='x-a mt5  layui-word-aux'>{$v.remark}</div>    
    </div>
</div>

{/foreach}
