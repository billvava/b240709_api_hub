
<body >

<style>
#tourl a{ margin-right: 5px;}
</style>
<div class="x-body">
    <?php
    $arr = array(
        array(
            array('name'=>'首页','url'=>'/pages/index/index'),
            array('name'=>'清空','url'=>'')
        ),
        array(
            array('name'=>'我的','url'=>'/pages/user/index/index'),
            array('name'=>'优惠券','url'=>'/pages/user/coupon/coupon'),
            array('name'=>'领取优惠券','url'=>'/pages/user/get_coupon/get_coupon'),
        ),
        array(
              array('name'=>'购物车','url'=>'/pages/goods/cart/cart'),
              array('name'=>'分类','url'=>'/pages/goods/cate/cate'),
              array('name'=>'拼团商品','url'=>'/pages/goods/pt_list/pt_list'),
              array('name'=>'秒杀商品','url'=>'/pages/goods/ms_list/ms_list'),
        ), 
                     
        array(
            array('name'=>'公告','url'=>'/pages/content/gonggao/gonggao'),
            array('name'=>'帮助中心','url'=>'/pages/content/help/help'),
            array('name'=>'意见反馈','url'=>'/pages/user/yijian/yijian'),
        ),
       
    );
    ?>

    <div style="padding: 20px; background-color: #F2F2F2;" id="tourl">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <input type="text" id="zdy" class=" layui-input" style="width:250px;float:left;" placeholder="自定义" />
                        <span href="javascript://" onclick="zdy_sub()" class="pear-btn pear-btn-primary ">确定</span>
                    </div>
                </div>
                <?php foreach($arr as $v){ ?>
                <div class="layui-card">
                    <div class="layui-card-body">
                        <?php foreach($v as $vv){ ?>
                        <a href="javascript://" data-url="{$vv.url}" class="pear-btn pear-btn-primary ">{$vv.name}</a>
                         <?php } ?>
                    </div>
                </div>
                <?php } ?>
               
                <div class="layui-card">
                    <div class="layui-card-body clearfix" >
                        
                            <div id="goods_id_div"  >
                                <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
                                    <label class="layui-form-label">商城商品链接</label>
                                    <div class="layui-input-block">
                                        <div>
                                            <select  data-url="/pages/goods/item/item?goods_id="   id="goodscid" lay-ignore  class="inline w200">
                                                <option value="">请输入商品名称</option>
                                            </select>
                                            <span href="javascript://"  onclick="qd('goodscid')" class="pear-btn pear-btn-primary ">确定</span>
                                        </div>
                                        <div class="x-a mt5"></div>    
                                    </div>
                                </div>

                            </div>
                        
                        
                            <div id="goods_id_div"  >
                                <div class="layui-form-item  layui-col-xs8 layui-col-md8" id="contentshow_goods_id">
                                    <label class="layui-form-label">拼团商品链接</label>
                                    <div class="layui-input-block">
                                        <div>
                                            <select  data-url="/pages/goods/item/item?is_pt=1&goods_id="   id="ptgoods" lay-ignore  class="inline w200">
                                                <option value="">请输入商品名称</option>
                                               
                                            </select>
                                            <span href="javascript://"  onclick="qd('ptgoods')" class="pear-btn pear-btn-primary ">确定</span>
                                        </div>
                                        <div class="x-a mt5"></div>    
                                    </div>
                                </div>

                            </div>
                        
                            
                        
                    </div>
                </div>
                
            </div>



        </div>
    </div> 
</div>

<script src="__LIB__/select2/js/select2.full.min.js" type="text/javascript"></script>
<link href="__LIB__/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
<script>
$(function(){
  $("#tourl a").bind('click',function(){
        var main=$(window.parent.document);
        var url=$(this).data('url');
        var index = parent.layer.getFrameIndex(window.name); 
        main.find("#contentshow_link input").val(url);
        parent.layer.close(index); 
  });
  
});

function qd(cs){
    var t = $("#"+cs);
    if(t.val()){
        var url = t.data('url');
        var new_url = url+t.val();
        var main = $(window.parent.document);
        main.find("#contentshow_link input").val(new_url);
        parent.layer.close(parent.layer.getFrameIndex(window.name)); 
    }
   
    
}


function formatRepo(repo){return repo.text}
function formatRepoSelection(repo){return repo.text}

$("#goodscid").select2({
    language: 'zh-CN',
  ajax: {
    url: "{:url('Mall/MallCouponTpl/getgoods')}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        name: params.term,
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

$("#ptgoods").select2({
    language: 'zh-CN',
  ajax: {
    url: "{:url('Mall/MallCouponTpl/getgoods')}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        name: params.term,
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

//自定义
function zdy_sub(){
    var v = $("#zdy").val();
    var main=$(window.parent.document);
    var index = parent.layer.getFrameIndex(window.name); 
    main.find("#contentshow_link input").val(v);
    parent.layer.close(index); 
}
$("#xmxq").select2({
    language: 'zh-CN',
  ajax: {
    url: "{:url('Open/ShopPro/get_select')}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        name: params.term,
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


$("#zxxq").select2({
    language: 'zh-CN',
  ajax: {
    url: "{:url('Open/ShopNews/get_select')}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        name: params.term,
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


$("#jsxq").select2({
    language: 'zh-CN',
  ajax: {
    url: "{:url('Yy/YyTeach/get_select')}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        name: params.term,
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
</body>

