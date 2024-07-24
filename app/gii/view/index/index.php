<?php 
$admin_app = C('admin_app');
$arr = [
    [
        'url'=>url('Crud/index'),
        'name'=>'增删改查'
    ],
    [
        'url'=>url('CreateApp/index'),
        'name'=>'应用生成'
    ],
    [
        'url'=>url('CreateTable/index'),
        'name'=>'表参考'
    ],
     [
        'url'=>url('CreateReport/index'),
        'name'=>'统计图生成'
    ],
    [
        'url'=>url('Poster/index'),
        'name'=>'海报生成'
    ],
    [
        'url'=>url($admin_app.'/ErrorLog/index'),
        'name'=>'错误日志'
    ],
    
    [
        'url'=>url($admin_app.'/Plug/index'),
        'name'=>'应用商店'
    ],
    [
        'url'=>url($admin_app.'/SystemConfigCate/index'),
        'name'=>'配置分组'
    ],
    [
        'url'=>url($admin_app.'/SystemConfig/index'),
        'name'=>'配置设置'
    ],
    [
        'url'=>url('other/index'),
        'name'=>'其他杂项'
    ],
];
$c = request()->controller();
?>
<ul class="layui-nav layui-nav-tree layui-inline" lay-filter="demo" style="position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 120px; border-radius: 0;">
    <?php foreach($arr as $k=>$v){ ?>
    <li class="layui-nav-item <?php if( $k==0){ echo ' layui-this'; } ?>"><a href="{$v.url}" target="main">{$v.name}</a></li>
    <?php } ?>
<!--  <li class="layui-nav-item layui-nav-itemed">
    <a href="javascript:;">默认展开</a>
    <dl class="layui-nav-child">
      <dd><a href="javascript:;">选项一</a></dd>
      <dd><a href="javascript:;">选项二</a></dd>
      <dd><a href="javascript:;">选项三</a></dd>
      <dd><a href="">跳转项</a></dd>
    </dl>
  </li>-->
</ul>
<iframe class="iframe2" id="main"  name="main" src="<?php echo $arr[0]['url']; ?>"></iframe>