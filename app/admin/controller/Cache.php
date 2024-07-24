<?php
namespace app\admin\controller;





class Cache extends Common {


    /**
     * 清除所有缓存
     * @auto true
     * @auth true
     * @menu true
     */
    public function clear_all() {
//        $x=xf_scandir('../runtime', 1);
        \think\facade\Cache::clear();
        if(app()->request->isAjax()){
            return json(['status'=>1,'info'=>'缓存清除完成']);
        }else{
            $this->msg('缓存清除完成');
        }

    }
}