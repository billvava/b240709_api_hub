<div class="x-body">
   
    <?php
    echo form_start(array('url'=>url('item')));
    echo hide_input(array('field'=>'id','val'=>$info['id']));
    echo form_input(array('fname'=>'用户名','field'=>'username','val'=>$info['username']));
    echo form_input(array('fname'=>'姓名','field'=>'realname','val'=>$info['realname']));
    echo photo(array('fname'=>'头像','field'=>'headimgurl','val'=>$info['headimgurl']));
//    if(!$info){
        echo form_input(array('fname'=>'一级密码','field'=>'pwd'));
//    }
    echo form_input(array('fname'=>'二级级密码','field'=>'pwd2'));
    echo form_input(array('fname'=>'昵称','field'=>'nickname','val'=>$info['nickname']));
    echo form_input(array('fname'=>'邮箱','field'=>'email','val'=>$info['email']));
    echo form_input(array('fname'=>'推广码','field'=>'invitation_code','val'=>$info['invitation_code']));
    echo fdate(array('fname'=>'生日','field'=>'birthday','val'=>$info['birthday']));
    echo form_input(array('fname'=>'手机号码','field'=>'tel','val'=>$info['tel']));
    $items = array(
        array('val'=>0,'name'=>'禁用'),
        array('val'=>1,'name'=>'正常'),
    );
    echo radio(array('field'=>'status','fname'=>'状态','defaultvalue'=>$info?$info['status']:1,'items'=>$items));
    
    $items = array();
    foreach($ranks as $k=>$v){
        $items[] = array('val'=>$k,'name'=>$v);
    }
//    foreach($role_array as $k1=>$v1){
//        $items1[] = array('val'=>$k1,'name'=>$v1);
//    }
    echo select(array('field'=>'rank','fname'=>'等级','defaultvalue'=>$info?$info['rank']:1,'items'=>$items));
//    echo select(array('field'=>'role','fname'=>'代理角色','defaultvalue'=>$info?$info['role']:0,'items'=>$role_array));

    echo submit();
    echo form_end();
    ?>
</div>
