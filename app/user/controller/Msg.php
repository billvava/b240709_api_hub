<?php

namespace app\user\controller;


use app\common\lib\Lib;
use app\common\Lib\Util;
use app\common\model\Weixin;
use app\user\model\User;
use app\user\model\UserMsg;
use think\App;
use think\facade\Db;
use think\facade\View;


/**
 * 站内信
 * @auto true
 * @auth true
 * @menu true
 */
class Msg extends Common {
    public $model;
    public $name;
    public $pk;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model =new UserMsg();
        $this->user=new User();
        $this->name = '站内信';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array($this->request->action(), array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }
        View::assign('is', lang('is'));
        $this->create_seo($this->name);
    }

    /**
     * 站内信列表
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
        $in = input();
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        unset($in['p']);
        $search_field = lang('search_field');
        foreach ($in as $key => $value) {
            if ($value !== '') {
                if (in_array($key, $search_field)) {
                    $where[] = array('a.' . $key,'like', "%{$value}%");
                } else {
                    $where[] = ['a.' . $key,'=',$value];
                }
            }
        }
        $count = Db::name('user_msg')->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $order = "id desc";
        $data['list'] =  Db::name('user_msg')
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

    public function xls() {
        $in = input();
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        $search_field = lang('search_field');
        foreach ($in as $key => $value) {
            if ($value !== '') {
                if (in_array($key, $search_field)) {
                    $where[] = array('a.' . $key,'like', "%{$value}%");
                } else {
                    $where[] = ['a.' . $key,'=',$value];
                }
            }
        }
        $data = $this->model
            ->alias('a')
            ->field($fields)
            ->where($where)
            ->limit(500)
            ->select()->toArray();
        $lib=new Util();
        $lib->xls($this->name, array_values($as), $data);
    }

    /**
     * 发送站内信
     * @auto true
     * @auth true
     * @menu true
     */
    public function item() {
        $in = input();
        if ($this->request->isPost()) {
            $rule = $this->model->rules();

            $this->validate($this->in,$rule['rule'],$rule['message']?$rule['message']:[]);
            $jsonAttr = $this->model->jsonAttr();
            if ($jsonAttr) {
                foreach ($jsonAttr as $v) {
                    $in[$v] = json_encode($in[$v]);
                }
            }
            $in['time'] = date('Y-m-d H:i:s');
            $in['sender'] = '系统';
            if ($in[$this->pk]) {
                $r = $this->model->update($in);
                $r = $in[$this->pk];
            } else {
                $this->in['user_id'] = str_replace("，", ',', $this->in['user_id']);
                $this->in['user_id'] = str_replace(array(" ", "　", "\t", "\n", "\r"), "", $this->in['user_id']);
                $us = explode(',', $this->in['user_id']);
                $user = $this->user;
                $msg = "";
                $send_num = 0;
                foreach ($us as $user_id) {
                    $id = $user->where(array('id' => $user_id))->value('id');
                    if (!$id) {
                        $msg .= "用户{$user_id}不存在<br/>";
                        continue;
                    }
                    $in['user_id'] = $user_id;
                    $this->model->insert($in);
                    $send_num+=1;
                }
                $msg.="成功发送{$send_num}条<br/>";
                $this->success($msg, '', -1);
            }

            if ($r === 0 || $r) {
                $this->success(lang('s'), '', -1);
            } else {
                $this->error(lang('e'), url('index'));
            }
        }

        if ($in[$this->pk]) {
            $where[$this->pk] = $in[$this->pk];
            $info = $this->model->where($where)->find()->toArray();
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }
        View::assign('show_top', 0);

        return $this->display();
    }

    /**
     * 删除站内信
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
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $in = input();
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
        $this->model->clear();
        $this->model->where($where)->save([$in['field']=> $in['val']]);
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
        $this->model->where(array($this->pk => array('in', $this->in[$this->pk])))->save([$this->in['field']=>$this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 发送公众号消息
     * @auto true
     * @auth true
     * @menu true
     */
    public function wx_msg() {
        if ($this->request->isPost()) {
            $common = new Weixin();
            $user = $this->user;


            $this->in['user_id'] = str_replace("，", ',', $this->in['user_id']);
            $this->in['user_id'] = str_replace(array(" ", "　", "\t", "\n", "\r"), "", $this->in['user_id']);
            $us = explode(',', $this->in['user_id']);
            $msg = "";
            $send_num = 0;
            foreach ($us as $user_id) {
                $openid = $user->where(array('id' => $user_id))->value('openid');
                if (!$openid) {
                    $msg .= "用户{$user_id}不存在openid<br/>";
                    continue;
                }
                $res = $common->sendTxt($openid, $this->in['content']);
                if ($res === false) {
                    $msg .= "用户{$user_id}发送失败<br/>";
                } else {
                    $send_num+=1;
                }
            }
            $msg.="成功发送{$send_num}条<br/>";
            $this->success($msg, '', -1);
        }
        View::assign('name', "发送微信消息");
        return $this->display();
    }
}