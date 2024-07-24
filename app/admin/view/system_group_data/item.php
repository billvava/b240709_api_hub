<div class='x-body'>
    <form class="layui-form"  action="<?php echo url((app()->request->action()))?>" method="post">
        
<!--  【id】 start  !-->
<input type='hidden'   name='id' value='<?php echo $info?$info['id']:''; ?>'>

        <input type='hidden'   name='group_id' value='<?php echo $info?$info['group_id']:$group_id; ?>'>


        <?php
        if($group_info['fields']){
            foreach($group_info['fields'] as $v){

                if($v['type'] == 'input'){
                    echo form_input(array('field'=>'value_'.$v['field'],'fname'=>$v['name'],'defaultvalue'=>$info?$info['value'][$v['field']]:'','col'=>4));
                }

                if($v['type'] == 'thumb'){
                    echo thumb(array('field'=>'value_'.$v['field'],'fname'=>$v['name'],'defaultvalue'=>$info?$info['value'][$v['field']]:'','col'=>4));
                }
                if($v['type'] == 'ftime'){
                    echo ftime(array('field'=>'value_'.$v['field'],'fname'=>$v['name'],'defaultvalue'=>$info?$info['value'][$v['field']]:'','col'=>4));
                }

                if($v['type'] == 'textarea'){
                    echo textarea(array('field'=>'value_'.$v['field'],'fname'=>$v['name'],'defaultvalue'=>$info?$info['value'][$v['field']]:'','col'=>4));
                }



            }
        }


        ?>


<?php echo form_input(array('field'=>'sort','fname'=>'排序','defaultvalue'=>$info?$info['sort']:'10','col'=>4)); ?>
<!--  【sort】 end  !-->
<!--  【status】 start  !-->
<?php $itms = $all_lan["status"];   echo radio(array('field'=>'status','items'=>$itms,'fname'=>'状态','defaultvalue'=>$info?$info['status']:'1',)); ?>
<!--  【status】 end  !-->
<!--  【create_time】 start  !-->

<!--  【create_time】 end  !-->
        <?php echo submit(); ?>
     </form>
</div>


