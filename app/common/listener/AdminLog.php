<?php
namespace app\common\listener;

use think\facade\Db;

class AdminLog
{
    public function handle()
    {
        // 事件监听处理
        if(!config('my.admin_log')){
            return false;
        }
        $request=request();
        $adminInfo = session('admin');
        if($adminInfo){
            $controller_name = strtolower(request()->controller());
            $action_name = strtolower(request()->action());
            $appname=App('http')->getName();

            $admin_module=config('my.admin_module');

            if(!in_array($appname,$admin_module)){
                return false;
            }

            if($appname=='admin'){
                $module_name=strtolower(C('my.admin_app'));
                if(in_array($controller_name,['index'])){ //不执行
                    return false;
                }
            }else{
                $module_name=strtolower($appname);
            }

            if(in_array($action_name,['index'])){ //不记录查询列表
                return false;
            }




            //当前节点
            $index = $module_name . '/' . $controller_name . '/' . $action_name;

            //操作名称
            $info=Db::name('admin_nav')->where(['node'=>$index])->cache(600)->find();
            $name=$info['name'];
            if($info['pid']){
                $pname=Db::name('admin_nav')->where(['id'=>$info['pid']])->value('name');
                $name=$pname.'/'.$name;
            }

            $param=$request->param();
            $param=json_encode($param);

            $add['node'] = $index;
            $add['create_time']=date('Y-m-d H:i:s');
            $add['admin_id']=$adminInfo['id'];
            $add['name']=$name?$name:'';
            $add['param']=$param;

            Db::name('system_log')->insert($add);
        }




    }
}