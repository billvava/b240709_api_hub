<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminRole extends Model
{

    protected $pk = 'role_id';
    //插入菜单进某个角色
    public static function insertNav($role_id, $nav_id) {
        $admin_role_node = Db::name('admin_role_nav');
        if (is_array($nav_id)) {
            $where[]=['role_id','=',$role_id];
            $where[]=['nav_id','in',$nav_id];
            $admin_role_node->where($where)->delete();
            $temp = array();
            foreach ($nav_id as $value) {
                $temp[] = array('role_id' => $role_id, 'nav_id' => $value);
            }
            $admin_role_node->insertAll($temp);
        } else {
            $admin_role_node->where(array('role_id' => $role_id, 'nav_id' => $nav_id))->delete();
            $admin_role_node->insert(array('role_id' => $role_id, 'nav_id' => $nav_id));
        }
    }

    public static function getNodeForRole($role_id) {
        return Db::name('admin_role_node')->where(array('role_id' => $role_id))->select()->toArray();
    }

    /**
     * 获取一维数组
     * @param type $role_id
     * @return type
     */
    public static function getOneNode($role_id) {
        return Db::name('admin_role_node')->where(array('role_id' => $role_id))
            ->column('node_id');
    }

    /**
     * 获取菜单一维数组
     * @param type $role_id
     * @return type
     */
    public static function getOneNav($role_id) {
        return Db::name('admin_role_nav')
            ->where(array('role_id' => $role_id))->column('nav_id');
    }

    public static function onAfterDelete($data) {
        if ($data->role_id) {

            Db::name('admin_role_node')->where(array('role_id' => $data->role_id))->delete();

            Db::name('admin_role_nav')->where(array('role_id' => $data->role_id))->delete();
            Db::name('admin_user')->where(array('role_id' => $data->role_id))->delete();
        }
    }

    //获取权限
    public static function getAuth($role_id) {
        if (!$role_id) {
            return null;
        }
        $cache_name = "getAuth{$role_id}";
        $data = cache($cache_name);
        if (!$data) {
            $Access = Db::name('admin_nav');
            $all = $Access->alias('a')->
            join('admin_role_nav b','a.id=b.nav_id')
                ->where(array(['b.role_id' ,'=',$role_id],['node' ,'<>','$role_id']))
                ->column("node");
            $data = array_filter(array_unique($all));
            foreach ($data as &$v) {
                $vs = explode('/', $v);
                if ($vs[2] == '') {
                    $v = $v . 'index';
                }
            }

            cache($cache_name, $data);
        }
        return $data;
    }

    //获取所有菜单的树，从上往下
    public static function recursion($res, $pid = 0, $lev = 1) {
        $list = array();
        foreach ($res as $v) {
            if ($v['pid'] == $pid) {
                $v['lev'] = $lev;
                $t = $lev + 1;
                $v['child'] = self::recursion($res, $v['node_id'], $t);
                $list[] = $v;
            }
        }
        return $list;
    }

    //转换
    public static function toNode($all) {
        $list = array();
        foreach ($all as $v) {
            foreach ($v['child'] as $vv) {
                foreach ($vv['child'] as $vvv) {
                    $list[$v['name']][$vv['name']][] = $vvv['name'];
                }
            }
        }
        return $list;
    }
    

}