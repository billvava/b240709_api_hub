{include file="goods/item_css" /}

<script type="text/javascript">
 var specIndex = 0;
</script>
        <xblock>
            <a class="pear-btn pear-btn-primary pear-btn-sm" href="{:url('index')}">返回列表</a>
            {include file="goods/copy_taobao" /}
        </xblock>
<input type="file" style="display: none" onchange="_upload_spec_thumb(this)" data-spec_index="" data-child_index=""  id="lrz_upload_finder" accept="image/*"  capture="camera" />
<form class="layui-form" id="goods_from">
<input type="hidden" name="p"  value="{$in.p}" />
<input type="hidden" name="is_up_spec" id="is_up_spec" value="0" />
<input type="hidden" name="is_up_attr" id="is_up_attr" value="0" />
<input type="hidden" name="goods_id" id="goods_id" value="{$goodsInfo['goods_id']}" />
<div class="layui-card">
    <div class="layui-card-body">
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
                <li>价格设置</li>
                 <li>商品详情</li>
                   <li>扩展属性</li>
                  
            </ul>
            <div class="layui-tab-content" style="overflow: hidden;">
                <div class="layui-tab-item layui-show">
                    <div id="base_div" >
                    
                
                        <div class="layui-form-item">
                                  <?php echo input_small(array(
                               'val'=>$goodsInfo['name'],
                               'field'=>'name',
                               'fname'=>'商品名称<span style="color:red">*</span>',
                               'msg'=>''
                           )); ?>
                            <?php echo input_small(array(
                               'val'=>$goodsInfo['small_title'],
                               'field'=>'small_title',
                               'fname'=>'副标题',
                               'msg'=>''
                           )); ?>



                        </div>

                         <div class="layui-form-item">
                             <div class='layui-inline'  id="category_id_div" >
                               <label class='layui-form-label'>商品类目</label>
                               <div class='layui-input-inline'>
                                       <select name='category_id' id="category_id"  lay-filter="category_id"   >
                                           <option value=''>请选择</option>
                                            <?php echo (new \app\mall\model\GoodsCategory())->getAllOptionHtml($goodsInfo['category_id']); ?>
                                         </select>
                               </div>
                               <div class='x-a mt5'></div>
                             </div>


                             <!-- <div class='layui-inline'  id="" >
                                 <label class='layui-form-label'>供应商</label>
                                 <div class='layui-input-inline'>
                                     <select name="shop_id">
                                         <option value="">选择</option>
                                         <?php if(is_array($shop_list)){ foreach($shop_list as $k=>$v){ ?>
                                             <option value="{$k}" <?php if($goodsInfo['shop_id']==$k ){echo 'selected=""';} ?>>{$v}</option>
                                         <?php } } ?>
                                     </select>
                                 </div>
                                 <div class='x-a mt5'></div>
                             </div> -->

                             <div class='layui-inline'  id="category_id_div" >
                               <label class='layui-form-label'>商品品牌</label>
                               <div class='layui-input-inline'>
                                     <select name='brand_id' >
                                       <option value=''>请选择</option>
                                        <?php echo (new \app\mall\model\GoodsBrand())->getAllOptionHtml($goodsInfo['brand_id']); ?>
                                     </select>
                               </div>
                               <div class='x-a mt5'></div>
                             </div>

                             
                             <?php $deli_tpls = (new \app\mall\model\MallDelivery())->getOption();  ?>
                            <div class='layui-inline'  >
                               <label class='layui-form-label'>运费模板</label>
                               <div class='layui-input-inline'>
                                     <select name='delivery_id' >
                                       <option value=''>请选择</option>
                                         {foreach name='deli_tpls' key='k' item='v'}
                                       <option value='{$k}' <?php if($goodsInfo['delivery_id']==$k){echo "selected=''";} ?>>{$v}</option>
                                        {/foreach}
                                     </select>
                               </div>
                               <div class='x-a mt5'></div>
                             </div>

                        </div>


                             <div class="layui-form-item">
                                 <?php echo input_small(array(
                                   'val'=>$goodsInfo['sale_num'],
                                   'field'=>'sale_num',
                                   'fname'=>'销量',
                                   'msg'=>''
                               )); ?>

                                 <?php echo input_small(array(
                                   'val'=>$goodsInfo['wares_no'],
                                   'field'=>'wares_no',
                                   'fname'=>'货号',
                                   'msg'=>''
                               )); ?>

                               <?php echo input_small(array(
                                   'val'=>$goodsInfo['unit'],
                                   'field'=>'unit',
                                   'fname'=>'计量单位',
                                   'msg'=>''
                               )); ?>
                             </div>

                               <?php
                            //    echo thumb(array(
                            //        'fname'=>'商品缩略图',
                            //        'field'=>'thumb',
                            //        'defaultvalue'=>$goodsInfo['thumb_file'],
                            //        'column'=>1,
                            //        'serverUrl'=>url(ADMIN_MODULE.'/Upload/upload_image'),
                            //        'col'=>2
                            //    ));

                               echo photo(array(
                                    'fname'=>'商品缩略图',
                                    'field'=>'thumb',
                                    'defaultvalue'=>$goodsInfo['thumb_file'],
                                    'select_num' => 1
                                ));

                               echo radio(array(
                                   'val'=>$goodsInfo?$goodsInfo['status']:1,
                                   'field'=>'status',
                                   'fname'=>'状态',
                                   'msg'=>'',
                                   'items'=>array(
                                       array('val'=>1,'name'=>'上架'),
                                       array('val'=>0,'name'=>'下架'),
                                   )
                               ));

                               echo radio(array(
                                   'val'=>$goodsInfo?$goodsInfo['is_recommend']:0,
                                   'field'=>'is_recommend',
                                   'fname'=>'推荐',
                                   'msg'=>'',
                                   'items'=>array(
                                       array('val'=>1,'name'=>'是'),
                                       array('val'=>0,'name'=>'否'),
                                   )
                               ));

                               echo radio(array(
                                   'val'=>$goodsInfo?$goodsInfo['is_new']:0,
                                   'field'=>'is_new',
                                   'fname'=>'特价',
                                   'msg'=>'',
                                   'items'=>array(
                                       array('val'=>1,'name'=>'是'),
                                       array('val'=>0,'name'=>'否'),
                                   )
                               ));



                               ?>
                               <div class="layui-form-item layui-col-xs6">
                                    <label class="layui-form-label"></label>
                                    <div class="layui-input-block">
                                        <button class="pear-btn pear-btn-primary " type="button" onclick="save()">
                                        保存
                                    </button>
                                    </div>
                                </div>
                        </div>
                </div>
                <div class="layui-tab-item">
                      <div id="sale_div" >
                            <div class="goods-content">
                                <div class="layui-card-body" pad15>
                                    <div lay-filter="">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label"><span class="form-label-asterisk">*</span>商品规格：</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="spec_type" lay-filter="spec-type" value="1" title="统一规格" checked>
                                                <input type="radio" name="spec_type" lay-filter="spec-type" value="2" title="多规格">
                                            </div>
                                        </div>
                                        <div class="layui-form-item" style="display: none">
                                            <label class="layui-form-label"></label>
                                            <div class="layui-input-block goods-spec-div" id="goods-spec-project">
                                            </div>
                                        </div>
                                        <div class="layui-form-item" style="display: none">
                                            <label class="layui-form-label"></label>
                                            <button class="layui-btn layui-btn-normal layui-btn-sm" type="button" id="add-spec" lay-verify="add_more_spec"
                                                    lay-verType="tips" autocomplete="off" switch-tab="1" verify-msg="至少添加一个规格">添加规格项目
                                            </button>
                                        </div>
                                        <div class="layui-form-item" id="one-spec-lists">
                                            <label class="layui-form-label">规格明细：</label>
                                            <div class="layui-input-block goods-spec-div">
                                                <table id="one-spec-lists-table" class="layui-table spec-lists-table" lay-size="sm">
                                                    <colgroup>
                                                        <col width="60px">
                                                    </colgroup>
                                                    <thead>
                                                    <tr style="background-color: #f3f5f9">
                                                        <?php foreach($goods_spec_field as $v){ ?>
                                                        <th>
                                                            <?php if($v['is_must']==1){ ?>
                                                            <span class="form-label-asterisk">*</span>
                                                            <?php } ?>
                                                            {$v.name}</th>
                                                        <?php } ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <?php foreach($goods_spec_field as $v){ ?>
                                                        <th>
                                                            <?php if($v['type']=='img'){ ?>
                                                            <div class="goods-spec-img-div"><input name="one_{$v.field}" type="hidden"
                                                                                                   value=""><img
                                                                    src="__STATIC__/admin/images/upload.png"
                                                                    class="goods-one-spec-img-add"></div>
                                                            <?php } ?>
                                                             <?php if($v['type']=='number'){ ?>
                                                           <input type="number" class="layui-input"
                                                                   autocomplete="off" switch-tab="1" 
                                                                   name="one_{$v.field}">
                                                            <?php } ?>
                                                            
                                                            
                                                          </th>
                                                        <?php } ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="layui-form-item" id="more-spec-lists" style="display: none">
                                            <label class="layui-form-label">规格明细：</label>
                                            <div class="layui-input-block goods-spec-div">
                                                <div class="batch-div"><span class="batch-spec-title">批量设置：</span>
                                                    <div>
                                                        <?php foreach($goods_spec_field as $v){ ?>
                                                        <th>
                                                            <?php if($v['type']!='img'){ ?>
                                                            <span class="batch-spec-content click-a" input-name="{$v.field}">{$v.name}</span>
                                                            <?php } ?>
                                                          
                                                          </th>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <table id="more-spec-lists-table" class="layui-table spec-lists-table" lay-size="sm">
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="layui-form-item layui-col-xs6">
                                    <label class="layui-form-label"></label>
                                    <div class="layui-input-block">
                                        <button class="pear-btn pear-btn-primary " type="button" onclick="save()">
                                        保存
                                    </button>
                                    </div>
                                </div>
                </div>
                <div class="layui-tab-item">
                    <div id="content_div" >
                                <?php 

                        //   echo images(
                        //           array(
                        //               'field'=>'images',
                        //               'fname'=>'商品组图',
                        //               'serverUrl'=>url(ADMIN_MODULE.'/Upload/upload_image'),
                        //               'val'=>   zutu_val('images', $goodsInfo['images_file']),
                        //               'msg'=>''
                        //               )
                        //           );
                        echo photo(array(
                            'fname'=>'商品组图',
                            'field'=>'images',
                            'defaultvalue'=>$goodsInfo['images_file'],
                            'select_num' => 5
                        ));
                        ?>
                          <?php echo editor(
                                  array(
                                      'field'=>'content',
                                      'fname'=>'商品详情',
                                      'defaultvalue'=>  htmlspecialchars_decode($goodsInfo['content']) 
                                      )
                                  ); ?>
                        <div class="layui-form-item layui-col-xs6">
                                    <label class="layui-form-label"></label>
                                    <div class="layui-input-block">
                                        <button class="pear-btn pear-btn-primary " type="button" onclick="save()">
                                        保存
                                    </button>
                                    </div>
                                </div>
                    </div>
                </div>

                <div class="layui-tab-item">
                   
                    <div class="layui-row">
                     <div class='layui-form-item  layui-col-xs8 layui-col-md8 ' id="category_id_div" >
                        <label class='layui-form-label'>属性组</label>
                         <?php  $attr_group = (new \app\mall\model\GoodsAttr())->getAll(); ?>
                        <div class='layui-input-block'>
                            <div>
                                <select  id="attr_id" lay-ignore onchange="get_attr_data()" name="attr_id">
                                 <option value="">请选择</option>   
                                {foreach name='attr_group' item='v'}
                                <option value="{$v.attr_id}" <?php if($v['attr_id']==$goodsInfo['attr_id']){echo "selected=''";} ?>>{$v.name}</option>
                                {/foreach}
                                </select>
                           </div>
                            <div class='x-a mt5 layui-text'><a href="javascript://" onclick="del('{:url('GoodsAttr/index')}','确定离开当前页面吗？')">去设置属性</a></div>
                        </div>
                      </div>
                    </div>
                    <div id="attr_child_div">

                    </div>
                    <div class="layui-form-item layui-col-xs6">
                                    <label class="layui-form-label"></label>
                                    <div class="layui-input-block">
                                        <button class="pear-btn pear-btn-primary " type="button" onclick="save()">
                                        保存
                                    </button>
                                    </div>
                                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

{include file="goods/item_js" /}
{include file="goods/item_tpl" /}