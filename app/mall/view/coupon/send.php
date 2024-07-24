<style>
   
</style>
<div class='x-body'>

    <xblock>
        <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回列表</a>
    </xblock>

    <fieldset class="layui-elem-field layui-field-title" >
        <legend>发放优惠券</legend>
    </fieldset>
    <form class="layui-form" role="form" action='__SELF__' method='post'>
        
        <?php $tpls =  (new \app\mall\model\MallCouponTpl)->getAll(array(
            'type'=>1
        )); ?>
         <div class='layui-form-item  layui-col-xs8 layui-col-md8' >
            <label class='layui-form-label'>模板</label>
            <div class='layui-input-block'>
                <div>
                    <select name='tpl_id' id="tpl_id"  lay-filter="category_id"   >
                        <option value=''>请选择</option>
                        <?php foreach ($tpls as $v) { ?>
                          <option value='{$v.id}'>{$v.name}-【面值{$v.money}门槛{$v.base_money}】</option>    
                         <?php  } ?>
                    </select>
              </div>
                <div class='x-a mt5'><a class="x-a" href="{:url('MallCouponTpl/item')}">去添加模板</a></div>
            </div>
          </div>
        
         <?php echo form_input(array('fname'=>'数量','field'=>'num','msg'=>'最少1','val'=>1)); ?>
          
         <div class='layui-form-item  layui-col-xs8 layui-col-md8'  >
            <label class='layui-form-label'>发送范围</label>
            <div class='layui-input-block'>
                <div>
                    <input type="radio" name="range" value="1"  lay-filter="range"   title="所有用户" >
                    <input type="radio" name="range" value="2"  lay-filter="range" checked=''  title="按用户编号" >
                </div>
                <div class='x-a mt5'></div>
            </div>
          </div>
        <?php echo textarea(array('fname'=>'用户编号','field'=>'user_id','msg'=>'多个用,号分隔，所有用户的话无需填写','val'=>$in['user_id'])); ?>
        <?php  echo submit(); ?>
    </form>
    

</div>