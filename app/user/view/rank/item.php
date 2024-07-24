<div class="x-body">
    {include file="rank/top" /}
     <?php
    echo form_start(array('url'=>url('item')));
    echo hide_input(array('field'=>'id','val'=>$info['id']));
    echo form_input(array('fname'=>'名称','field'=>'name','val'=>$info['name'],'required'=>'required'));
    ?>
        <input type="hidden" name="discount" id="discount" value="<?php echo $info?$info['discount']:100; ?>" />

       <div class="layui-form-item">
          <div class='layui-inline'>
            <label class='layui-form-label'>会员折扣</label>
            <div class='layui-input-inline'>
                <div id="slide" style="margin-top: 17px;"></div>
            </div>
            <div class='x-a ' style=" margin-top: 30px;" id="slide_txt" ><?php echo $info?$info['discount']:100; ?>%</div>
          </div>
      </div> 
   <?php
    echo submit();
    echo form_end();
    ?>
</div>

<script type="text/javascript">

$(function(){
    layui.use(['slider'],function() {
        slider = layui.slider;
       //定义初始值
        slider.render({
          elem: '#slide'
          ,value: <?php echo $info?$info['discount']:100; ?>
          ,min: 0
            ,max: 100,
           setTips: function(value){ 
            return value ;
           },
           change: function(value){
            $('#slide_txt').html(value+" %");
            $("#discount").val(value);
          }
        });
       
        
    });

        
})
</script>