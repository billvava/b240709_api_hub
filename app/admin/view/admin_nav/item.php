<div class='col-md-12 x-body'>

    <div class='panel' >
        {include file='admin_nav/top' /}
        <form class="layui-form mt10"  action="{:url('item')}" method="post">
            <input type="hidden"  name="id" value="{$info.id}" >
            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                    <span class="x-red"></span>父级菜单
                </label>
                <div class='layui-input-block'>
                    <div>
                        <select class="form-control select2"  lay-ignore  name="pid">
                        <option value="0">无</option>
                        {foreach name="plist" item="v"}
                        {if condition="$v['id'] neq $info['id']"}
                        <option value="{$v.id}" {if condition="$v['id'] eq $info['pid']"}selected="selected"{/if} ><?php echo str_repeat('--',$v['levs']); ?>{$v.name}</option>
                        {/if}
                        {/foreach}
                        </select>
                    </div>
                    <div class='layui-word-aux mt5'></div>       
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                    <span class="x-red">*</span>菜单名称
                </label>
                <div class='layui-input-block'>
                    <div >
                        <input type="text" name="name" value="{$info.name}" id="name" class="layui-input" required  >
                    </div>
                    <div class='layui-word-aux mt5'></div>       
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                图标class
                </label>
                <div class='layui-input-block'>
                    <div >
                        <input type="text"  name="icon" value="{$info.icon}" placeholder="不需要前面icon-，只支持一级菜单" class="layui-input"   >
                    </div>
                    <div class='layui-word-aux mt5'><a href='https://www.layui.com/doc/element/icon.html' target="_blank">查看图标</a></div>       
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                打开方式
                </label>
                <div class='layui-input-block'>
                    <div >
                        <input type="text"  name="target" value="{$info.target}"  class="layui-input"   >
                    </div>
                    <div class='layui-word-aux mt5'>目前支持：main，_blank，_self.默认为main</div>       
                </div>

            </div>

            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                    自动生成
                </label>
                <div class='layui-input-block'>
                    <div >
                        <label>
                            <input type="radio"   lay-ignore   name="isauto" value="1"  {if condition="($info.isauto eq 1) "}checked="checked"{/if} > 是
                        </label>
                        <label>
                            <input type="radio"   lay-ignore   name="isauto" value="0" {if condition="($info.isauto eq '0') "}checked="checked"{/if} > 否
                        </label>
                    </div>
                    <div class='layui-word-aux mt5'></div>
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                是否展开
                </label>
                <div class='layui-input-block'>
                    <div >
                        <label>
                            <input type="radio"   lay-ignore   name="is_show" value="1"  {if condition="($info.is_show eq 1) or ($info.is_show eq '')"}checked="checked"{/if} > 是
                        </label>
                        <label>
                            <input type="radio"   lay-ignore   name="is_show" value="0" {if condition="($info.is_show eq '0') "}checked="checked"{/if} > 否
                        </label>
                    </div>
                    <div class='layui-word-aux mt5'></div>       
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                是否生成菜单
                </label>
                <div class='layui-input-block'>
                    <div >
                        <label>
                            <input type="radio"   lay-ignore    name="ismenu" value="1"  {if condition="($info.ismenu eq 1) or ($info.ismenu eq '')"}checked="checked"{/if} > 是
                        </label>
                        <label>
                            <input type="radio"    lay-ignore    name="ismenu" value="0" {if condition="($info.ismenu eq '0') "}checked="checked"{/if} > 否
                        </label>
                    </div>
                    <div class='layui-word-aux mt5'></div>       
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                是否判断权限
                </label>
                <div class='layui-input-block'>
                    <div >
                        <label>
                            <input type="radio"     lay-ignore name="isauth" value="1"  {if condition="($info.isauth eq 1) or ($info.isauth eq '')"}checked="checked"{/if} > 是
                        </label>
                        <label>
                            <input type="radio"     lay-ignore  name="isauth" value="0" {if condition="($info.isauth eq '0') "}checked="checked"{/if} > 否
                        </label>
                    </div>
                    <div class='layui-word-aux mt5'></div>       
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
            <label  class="layui-form-label">
                权限节点
                </label>
                <div class='layui-input-block'>
                    <div >
                        <input  name="node" value="{$info.node}" class="layui-input"    >
                    </div>
                    <div class='layui-word-aux mt5'>例如:/Xf/admin/index</div>       
                </div>

            </div>
            <?php $nav_type = lang('nav_type'); ?>
            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                链接类型
                </label>
                <div class='layui-input-block'>
                    <label>
                        <input type="radio"    lay-ignore  name="type" value="1" <?php if($info['type']==1 ){echo 'checked="checked"';} ?> > {$nav_type.1}
                    </label>
                    <label>
                        <input type="radio"    lay-ignore   name="type" value="2" <?php if($info['type']==2 || !$info){echo 'checked="checked"';} ?> > {$nav_type.2}
                    </label>
                </div>

            </div>


            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                链接地址
                </label>
                <div class='layui-input-block'>
                    <div >
                        <input  name="url" value="{$info.url}" class="layui-input"    >
                    </div>
                    <div class='layui-word-aux mt5'>站内链接用/开头，外部链接请加http://</div>       
                </div>

            </div>

            <div class="layui-form-item  layui-col-xs10 layui-col-md8">
                <label  class="layui-form-label">
                系统链接设置
                </label>
                <div class='layui-input-block'>
                    <div >
                        <input  value="{$info.lev1}"  name="lev1" placeholder="模块名" class="layui-input "   >
                        <input   value="{$info.lev2}"  name="lev2"   placeholder="控制器名" class="layui-input mt5"   >
                        <input   value="{$info.lev3}"  name="lev3"   placeholder="方法名" class="layui-input mt5"   >
                        <input   value="{$info.param}"  name="param"   placeholder="其他参数，格式：?catid=10&show=5"  class="layui-input mt5"   >
                    </div>
                    <div class='layui-word-aux mt5'></div>       
                </div>

            </div>

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label"></label>
                <button  class="pear-btn pear-btn-primary " lay-filter="add" lay-submit="">
                保存
                </button>
            </div>
        </form>
    </div>
</div>
<?php echo W('js/select2');  ?>
<script type="text/javascript">
$(function(){
    $("input[name=type]").change(function(){
        var t = $(this);
        var v = t.val();
        if(v=='1'){
            $("#type1").addClass('show').removeClass('hide');
            $("#type2").addClass('hide').removeClass('show');
        }else{
            $("#type2").addClass('show').removeClass('hide');
            $("#type1").addClass('hide').removeClass('show');

        }
    })
})
</script>