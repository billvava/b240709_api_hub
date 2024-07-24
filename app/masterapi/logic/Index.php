<?php

namespace app\masterapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoCate;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use think\App;
use think\facade\Db;

class Index
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function index()
    {   
        $type = $this ->in['type']?:'master_banner_index';
        $this->data['banner'] = (new Ad())->get_ad($type);
        return ['status' => 1, 'data' => $this->data];
    }

   

    public function getsuotype()
    {
        $this->data['order_type_arr'] = lang('order_type_arr');
        return ['status' => 1, 'data' => $this->data];
    }

    public function getTixian()
    {
        $this->data['content'] = ((new SystemGroup())->getCacheOne('tixian')) ['content'];
        return ['status' => 1, 'data' => $this->data];
    }

    public function cate()
    {
        $this->data['list'] = (new SuoCate())->getAll(['status' => 1, 'pid' => 0]);
        array_unshift($this->data['list'], ['name' => '全部', 'id' => '']);

        $this->data['order_type_arr'] = lang('order_type_arr');

        return ['status' => 1, 'data' => $this->data];
    }

    public function product_list()
    {
        $this->data = (new SuoProduct())->getMapList($this->in);
        return ['status' => 1, 'data' => $this->data];
    }
    public function ptremark()
    {
        $this->data['content'] = ((new SystemGroup())->getCacheOne($this->in['key'])) ['content'];
        return ['status' => 1, 'data' => $this->data];
    }
    public function load_time()
    {
        $shijianduan = ((new SystemGroup())->getCache('shijianduan'));
        $data = [];
        for ($i = 1; $i <= 10; $i++) {

            $tmp = $shijianduan;
            foreach ($tmp as &$v) {
                $v['start'] = mb_substr($v['start'],0,5);
                $v['end'] = mb_substr($v['end'],0,5);

                $v['time'] = "{$v['start']}-{$v['end']}";

            }
            $jia = $i - 1;
            $str = date('m月d日', strtotime("+{$jia} day"));
            if ($i == 1) {
                $h = date('H');

                foreach ($tmp as $k => $v) {

                    $ss = explode(':', $v['start']);
//                    $ee = explode(':', $v['end']);
                    if ($ss[0] <= $h) {
                        unset($tmp[$k]);
                    }
                }
                $str = "今天" . date('m.d');
            }else  if ($i == 2) {
                $str = "明天" . date('m.d', strtotime("+{$jia} day"));
            }else if ($i == 3) {
                $str = "后天" . date('m.d', strtotime("+{$jia} day"));
            }

            $data[] = [
                'name' => $str,
                'date' => date('Y-m-d', strtotime("+{$jia} day")),
                'list' => array_values($tmp)
            ];

        }
        return ['status' => 1, 'data' => $data];
    }
}
