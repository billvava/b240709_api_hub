<style>
    .wytb{ text-align: center;}
    .baont{
        margin-top:15px;
    }
    .layui-col-xs2{
        padding:0 10px;height: 270px;margin-bottom: 20px;
    }
    .layui-col-xs2 .panel{
        border:1px solid #dddddd;box-sizing: border-box;border-radius: 4px;
    }
    .layui-col-xs2 .panel-body{padding: 15px;}
    .layui-col-xs2 .panel-body h1{
        font-size: 26px;text-align: center;margin: 20px 0 10px;
    }
    .layui-col-xs2 .panel-body p{margin: 0 0 10px;}

</style>

<div class='x-body'>
    <p>安装后，请去扫描<a href="<?php echo url('AdminNav/up_all_node'); ?>">生成菜单</a>，然后清除缓存</p>
    <div class="table-container">
        <table class="layui-table" >
            <thead>
                <tr>
            <th>名称</th>
            <th>类型</th>
            <th>大小</th>
            <th>版本</th>
            <th>更新</th>
            <th>操作</th>
             </tr>
            </thead>
            <tbody id="plug_div">
                
                
                        

            
                
                
            </tbody>
        </table>
       </div>
    
    <div class='panel' id="pass_div">
        <div class='panel-body'>
            <p id="install_msg" style=" display: none;" class=" text-danger">文件正在下载中，请不要关闭浏览器。。。</p>
            <section class="row" >
                <input type="text" value="" id="pwd" class="layui-input" placeholder="请输入密码，提示：wifi密码" />
                <input type="button" onclick="if($('#pwd').attr('type')=='text'){$('#pwd').attr('type','password');}else{$('#pwd').attr('type','text')}" class="pear-btn pear-btn-primary  pear-btn-sm baont" value="切换显示"  />
                <input type="button" onclick="getjsonp();" class="pear-btn pear-btn-primary  pear-btn-sm baont" value="确定"  />
            </section>

        </div>
    </div>
</div>
<script type="text/html" id='news_tpl'>
    <%if(isNotEmpty(data)){%>
        <%for(i in data){%>
            <% var v=data[i];  %>
            <tr>
                <td>
                    <p><%:=v.name%></p>
                     <p style="color:#19a72c;max-width: 200px;"><%:=v.desc%></p>
                </td>
                <td>
                <p><%:=v.type%></p>
                <p><%:=v.token%></p>
                </td>
                <td><%:=v.size%></td>
                <td>
                    <p>当前：<%:=v.versions%></p>
                    <p>系统要求：<%:=v.minversions%> ~ <%:=v.maxversions%></p>
                </td>
                <td><%:=v.updatetime%></td>
                <td>
                    <form method="post" target="_blank" action="<?php echo url('install'); ?>">
                        <%for(j in v){%>
                        <input type="hidden" name="<%:=j%>" value="<%:=v[j]%>"  />
                        <%}%>
                    </form>
                     <% var f = 0; for(j in logs){ if(logs[j] == v.token){ f=1; %>
                     <button class="pear-btn pear-btn-sm pear-btn-success  "  type="button">已安装</button>
                     <% } %>
                     <% } %>
                     
                     <% if(f==0){ %>
                      <button class="pear-btn pear-btn-sm anzhuang "  type="button">开始安装</button>
                      <% } %>
                    </td>
             </tr>
        <%}%>
    <%}%>
</script>
<script type="text/javascript" src="__LIB__/template.js"></script>
<script type="text/javascript">
var logs = [<?php foreach($logs as $k=>$v){ echo "'".$v."',";} ?>]    
function getjsonp(){
    var token = '<?php echo C('apikey'); ?>';
    var pwd = $("#pwd").val();
    $.ajax({
        type: "post",
        async: false,
        data:{},
        url: '<?php echo C('apiurl'); ?>/api/index/appstore?token='+token+'&pwd='+pwd,
        dataType: "jsonp",
        jsonp: "callback",
        jsonpCallback:"callback",
        success: function(data){
            var render = template($("#news_tpl").html(), {
                data: data
            });
            $("#plug_div").html(render);
            $("#pass_div").hide();
        },error: function(){
            layer.alert('数据加载失败，可能密钥错误了。');
        }
    });
}
$(function(){
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            getjsonp();
        }
    });
    //        getjsonp();
    //点击安装
    $("body").on('click','.anzhuang',function(data){
        var t = $(this);
        layer.confirm('确定要安装？', {
        btn: ['确定','取消'] //按钮
        }, function(){
            t.siblings('form').submit();
            layer.closeAll();
            return false;
        }, function(){
            layer.closeAll();
        });
    })
    //jsonp加载

})
</script>