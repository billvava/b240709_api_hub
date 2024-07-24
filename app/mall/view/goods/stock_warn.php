


<div class='x-body' >
    
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" action="{:url('stock_warn')}">
            <input type="text"   name='num' value=""  placeholder="预警数量"  class="layui-input ">
            <button class="pear-btn pear-btn-primary pear-btn-sm"  type="submit" >确定</button>
        </form>
    </div>
    
    
    <div class="table-container">
        
        
        
        
        

        <table class="layui-table layui-form" >
   
    <thead>
        <tr>
             <th>商品</th>
            <th>当前数量</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        {foreach name='data' item='v' key='k' }
          <tr>
              <th >{$v.name}</th>
              <th>{$v.num}</th>
              <th><a href="{:url('item',array('goods_id'=>$v['goods_id'],'nav'=>'sale'))}">去编辑</a></th>
           </tr>
        {/foreach}
        <?php if(!$data){ ?>
        <tr>
            <th colspan="3" >暂无数据</th>
          
           </tr>
        <?php } ?>
    </tbody>
</table>
    </div>
</div>

