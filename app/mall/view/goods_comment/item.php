<?php 
$uinfo = (new \app\common\model\User())->getUserInfo($info['user_id']);
$name = \think\facade\Db::name('mall_goods')->where(array('goods_id'=>$info['goods_id']))->cache(true)->value('name');
?>
<link href="__ADMIN__/css/template.css" rel="stylesheet" type="text/css"/>
<div class="layui-fluid layadmin-homepage-fluid x-body">
    <div class="layui-row layui-col-space8">
        <div class="layui-col-md2">
            <div class="layadmin-homepage-panel layadmin-homepage-shadow">
                <div class="layui-card text-center">
                    <div class="layui-card-body">
                        <div class="layadmin-homepage-pad-ver">
                            <img class="layadmin-homepage-pad-img" src="{$uinfo.headimgurl}" width="96" height="96">
                        </div>
                        <h4 class="layadmin-homepage-font">{$uinfo.username}</h4>
                        <p class="layadmin-homepage-min-font">{$uinfo.rank_name}</p>
                        
                    </div>
                </div>
                
                <ul class="layadmin-homepage-list-group">
                    <li class="list-group-item">商品：{$name}</li>
                    <li class="list-group-item">日期：{$info.time}</li>
                </ul>
                <div style=" padding-left: 15px">星级：<div  id="rate"></div></div>
               
               
            </div>
        </div>
        <div class="layui-col-md10">
            <div class="layui-fluid layadmin-homepage-content">
             
                <div class="layui-row layui-col-space20 layadmin-homepage-list-imgtxt">
                    <div class="layui-col-md9">
                        <div class="grid-demo">
                            <div class="panel-body layadmin-homepage-shadow">
                                <a href="javascript:;" class="media-left">
                                    <img src="{$uinfo.headimgurl}" height="46px" width="46px">
                                </a>
                                <div class="media-body">
                                    <div class="pad-btm">
                                        <p class="fontColor"><a href="javascript:;">{$uinfo.username}</a> <span>发表的评价：</span></p>
                                        <p class="min-font">
                                            <span class="layui-breadcrumb" lay-separator="-" style="visibility: visible;">
                                                <a href="javascript:;" class="layui-icon layui-icon-cellphone"></a><span lay-separator="">-</span>
                                                <a href="javascript:;">{$info.time}</a>
                                            </span>
                                        </p>         
                                    </div>
                                    <p>{$info.content|raw}</p>
                                     <?php $img = json_decode($info['images'],true); ?>
                                    <div class="media">
                                        
                                        <div class="media-left">
                                            <ul class="list-inline">
                                                <?php if($img){ foreach($img as $v){ if(!$v){     continue;} ?>
                                                <li>
                                                    <a target="_blank" href="<?php echo get_img_url($v); ?>">
                                                        <img class="img-xs" src="<?php echo get_img_url($v); ?>">
                                                    </a>
                                                </li>
                                                <?php } } ?>
                                              
                                            </ul>
                                        </div>
                                    </div>
<!--                                    <div class="media-list">
                                        
                                        <div class="media-item">
                                        
                                          <div class="media-text">
                                            <div>
                                                <a href="javascript:;">管理员回复：</a>
                                            </div>
                                            <div>{$info.reply}</div>
                                          </div>
                                        </div>
                                        
                                      </div>-->
                                   
                                    
                                </div>
                            </div>
<!--                            <div class="panel-body layadmin-homepage-shadow">
                            
                                <div class="media-body">
                                    <div class="pad-btm">
                                        <p class="fontColor">管理员回复：</p>
                                              
                                    </div>
                                    <p>
                                        <?php echo form_start(array('url'=>url('item'))); ?>
                                        <?php echo hide_input(array('field'=>'id','val'=>$info['id'])); ?>
                                        <?php echo textarea(array('fname'=>'','field'=>'reply','val'=>$info['reply'])); ?>
                                        <?php echo submit(); ?>
                                        <?php echo form_end(); ?>
                                    </p>
                                    
                                </div>
                            </div>-->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
     layui.use(['rate'],
        function() {
            rate = layui.rate;
            rate.render({
                elem: '#rate'
                ,value: {$info.star}
                ,readonly: true
              });
    });
})
</script>