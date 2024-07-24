
<div class="x-body" >
    <ul id="zTree" class="ztree notselect" ></ul>
    <div class="hr-line-dashed"></div>
    <div class="layui-form-item text-center">
        <button class="pear-btn pear-btn-primary " data-submit-role type='button'>保存数据</button>
        <button class="pear-btn  pear-btn-danger" type='button' onclick="window.history.back()">取消编辑</button>
    </div>
</div>

<script>
    window.RoleAction = new function () {
        this.data = {};
        this.ztree = null;
        this.setting = {
            view: {showLine: false, showIcon: false, dblClickExpand: false},
            check: {enable: true, nocheck: false, chkboxType: {"Y": "ps", "N": "ps"}},
            callback: {
                beforeClick: function (id, node) {
                    node.children.length < 1 ? RoleAction.ztree.checkNode(node, !node.checked, null, true) : RoleAction.ztree.expandNode(node);
                    return false;
                }
            }
        };
        this.renderChildren = function (list, level) {
            var childrens = [];
            for (var i in list) childrens.push({
                open: true, node: list[i].node, name: list[i].title || list[i].node,
                checked: list[i].checked || false, children: this.renderChildren(list[i]._sub_, level + 1)
            });
            return childrens;
        };
        this.getData = function (that, index) {
            $.post('{:url("apply")}?v=1', {id: '{$vo.id}',role_id: '{$in.role_id}', action: 'get'},  function (ret) {
                console.log(ret);
                that.data = that.renderChildren(ret, 1);
                return  that.showTree(), false;
            },'json');
        };
        this.showTree = function () {
            this.ztree = $.fn.zTree.init($("#zTree"), this.setting, this.data);
            while (true) {
                var nodes = this.ztree.getNodesByFilter(function (node) {
                    return (!node.node && node.children.length < 1);
                });
                if (nodes.length < 1) break;
                for (var i in nodes) this.ztree.removeNode(nodes[i]);
            }
        };
        this.submit = function () {
            var nodes = [], data = this.ztree.getCheckedNodes(true);
            for (var i in data) if (data[i].node) nodes.push(data[i].node);
            ajax('{:url("nav")}',{role_id: '{$in.role_id}', action: 'save', nodes: nodes},function(){
                window.history.back();
            });
        };
        // 刷新数据
        this.getData(this);
        // 提交表单
        $('[data-submit-role]').on('click', function () {
            RoleAction.submit();
        });
    };
</script>
<link href="__LIB__/ztree/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css"/>
<script src="__LIB__/ztree/ztree.all.min.js" type="text/javascript"></script>
<style>
    ul.ztree li {
        white-space: normal !important;
    }

    ul.ztree li span.button.switch {
        margin-right: 5px;
    }

    ul.ztree ul ul li {
        display: inline-block;
        white-space: normal;
    }

    ul.ztree > li {
        padding: 15px 25px 15px 15px;
    }

    ul.ztree > li > ul {
        margin-top: 12px;
        border-top: 1px solid rgba(0, 0, 0, .1);
    }

    ul.ztree > li > ul > li {
        padding: 5px;
    }

    ul.ztree > li > a > span {
        font-weight: 700;
        font-size: 15px;
    }

    ul.ztree .level2 .button.level2 {
        background: 0 0;
    }
</style>
