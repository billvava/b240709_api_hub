<?php

namespace app\suoapi\logic;

use app\admin\model\Ad;
use app\admin\model\City;
use app\admin\model\SuoCate;
use app\admin\model\SuoMaster;
use app\admin\model\SuoOrder;
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
        $this->data['home_banner'] = (new Ad())->get_ad('home_banner');
        return ['status' => 1, 'data' => $this->data];
    }

    public function pingtaigonggao()
    {
        $this->data['content'] = ((new SystemGroup())->getCacheOne('pingtaigonggao')) ['content'];
        return ['status' => 1, 'data' => $this->data];
    }

    public function getsuotype()
    {
        $this->data['order_type_arr'] = lang('order_type_arr');
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

    public function ptremark2()
    {
        $this->data = ((new SystemGroup())->getCache($this->in['key']));
        return ['status' => 1, 'data' => $this->data];
    }
    public function load_time()
    {
        $shijianduan = ((new SystemGroup())->getCache('shijianduan'));
        $data = [];
        $type = $this -> in['type'];

        $interval = C('interval');//分钟间隔
        $minute = 24 * 60;//一天有多少分钟
        $minute_num = $minute/$interval;

        if($type==1){


            $time_array =  [];
            for ($k=1;$k <= 7; $k++){
                $jia = $k - 1;
                $str = date('m月d日', strtotime("+{$jia} day"));

                if($k==1){
                    $str = "今天" . date('m.d');
                    $time_array[0]['start'] = '立即上门';
                    $time_array[0]['end'] = '';
                    $time_array[0]['time'] = '立即上门';
                    $day_start_time = strtotime('Y-m-d');
                }else{
                    $day_start_time = strtotime(date('Y-m-d', strtotime("+{$jia} day")));
                }

                for ($j = 1; $j <= $minute_num-1; $j++){
                    $time_str = date( 'H:i',$day_start_time+60*$interval*$j);
                    if($time_str > date('H:i') && time() > $day_start_time && $k==1){ //当天时间

                        $time_array[$j]['start'] = $time_str;
                        $time_array[$j]['end'] = '';
                        $time_array[$j]['time'] = $time_str;
                    }elseif(time() < $day_start_time && $k>1){
                        $time_array[$j-1]['start'] = $time_str;
                        $time_array[$j-1]['time'] = $time_str;
                    }
                }

                $data[] = [
                    'name' => $str,
                    'date' => date('Y-m-d', strtotime("+{$jia} day")),
                    'list' => array_values($time_array)
                ];

            }



//            dump($data);exit;
        }else{
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

                    foreach ($tmp as $k => $v2) {

                        $ss = explode(':', $v2['start']);
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
        }

        return ['status' => 1, 'data' => $data];
    }

    public function sel_city(){
       $this->data['list'] =  (new City())->getAll(['status'=>1]);
        return ['status' => 1,'data'=>$this->data];

    }

    public function master_list(){
        $model = (new SuoMaster());
       $res =  $model->getMapList([
            'page'=>$this->in['page'],
            'status'=>1,
            'is_auth'=>1,
            'is_work'=>1,
           'lat'=>$this->in['lat'],
           'lng'=>$this->in['lng'],
           'level'=>1,

       ]);
       $this->data = array_merge($this->data,$res);
        return ['status' => 1,'data'=>$this->data];

    }

    public function master_item(){
        $model = (new SuoMaster());
        $this->data['info'] =  $model->getMapList([
            'page'=>$this->in['page'],
            'status'=>1,
            'is_auth'=>1,
            'is_work'=>1,
            'lat'=>$this->in['lat'],
            'lng'=>$this->in['lng'],
            'type'=>1,
            'find'=>1,
            'id'=>$this->in['id']
        ]);
        $this->data['comment_count'] = Db::name('suo_order_comment')->where([
            'master_id'=>$this->in['id']
        ])->count();
        return ['status' => 1,'data'=>$this->data];
    }


    public function master_comment(){
        $model = (new SuoOrder());
        $res =  $model->getcommentlist([
            'page'=>$this->in['page'],
            'status'=>1,
            'master_id'=>$this->in['id']
        ]);
        $this->data = array_merge($this->data,$res);

        return ['status' => 1,'data'=>$this->data];
    }


    public function lat_to_address(){
        $map_key = C('map_key');
       $res = file_get_contents("https://apis.map.qq.com/ws/geocoder/v1/?key={$map_key}&location={$this->in['lat']},{$this->in['lng']}");
        $this->data['address'] = '未知';
        $res = json_decode($res,true);
            if($res['result']){
                $this->data['address'] = str_replace('广西壮族自治区','广西',$res['result']['address']) ;
            }
        return ['status' => 1,'data'=>$this->data];

    }
}
