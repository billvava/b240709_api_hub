
<div class='col-md-12 x-body'>
  <div class="layui-card">
      <div class="layui-card-body">
          <table id="table" lay-filter="table"></table>
      </div>
  </div>
</div>
<script type="text/html" id="toolbar">
    <button class="pear-btn pear-btn-primary pear-btn-sm" onclick="show_url('/xf/admin_nav/item')">
        <i class="layui-icon layui-icon-add-1"></i>
        新增
    </button>
    <button class="pear-btn pear-btn-success pear-btn-md" lay-event="expandAll">
        <i class="layui-icon layui-icon-spread-left"></i>
        展开
    </button>
    <button class="pear-btn pear-btn-success pear-btn-md" lay-event="foldAll">
        <i class="layui-icon layui-icon-shrink-right"></i>
        折叠
    </button>
    <a class="pear-btn pear-btn-warm pear-btn-md" href="{:url('up_all_node')}">
        <i class="layui-icon layui-icon-chart"></i>
        扫描更新所有权限
    </a>
</script>

<script type="text/html" id="bar">
    <button class="pear-btn pear-btn-primary pear-btn-sm" onclick="ajax('{:url('copy')}',{id:'{{d.id}}'})">复制</button>
    <button class="pear-btn pear-btn-primary pear-btn-sm" onclick="show_url('/xf/admin_nav/item?id={{d.id}}')"><i class="layui-icon layui-icon-edit"></i></button>
    <button class="pear-btn pear-btn-danger pear-btn-sm" onclick="del_nav('{{d.id}}')"><i class="layui-icon layui-icon-delete"></i></button>
</script>

<script type="text/html" id="sort">
  <input data-key="id" data-keyid="{{d.id}}" data-field="sort" data-url="/xf/admin_nav/set" value="{{d.sort}}" type="text" class="layui-input" data-type="setval">
</script>

<script type="text/html" id="url">
  {{#if (d.type == '2') { }}
    {{d.url}}
  {{# }else if(d.type == '1'){ }}
    {{d.lev1}}/{{d.lev2}}/{{d.lev3}}{{d.param}}
  {{# } }}
</script>

<script type="text/html" id="status">
    <input type="checkbox" data-type="setval" data-key="id" data-keyid="{{d.id}}" data-field="status" data-url="/xf/admin_nav/set" lay-filter="setval"  name="status" value="{{d.id}}" lay-skin="switch" lay-text="显示|隐藏"  {{ d.status == 1 ? 'checked=""' : '' }}>
</script>

<script type="text/html" id="icon">
    <i class="iconfont {{d.icon}}"></i>
</script>


<script>
    layui.use(['table','form','jquery','treetable'],function () {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        let treetable = layui.treetable;

        treetable.render({
                treeColIndex: 1,
                treeSpid: 0,
                treeIdName: 'id',
                treePidName: 'pid',
                skin:'line',
                treeDefaultClose: false,
                toolbar:'#toolbar',
                defaultToolbar: [],
                elem: '#table',
                url: '/xf/admin_nav/json',
                page: false,
                cols: [
                    [
                    {field: 'sort', width: 80, title: '排序',templet:'#sort'},
                    {field: 'name', minWidth: 200, title: '名称'},
                    {field: 'icon', title: '图标',templet:'#icon'},
                    {title: '网址',templet:'#url'},
                    {field: 'node', title: '权限',},
                    {field: 'status', title: '显示',templet:'#status'},
                     {field: 'isauto', title: '自动',templet:'#isauto'},
                    {title: '操作',templet: '#bar', width: 200, align: 'center'}
                    ]
                ]
        });


        table.on('toolbar(table)', function(obj){
            if(obj.event === 'expandAll'){
                treetable.expandAll("#table");
            } else if(obj.event === 'foldAll'){
                treetable.foldAll("#table");
            }
        });

		
    })


function del_nav(id){
    layer.confirm("确定删除吗？", function() {
        ajax("{:url('del')}",{id:id},function(){
            $(".child-parent[data-id="+id+"]").fadeOut();
        });
    });
}
</script>