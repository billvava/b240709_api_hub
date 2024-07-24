

<div class='xblock'>
    <?php $nav_arr = array(
        'cate'=>'等级分布',
         
          'year'=>'按年', 
        'month'=>'按月',
          'day'=>'按日',
        
        
    ); 
    foreach($nav_arr as $k=>$v){
    ?>
    <a href="<?php echo url($k); ?>" class="pear-btn pear-btn-primary pear-btn-sm <?php if(app()->request->action()!=$k){echo 'pear-btn pear-btn-primary pear-btn-sm-primary';} ?>">{$v}</a>
    <?php } ?>
   
</div>