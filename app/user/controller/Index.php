<?php

namespace app\user\controller;

use app\user\model\User;
use app\user\model\UserAddress;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 用户管理
 * @auto true
 * @auth true
 * @menu true
 */
class Index extends Common {

    public $model;
    public function __construct(App $app) {
        parent::__construct($app);
        View::assign('title', '用户信息');
        View::assign('sex', lang('sex'));
        $this->model = new User();
    }

    /**
     * 用户列表
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
        $this->in['page_type'] = 'admin';
        $data = $this->model->getList($this->in);
        View::assign('data', $data);
//        $ranks = $this->model->getRanks();
        $ranks = get_user_level();
        $role_array = ['无','代理',];
        View::assign('ranks', $ranks);
        View::assign('role_array', $role_array);
        return $this->display();
    }

    /**
     * 删除用户
     * @auto true
     * @auth true
     * @menu false
     */
    public function user_del() {
        $id = intval(input('id'));
        if ($this->model->where('id',$id) -> delete()) {
            $this->success(lang('s'));
        } else {
            $this->error(lang('e'));
        }
    }

    /**
     * 添加用户
     * @auto true
     * @auth true
     * @menu true
     */
    public function item() {
        if ($this->request->isPost()) {
            $in = input();
            $rule = ['rule' => []];
            $this->validate($this->in, $rule['rule'], $rule['message'] ? $rule['message'] : []);
            if ($in['pwd']) {
                $in['pwd'] = xf_md5($in['pwd']);
            }else{
                unset($in['pwd']);
            }
            if ($in['pwd2']) {
                $in['pwd2'] = xf_md5($in['pwd2']);
            }else{
                unset($in['pwd2']);
            }
            if ($in['id']) {

                $this->model->clearCache($in['id']);
                $r = $this->model->update($in);
            } else {

                $in['create_time'] = date('Y-m-d H:i:s');

                $in['referee_path'] =',';
                $r = $this->model->save(array_filter($in));
                $id = $this->model->id;
                Db::name('user_parent')->insert(array('user_id' => $id));
                Db::name('user_ext')->insert(array('user_id' => $id));
            }


            if ($r === 0 || $r) {
                $this->success(lang('s'));
            } else {
                $this->error(lang('e'));
            }
            die;
        }
        if (input('id')) {
            $info = $this->model->find(input('id'))->toArray();
            View::assign('info', $info);
        }
        $ranks = get_user_level();

        $role_array = ['无','代理',];
        View::assign('ranks', $ranks);
        View::assign('role_array', $role_array);
        return $this->display();
    }

    /**
     * 查看用户
     * @auto true
     * @auth true
     * @menu false
     */
    public function item_show() {
        $data['uinfo'] = $this->model->getUserInfo($this->in['id']);
        $data['finfo'] = $this->model->getFinance($this->in['id']);

        $data['einfo'] = $this->model->getExt($this->in['id'], false);

        $UserAddress = new UserAddress();
        $data['add_list'] = $UserAddress->getList(array('user_id' => $this->in['id']));
        View::assign('data', $data);
//        View::assign('show_top', 0);
        return $this->display();
    }

    /**
     * 查看父级
     * @auto true
     * @auth true
     * @menu false
     */
    public function show_parent() {
        $pinfo = $this->model->getPinfo($this->in['user_id']);
        View::assign('pinfo', $pinfo);
        $this->title = '上级信息';
        return $this->display();
    }

    /**
     * 查看下级
     * @auto true
     * @auth true
     * @menu false
     */
    public function tree_img() {
        $in = input();
        $username = $this->model->where(array('id' => $in['user_id']))->value('username');
        View::assign('username', $username);
        return $this->display();
    }

     /**
     * 解绑微信
     * @auto true
     * @auth true
     * @menu false
     */
    public function clearwx(){
        $this->model->where(array('id' => $this->in['id']))
                        ->field('id,nickname,headimgurl')
                        ->save(array('openid'=>''));
        $this->model->clearCache($this->in['id']);
        Db::name('user_token')->where(array('user_id' => $this->in['id']))->delete();
        $this->success(lang('s'));
    }
    
    public function get_nodes() {
        $in = input();
        $pid = $in['id'] ? $in['id'] : $in['user_id'];
        $data = $this->model
                        ->where(array('pid' => $pid))
                        ->field('id,nickname,headimgurl')
                        ->cache(3600)
                        ->select()->toArray();
        $json = "[";



        foreach ($data as $v) {
            $v['nickname'] = $this->match_chinese($v['nickname']);
            $rank = '';
            $pstr = '';
            $json .= "{ id:'{$v['id']}',name:'【{$v['id']}】{$v['nickname']}{$rank}{$pstr}',isParent:true},";
        }
        $json = trim($json, ',') . ']';
        echo $json;
        die;
    }

    function match_chinese($chars, $encoding = 'utf8') {
        $pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u' : '/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);
        return $temp;
    }

    /**
     * 重置密码
     * @auto true
     * @auth true
     * @menu false
     */
    public function user_res() {
        $where['id'] = input('id');
        $save['pwd'] = xf_md5(C('res_pwd'));
        $r = $this->model->where($where)->save($save);
        $this->success(lang('s'));
    }
    /**
     * 余额修改
     * @auto true
     * @auth true
     * @menu false
     */
    public function log_handle() {
        if (!is_numeric($this->in['total'])) {
            $this->error('请输入数字');
        }
        if ($this->in['total'] <= 0) {
            $this->error('请大于0');
        }

        $res = $this->model->handleUser($this->in['table'], $this->in['user_id'], $this->in['total'], $this->in['type'], array('cate' => 1, 'admin_id' => $this->adminInfo['id'], 'msg' => $this->in['msg']));
        if ($res) {
            $this->success(lang('s'));
        } else {
            $this->error(lang('e'));
        }
    }

    public function getname() {
        $name = Db::name('user')->where(array('id' => $this->in['id']))->value('nickname');
        $name = $name ? $name : '';
        json_msg(1, null, null, $name);
    }

    public function checkp($pid, $is_id) {
        $ids = $this->model->where(array('pid' => $pid))->column('id');
        foreach ($ids as $value) {
            if ($value == $is_id) {
                return true;
            }
            $this->checkp($value, $is_id);
        }
        return false;
    }

    /**
     * 修改上级
     * @auto true
     * @auth true
     * @menu true
     */
    public function set_pid() {
        if ($this->request->isPost()) {
            if ($this->in['user_id1'] == $this->in['user_id2']) {
                $this->error('2者不能一样');
            }

            if ($this->checkp($this->in['user_id1'], $this->in['user_id2'])) {
                $this->error('他们已经有关系了');
            }

            $newInfo = Db::name('user_parent')->where(array('user_id' => $this->in['user_id2']))->find();
            $this->model->where(array('id' => $this->in['user_id1']))->save(['pid' => $this->in['user_id2']]);
            Db::name('user_parent')->where(array('user_id' => $this->in['user_id1']))->save(array(
                'pid1' => $newInfo['user_id'],
                'pid2' => $newInfo['pid1'],
                'pid3' => $newInfo['pid2'],
                'uptime' => date('Y-m-d H:i:s')
            ));
            Db::name('user_parent')->where(array('pid1' => $this->in['user_id1']))->save(array(
                'pid2' => $newInfo['user_id'],
                'pid3' => $newInfo['pid1'],
                'uptime' => date('Y-m-d H:i:s')
            ));
            Db::name('user_parent')->where(array('pid2' => $this->in['user_id1']))->save(array(
                'pid3' => $newInfo['user_id'],
                'uptime' => date('Y-m-d H:i:s')
            ));
            $this->model->clearCache($this->in['user_id1']);
            $this->model->clearCache($this->in['user_id2']);
            $this->success(lang('s'), url('set_pid'));
        }
        View::assign('title', '设置上级');
        return $this->display();
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $in = input();
        $where[$in['key']] = $in['keyid'];
        $this->model->where($where)->save([$in['field'] => $in['val']]);
        $this->model->clearCache($in['keyid']);
        $this->success(lang('s'));
    }

    public function getuser() {
        $key = $this->in['username'] ?? $this->in['key'];
        if ($key) {
            $where = [];
            if (is_numeric($key)) {
                $where[] = ['username', 'like', "%{$key}%"];
                $where[] = ['id', '=', $key];
                $res = Db::name('user')->whereOr($where)->limit(20)->field('id,username')->select()->toArray();
            } else if ($key) {
                $where[] = ['username', 'like', "%{$key}%"];
                $res = Db::name('user')->where($where)->limit(20)->field('id,username')->select()->toArray();
            } else {
                die;
            }


            $temp = array();
            foreach ($res as $key => $value) {
                $temp[] = array(
                    'id' => $value['id'],
                    'text' => "【{$value['id']}】{$value['username']}"
                );
            }
            echo json_encode($temp);
        }
    }

    /**
     * 等级设置
     * @auto true
     * @auth true
     * @menu true
     */
    public function set_rank() {
        if ($this->request->isPost()) {

            $this->in['user_id'] = str_replace("，", ',', $this->in['user_id']);
            $this->in['user_id'] = str_replace(array(" ", "　", "\t", "\n", "\r"), "", $this->in['user_id']);
            $us = explode(',', $this->in['user_id']);
            if (!$us) {
                $this->error('请输入用户编号');
            }
            $this->in['rank'] || $this->error('请选择等级');
            foreach ($us as $user_id) {
                $id = Db::name('user')->where(array('id' => $user_id))->value('id');
                if (!$id) {
                    $this->error("用户{$user_id}不存在");
                }
                $this->model->clearCache($user_id);
            }
            Db::name('user')->where([['id', 'in', $us]])->save(array('rank' => $this->in['rank']));
            $this->success(lang('s'), '', -1);
        }
        return $this->display();
    }

}
