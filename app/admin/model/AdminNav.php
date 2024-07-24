<?php

declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;
use app\admin\model\AdminRole;

/**
 * @mixin think\Model
 */
class AdminNav extends Model {

    public function up_all_node($path = '', $top_id = 1, $is_del = 1) {
        tool()->classs('Node');
        $node = new \Node();
        $res = $node->getMethods($path);
        $temp = array();
        foreach ($res as $k => $v) {
            $pid = $this->addHandle($v, $top_id);
            $temp[] = $node->xiahuaxianTotop($v['node']);
            isset($v['list']) || $v['list'] = [];
            foreach ($v['list'] as $vv) {
                $temp[] = $node->xiahuaxianTotop($vv['node']);
                $this->addHandle($vv, $pid);
            }
        }
        //删除不存在的
        if ($is_del == 1) {
            if ($temp) {
                Db::name('admin_nav')->where([['isauto', '=', 1], ['node', 'not in', $temp]])->delete();
            } else {
                Db::name('admin_nav')->where(['isauto', '=', 1])->delete();
            }
        }
    }

    private function addHandle($v, $pid = 1) {
        tool()->classs('Node');
        $node = new \Node();
        $nstr = $node->xiahuaxianTotop($v['node']);
        $is = Db::name('admin_nav')->where(array('isauto' => 1, 'node' => $nstr))->find();
        $save = array(
            'isauto' => 1,
            'isauth' => $v['isauth'],
            'ismenu' => $v['ismenu'],
            'name' => $v['name'],
        );
        if ($v['icon']) {
            $save['icon'] = $v['icon'];
        }
        if ($is) {
            Db::name('admin_nav')->where(array('isauto' => 1, 'node' => $nstr))->save($save);
            $pid = $is['id'];
        } else {
            $ex_add = array(
                'pid' => $pid,
                'url' => "/" . $nstr,
                'node' => $nstr,
            );
            $save = array_merge($save, $ex_add);
            $pid = Db::name('admin_nav')->insertGetId($save);
        }
        return $pid;
    }

    //获取所有菜单的树，从上往下
    public function getAllAuthNode() {
        $cache_name = "getAllAuthNode";
        $data = cache($cache_name);
        if (!$data) {
            $where = [
                    ['isauth', '=', 1],
                    ['node', '<>', ''],
            ];
            $all = $this->where($where)->column('node');
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
    public function getAllTree() {
        $role_id = session('admin.role_id');
        $admin_developer = session('admin_developer');
        $name = "getAllTree_{$role_id}_{$admin_developer}";
        if (!$res = cache($name)) {
            $res = $this->get_all();
            foreach ($res as &$v) {
                if (!is_contain_http($v['url'])) {
                    $v['url'] = __ROOT__ . $v['url'];
                }
            }
            $res = $this->recursion($res);

            cache($name, $res);
        }
        return $res;
    }

    /**
     * 菜单的递归
     * @param type $res
     * @param type $pid
     * @param type $lev
     * @return type
     */
    public function recursion($res, $pid = 0, $lev = 1) {
        $list = array();
        foreach ($res as $v) {
            if ($v['pid'] == $pid) {
                $v['lev'] = $lev;
                $t = $lev + 1;
                $v['child'] = $this->recursion($res, $v['id'], $t);
                $list[] = $v;
            }
        }
        return $list;
    }

    //获得该角色所有菜单
    public function get_all($cache = 3600, $type = 1) {
        $where = array();
        $where[] = ['status', '=', 1];
        $where[] = ['ismenu', '=', 1];
        if ($type == 1) {
            
        }

        if ($type == 2) {
            $where = [
                    ['status', 'in', [0, 1]],
                    ['ismenu', '=', 1],
            ];
        }
        $adminInfo = session('admin');
        $nav = (new AdminRole())->getOneNav($adminInfo['role_id']);
        $admin_developer = session('admin_developer');
        if (!$nav && !$admin_developer) {
            return null;
        } elseif (!$admin_developer && $nav) {
            $where[] = array('id', 'in', $nav);
        }
        return $this->where($where)->field('*')->order('sort asc,id asc')->cache($cache)->select()->toArray();
    }

    /**
     * 对菜单进行递归,主要为了区分层次
     * @staticvar array $list
     * @param type $arr
     * @param type $id
     * @param type $key
     * @param type $lev
     * @return type
     */
    public function recursionPinfo($arr, $id = 0, $key = 'id', $lev = 0) {
        $list = array();
        foreach ($arr as $k => $v) {
            if ($v['pid'] == $id) {
                $v['lev'] = $lev;
                $v['levs'] = $lev * 2;
                $list[] = $v;
                $list = array_merge($list, $this->recursionPinfo($arr, $v[$key], $key, $lev + 1));
            }
        }
        return $list;
    }

    /**
     * 递归向下查询子集分类
     * @param type $catid
     * @return type
     */
    public function getZtree($pid, $check_nav, $where = array()) {
        $w = array(
                ['pid', '=', $pid],
        );
        if ($where) {
            $w = array_merge($w, $where);
        }
        $result = $this->where($w)->order('sort asc')->select()->toArray();
        foreach ($result as $k => &$v) {
            $result[$k] = array(
                'node' => "{$v['id']}",
                'id' => $v['id'],
                'title' => $v['name'],
                'pnide' => $pid ? "{$v['id']}" : '',
                "checked" => in_array($v['id'], $check_nav) ? true : false,
            );
        }
        if ($result) {
            foreach ($result as $key => &$value) {
                $value['_sub_'] = $this->getZtree($value['id'], $check_nav, $where);
            }
        }
        return $result;
    }

    //获取递归好的数据
    public function getRPlist() {
        return $this->recursionPinfo($this->get_all());
    }

    //获得内容下面菜单
    public function getContentMeun() {
        $ids = null;
        $adminInfo = session('admin');
        $nav = (new AdminRole())->getOneNav($adminInfo['role_id']);
        $admin_developer = session('admin_developer');
        if (!$nav && !$admin_developer) {
            return null;
        } elseif (!$admin_developer && $nav) {
            $ids = array('in', $nav);
        }
        $list = $this->getContentRes(1, $ids);
        return $list;
    }

    //获取下面的数据
    public function getContentRes($pid = 1, $ids = null) {
        $list = array();
        $where['pid'] = $pid;
        if ($ids != null) {
            $where['id'] = $ids;
        }
        $t = $this->where($where)->order('sort asc')->select()->toArray();
        $list = array_merge($list, $t);
        if ($t) {
            foreach ($t as $key => $value) {
                $arr = $this->getContentRes($value['id'], $ids);
                $list = array_merge($list, $arr);
            }
        }
        return $list;
    }

    /**
     * ztree版本,左边菜单
     * @return type
     */
    public function create_json_meun() {
        $dev = session('admin_developer') ? 1 : 0;
        $ca_name = 'create_json_meun_' . session('admin.role_id') . '_' . $dev;
        $html = cache($ca_name);
        if ($html) {
            return $html;
        }
        $res = $this->getContentMeun();
        $html = '';
        $i = 0;
        foreach ($res as $k => &$v) {
            $url = $this->getUrl($v);
            $open = $v['is_show'] ? 'true' : 'false';
            $html .= "{ id:{$v['id']}, pId:{$v['pid']}, name:'{$v['name']}',url:'{$url}',target:'main', open:'{$open}'},\n";
        }
        cache($ca_name, $html);
        return $html;
    }

    /**
     * 生成新版的菜单
     * @return type
     */
    public function create_new_meun() {
        $dev = session('admin_developer') ? 1 : 0;
        $ca_name = 'create_new_meun_' . session('admin.role_id') . '_' . $dev;
        $html = cache($ca_name);
        if ($html) {
            return $html;
        }
        $res = $this->get_all();
        $res = $this->getNavData($res);
        $html = $this->getNavHtml($res);
        cache($ca_name, $html);
        return $html;
    }

    /**
     * 生成左边菜单的html
     * @param type $res
     * @return type
     */
    public function getNavHtml($res) {
        $html = '<ul>';
        foreach ($res as $v) {
            if ($v['lev'] == 1 && $v['id'] != 1) {
                $url = $this->getUrl($v);
                $icon = $v['icon'] ? "icon-" . $v['icon'] : "icon-th";
                $html .= '<li class="meunParentLi">
        <a id="meun' . $v['id'] . '"  href="' . $url . '" target="' . $value['target'] . '" class="meunParentA"><i class="icon ' . $icon . ' mg9"></i>' . $v['name'] . '<i class="icon icon-angle-right juebi"></i></a> ' . PHP_EOL;
                if ($v['child']) {
                    $hide = '';
                    if ($v['is_show'] == 0) {
                        $hide = 'hide';
                    }
                    $url = $this->getUrl($value);
                    $html .= '<ul class="meunChildUl ' . $hide . '">' . PHP_EOL;
                    foreach ($v['child'] as $value) {
                        $html .= '<li><a id="meun' . $value['id'] . '" target="' . $value['target'] . '" class="meunChildA" href="' . $url . '">' . $value['name'] . '</a></li>' . PHP_EOL;
                    }
                    $html .= '</ul>' . PHP_EOL;
                }
                $html .= '</li>';
            }
        }
        $html .= "<li class='meunParentLi'  id='meun_neorong'><a href='#' class='meunParentA'><i class='icon icon-th mg9'></i>内容管理<i class='icon icon-angle-right juebi'></i></a><ul class='ztree meunChildUl hide' id='treeDemo'></ul></li>";
        return $html . '</ul>';
    }

    /**
     * 左边菜单的递归
     * @param type $res
     * @param type $pid
     * @param type $lev
     * @return type
     */
    public function getNavData($res, $pid = 0, $lev = 1) {
        $list = array();
        foreach ($res as &$v) {
            $v['href'] = $v['url'];

            if ($v['icon'] && strpos($v['icon'], "iconfont ") === false && strpos($v['icon'], "layui-") === false) {
                $v['icon'] = "iconfont {$v['icon']}";
            }
            if (!$v['icon']) {
                $v['icon'] = "iconfont icon-keyboard";
            }
            $v['title'] = $v['name'];
            is_array(!$v['children']) || $v['children'] = [];
            if (count($v['children']) > 0) {
                $v['type'] = 0;
            } else {
                $v['type'] = 1;
            }
            if ($v['pid'] == $pid) {
                $v['lev'] = $lev;
                $t = $lev + 1;
                $v['children'] = $this->getNavData($res, $v['id'], $t);
                if (count($v['children']) > 0) {
                    $v['type'] = 0;
                } else {
                    $v['type'] = 1;
                }
                $list[] = $v;
            }
        }
        return $list;
    }

    //生成菜单URL
    public function getUrl($info, $is_echo = false) {
        $url = '';
        if ($info['type'] == 1 && $info['lev1'] && $info['lev2'] && $info['lev3']) {
            $info['lev1'] = str_replace(array('@'), array(ADMIN_URL), $info['lev1']);
            $url = __ROOT__ . "/index.php/{$info['lev1']}/{$info['lev2']}/{$info['lev3']}{$info['param']}";
        } else if ($info['type'] == 2) {
            $url = $info['url'];
            if (!is_contain_http($url)) {
                $url = __ROOT__ . $url;
            }
        }
        if ($is_echo) {
            echo $url;
        } else {
            return $url;
        }
    }

}
