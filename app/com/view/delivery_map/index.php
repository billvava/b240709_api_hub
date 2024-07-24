<script charset="utf-8" src="https://map.qq.com/api/gljs?libraries=tools&v=1.exp&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<style type="text/css">
    html,
    body {
        height: 100%;
        margin: 0px;
        padding: 0px;
    }

    #container {
        width: 100%;
        height: 80%;
    }

	.toolItem {
		width: 30px;
		height: 30px;
		float: left;
		margin: 1px;
		padding: 4px;
		border-radius: 3px;
		background-size: 30px 30px;
		background-position: 4px 4px;
		background-repeat: no-repeat;
		box-shadow: 0 1px 2px 0 #E4E7EF;
		background-color: #ffffff;
		border: 1px solid #ffffff;
	}
	.toolItem:hover {
		border-color: #789CFF;
	}
	.active {
		border-color: #D5DFF2;
		background-color: #D5DFF2;
	}
  .layui-elem-quote{
    overflow: hidden;
  }
  #map_box{
    overflow: hidden;
    width: 100%;
    height: auto;
    float: left;
  }
</style>

<body onload="initMap()">
<blockquote class="layui-elem-quote">
  <span class="fr" style='margin-right: 10px;'><a href="JavaScript:;" class="layui-btn layui-btn-sm" id="sub">保存范围</a></span>
  <span style='color: #677ae4'>开始绘制</span>：鼠标左键<span class="x-red">点击</span>及<span class="x-red">移动</span>即可绘制图形。<br>
  <span style='color: #677ae4'>结束绘制</span>：鼠标左键<span class="x-red">双击</span>即可结束绘制。<br>
  <span style='color: #677ae4'>中断绘制</span>：绘制过程中按下<span class="x-red">ESC键</span>可中断该过程。<br>
  <form action='' method='post' id='form'><id id="positions"></id></form>
  </blockquote>

<?php if ($map) { ?>
  <blockquote class="layui-elem-quote">
      {foreach name='map' item='v' key='k'}
      <span class="fl del_map" data-positions='{$v.positions}' data-id='{$v.id}' style='margin: 0 10px 10px 0;'>
        <a href="JavaScript:;" class="layui-btn layui-btn-sm" style='background: #f56c6c !important;'>删除范围{$k + 1}</a>
      </span>
      {/foreach}
  </blockquote>
<?php } ?>
  <div id='map_box'>
    <div id="container"></div>
    <div class="toolItem" style='display: none;' id="polygon"></div>
    <script type="text/javascript">

    $('#sub').click(function () {
        $('#form').submit();
    })

        function initMap() {
              // 初始化地图
              map = new TMap.Map("container", {
                  zoom: 12, // 设置地图缩放级别
                  center: new TMap.LatLng(22.78121, 108.27331) // 设置地图中心点坐标
        });
        
        // 初始化几何图形及编辑器
        editor = new TMap.tools.GeometryEditor({
          map, // 编辑器绑定的地图对象
          overlayList: [ // 可编辑图层
            {
              overlay: new TMap.MultiPolygon({
                map
              }),
              id: 'polygon'
            }
          ],
          actionMode: TMap.tools.constants.EDITOR_ACTION.DRAW, // 编辑器的工作模式
          activeOverlayId: 'marker', // 激活图层
          snappable: true // 开启吸附
        });
        
        editor.setActiveOverlay('polygon');

        // 监听绘制结束事件，获取绘制几何图形
        editor.on('draw_complete', (geometry) => {
            // 后续升级按配送范围设置价格
            // layer.prompt({
            //     'title': '配送价格',
            //     yes: function(value, index, elem){
            //         layer.close(index)
            //     },
            //     cancel: function (index) {
            //         layer.close(index)
            //     },
            //     btn2: function (index) {
            //         layer.close(index)
            //     }
            // });
          var val = '';
          for (var i = 0; i < geometry.paths.length; i++) {
            val += geometry.paths[i].lat + ',' + geometry.paths[i].lng + '|';
          }
          $('#positions').append("<input type='hidden' value='" + val + "' name='positions[]'>");
        });

        if ({$showCover}) {
          //初始化polygon
          var polygon = new TMap.MultiPolygon({
              id: 'polygon', //图层id
              map: map, //设置多边形图层显示到哪个地图实例中
              geometries: [{$cover}]
          });

          // 预览
          var preview_polygon = new TMap.MultiPolygon({
              id: 'polygon1', //图层id
              map: map, //设置多边形图层显示到哪个地图实例中
              styles: {
                  'polygon': new TMap.PolygonStyle({
                      'color': '#ea644a', //面填充色
                      // 'borderColor': '#e75033' //边线颜色
                  })
              }
          });
          $('.del_map').hover(function () {
            var positions = $(this).data('positions');
            var preview_path = [];
            for (var i = 0; i < positions.length; i++) {
              var position = positions[i].split(',');
              preview_path[i] = new TMap.LatLng(position[0], position[1]);
            }
            preview_polygon.add([
                  {
                      'id': 'preview_layer', //该多边形在图层中的唯一标识（删除、更新数据时需要）
                      'styleId': 'polygon', //绑定样式名
                      'paths': preview_path, //多边形轮廓
                  }
              ]);
          }, function () {
            preview_polygon.remove('preview_layer');
          })

          $('.del_map').click(function () {
            var _this = $(this);
            var id = _this.data('id');
            layer.confirm('确定删除该范围吗?', function(index){
              ajax('{:url("del")}', {id: id}, function () {
                polygon.remove('cover_' + id);
                preview_polygon.remove('preview_layer');
                _this.remove();
                layer.close(index);
              });
            });       
          })
        }
      }
      </script>
</div>