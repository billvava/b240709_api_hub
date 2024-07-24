<?php

namespace app\mall\controller;

use think\App;
use think\facade\View;
use think\facade\Db;

/**
 * 售后管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-customerservice_fill
 */
class AfterSale extends Common {

    public $model;
    public $name;
    public $pk;
    public $mall_after_log;
    public $mall_order_goods;

    public function __construct(App $app) {
        parent::__construct($app);
        $this->model = new \app\mall\model\AfterSale();
        $this->name = '售后';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array(ACTION_NAME, array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }
        $all_lan = $this->model->getLan();
        View::assign('all_lan', $all_lan);
        $this->mall_after_log = Db::name('mall_after_log');
        $this->mall_order_goods = Db::name('mall_order_goods');
        $this->create_seo($this->name);
    }

    /**
     * 售后列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $in = $this->in;
        $where = array();
        unset($in['p']);
        $search_field = lang('search_field');
        if($in['username']){
            $user_ids = $this->model-> getUserId($in['username']);
            $where[] = ['a.user_id','in',$user_ids];

        }

        unset($in['username']);
        foreach ($in as $key => $value) {
            if ($value !== '') {
                if (in_array($key, $search_field)) {
                    $where[] = ['a.' . $key,'like', "%{$value}%"];
                    
                } else {
                    $where[] = ['a.' . $key,'=',$value];
                }
            }
        }

        $count = $this->model->alias('a')->where($where)->count();

        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $order = "id desc";
        $data['list'] = Db::name('mall_after_sale')
                        ->alias('a')
                        ->field('a.*')
                        ->where($where)
                        ->limit($Page->firstRow . ',' . $Page->listRows)
                        ->order($order)
                        ->select()->toArray();
        View::assign('is_add', 0);
        View::assign('is_xls', 0);
        View::assign('is_search', 1);
        View::assign('is_del', 1);
        View::assign('is_edit', 1);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 同意售后
     * @auto true
     * @auth true
     * @menu false
     */
    public function agree() {
        $info = $this->model->getInfo($this->in['id']);
        if ($info && $info['status'] == 0) {
            $save = array();
            $save['update_time'] = date('Y-m-d H:i:s');
            $log = array(
                'type' => 2,
                'order_id' => $info['order_id'],
                'after_id' => $info['id'],
                'handle_id' => $this->adminInfo['id'],
                'content' => \app\mall\model\AfterSale::SHOP_AGREE_REFUND,
                'create_time' => date('Y-m-d H:i:s')
            );
            //仅退款
            if ($info['refund_type'] == 0) {
                $update_status = 5; //更新为等待退款状态
            }

            //退款退货
            if ($info['refund_type'] == 1) {
                $update_status = 3; //更新为商品待退货状态
            }
            $orderModel = new \app\mall\model\Order();
            $res = $orderModel->refund($info['order_id'], $info['refund_price']);
            if ($res['status'] != 1) {
                $this->error($res['info']);
            }



            $update_status = 6;
            $save['status'] = $update_status;
            $this->model->removeOption()->where(array('id' => $info['id']))->save($save);
//            $this->model->USER_CANCEL_REFUND
            //记录日志
            $this->mall_after_log->insert($log);

            // 仅退款;更新订单商品为等待退款
            if ($info['refund_type'] == 0) {
                $this->mall_order_goods->where(array('id' => $info['order_goods_id']))->save(array('after_status' => 2));
            }
            $this->success(lang('s'));
        }
        $this->error(lang('e'));
    }

    /**
     * 拒绝售后
     * @auto true
     * @auth true
     * @menu false
     */
    public function refuse() {
        $info = $this->model->getInfo($this->in['id']);
        if ($info && $info['status'] == 0) {
            $save = array();
            $save['update_time'] = date('Y-m-d H:i:s');
            $log = array(
                'type' => 2,
                'order_id' => $info['order_id'],
                'after_id' => $info['id'],
                'handle_id' => $this->adminInfo['id'],
                'content' => \app\mall\model\AfterSale::SHOP_REFUSE_REFUND,
                'create_time' => date('Y-m-d H:i:s')
            );
            $update_status = 1;
            $save['status'] = $update_status;
            $this->model->removeOption()->where(array('id' => $info['id']))->save($save);
            //记录日志
            $this->mall_after_log->insert($log);

            if ($info['refund_type'] == 0) {
                $this->mall_order_goods->where(array('id' => $info['order_goods_id']))->save(array('after_status' => 0));
            }
            $this->success(lang('s'));
        }
        $this->error(lang('e'));
    }

    public function update_refund_price() {
        $info = $this->model->getInfo($this->in['id']);
        if ($info['status'] != 0) {
            $this->error('当前状态不可以修改');
        }
        if ($this->in['refund_price'] <= 0) {
            $this->error('金额要大于0');
        }
        if ($this->in['refund_price'] > $info['total']) {
            $this->error('不能超过订单金额');
        }
        $this->model->removeOption()->where(array('id' => $this->in['id']))->limit(1)->save(array(
            'refund_price' => $this->in['refund_price']
        ));
        $this->success(lang('s'));
    }

    //商家收货
    public function takeGoods() {
        $info = $this->model->getInfo($this->in['id']);
        if ($info && $info['status'] == 3) {
            $save = array();
            $save['update_time'] = date('Y-m-d H:i:s');
            $log = array(
                'type' => 2,
                'order_id' => $info['order_id'],
                'after_id' => $info['id'],
                'handle_id' => $this->adminInfo['id'],
                'content' => \app\mall\model\AfterSale::SHOP_TAKE_GOODS,
                'create_time' => date('Y-m-d H:i:s')
            );
            $update_status = 5;
            $save['status'] = $update_status;

            $this->model->removeOption()->where(array('id' => $info['id']))->save($save);
            //记录日志
            $this->mall_after_log->insert($log);

            //更新订单商品为等待退款状态
            $this->mall_order_goods->where(array('id' => $info['order_goods_id']))->save(array('after_status' => 2));
            $this->success(lang('s'));
        }
        $this->error(lang('e'));
    }

    //商家拒绝收货
    public function refuseGoods() {
        $info = $this->model->getInfo($this->in['id']);
        if ($info && $info['status'] == 3) {
            $save = array();
            $save['update_time'] = date('Y-m-d H:i:s');
            $log = array(
                'type' => 2,
                'order_id' => $info['order_id'],
                'after_id' => $info['id'],
                'handle_id' => $this->adminInfo['id'],
                'content' => \app\mall\model\AfterSale::SHOP_REFUSE_TAKE_GOODS,
                'create_time' => date('Y-m-d H:i:s')
            );
            $update_status = 5;
            $save['status'] = $update_status;
            $this->model->removeOption()->where(array('id' => $info['id']))->save($save);
            //记录日志
            $this->mall_after_log->insert($log);
            //更新订单商品为等待退款状态
            $this->mall_order_goods->where(array('id' => $info['order_goods_id']))->save(array('after_status' => 0));
            $this->success(lang('s'));
        }
        $this->error(lang('e'));
    }

    //确认退款 ===> 退款
    public function confirm() {
        $info = $this->model->getInfo($this->in['id']);
        if ($info && $info['status'] == 5 && $info['del'] == 0) {
            //增加退款记录
            $order_refund = Db::name('mall_order_refund');
            $refund_data = [
                'order_id' => $info['order_id'],
                'user_id' => $info['user_id'],
                'refund_sn' => get_ordernum(),
                'order_amount' => $info['total'],
                'refund_amount' => $info['refund_price'],
                'transaction_id' => $info['trade_no'],
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $refund_id = $order_refund->insert($refund_data);
            //余额支付回退余额
            if ($info['pay_type'] == 1) {
                $User = new \app\common\model\User();
                $User->handleUser('money', $info['user_id'], $info['refund_price'], 1, array('ordernum' => $info['ordernum']));
            }

            //微信支付
            if ($info['pay_type'] == 4) {

                tool()->classs('pay/Pay');
                $Pay = new \Pay(C('wx_pay_type'));
                $res = $Pay->refund($info['trade_no'], $info['total'], $info['refund_price']);
                if ($res['status'] == 1) {
                    //更新退款记录
                    $update_refund = [
                        'refund_status' => 1,
                        'refund_at' => date('Y-m-d H:i:s'),
                        'wechat_refund_id' => $res['refund_id'] ?? '', //微信的退款id
                        'refund_msg' => json_encode($res, JSON_UNESCAPED_UNICODE),
                        'update_time' => date('Y-m-d H:i:s'),
                    ];
                    $order_refund->where(['id' => $refund_id])->save($update_refund);
                } else {
                    $order_refund->where(['id' => $refund_id])->save(array('refund_way' => 2));
                    $this->error('退款失败');
                }
            }
            $save = array();
            $save['update_time'] = date('Y-m-d H:i:s');
            $log = array(
                'type' => 2,
                'order_id' => $info['order_id'],
                'after_id' => $info['id'],
                'handle_id' => $this->adminInfo['id'],
                'content' => \app\mall\model\AfterSale::SHOP_CONFIRM_REFUND,
                'create_time' => date('Y-m-d H:i:s')
            );
            $update_status = 6;
            $save['status'] = $update_status;
            $this->model->removeOption()->where(array('id' => $info['id']))->save($save);
            //记录日志
            $this->mall_after_log->insert($log);
            //更新订单商品为等待退款状态
            $this->mall_order_goods->where(array('id' => $info['order_goods_id']))->save(array('after_status' => 3));
            Db::name('mall_order')->where(array('order_id' => $info['order_id']))->inc('refund_money', $info['refund_price'])->update();
            $this->success(lang('s'));
        }
        $this->error(lang('e'));
    }

    /**
     * xls
     * @auto true
     * @auth true
     * @menu false
     */
    public function xls() {
        $in = $this->in;
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        foreach ($in as $key => $value) {
            if ($value !== '' && in_array($key, $fields)) {
                $where['a.' . $key] = array('like', "%{$value}%");
            }
        }
        $data = $this->model
                ->alias('a')
                ->field($fields)
                ->where($where)
                ->select();
        if ($data) {
            $data = $this->toArray();
        } else {
            $data = [];
        }
        $all_lan = $this->model->getLan();
        foreach ($data as &$v) {
            foreach ($all_lan as $ak => $av) {
                if (isset($v[$ak])) {
                    $v[$ak] = $all_lan[$ak][$v[$ak]];
                }
            }
            if (isset($v['user_id'])) {
                $v['user_id'] = getname($v['user_id']);
            }
        }
        (new \app\common\lib\Util())->xls($this->name, array_values($as), $data);
    }

    /**
     * 添加售后
     * @auto true
     * @auth true
     * @menu false
     */
    public function add() {
        $in = $this->in;
        if ($in[$this->pk]) {
            $this->error('错误');
        }
        $this->item();
    }

    /**
     * 编辑售后
     * @auto true
     * @auth true
     * @menu false
     */
    public function edit() {
        $in = $this->in;
        if (!$in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }

    private function item() {
        $in = $this->in;

        if (request()->isPost()) {
            $rule = $this->model->rules();
            $this->model->validate($rule)->create();
            if ($e = $this->model->getError()) {
                $this->error($e);
            }
            $jsonAttr = $this->model->jsonAttr();
            if ($jsonAttr) {
                foreach ($jsonAttr as $v) {
                    $in[$v] = $in[$v] ? json_encode($in[$v]) : '';
                }
            }
            if ($in[$this->pk]) {
                $r = $this->model->save($in);
                $r = $in[$this->pk];
            } else {
                $r = $this->model->insert($in);
            }
            $this->model->clear($r);
            if ($r === 0 || $r) {
                $this->success(lang('s'), U('index'));
            } else {
                $this->error(lang('e'), U('index'));
            }
        }

        if ($in[$this->pk]) {
            $info = $this->model->getInfo($in[$this->pk]);
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }

        return $this->display('item');
    }

    /**
     * 删除售后
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $in = $this->in;
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $where[$this->pk] = array('in', $in[$this->pk]);
            foreach ($in[$this->pk] as $v) {
                $this->model->clear($v);
            }
        } else {
            $where[$this->pk] = $in[$this->pk];
            $this->model->clear($in[$this->pk]);
        }
        $r = $this->model->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), U('index'));
        } else {
            $this->error(lang('操作失败'), U('index'));
        }
    }

    /**
     * 复制
     * @auto true
     * @auth true
     * @menu false
     */
    public function copy() {
        $in = $this->in;
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $ks = $in[$this->pk];
        } else {
            $ks = array($in[$this->pk]);
        }
        foreach ($ks as $v) {
            $info = $this->model->where(array($this->pk => $v))->find();
            unset($info[$this->pk]);
            $this->model->insert($info);
        }
        $this->success(lang('操作成功'), U('index'));
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $in = $this->in;
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
        $this->model->clear();
        $this->model->where($where)->setField($in['field'], $in['val']);
        $this->success(lang('s'));
    }

    /**
     * 多选设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function mul_set() {
        if (!$this->in[$this->pk]) {
            $this->error('请先选择');
        }
        $this->model->where(array($this->pk => array('in', $this->in[$this->pk])))->setField($this->in['field'], $this->in['val']);
        $this->success(lang('s'));
    }

}
