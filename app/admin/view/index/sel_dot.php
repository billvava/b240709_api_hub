
<body >

<div class="x-body">
   
    <div style="padding: 20px; background-color: #F2F2F2;" id="tourl">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-md12">
                <p style=" line-height: 25px;color:#666" >点击地图选择您的位置，然后点《确定》按钮即可</p>
                <div class="layui-card">
                    <div class="layui-card-body">
                        <input type="text" id="lat" class=" layui-input" style="width:250px;float:left;" placeholder="纬度" />
                        <input type="text" id="lng" class=" layui-input" style="width:250px;float:left;" placeholder="经度" />
                        <span href="javascript://" onclick="zdy_sub()" class="pear-btn pear-btn-primary ">确定</span>
                        <span href="javascript://" onclick="clear_input()" class="pear-btn pear-btn-primary ">清空</span>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-body">
                        <input type="text" id="address" class=" layui-input" style="width:250px;float:left;" placeholder="地址" />
                        <span href="javascript://" onclick="jiexi()" class="pear-btn pear-btn-primary ">解析</span>
                    </div>
                </div>
                <div id="map" style=" width:100%;height: 500px;">
                    
                </div>
                
                
            </div>



        </div>
    </div> 
</div>
</body>


<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=YAPBZ-Y2FKQ-QT652-GK2LX-FIKSJ-MYF7J"></script>
<script>
function zdy_sub(){
    parent.sel_dot_{$in['field']}( $("#lat").val(),$("#lng").val());
    var index = parent.layer.getFrameIndex(window.name); 
    parent.layer.close(index); 
}    
function clear_input(){
    $("#lat,#lng").val('');
}
function jiexi(){
    var v = $("#address").val();
    geocoder.getLocation(v);
}
function clear_marker(){
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(null);
        }
        markersArray.length = 0;
    }
}
var map,geocoder;
var markersArray = [];
$(function(){
    map = new qq.maps.Map(document.getElementById("map"), {
        // 地图的中心地理坐标。
        center: new qq.maps.LatLng(22.822996,108.345817),
        zoom: 13,
    });
    var listener = qq.maps.event.addListener(
        map,
        'click',
        function(e) {
            layer.msg('点击成功');
           $("#lat").val( e.latLng.lat);
           $("#lng").val( e.latLng.lng);
            var marker = new qq.maps.Marker({
                map: map,
                position: e.latLng
            });
            clear_marker();
            markersArray.push(marker);
        }
    );
  
    //地址和经纬度之间进行转换服务
    geocoder = new qq.maps.Geocoder();
    //设置服务请求成功的回调函数
    geocoder.setComplete(function(result) {
        $("#lat").val(result.detail.location.lat);
        $("#lng").val(result.detail.location.lng);
        layer.msg('解析成功');
        map.setCenter(result.detail.location);
        var marker = new qq.maps.Marker({
            map: map,
            position: result.detail.location
        });
        clear_marker();
         markersArray.push(marker);
        //点击Marker会弹出反查结果
        qq.maps.event.addListener(marker, 'click', function() {
//            alert("坐标地址为： " + result.detail.location);
        });
    });
    //若服务请求失败，则运行以下函数
    geocoder.setError(function() {
        alert("出错了，请输入正确的地址！！！");
    });
    //对指定地址进行解析
})
</script>