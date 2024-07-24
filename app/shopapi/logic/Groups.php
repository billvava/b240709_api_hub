<?php

namespace app\shopapi\logic;


use app\mall\model\Goods;
use app\mall\model\PtGoods;
use app\mall\model\PtOrder;
use app\mall\model\RechargeItem;
use app\mall\model\RechargeOrder;
use think\App;
use think\facade\Db;

class Groups
{
    public $clear;
    public $uinfo;
    public $data;
    public $model;
    public $goodsModel;
    public $in;
    public $groupModel;

    public function __construct()
    {
        $this->model = new \app\common\model\SuoMaster();
        $this->goodsModel = new Goods();
        $this->groupModel = new PtOrder();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }


    //拼团列表
    public function index()
    {
        if ($this->in['load_other'] == 1) {

        }
        $PtGoodsModel = new PtGoods();
        $where = array(
            'get_goods' => 1,
            'page' => $this->in['page'],
            'end' => true,
            'order' => $this->in['order'],
            'status' => 1,
        );
        $res = $PtGoodsModel->get_data($where);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //获取正在拼团的列表
    public function grouping_list()
    {
        $this->data['list'] = $this->groupModel->getCurrentList($this->in['group_id'],$this->in['p_ordernum']);

        $this->data['sale_num'] = Db::name('pt_goods')->where([
            ['id', '=', $this->in['group_id']]
        ])->value('sale_num');

        return array('status' => 1, 'data' => $this->data);
    }

    public function item()
    {
        $PtGoodsModel = new PtGoods();
        $where = [
            ['id', '=', $this->in['id']],
            ['status', '=', 1],
            ['end', '>', date('Y-m-d H:i:s')]
        ];

        $ptinfo = Db::name('pt_goods')->where($where)->find();
        if (!$ptinfo) {
            return array('status' => 0, 'info' => '已下架');
        }

        if (strtotime($ptinfo['end']) < time()) {
            return array('status' => 0, 'info' => '已经结束拼团');
        }


        $this->in['goods_id'] = $ptinfo['goods_id'];
        $logic = new \app\shopapi\logic\Goods();
        $logic->config(array(
            'in' => $this->in,
            'uinfo' => $this->uinfo,
            'data' => $this->data
        ));
        $PtOrder = new PtOrder();
        $pt_list = $PtOrder->getCurrentList($this->in['id']);
        $res = $logic->item();
        $res['data']['pt_list'] = $pt_list;
        $res['data']['pt_msg'] = C('pt_msg');
        $res['data']['goods_tips'] = '一件包邮';
        $res['data']['min_price'] = $ptinfo['min_price'];
        $res['data']['end'] = $ptinfo['end'];
        $res['data']['ptinfo'] = $ptinfo;
        return $res;


    }


    //拼团订单列表
    public function pt_order()
    {
        $PtOrder = new PtOrder();
        $where = array('master_id' => $this->uinfo['id']);
        if ($this->in['flag'] !== '') {
            $where['status'] = $this->in['flag'];
        }
        if ($this->in['ordernum']) {
            $info = Db::name('pt_order')->where(array('master_id' => $this->uinfo['id'], 'ordernum' => $this->in['ordernum']))->find();
            $this->data['uinfo'] = $this->uinfo;

            $ptinfo = Db::name('pt_goods')->where(array('id' => $info['pt_id']))->find();

            if ($info) {
                $Goods = new Goods();
                if ($info['type'] == 1) {
                    $cha_num = $info['need_num'] - $info['num'];
                    $cha_arr = array();
                    for ($i = 0; $i < $cha_num; $i++) {
                        $cha_arr[] = $i;
                    }
                } else if ($info['type'] == 2) {
                    $master_ids = Db::name('pt_order')->where(array('p_ordernum' => $info['p_ordernum']))->order("time asc")->column('master_id');
                    $user_data = array();
                    if ($master_ids) {
                        $base = new \app\common\model\User();
                        foreach ($master_ids as $v) {
                            $t = $base->getUserInfo($v);
                            $user_data[] = array(
                                'nickname' => $t['nickname'],
                                'headimgurl' => $t['headimgurl'],
                            );
                        }
                    }
                }
                $ginfo = $Goods->getInfo($info['goods_id'], 0, false);
                $this->data['pt_info'] = array(
                    'pt_id' => $ptinfo['id'],
                    'cha_arr' => $cha_arr,
                    'type' => $info['type'],
                    'pt_price' => $ptinfo['min_price'],
                    'min_price' => $ginfo['min_price'],
                    'goods_name' => $ptinfo['name'] ? $ptinfo['name'] : $info['goods_name'],
                    'thumb' => $ginfo['thumb'], 'goods_id' => $info['goods_id'],
                    'user_data' => $user_data,
                );
            }
        }
//        C('sql',1);

        $this->data['list'] = $PtOrder->getPageList($where, $this->in['page']);
        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //支付成功
    public function pay_success()
    {

        if ($this->in['ordernum']) {
            $info = Db::name('pt_order')->where(array('master_id' => $this->uinfo['id'], 'ordernum' => $this->in['ordernum']))->find();

            $info['sy_time'] = strtotime($info['end_time']) - time();
            $info['sy_num'] = $info['group_num'] - $info['num'];

            $this->data['info'] = $info;


            $ptinfo = Db::name('pt_goods')->where(array('id' => $info['group_id']))->find();
            if ($info) {

                $this->data['team_list'] = $this->groupModel->getTeamList($this->in['group_id'], 0, $info['p_ordernum']);


                $ginfo = $this->goodsModel->getInfo($info['goods_id'], 0, false);
                $this->data['pt_info'] = array(
                    'group_id' => $ptinfo['id'],
                    'type' => $info['type'],
                    'pt_price' => $ptinfo['min_price'],
                    'min_price' => $ginfo['min_price'],
                    'name' =>  $ginfo['name'],
                    'thumb' => $ginfo['thumb'],
                    'sale_num' => $ptinfo['sale_num'],
                    'goods_id' => $info['goods_id'],
                );
            }

            return array('status' => 1, 'data' => $this->data);

        }


    }

}