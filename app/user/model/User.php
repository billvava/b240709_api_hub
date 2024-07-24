<?php
declare (strict_types = 1);

namespace app\user\model;

use app\common\Lib\Util;
use app\home\model\O;
use think\facade\Config;
use think\facade\Db;
use think\Model;
use app\common\model\User as Base;

/**
 * @mixin think\Model
 */
class User extends Base{

    public function getList($in) {
        $cache_name = "goods_data_" . md5(serialize($in));
        if ($in['cache'] == true) {
            $data = cache($cache_name);
            if ($data) {
                return $data;
            }
        }
        $where = array();
        //时间查询
        if ($in['start_datetime'] && $in['end_datetime']) {
            $where[] = array('create_time','between', array(($in['start_datetime']), ($in['end_datetime'])));
        } elseif ($in['start_datetime']) {
            $where[] = array('create_time','>', ($in['start_datetime']));
        } elseif ($in['end_datetime']) {
            $where[] = array('create_time','<', ($in['end_datetime']));
        }
        //用户名查询
        if ($in['username']) {
            $where[] = array('username','like', "%{$in['username']}%");
        }
        if ($in['nickname']) {
            $where[] = array('nickname','like', "%{$in['nickname']}%");
        }
        if ($in['rank']) {
            $where[] = ['rank','=',$in['rank']];
        }
        if ($in['id']) {
            $where[] = ['id','=',$in['id']];
        }
        //状态查询
        if ($in['status'] != '') {
            $where[] = ['status','=',$in['status']];
        }
        $order = "id desc";
        if ($in['order']) {
            $order = "{$in['order']} desc";
        }
        //是否需要分页
        $page_num = $in['page_num'] ? $in['page_num'] : 10;
        if ($in['page_type']) {
            $count = Db::name('user')->alias('a')->where($where)->count();
            if ($in['page_type'] == 'admin') {
                tool()->classs('PageForAdmin');
                $Page = new \PageForAdmin($count, $page_num);

            } else {
                tool()->classs('Page');
                $Page = new \Page($count, $page_num);
            }
            $data['page'] = $Page->show();
            $data['total'] = $count;
            $start = $Page->firstRow;
        } else {
            $page = $in['page'] ? $in['page'] : 1;
            $start = ($page - 1) * $page_num;
        }

        $data['list'] = Db::name('user')
            ->alias('a')
            ->field('a.*')
            ->where($where)
            ->limit($start , $page_num)
            ->order($order)
            ->select()->toArray();
        $oh =new O();
        foreach ($data['list'] as $key=> $value){
            $province_name = $oh->getAreas($value['province']);
            $city_name = $oh->getAreas($value['city']);
            $country_name =$oh->getAreas($value['country']);
            $area_name = $province_name.'-'.$city_name.'-'.$country_name;
            $value['address_text'] =$area_name;
            $value['shop_name'] = Db::name('shop') -> where('id',$value['shop_id'])->value('name');
            $data['list'][$key] = $value;
        }
        $data['num'] = count($data['list']);
        if ($in['cache'] == true) {
            cache($cache_name, $data);
        }
        return $data;
    }


    public function getRanks($cache=false) {
        return $ranks = ['游客','vip','svip'];
        $data=Db::name('user_rank')->cache($cache)->field('id,name')->select()->toArray();
        foreach ($data as $v){
            $res[$v['id']]=$v['name'];
        }
        return $res;
    }


    /**
     * 登录验证
     * @param type $username
     * @param type $pwd
     * @return type
     */
    public function login($username='', $pwd='') {
        $username = trim($username);
        $pwd = trim($pwd);
        $where[] =['pwd','=',xf_md5($pwd)] ;
        $where[] =['status','=',1] ;

        $where[]=function ($query) use($username){
            $w[] =['username','=',$username] ;
//            $w[] =['tel','=',$username];
            $query->whereOr($w);
        };
        $user = $this->where($where)->find();

        return $user;
    }

    /**
     * 更新登录信息
     */
    public function update_info($info) {
        return $this->add_token($info['id']);
    }

    /**
     * 导出
     */
    public function outUserLog($table, $in = array()) {
        $model = Db::name("user_{$table}");
        $where = array();
        //时间查询
        if ($in['start_datetime'] && $in['end_datetime']) {
            $where[] = array('a.time','between', array(($in['start_datetime']), ($in['end_datetime'])));
        } elseif ($in['start_datetime']) {
            $where[] = array('a.time','>', ($in['start_datetime']));
        } elseif ($in['end_datetime']) {
            $where[] = array('a.time','<', ($in['end_datetime']));
        }

        //用户名查询
        if ($in['user_id']) {
            $where[] =['a.user_id','=',$in['user_id']] ;
        }
        if ($in['ordernum']) {
            $where[] =['a.ordernum','=',$in['ordernum']] ;
        }
        if ($in['type']) {
            $where[] =['a.type','=',$in['type']] ;
    
        }
        if ($in['cate']) {
            $where[] =['a.cate','=',$in['cate']] ;
        }

        $a = array(
            'a.id' => '编号',
            'a.user_id' => '用户编号',
            'b.username' => '用户',
            'a.time' => '时间',
            'a.total' => '额度',
            'a.type' => '类型',
            'a.msg' => '说明',
            'a.cate' => '分类',
            'a.admin_id' => '后台操作ID',
        );
        $names = array(
            'bro' => '佣金', 'dot' => '积分', 'money' => '余额',
        );

        $data = $model->alias('a')
            ->leftJoin('user b','a.user_id=b.id')
            ->field(array_keys($a))
            ->where($where)
            ->order('a.id asc')
            ->select()->toArray();
        $cate = lang("user_{$table}_cate");
        $type = lang("user_log_type");
        foreach ($data as &$v) {
            $v['cate'] = $cate[$v['cate']];
            $v['type'] = $type[$v['type']];
        }
        (new Util())->format_xls($names[$table], array_values($a), $data);
    }



    
}