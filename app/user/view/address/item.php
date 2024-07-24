<div class='x-body'>
   
    {include file="address/top" /}
   
 <form class="" action='__SELF__' method='post'>
      <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_user_id">
        <label class="layui-form-label">所属用户</label>
        <div class="layui-input-block">
            <div>
                <select name="user_id" id="user_id" lay-ignore  class="inline w200">
                    <option value="">请输入用户名</option>
                    <?php if($info['user_id']){ ?>
                    <option value="{$info['user_id']}" selected="">【{$info['user_id']}】</option>
                    <?php } ?>
                </select>
            </div>
            <div class="x-a mt5"></div>    
        </div>
    </div>
    <?php
    echo   hide_input(array('field'=>'id','val'=>$info['id']));
    echo   form_input(array('fname'=>'收货人','field'=>'name','val'=>$info['name']));
     echo   form_input(array('fname'=>'手机','field'=>'tel','val'=>$info['tel']));
    ?>
        <?php $sf =  $ho->get_shengfen(); ?>

        <div class='layui-form-item  layui-col-xs8 layui-col-md8'  >
            <label class='layui-form-label'>省份</label>
            <div class='layui-input-block'>
                <div>
              <select name='province' id="province"   onchange="selCity('province','city')">
                <option value=""  >请选择</option>
                    <?php foreach($sf as $v){ ?>
                    <option value="{$v.id}" <?php if($info['province']==$v['id']){ echo "selected='selected'";} ?>>{$v.name}</option>
                    <?php } ?>
              </select>
              </div>
              <div class='x-a mt5'></div>
            </div>
          </div>
          <?php $cs =  $ho->get_quyu($info['province']); ?>
          <div class='layui-form-item  layui-col-xs8 layui-col-md8'  >
            <label class='layui-form-label'>城市</label>
            <div class='layui-input-block'>
                <div>
              <select name='city' id="city"   onchange="selCity('city','country')">
                <option value=""  >请选择</option>
                   <?php foreach($cs as $v){ ?>
                    <option value="{$v.id}" <?php if($info['city']==$v['id']){ echo "selected='selected'";} ?>>{$v.name}</option>
                    <?php } ?>
              </select>
              </div>
              <div class='x-a mt5'></div>
            </div>
          </div>



         <?php $qy =  $ho->get_quyu($info['city']); ?>
         <div class='layui-form-item  layui-col-xs8 layui-col-md8'  >
            <label class='layui-form-label'>区域</label>
            <div class='layui-input-block'>
                <div>
               <select name="country"  id="country" >
                    <option value=""  >请选择</option>
                    <?php foreach($qy as $v){ ?>
                    <option value="{$v.id}" <?php if($info['country']==$v['id']){ echo "selected='selected'";} ?>>{$v.name}</option>
                    <?php } ?>
                </select>
              </div>
              <div class='x-a mt5'></div>
            </div>
          </div>
            
      <?php
    echo   form_input(array('fname'=>'地址','field'=>'address','val'=>$info['address']));
    $items  = array(
        array('name'=>'是','val'=>1),
        array('name'=>'否','val'=>0),
    );
      echo   select(array('fname'=>'是否默认','field'=>'is_default','items'=>$items,'val'=>$info['is_default']));
        echo submit();
    ?>
    
    
        </form>

    
</div>
    
<script src="__LIB__/select2/js/select2.full.min.js" type="text/javascript"></script>
<link href="__LIB__/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
    function selCity(id,cid){
        var k = $("#"+id+" option:checked").val();
        ajax("{:url('home/ajax/ajax_address')}",{id:k},function(data){
            $("#"+cid).html(data.data.html);
        })
    }
    
    function formatRepo(repo){return repo.text}
    function formatRepoSelection(repo){return repo.text}

    $("#user_id").select2({
        language: 'zh-CN',
      ajax: {
        url: "{:url('Index/getuser')}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            username: params.term,
          };
        },
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      },
      escapeMarkup: function (markup) { return markup; }, 
      minimumInputLength: 1,
      templateResult: formatRepo, 
      templateSelection: formatRepoSelection 
    });

    </script>
    