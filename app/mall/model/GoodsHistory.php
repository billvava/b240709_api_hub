<?php
namespace app\mall\model;

use think\facade\Db;
use think\Model;
/**
*代码自动生成所需参数
*moudleName:模块名称
*tableName:表名
*modelName:模型名称
**/
class GoodsHistory extends Model {

    protected $name = 'mall_goods_history';


    public function addLog($goods_id, $user_id, $type = 1) {
        if (!$goods_id || !$user_id) {
            return false;
        }
        $this->where(array('user_id' => $user_id, 'goods_id' => $goods_id, 'type' => $type))->delete();
        $this->insert(array('user_id' => $user_id, 'goods_id' => $goods_id, 'type' => $type, 'time' => date('Y-m-d H:i:s')));
        return true;
    }

    public function getUserList($user_id, $page = 1) {
        $where = array(
            'a.user_id' => $user_id,
            'b.status' => 1,
        );
        $field = array('thumb', 'name', 'a.goods_id', 'min_price', 'min_market_price', 'small_title');
        $list = Db::name($this->name)->alias('a')->join('mall_goods b','a.goods_id=b.goods_id')
            ->where($where)->field($field)->page(($page ?: 1), 10)->order("id desc")->select()->toArray();
        foreach ($list as &$v) {
            $v = $this->handle($v);
        }
        return $list;
    }

    public function handle($v) {
        $v['thumb'] = get_img_url($v['thumb']);
        return $v;
    }

    public function getList($where, $page = 1, $num = 10, $cache = false) {
        $order = "id desc";
        $data = $this->where($where)->page(($page ?: 1), $num)->order($order)->cache($cache)->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function getCount($user_id){
        $where = array(
            'a.user_id' => $user_id,
            'b.status' => 1,
        );
        return Db::name($this->name)->alias('a')->join('mall_goods b','a.goods_id=b.goods_id')
            ->where($where)->count();
    }

    public function getData($where = array(), $num = 10) {
        $count = $this->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, $num);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = $this->where($where)->limit($Page->firstRow , $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array()) {
        $pre = md5(serialize($where));
        $name = "mall_content_1597977173_{$pre}";
        $data = cache($name);
        if (!$data) {
            $order = "id desc";
            $data = $this->where($where)->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "mall_content_info_1597977173_{$pk}";
        $data = cache($name);
        if (!$data) {
            $mypk = $this->getPk();
            if (!$mypk) {
                return null;
            }
            $data = $this->find($pk);
            if ($data) {
                $data = $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }

}
