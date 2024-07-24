<?php
namespace app\mall\controller;
use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
 * 优惠券管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-service_fill
 */
class Coupon extends Common {

    public $model;
    public $name;
    public $pk;
    public $db_name;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\MallCoupon();

        $this->name = '用户优惠券';

        View::assign('coupon_type', lang('coupon_type'));
        View::assign('coupon_range', lang('coupon_range'));
        View::assign('coupon_end_type', lang('coupon_end_type'));
        View::assign('coupon_source', lang('coupon_source'));
        $this->pk = 'id';
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array(request()->action(), ['show', 'edit', 'delete'])) {
            $this->error('缺少主键');
        }
        $this->create_seo($this->name);
    }

    /**
     * 优惠券列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index() {
        $where = array();
        $in = input();
        foreach ($in as $key => $value) {
            if ($value !== '' && !in_array($key, array('p'))) {
                $where['a.' . $key] = $value;
            }
        }
        $count = Db::name('mall_coupon')->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $data['list'] = Db::name('mall_coupon')->alias('a')->where($where)
            ->leftJoin('user b','a.user_id=b.id')
            ->order('a.id desc')
            ->field("a.*,b.username")
            ->limit($Page->firstRow ,$Page->listRows)
            ->select()->toArray();
        View::assign('data', $data);
        View::assign('is_del', 1);

        return $this->display();
    }




    /**
     * 发送优惠劵
     * @auto true
     * @auth true
     * @menu false
     */
    public function send() {
        if (app()->request->isPost()) {
            !$this->in['tpl_id'] && $this->error('请选择模板');
            $this->in['num'] <= 0 && $this->error('数量不正确');
            $msg = "";
            $send_num = 0;
            if ($this->in['range'] == 1) {
                $count = Db::name('user')->count();
                if ($count <= 0) {
                    $this->error('没有用户');
                }
                $num = ceil($count / 1000);
                for ($i = 1; $i <= $num; $i++) {
                    $us = Db::name('user')->page($i, 1000)->order("id asc")->column('id');
                    foreach ($us as $user_id) {
                        $r = $this->model->send_tpl($user_id, $this->in['tpl_id'], $this->in['num']);
                        if ($r['status'] == 1) {
                            $send_num+=$r['num'];
                        }
                    }
                }
            } else if ($this->in['range'] == 2) {
                $this->in['user_id'] = str_replace("，", ',', $this->in['user_id']);
                $this->in['user_id'] = str_replace(array(" ", "　", "\t", "\n", "\r"), "", $this->in['user_id']);
                $us = explode(',', $this->in['user_id']);
                foreach ($us as $user_id) {
                    $id = Db::name('user')->where(array('id' => $user_id))->value('id');
                    if (!$id) {
                        $msg .= "用户{$user_id}不存在<br/>";
                        continue;
                    }
                    $r = $this->model->send_tpl($user_id, $this->in['tpl_id'], $this->in['num']);
                    if ($r['status'] == 1) {
                        $send_num+=$r['num'];
                    }
                }
            }
            $msg.="成功发放{$send_num}张<br/>";
            $this->success($msg, '', -1);
        }
        return $this->display();
    }


    /**
     * 删除优惠劵
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $where[] = [$this->pk,'in',$in[$this->pk]];
        } else {
            $where[] = [$this->pk,'=',$in[$this->pk]];
        }
        $r = $this->model->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }

}
