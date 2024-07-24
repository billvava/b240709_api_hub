<?php

namespace app\mallapi\logic;

use app\mallapi\model\O;
use think\App;
use think\facade\Db;

class Fenxiao
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {
        $this->model = new \app\common\model\User();
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
        $this->data['uinfo'] = $this->uinfo;
        $this->data['finance'] = $this->model->getFinance($this->uinfo['id']);
        $this->data['history'] = $this->model->getHistoryBro($this->uinfo['id']);
        $this->data['history_day'] = $this->model->getHistoryBro($this->uinfo['id'], date('Y-m-d'));
        $this->data['history_day30'] = $this->model->getHistoryBro($this->uinfo['id'], date('Y-m-d', strtotime('-30 day')));
        $team = $this->team();
        $this->data['t_list'] = $team['data']['list'];
        return array('status' => 1, 'data' => $this->data);
    }

    public function countBonus($user_id)
    {
        $user_bonus = Db::name('user_bonus');
        $bonus_set = C("bonus");
        $bonus_where_s = array(
            'user_id' => $user_id,
            'time' => array('elt', date('Y-m-d H:i:s', strtotime('- ' . $bonus_set . 'days'))),
            'type' => 1,
        );
        $bonus_where_z = array(
            'user_id' => $user_id,
            'type' => 2,
        );
        //收入
        $income = $user_bonus->where($bonus_where_s)->sum('total');
        //支出
        $pay = $user_bonus->where($bonus_where_z)->sum('total');
        $total = number_format($income - $pay, 2, '.', '');
        return $total;
    }

    public function get_sale_count()
    {
        $where = [];
        switch ($this->in['sale_count']) {
            case 1:
                $where[] = ['create_time', '>', date('Y-m-d')];
                break;
            case 2:
                $where[] = ['create_time', '>', date('Y-m-d', strtotime('-7 day'))];
                break;
            case 3:
                $where[] = ['create_time', '>', date('Y-m-d', strtotime('-30 day'))];
                break;
            case 4:

                break;
        }

        $arr = Db::name('user')->where(array('pid' => $this->uinfo['id']))->field('id')->select()->toArray();
        if (!$arr) {
            $where['user_id'] = 0;
        } else {
            $arr = array_column($arr, 'id');
            $where[] = ['user_id', 'in', $arr];
        }
        $order = Db::name('mall_order');
        $data['count'] = $order->where($where)->count();
        $data['total'] = $order->where($where)->sum('total') + 0;
        return array('status' => 1, 'data' => $data);
    }

    public function fans()
    {
        $where = [];
        if ($this->in['tag'] == 2) {
            $where[] = ['pid2', '=', $this->uinfo['id']];
        } else {
            $where[] = ['pid1', '=', $this->uinfo['id']];
        }
        if ($this->in['nickname']) {
            $where[] = ['nickname', 'like', "%{$this->in['nickname']}%"];
        }
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $model = Db::name('user_parent');
        $data['list'] = $model
            ->alias('a')
            ->where($where)
            ->join('user b', 'a.user_id =b.id')
            ->field('a.*,headimgurl,nickname,create_time')
            ->order('user_id desc')
            ->page($page, 20)
            ->select()->toArray();
        $mall = new \app\mall\model\Order();
        foreach ($data['list'] as &$v) {
            $t = $mall->getUserAnay($v['user_id'], 3600);
            $v['goods_total'] = $t['goods_total'];
            $v['count'] = $t['count'];
            $v['headimgurl'] = get_img_url($v['headimgurl']);
        }

        return array('status' => 1, 'data' => $data);
    }

    public function team()
    {
        return $this->fans();
    }

    public function sale_order()
    {
        $mall = new \app\mall\model\Order();
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $order = $mall->getSaleOrder($this->uinfo['id'], $page);
        $this->data['list'] = $order['list'];

        $this->data['count'] = count($order['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    public function ewm()
    {
        $o = new O();
        $this->data['imgs'] = $o->create_ad_share($this->uinfo['id']);
        $this->data['ewm'] = $this->data['imgs'] [0];

        $this->data['uinfo'] = $this->uinfo;
        return array('status' => 1, 'data' => $this->data);
    }
    public function ewm2(){
        $o = new O();
        $this->data['imgs'] = $o->create_ad_share($this->uinfo['id'],2);
        $this->data['ewm'] = $this->data['imgs'] [0];

        $this->data['uinfo'] = $this->uinfo;
        return array('status' => 1, 'data' => $this->data);
    }
    public function fans_yq(){
        $this->data['leiji'] =   Db::name('user_bro')->where([
                ['user_id','=',$this->uinfo['id']],
                ['type','=',1],
                ['cate','in',[5]],

            ])->sum('total')+0;

        $this->data['user_brodj'] =   Db::name('user_brodj')->where([
                ['user_id','=',$this->uinfo['id']],
                ['expire','>',date('Y-m-d H:i:s')],
                ['cate','in',[5]],

            ])->sum('total')+0;
        $Ad = new \app\admin\model\Ad();
        $ad3 = $Ad->get_ad('yqhy_bg');
        $bg = $ad3[0]['big'];
        $this->data['share_bg'] = $bg;
        $this->data['share_num'] = Db::name('user_ext')->where(['user_id'=>$this->uinfo['id']])->value('share_num');
        return ['status' => 1, 'data' => $this->data];
    }

    public function suo_yq(){
        $this->data['leiji'] =   Db::name('user_bro')->where([
                ['user_id','=',$this->uinfo['id']],
                ['type','=',1],
                ['cate','in',[6]],

            ])->sum('total')+0;

        $this->data['user_brodj'] =   Db::name('user_brodj')->where([
                ['user_id','=',$this->uinfo['id']],
                ['expire','>',date('Y-m-d H:i:s')],
                ['cate','in',[5]],

            ])->sum('total')+0;

        $this->data['share_num'] = Db::name('user_ext')->where(['user_id'=>$this->uinfo['id']])->value('share_master_num');
        return ['status' => 1, 'data' => $this->data];
    }

    //生成海报
    public function create_haibao()
    {
        $ewm = "https://xfbase.oss-cn-shenzhen.aliyuncs.com/chen/shouji/com/qr-icon.png";
//        $ewm=C('reverse_proxy')."/Mall/index/qr/user_id/{$this->uinfo['id']}"; //正式版更换https的二维码下载链接
        //海报图
        $bd = get_img_url($this->in['img']);

        $images[] = array(
            'x' => 0,
            'y' => 0,
            'width' => 750,
            'height' => 1200,
            'url' => $bd,
        );

        $images[] = array(
            'x' => 520,
            'y' => 970,
            'width' => 220,
            'height' => 220,
            'url' => $ewm,
        );
        $haibao = array(
            'width' => 750,
            'height' => 1200,
//            'texts' => $texts,
            'images' => $images
//            'blocks'=>$blocks,
        );
        $this->data['posterConfig'] = $haibao;
        return array('status' => 1, 'data' => $this->data);
    }

}
