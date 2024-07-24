<?php

namespace app\mallapi\logic;

use app\home\model\O;
use think\App;
use think\facade\Db;

class AfterSale
{

    public $in;
    public $uinfo;
    public $data;
    public $model;
    public $orderModel;

    public function __construct()
    {
        $this->model = new \app\mall\model\AfterSale();
        $this->orderModel = new \app\mall\model\Order();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }

    public function home() {
        $this->data['nav'] = [
            ['name' => '全部', 'flag' => ''],
            ['name' => '待处理', 'flag' => '1'],
            ['name' => '已完成', 'flag' => '2'],
        ];
        $this->data['tel'] = C('companytel');
        return array('status' => 1, 'data' => $this->data);
    }
    //可申请售后
    public function index()
    {
        $map = array('user_id' => $this->uinfo['id'],
            'no_cache' => 1,
            'pay_status' => 1,
            'is_u_del' => 0,
            'get_status' => 1,
            'page' => $this->in['page'], 'type' => ['in', [1, 3]]);
        $res = $this->orderModel->get_data($map);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //已申请售后
    public function index_handing()
    {

        $page = $this->in['page'] ? intval( $this->in['page']) : 1;
        $where = [['a.user_id', '=', $this->uinfo['id']]];
        if ($this->in['flag'] == 1) {
            $where[] = ['a.status', 'in', [0, 2, 3, 4, 5]];
        }
        if ($this->in['flag'] == 2) {
            $where[] = ['a.status', 'in', [1, 6]];
        }
        $this->data['list'] = $this->model->getList($where, $page);
        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //获取表单
    public function apply_after_get()
    {
        $r = $this->check_after();
        if($r['status']!=1){
            return ['status'=>0,'info'=>$r['info']];
        }
        $content = new \app\mall\model\Content();
        $this->data['txt_msg'] = $content->getInfo('shouhou');
        $this->data['ReasonLists'] = $this->model->getReasonLists();
        $this->data['refund_type_list'] = $this->model->getLan('refund_type', 2);
        return array('status' => 1, 'data' => $this->data);
    }

    private function check_after(){
        $order_goods_id = $this->in['order_goods_id'] ? $this->in['order_goods_id'] : $this->in['id'];
        $this->data['order_goods_id'] = $order_goods_id;
        if ($order_goods_id) {
            $this->data['info'] = Db::name('mall_order_goods')->where(
                [
                    ['after_status', '=', 0],
                    ['id', '=', $order_goods_id],
                    ['user_id', '=', $this->uinfo['id']],
                ]
            )->find();
            if (!$this->data['info']) {
                return array('status' => 0, 'info' => '不存在');
            }
            $this->data['type'] = 'goods';
        }else{
            $where = [
                ['ordernum','=',$this->in['ordernum']],
                ['pay_status', '=', 1],
                ['after_status', '=', 0],
                ['user_id', '=', $this->uinfo['id']],

            ];
            $info = Db::name('mall_order')->removeOption()->where(
                $where
            )->find();
            if(!$info){
                return ['status'=>0,'info'=>'不存在'];
            }
            $this->data['info'] = $info;

            $this->data['type'] = 'order';
        }
        return ['status'=>1,'data'=>$this->data];

    }
    //提交表单
    public function apply_after_sub()
    {
        $mall_order_goods = Db::name('mall_order_goods');
        $r = $this->check_after();
        if($r['status']!=1){
            return ['status'=>0,'info'=>$r['info']];
        }
        if ($this->data['info']['after_status'] != 0) {
            return array('status' => 0, 'info' => '已申请售后');
        }
        $num = $this->in['num'] ? $this->in['num'] : 1;
        if ($this->data['type']=='goods' && $num > $this->data['info']['num']) {
            return array('status' => 0, 'info' => '数量错误');
        }
        $this->in['refund_type'] = $this->in['refund_type'] ? $this->in['refund_type'] : 0;
        if ($this->in['refund_type'] === '') {
            return array('status' => 0, 'info' => 'refund_type错误');
        }
        $refund_image = '';
        if ($this->in['refund_image']) {
            if (is_string($this->in['refund_image'])) {
                $this->in['refund_image'] = explod(',', $this->in['refund_image']);
            }
            if (is_array($this->in['refund_image'])) {
                $refund_image = json_encode($this->in['refund_image']);
            }
        }
        $add = array(
            'refund_image' => $refund_image,
            'order_id' => $this->data['info']['order_id'],
            'user_id' => $this->uinfo['id'],
            'goods_num' => $num,
            'sn' => get_ordernum(),
            'status' => 0,
            'refund_reason' => $this->in['refund_reason'],
            'refund_remark' => $this->in['refund_remark'],
            'refund_type' => $this->in['refund_type'],
            'create_time' => date('Y-m-d H:i:s'),
        );
        if($this->data['type'] == 'goods'){
            $add['order_goods_id'] = $this->data['info']['id'];
            $add['goods_id'] = $this->data['info']['goods_id'];
            $add['item_id'] = $this->data['info']['item_id'];
            $add['refund_price'] = $this->data['info']['unit_price']* $num;

        }else{
            $add['refund_price'] = $this->data['info']['total'];
        }
        $r = $this->model->insertGetId($add);

        $log = array(
            'type' => 1,
            'order_id' => $this->data['info']['order_id'],
            'after_id' => $r,
            'handle_id' => $this->uinfo['id'],
            'content' => \app\mall\model\AfterSale::USER_APPLY_REFUND,
            'create_time' => date('Y-m-d H:i:s')
        );
        //记录日志
        Db::name('mall_after_log')->insert($log);
        if($this->data['order_goods_id']){
            $mall_order_goods->where(array('id' => $this->in['order_goods_id'], 'after_status' => 0))->save(array('after_status' => 1));
        }
        Db::name('mall_order')->removeOption()->where([
            ['order_id','=',$this->data['info']['order_id']]
        ])->save([
            'after_status'=>1
        ]);
        return array('status' => 1, 'info' => lang('s'));
    }

    //取消
    public function cacel()
    {
        $info = $this->model->getInfo($this->in['id']);
        if ($info['user_id'] != $this->uinfo['id']) {
            return array('status' => 0, 'info' => '不是你的');
        }
        if ($info['status'] == 2) {
            return array('status' => 0, 'info' => '商家已拒绝');
        }

        if ($info['status'] == 6) {
            return array('status' => 0, 'info' => '已完成');
        }
        $log = array(
            'type' => 1,
            'order_id' => $info['order_id'],
            'after_id' => $info['id'],
            'handle_id' => $this->uinfo['id'],
            'content' => \app\mall\model\AfterSale::USER_CANCEL_REFUND,
            'create_time' => date('Y-m-d H:i:s')
        );
        //记录日志
        Db::name('mall_after_log')->insert($log);
        $this->model->removeOption()->where(array('id' => $this->in['id']))->save(array('update_time' => date('Y-m-d H:i:s'), 'del' => 1, 'status' => 7));
        if($info['order_goods_id']){
            Db::name('mall_order_goods')->where(array('id' => $info['order_goods_id'],))->save(array(
                'after_status' => 0
            ));
        }
        Db::name('mall_order')->removeOption()->where([
            ['order_id','=',$info['order_id']]
        ])->save([
            'after_status'=>0
        ]);
        return array('status' => 1, 'info' => lang('s'));
    }

    //物流
    public function send()
    {
        $info = $this->model->getInfo($this->in['id']);
        if ($info['user_id'] != $this->uinfo['id']) {
            return array('status' => 0, 'info' => '不是你的');
        }
        if ($info['status'] != 2) {
            return array('status' => 0, 'info' => '状态不对');
        }
        if (!$this->in['invoice_no']) {
            return array('status' => 0, 'info' => '单号必填');
        }
        $log = array(
            'type' => 1,
            'order_id' => $info['order_id'],
            'after_id' => $info['id'],
            'handle_id' => $this->uinfo['id'],
            'content' => \app\mall\model\AfterSale::USER_SEND_EXPRESS,
            'create_time' => date('Y-m-d H:i:s')
        );
        //记录日志
        Db::name('mall_after_log')->insert($log);

        $save = array('update_time' => date('Y-m-d H:i:s'), 'status' => 3,
            'express_name' => $this->in['express_name'],
            'invoice_no' => $this->in['invoice_no'],
            'express_remark' => $this->in['express_name'],
            'express_image' => $this->in['express_image'] ? json_encode($this->in['express_image']) : '',
        );
        $this->model->where(array('id' => $this->in['id']))->save($save);
        return array('status' => 1, 'info' => lang('s'));
    }

    //详情
    public function item()
    {
        $info = $this->model->getInfo($this->in['id']);
        if ($info['user_id'] != $this->uinfo['id']) {
            return array('status' => 0, 'info' => '操作失败');
        }
        return array('status' => 1, 'data' => $info);
    }

}
