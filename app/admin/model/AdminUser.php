<?php
declare (strict_types = 1);

namespace app\admin\model;

use app\admin\logic\LoginLogic;
use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminUser extends Model
{
    //核对就密码
    function checkPwd($pwd) {
        $login = new LoginLogic();
        $info = $login->getInfo();
        $uid = $info['id'];
        if (!$uid)
            return false;
        $pwd = xf_md5($pwd);
        $id = $this->where(array('id' => $uid, 'pwd' => $pwd))->value('id');
        if (empty($id)) {
            return false;
        } else {
            return true;
        }
    }

    public function setpwd($pwd) {
        if ($pwd) {
            return xf_md5($pwd);
        }
    }

    //获取所有
    public function getAll() {
        return Db::name('admin_user')->alias('a')
            ->leftJoin('admin_role b','a.role_id=b.role_id')
            ->field("a.*,b.name as role_name")
            ->select()->toArray();
    }

    //获取单条信息
    public function getInfo($id) {
        $data = Db::name('admin_user')->alias('a')
            ->leftJoin('admin_role b','a.role_id=b.role_id')
            ->field("a.*,b.name as role_name,b.status as role_status")
            ->where(array('a.id' => $id))
            ->find();
        return $data;
    }

    //获取角色状态
    public function getRoleStatus($id) {
        $data = Db::name('admin_user')->alias('a')
            ->leftJoin('admin_role b','a.role_id=b.role_id')
            ->field("b.status as role_status")
            ->where(array('a.id' => $id))
            ->find();
        return $data['role_status'];
    }

    //记录登录日志
    public function addLog($map = array()) {
        $map['create_time'] = date('Y-m-d H:i:s');
        $map['ip'] = get_client_ip();
        return Db::name('admin_login_log')->insertGetId(
            $map
        );
    }

    public function setLogE($logid, $is_error = 0) {
        return Db::name('admin_login_log')->where(array('id' => $logid))->save(array('is_error' => $is_error, 'pwd' => ''));
    }

    //当天登录错误次数
    public function getLoginErrorCount() {
        $where[] = ['is_error','=',1];
        $date = date('Y-m-d');
        $where[] = array('create_time','between', array("{$date} 00:00:00", "{$date} 23:59:59"));
        return Db::name('admin_login_log')->where($where)->count();
    }

    //登录机会
    public function surplusLoginNum($username) {
        if (!$username) {
            return 0;
        }
        $login_error_admin_count = C('login_error_admin_count');
        $login_error_num = $this->where(array('username' => $username))->value('login_error_num');
        return $login_error_admin_count - $login_error_num;
    }

    //禁用用户
    public function forbiddenUser($username) {
        return $this->where(array('username' => $username))->save(['status'=> 0]);
    }

    /**
     * 登陆日志
     * @param type $admin_id
     */
    public function getTopLog($username) {
        return Db::name('admin_login_log')->where(array('username' => $username, 'is_error' => 0))->cache(1800)->limit(5)->order("id desc")->select()->toArray();
    }

    public function getLgoData($in) {
        $where = array();
        if ($in['username']) {
            $where['username'] = array("like", "%{$in['username']}%");
        }
        if ($in['ip']) {
            $where['ip'] = array("like", "%{$in['ip']}%");
        }
        $count = Db::name('admin_login_log')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $order = "id desc";
        $data['count'] = $count;
        $data['list'] = Db::name('admin_login_log')->where($where)->limit($Page->firstRow , $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

}
