
<div class='x-body' >
    
        
        <?php echo form_start(array('url'=>url('set_address'))); echo hide_input(array('field'=>'order_id','val'=>$orderInfo['order_id']));
        
        $arr = array(
            array('field'=>'linkman','name'=>'收货人'),
             array('field'=>'tel','name'=>'电话'),
             array('field'=>'province','name'=>'省份'),
               array('field'=>'city','name'=>'城市'),
              array('field'=>'country','name'=>'区域'),
             array('field'=>'address','name'=>'地址'),
        );
        foreach($arr as $v){
            
            echo form_input(array('field'=>$v['field'],'fname'=>$v['name'],'val'=>$orderInfo[$v['field']]));
            
        }
        ?>
        
    
    
      <?php echo submit(); echo form_end(); ?>
       
</div>

