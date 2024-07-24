        <?php $feedback_type = lang('feedback_type'); tool()->func('html'); ?>

<div class="x-body">
  <div class="panel">
  
    <table class="layui-table">
        
        <tbody>
             <?php if($feedback['name']){ ?>
          <tr>
            <td>姓名</td>
            <td>{$feedback.name}</td>
          </tr>
           <?php } ?>
          <?php if($feedback['tel']){ ?>
          <tr>
            <td>手机</td>
            <td>{$feedback.tel}</td>
          </tr>
           <tr>
            <td>类型</td>
            <td><?php echo $feedback_type[$feedback['type']]; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td>时间</td>
            <td>{$feedback.create_time}</td>
          </tr>
          <tr>
            <td>具体内容</td>
            <td>{$feedback.content}</td>
          </tr>
          
          <tr>
            <td>图片</td>
            <td><?php echo_img($feedback['img']) ?></td>
          </tr>
        </tbody>
       
      </table>
      <?php echo form_start(array('url'=>url('feedback'))); ?>
      <?php echo textarea(array('fname'=>'处理备注','field'=>'remark','val'=>$feedback['remark'])); ?>
      
          <?php $items = array();
           $feedback_status = lang('feedback_status');
          foreach($feedback_status as $k=>$v){
              $items[] = array(
                  'val'=>$k,
                  'name'=>$v,
                  'checked'=>  $orderInfo['feedback_status']==$k?1:0
              );
            
          }
          echo radio(array('fname'=>'状态修改','field'=>'feedback_status','items'=>$items));
          ?>
      
          <input type="hidden" value="{$feedback.id}" name="id" />
          <input type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>" name="up_time" />
           <input type="hidden" value="{$orderInfo.order_id}" name="order_id" />
        <?php echo submit(); ?>
        <?php echo form_end(); ?>
</div>
</div>