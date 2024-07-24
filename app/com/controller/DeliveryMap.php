<?php

namespace app\com\controller;

use think\facade\Db;
use think\facade\View;

/**
 * 配送区域
 * @auto true
 * @auth true
 * @menu true
 * */
class DeliveryMap extends Common {

    public function index() {

        if (request()->isPost()) {
            if (empty($this->in['positions'])) {
                $this->error('请选择配送范围');
            }

            $readyData = [];
            foreach ($this->in['positions'] as $k => $v) {
                $position = array_filter(explode('|', $v));
                $readyData[$k]['positions'] = json_encode($position);
            }

            if (Db::name('delivery_map')->insertAll($readyData)) {
                $this->success(lang('s'));
            }
            $this->error('操作失败');
        }
        $map = Db::name('delivery_map')->select();
        $cover = '';
        if ($map) {
            foreach ($map as $k => $v) {
                $paths = '[';
                if ($v['positions']) {
                    $positions = json_decode($v['positions'], true);
                    if ($positions) {
                        foreach ($positions as $key => $val) {
                            $paths .= 'new TMap.LatLng(' . $val . '),';
                        }
                        $paths = rtrim($paths, ',');
                    }
                }
                $paths .= ']';
                $cover .= '{';
                $cover .= "'id': 'cover_" . $v['id'] . "',"; //该多边形在图层中的唯一标识（删除、更新数据时需要）
                $cover .= "'styleId': 'polygon',"; //绑定样式名
                $cover .= "'paths': " . $paths; //多边形轮廓
                $cover .= '},';
            }
            $cover = rtrim($cover, ',');
        }
        return View::fetch('', [
                    'map' => $map,
                    'cover' => $cover,
                    'showCover' => ($cover ? 1 : 0),
        ]);
    }


    public function del() {
        if (Db::name('delivery_map')->where(['id' => $this->in['id']])->delete()) {
            $this->success(lang('s'));
        }
        $this->error('删除失败，请刷新重试');
    }

}
