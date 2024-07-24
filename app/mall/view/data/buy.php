<style>
    .search_panel{
        
    }
    .search_panel div{
        height: 40px;
        line-height: 40px;
    }
    .search_panel .title{
        font-weight: bold;
        display: inline-block;
    }
    .search_panel .link{
        width: 90px;
        display: inline-block;
        color:#000;
        text-align: center;
    }
    .search_panel .link.act{
        color:#009688;
    }
</style>
<?php
$d14 = date('Y-m-d',strtotime("-14 day"));
$d7 = date('Y-m-d',strtotime("-7 day"));
$d30 = date('Y-m-d',strtotime("-30 day"));
$d60 = date('Y-m-d',strtotime("-60 day"));
$new_time = array(
    array('name'=>'不限',"set"=>"mall_new_time_min#@mall_new_time_max#",'min'=>'','max'=>''),
    array('name'=>'1周内',"set"=>"mall_new_time_min#".$d7."@mall_new_time_max#" ,'min'=>$d7,'max'=>''),
    array('name'=>'2周内',"set"=>"mall_new_time_min#". $d14. "@mall_new_time_max#",'min'=>$d14,'max'=>''),
    array('name'=>'1个月内',"set"=>"mall_new_time_min#".$d30. "@mall_new_time_max#" ,'min'=>$d30,'max'=>''),
    array('name'=>'1个月前',"set"=>"mall_new_time_min#"."@mall_new_time_max#".$d30 ,'min'=>'','max'=>$d30),
    array('name'=>'2个月前',"set"=>"mall_new_time_min#"."@mall_new_time_max#".$d60 ,'min'=>'','max'=>$d60),
);

$order_num = array(
    array('name'=>'不限',"set"=>"mall_order_num_min#@mall_order_num_max#",'min'=>'','max'=>''),
    array('name'=>'1次+',"set"=>"mall_order_num_min#1@mall_order_num_max#",'min'=>'1','max'=>''),
    array('name'=>'2次+',"set"=>"mall_order_num_min#2@mall_order_num_max#",'min'=>'2','max'=>''),
    array('name'=>'3次+',"set"=>"mall_order_num_min#3@mall_order_num_max#",'min'=>'3','max'=>''),
    array('name'=>'10次+',"set"=>"mall_order_num_min#10@mall_order_num_max#",'min'=>'10','max'=>''),
    array('name'=>'20次+',"set"=>"mall_order_num_min#20@mall_order_num_max#",'min'=>'20','max'=>''),
);
$order_total = array(
    array('name'=>'不限',"set"=>"mall_total_min#@mall_total_max#",'min'=>'','max'=>''),
    array('name'=>'0-50',"set"=>"mall_total_min#0@mall_total_max#50",'min'=>'0','max'=>'50'),
    array('name'=>'51-100',"set"=>"mall_total_min#51@mall_total_max#100",'min'=>'51','max'=>'100'),
    array('name'=>'101-200',"set"=>"mall_total_min#101@mall_total_max#200",'min'=>'101','max'=>'200'),
    array('name'=>'201-300',"set"=>"mall_total_min#201@mall_total_max#300",'min'=>'201','max'=>'300'),
    array('name'=>'301-500',"set"=>"mall_total_min#301@mall_total_max#500",'min'=>'301','max'=>'500'),
);

$order_avg = array(
    array('name'=>'不限',"set"=>"mall_order_avg_min#@mall_order_avg_max#",'min'=>'','max'=>''),
    array('name'=>'0-20',"set"=>"mall_order_avg_min#0@mall_order_avg_max#20",'min'=>'0','max'=>'20'),
    array('name'=>'21-50',"set"=>"mall_order_avg_min#21@mall_order_avg_max#50",'min'=>'21','max'=>'50'),
    array('name'=>'51-100',"set"=>"mall_order_avg_min#51@mall_order_avg_max#100",'min'=>'51','max'=>'100'),
    array('name'=>'101-150',"set"=>"mall_order_avg_min#101@mall_order_avg_max#150",'min'=>'101','max'=>'150'),
    array('name'=>'151-200',"set"=>"mall_order_avg_min#151@mall_order_avg_max#200",'min'=>'151','max'=>'200'),
);
?>
<div class='x-body' >
    <div class="layui-collapse" lay-filter="component-panel">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">筛选查询<i class="layui-icon layui-colla-icon"></i></h2>
            <div class="layui-colla-content layui-show">
                <div class="search_panel">
                    <div>
                        <span class="title">最近消费：</span>
                        <?php foreach($new_time as $v){ ?>
                        <a href="###" class="link <?php if($in['mall_new_time_min']==$v['min'] && $in['mall_new_time_max']==$v['max']  ){ echo 'act';} ?>" onclick="set_data(this)" data-field='{$v.set}'>{$v.name}</a>
                        <?php } ?>
                        <a href="###" class="link act" onclick="$('#mul_time').toggle()">自定义</a>
                        <input type="text" class="link layui-input"  name='mul_time' value="{$in.mul_time}" readonly="" style=" display: none;"  id="mul_time"  placeholder="请选择" >
                    </div>
                    <div>
                        <span class="title">消费次数：</span>
                        <?php foreach($order_num as $v){ ?>
                        <a href="###" class="link <?php if($in['mall_order_num_min']==$v['min'] && $in['mall_order_num_max']==$v['max']  ){ echo 'act';} ?>" onclick="set_data(this)" data-field='{$v.set}'>{$v.name}</a>
                        <?php } ?>
                        <a href="###" class="link act" onclick="$('.num_zdy').toggle()">自定义</a>
                        <input type="text" class="link layui-input num_zdy"  id='num_zdy1'   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" style=" display: none;"    placeholder="请输入" >
                        <font class="link num_zdy " style=" display: none; width: 30px;" >&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;</font>
                        <input type="text" class="link layui-input num_zdy"  id='num_zdy2'   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  style=" display: none;"    placeholder="请输入" >
                        <input type="button" class=" pear-btn pear-btn-primary pear-btn-sm num_zdy"  value="确定" onclick="num_zdy_sub()"   style=" display: none;"  >
                    </div>
                    
                     <div>
                        <span class="title">消费金额：</span>
                        <?php foreach($order_total as $v){ ?>
                        <a href="###" class="link <?php if($in['mall_total_min']==$v['min'] && $in['mall_total_max']==$v['max']  ){ echo 'act';} ?>" onclick="set_data(this)" data-field='{$v.set}'>{$v.name}</a>
                        <?php } ?>
                        <a href="###" class="link act" onclick="$('.total_zdy').toggle()">自定义</a>
                        <input type="text" class="link layui-input total_zdy"  id='total_zdy1'   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" style=" display: none;"    placeholder="请输入" >
                        <font class="link total_zdy " style=" display: none; width: 30px;" >&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;</font>
                        <input type="text" class="link layui-input total_zdy"  id='total_zdy2'   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  style=" display: none;"    placeholder="请输入" >
                        <input type="button" class=" pear-btn pear-btn-primary pear-btn-sm total_zdy"  value="确定" onclick="total_zdy_sub()"   style=" display: none;"  >
                    </div>
                    
                    <div>
                        <span class="title">订单均价：</span>
                        <?php foreach($order_avg as $v){ ?>
                        <a href="###" class="link <?php if($in['mall_order_avg_min']==$v['min'] && $in['mall_order_avg_max']==$v['max']  ){ echo 'act';} ?>" onclick="set_data(this)" data-field='{$v.set}'>{$v.name}</a>
                        <?php } ?>
                        <a href="###" class="link act" onclick="$('.avg_zdy').toggle()">自定义</a>
                        <input type="text" class="link layui-input avg_zdy"  id='avg_zdy1'   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" style=" display: none;"    placeholder="请输入" >
                        <font class="link avg_zdy " style=" display: none; width: 30px;" >&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;</font>
                        <input type="text" class="link layui-input avg_zdy"  id='avg_zdy2'   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  style=" display: none;"    placeholder="请输入" >
                        <input type="button" class=" pear-btn pear-btn-primary pear-btn-sm avg_zdy"  value="确定" onclick="avg_zdy_sub()"   style=" display: none;"  >
                    </div>
                    <?php  $ranks = (new \app\user\model\User())->getRanks(true); ?>
                    <div>
                        <span class="title">会员等级：</span>
                        <a href="###" class="link <?php if($in['rank']==''  ){ echo 'act';} ?>"  onclick="set_data(this)" data-field='rank#@'>不限</a>
                        <?php foreach($ranks as $k=>$v){ ?>
                        <a href="###" class="link  <?php if($in['rank']==$k  ){ echo 'act';} ?>" onclick="set_data(this)" data-field='rank#{$k}@'>{$v}</a>
                        <?php } ?>
                    </div>
                    
                    <div>
                        <span class="title">显示条数：</span>
                        <input type="text" class="link layui-input pgae_num"  id='page_num' value="{$data['page_num']}"   onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  placeholder="请输入" >
                        <input type="button" class=" pear-btn pear-btn-primary pear-btn-sm pgae_num"  value="确定" onclick="$('input[name=page_num]').val($('#page_num').val());$('#buy').submit()"  >
                        <a  class=" pear-btn pear-btn-primary pear-btn-sm pgae_num" href="{:url('buy')}">重置</a>
                    </div>
                    
               </div>
               
            </div>

        </div>
    </div>
    <form action="{:url('buy')}" id="buy">
        <input type="hidden" name="mall_total_min" value="{$in.mall_total_min}"  />
        <input type="hidden" name="mall_total_max" value="{$in.mall_total_max}"  />
        <input type="hidden" name="mall_order_num_min" value="{$in.mall_order_num_min}"  />
        <input type="hidden" name="mall_order_num_max" value="{$in.mall_order_num_max}"  />
        <input type="hidden" name="mall_order_avg_min" value="{$in.mall_order_avg_min}"  />
        <input type="hidden" name="mall_order_avg_max" value="{$in.mall_order_avg_max}"  />
        <input type="hidden" name="mall_new_time_min" value="{$in.mall_new_time_min}"  />
        <input type="hidden" name="mall_new_time_max" value="{$in.mall_new_time_max}"  />
        <input type="hidden" name="rank" value="{$in.rank}"  />
        <input type="hidden" name="p" value="1"  />
         <input type="hidden" name="page_num" value="{$data['page_num']}"  />
    </form>
    <div class="table-container">
        <table class="layui-table layui-form" >
            
            <thead>
                 <tr>
                    <th colspan="100">
                        <a href="javascript://" onclick="send_coupon()" class=" pear-btn pear-btn-primary pear-btn-sm">赠送优惠券</a>
                        <a href="javascript://" onclick="send_msg()" class=" pear-btn pear-btn-primary pear-btn-sm">发送站内信</a>
                        <a href="javascript://" onclick="send_wx()"  class=" pear-btn pear-btn-primary pear-btn-sm">发送微信消息</a>
                        <!--<a href="javascript://" onclick="send_xls()" class=" pear-btn pear-btn-primary pear-btn-sm">导出数据</a>-->
                        <a href="javascript://" onclick="set_rank()" class=" pear-btn pear-btn-primary pear-btn-sm">修改等级</a>
                    </th>
                </tr>
                <tr class="user-head">
                    <th  class="w50">
                            <a  class="x-a" href="javascript://" onclick="$('input[type=checkbox]').prop('checked',true)" >全选</a> | 
                <a class="x-a"  href="javascript://" onclick="$('input[type=checkbox]').each(function(){var t = $(this); t.prop('checked',!t.is(':checked')); })">反选</a>
                    </th>
                    <th class="w50">用户ID</th>
                    <th>用户名</th>
                    <th>会员等级</th>
                    <th>消费金额</th>
                    <th>消费次数</th>
                    <th>订单均价</th>
                    <th>最近购买时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['list'] as $v){ ?>
                <tr class="user-data-{$v.user_id}">
                    <th  class="w50">
                       <input type="checkbox" lay-ignore value="<?php echo $v['user_id']; ?>" name="user_id[]"/>
                    </th>
                    <th class="w50">{$v.user_id}</th>
                    <th>{$v.username}</th>
                    <th><?php echo $ranks[$v['rank']]; ?></th>
                    <th>{$v.mall_total}</th>
                    <th>{$v.mall_order_num}</th>
                    <th>{$v.mall_order_avg}</th>
                    <th>{$v.mall_new_time}</th>
                    <th><a href="{:url('user/Index/item_show',array('id'=>$v['user_id']))}">查看</a></th>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        {$data.page|raw}
    </div>
</div>
<a href="" download="购买力筛选.xlsx" id="downloadA"></a>

<script src="__LIB__/xlsx.full.min.js" type="text/javascript"></script>
<script type="text/javascript">
function set_data(obj){
    var t = $(obj);
    var field = t.data('field');
    var arr =  field.split("@");
    for(var i in arr){
        var item = arr[i];
        var kv = item.split("#");
        $("input[name="+kv[0]+"]").val(kv[1]);
    }
    $("#buy").submit();
}
$(function(){
    layui.use('laydate', function(){
        var laydate = layui.laydate;
         //日期范围
        laydate.render({
           theme: '#677ae4',
          elem: '#mul_time'
          ,range: true,
          done: function(value, date, endDate){
               var arr =  value.split(" - ");
               console.log(arr);
               $("input[name=mall_new_time_min]").val(arr[0]);
               $("input[name=mall_new_time_max]").val(arr[1]);
               $("#buy").submit();
          }
        });
        
    });
    
    layui.use(['slider'],function() {
        slider = layui.slider;
       //定义初始值
        slider.render({
          elem: '#slide'
          ,value: 20
          ,min: 1
            ,max: 100,
           setTips: function(value){ 
            return value ;
           },
           change: function(value){
           
          }
        });
       
        
    });
})
function num_zdy_sub(){
   $("input[name=mall_order_num_min]").val($('#num_zdy1').val()); 
   $("input[name=mall_order_num_max]").val($('#num_zdy2').val()); 
   $("#buy").submit();
}
function total_zdy_sub(){
    $("input[name=mall_total_min]").val($('#total_zdy1').val()); 
   $("input[name=mall_total_max]").val($('#total_zdy2').val()); 
   $("#buy").submit();
}

function avg_zdy_sub(){
    $("input[name=mall_order_avg_min]").val($('#avg_zdy1').val()); 
   $("input[name=mall_order_avg_max]").val($('#avg_zdy2').val()); 
   $("#buy").submit();
}


function get_ids(){
    var ids = [];
    $("input:checkbox:checked").each(function() {
        ids.push($(this).val());
    });
    if(ids.length <= 0){
        
        return null;
    }else{
        return ids;
    }
}

function send_coupon(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    show_url('{:url('Coupon/send')}?user_id='+ids.join(','));
}

function send_msg(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    show_url('{:url('user/Msg/item')}?user_id='+ids.join(','));
}

function send_wx(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    show_url('{:url('user/Msg/wx_msg')}?user_id='+ids.join(','));
}

function set_rank(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    show_url('{:url('user/Index/set_rank')}?user_id='+ids.join(','));
}

function send_xls(){
    var ids = get_ids();
    if(ids==null){
        layer.alert('请先选择');
        return;
    }
    var h = $(".user-head");
    var he = h.find('th').length - 2;
    var jsono = [];
    for(var i in ids){
        var user_id = ids[i];
        var t =  $(".user-data-"+user_id);
        var p = {
            "用户ID": t.find("th:eq(1)").text(),
            "用户名": t.find("th:eq(2)").text(),
            "会员等级":t.find("th:eq(3)").text(),
            "消费金额":t.find("th:eq(4)").text(),
            "消费次数":t.find("th:eq(5)").text(),
            "订单均价":t.find("th:eq(6)").text(),
            "最近购买时间":t.find("th:eq(7)").text(),
        }
        jsono.push(p);
    }
   
      downloadExl(jsono);
        var tmpDown; //导出的二进制对象
        function downloadExl(json, type) {
        	//根据json数据，获取excel的第一行(例如:姓名、年龄、性别)存至map
            var tmpdata = json[0];
            json.unshift({});
            var keyMap = []; //获取keys
            for (var k in tmpdata) {
                keyMap.push(k);
                json[0][k] = k;
            }
            
            
            var tmpdata = [];
            json.map((v, i) => keyMap.map((k, j) => Object.assign({}, {
                v: v[k],
                position: (j > 25 ? getCharCol(j) : String.fromCharCode(65 + j)) + (i + 1)
            }))).reduce((prev, next) => prev.concat(next)).forEach((v, i) => tmpdata[v.position] = {
                v: v.v
            });
          	
          	//设置区域,比如表格从A1到D10
            var outputPos = Object.keys(tmpdata); 
            var tmpWB = {
                SheetNames: ['mySheet'], //保存的表标题
                Sheets: {
                    'mySheet': Object.assign({},
                        tmpdata, //内容
                        {
                            '!ref': outputPos[0] + ':' + outputPos[outputPos.length - 1] //设置填充区域
                        })
                }
            };
            
          	//创建二进制对象写入转换好的字节流
            tmpDown = new Blob([s2ab(XLSX.write(tmpWB, 
                {bookType: (type == undefined ? 'xlsx':type),bookSST: false, type: 'binary'}//这里的数据是用来定义导出的格式类型
                ))], {
                type: ""
            });
          	
            var href = URL.createObjectURlang(tmpDown); //创建对象超链接
            document.getElementById("downloadA").href = href; //绑定a标签
            document.getElementById("downloadA").click(); //模拟点击实现下载
            setTimeout(function() { //延时释放
                URL.revokeObjectURlang(tmpDown); //用URL.revokeObjectURlang()来释放这个object URL
            }, 100);
        }
 
      	//字符串转字符流
        function s2ab(s) { 
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }
      	
        //将指定的自然数转换为26进制表示。映射关系：[0-25] -> [A-Z]。
        function getCharCol(n) {
            let temCol = '',
            s = '',
            m = 0
            while (n > 0) {
                m = n % 26 + 1
                s = String.fromCharCode(m + 64) + s
                n = (n - m) / 26
            }
            return s
        }
}
</script>