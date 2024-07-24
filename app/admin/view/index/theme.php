<style>
    .store-list-box {
    display: block;
    background-color: #FFF;
    transition: all .5s;
    -webkit-transition: all .5s;
    padding-bottom: 6px;
     max-width: 100%;
}
.store-list-box img {
    width: 100%;
    height: 230px;
}
.temp-hot .store-list-box h2 {
    padding: 20px 10px;
    text-align: center;
}
.act{
    border: 10px solid #009688;
}
</style>
<?php 
$arr = array(
    
    array(
        'name'=>'默认',
        'img'=>"006FatLvgy1g2rqchl267j311y0iodho.jpg",
        'theme'=>"",
    ),
    
    array(
        'name'=>'黑色',
        'img'=>"006FatLvgy1g280z6a7i5j31hd0u0gsx.jpg",
        'theme'=>"theme_black",
    ),
    array(
        'name'=>'绿色',
        'img'=>"006FatLvgy1g2t96iaqj1j31ny0u0441.jpg",
        'theme'=>"theme_green",
    ),
    array(
        'name'=>'橘红',
        'img'=>"006FatLvgy1g2si5cksqzj318g0lb76l.jpg",
        'theme'=>"theme_orange",
    ),
    
);
?>
<div class="x-body">
    

   <div class="layui-row layui-col-space20">
       <?php foreach($arr as $v){ ?>
        <div class="layui-col-xs6 layui-col-md3">
         <a class="template store-list-box " href="javascript://" data-theme="{$v.theme}">
             <img src="__ADMIN__/images/{$v.img}" class="store-list-cover" />
             <h2 class="layui-elip" style="text-align: center">{$v.name}</h2>
         </a>
        </div>
       <?php } ?>
       
   </div>
 </div>
<script type="text/javascript">
    $(function() {
        $(".template").click(function() {
            var t = $(this);
            var theme = t.data('theme');
            $.post('__SELF__', {admin_theme: theme}, function(data) {
//                document.location.reload();
                  parent.location.reload();
            });

        });
        var theme = '<?php echo getTheme(); ?>';
        $(".template").each(function() {
            var t = $(this);
            var ti = t.data('theme');
            if (ti == theme) {
                t.addClass('act');
            }
        })
    })
</script>