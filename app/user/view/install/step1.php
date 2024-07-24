<style>
    .text-success{
        color: green;
    }
    .text-danger{
        color: red;
    }
</style>

        <div class="panel panel-success">
            <div class="panel-body">
                <table class="layui-table table-bordered table-hover table-striped">
                    <thead>
                        <tr><th colspan="3" class="space text-primary">运行环境检测</th></tr>
                        <tr>
                            <th>项目</th>
                            <th>所需配置</th>
                            <th>当前配置</th>
                        </tr>
                    </thead>
                    <tbody>
                        {volist name="env" id="item"}
                        <tr>
                            <td>{$item[0]}</td>
                            <td>{$item[1]}</td>
                            <td class="text-{$item[4]}">{$item[3]}</td>       
                        </tr>
                     {/volist}
                   
                    </tbody>
                </table>
                
                 <table class="layui-table table-bordered table-hover table-striped">
                    <thead>
                        <tr><th colspan="3" class="space text-primary">目录、文件权限检查</th></tr>
                        <tr>
                            <th>目录/文件</th>
                            <th>所需状态</th>
                            <th>当前状态</th>
                        </tr>
                    </thead>
                    <tbody>
                                {volist name="dirfile" id="item"}
                            <tr>
                                <td>{$item[3]}</td>
                                <td>可写</td>
                                <td class="text-{$item[2]}">{$item[1]}</td>   
                            </tr>
                       {/volist}

                    </tbody>
                </table>
                
                
                <table class="layui-table table-bordered table-hover table-striped">
                    <thead>
                        <tr><th colspan="3" class="space text-primary">表名检查</th></tr>
                        <tr>
                            <th>表名</th>
                            <th>所需状态</th>
                            <th>当前状态</th>
                        </tr>
                    </thead>
                    <tbody>
                                {volist name="tab" id="item"}
                            <tr>
                                <td>{$item[0]}</td>
                                <td>{$item[1]}</td>
                                <td class="text-{$item[3]}">{$item[2]}</td>   
                            </tr>
                       {/volist}

                    </tbody>
                </table>
                <?php if(session('error')==true){ ?>
                <p><a href="###" class="btn btn-primary <?php if(session('error')==true){echo 'disabled';} ?> btn-block">下一步</a></p>
                <?php } ?>
                <p><a href="{:url('step2')}" class="pear-btn pear-btn-primary pear-btn-block">下一步</a></p>
            </div>
        </div>
 