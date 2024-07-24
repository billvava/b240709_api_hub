<?php $defaultValue = $model->defaultValue(); ?>
<div class="x-body">
        <fieldset class="layui-elem-field layui-field-title" >
            <legend>{$ad_info.name}</legend>
        </fieldset>
        <?php 
        echo form_start(array('url'=>url('item')));
        echo $info? hide_input(array(
            'field'=>'id',
            'val'=>$info['id']
        )):'';
       echo hide_input(array(
            'field'=>'ad_id',
            'val'=>$info?$info['ad_id']:$in['ad_id']
        ));
        
        
        echo form_input(array(
            'field'=>'name',
            'fname'=>'名称',
             'defaultvalue'=>$info?$info['name']:$defaultValue['name'],
        ));
        if($ad_info['width'] && $ad_info['height']){
            echo thumb_clip(array(
                'field'=>'big',
                  'fname'=>'大图',
                  'defaultvalue'=>$info['big'],
                  'width'=>$ad_info['width'],
                   'height'=>$ad_info['height'],
                'is_func_auto'=>1
            ));
        }else{
            echo photo(array(
                'field'=>'big',
                  'fname'=>'大图',
                  'defaultvalue'=>$info['big'],
            ));

        }
        echo  photo(array(
                    'field'=>'small',
                      'fname'=>'小图',
                      'defaultvalue'=>$info['small'],
                ));
         echo form_input(array(
            'field'=>'txt',
            'fname'=>'文字',
             'defaultvalue'=>$info?$info['txt']:$defaultValue['txt'],
        ));
        echo form_input(array(
            'field'=>'link',
            'fname'=>'链接',
             'defaultvalue'=>$info?$info['link']:$defaultValue['link'],
        ));
         echo datetime(array(
            'field'=>'start',
            'fname'=>'开始',
             'defaultvalue'=>$info?$info['start']:date('Y-m-d H:i:s'),
        ));
        echo datetime(array(
            'field'=>'end',
            'fname'=>'结束',
             'defaultvalue'=>$info?$info['end']:date('Y-m-d H:i:s',strtotime("+10 year")),
        ));
         echo form_input(array(
            'field'=>'sort',
            'fname'=>'排序',
             'defaultvalue'=>$info?$info['sort']:$defaultValue['sort'],
             'msg'=>'越小的越在前面',
        ));
        echo submit();
        echo form_end();
        ?>
</div>

<script type="text/javascript">
$(function(){
    //小程序的地址
    $('input[name=link]').click(function(){
        //show_url('<?php echo url(ADMIN_MODULE. '/index/sel_link'); ?>');
    });
    
})
</script>