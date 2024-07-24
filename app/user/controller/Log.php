<?php

namespace app\user\controller;



use app\user\model\User;
use app\user\model\UserLogin;
use app\user\model\UserRank;
use think\App;
use think\facade\Db;
use think\facade\View;


/**
 * 用户日志
 * @auto true
 * @auth true
 * @menu true
 */
class Log extends Common {

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new User();
        View::assign('user_log_type',  lang('user_log_type'));
    }

    /**
     * 删除日志
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete() {
        $in = input();
        $this->model = new UserLogin();
        if (!$in['id']) {
            $this->error('缺少主键参数');
        }
        if (is_array($in['id'])) {
            $where['id'] = array('in', $in['id']);
            foreach ($in['id'] as $v) {
                $this->model->clear($v);
            }
        } else {
            $where['id'] = $in['id'];
            $this->model->clear($in['id']);
        }
        $r = $this->model->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }
    /**
     * 登陆日志
     * @auto true
     * @auth true
     * @menu true
     */
    public function login() {
        $in = input();
        $where = array();
        $this->create_seo('登陆日志');
        unset($in['p']);
        $search_field = lang('search_field');
        foreach ($in as $key => $value) {
            if ($value !== '') {
                if (in_array($key, $search_field)) {
                    $where[] = ['a.' . $key,'like', "%{$value}%"];
                } else {
                    $where[] = ['a.' . $key,'=',$value];
                }
            }
        }
        $this->model = new UserLogin();
        $count = $this->model->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $order = "id desc";
        $data['list'] = $this->model
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
        View::assign('is_edit', 0);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 余额日志
     * @auto true
     * @auth true
     * @menu true
     */
    public function money() {
        $in = input();
        $this->create_seo('用户余额日志');
        $data = $this->model->getUserLog('money', $in, 'admin');
        View::assign('data', $data);
        View::assign('cate', lang('user_money_cate'));
        return $this->display('log');
    }

    /**
     * 用户积分日志
     * @auto true
     * @auth true
     * @menu true
     */
    public function dot() {
        $in = input();
        View::assign('cate', lang('user_dot_cate'));
        $data = $this->model->getUserLog('dot', $in, 'admin');
        View::assign('data', $data);
        $this->create_seo('用户积分日志');
        return $this->display('log');
    }

    /**
     * 佣金日志
     * @auto true
     * @auth true
     * @menu true
     */
    public function bro() {
        $in = input();
        View::assign('cate', lang('user_bro_cate'));
        $data = $this->model->getUserLog('bro', $in, 'admin');
        $this->create_seo('用户佣金日志');
        View::assign('data', $data);
        return $this->display('log');
    }

    /**
     * 撤回
     * 危险功能
     */
    public function cacel() {
        $tb = $this->in['table'];
        $info = Db::name('user_'.$tb)->where(['id'=>$this->in['id']])->find();
        if(!$info){
            $this->error('数据不存在');
        }
        $fu = "-";
        if($info['type'] == 2){
            $fu = "+";
        }
        if($info['total'] < 0){
            $this->error('金额有误');
        }
        Db::name('user')->where(['id'=>$info['user_id']])->update([
            "{$tb}"	=>	Db::raw("{$tb}{$fu}{$info['total']}"),
        ]);
        Db::name('user_'.$tb)->where(['id'=>$info['id']])->limit(1)->delete();
        $this->success(lang('s'));
    }

    public function out_xls() {
        $this->model->outUserLog($this->in['table'], $this->in);
    }



}