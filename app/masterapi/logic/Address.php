<?php

namespace app\masterapi\logic;

use app\home\model\O;
use app\masterapi\model\UserAddress;
use think\App;
use think\facade\Db;

/**使用uni 地址插件的版本
 * Class Address
 * @package app\mallapi\logic
 */
class Address {

    protected $oModel;
    protected $user_add;
    public $in;
    public $uinfo;
    public $data;
    public $request;

    public function __construct() {
        $this->oModel = new O();
        $this->user_add = new UserAddress();
    }

    public function config($map) {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    /**
     * 区域列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_region() {
        $list = Db::name('system_areas')->order('sort asc')->where(array(
            'pid' => 0,
            'level' => 1
        ))->cache(true)->select()->toArray();
        array_unshift($list, array('id' => '', 'name' => '请选择区域'));
        return array(
            'status' => 1,
            'data' => array($list, array(array('id' => '', 'name' => '')), array(array('id' => '', 'name' => '')))
        );
    }

    /**
     * 获取区域
     * @return array
     */
    public function get_areas() {
        $list = $this->oModel->get_quyu($this->in['pid']);
        $list = $list ?: [];
        array_unshift($list, array('id' => '', 'name' => '请选择'));
        $this->data['data'] = $list;
        return array('status' => 1, 'data' => $this->data);
    }

    /**
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function info()
    {
        $this->data['info'] = [];
        if($this->in['id']){
            $where = array('master_id' => $this->uinfo['id'], 'id' => $this->in['id']);
            $this->data['info'] = $this->user_add->where($where)->find();
            $oh=new O();
            if ($this->data['info']) {
                $this->data['info']['province_name']=$oh->getAreas($this->data['info']['province']);
                $this->data['info']['city_name']=$oh->getAreas($this->data['info']['city']);
                $this->data['info']['country_name']=$oh->getAreas($this->data['info']['country']);
                $area_name = $this->data['info']['province_name'].'-'.$this->data['info']['city_name'].'-'.$this->data['info']['country_name'];
                $this->data['address_text'] =$area_name;
            }
        }
        $this->data['city_json']=(new O())->get_json();

        return array('status' => 1, 'data' => $this->data);
    }

    //删除
    public function del() {
        $where = array('master_id' => $this->uinfo['id'], 'id' => $this->in['id']);
        $this->user_add->where($where)->delete();
        return array('status' => 1, 'info' => '删除成功');
    }

    //加载列表
    public function get_list() {
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $this->data['list'] = $this->user_add->user_list($this->uinfo['id'], $page);
        return array('status' => 1, 'data' => $this->data);
    }


    //保存
    public function item()
    {

        $Common = new \app\comapi\controller\Rule(app());
        // $Common->check('address');
        if (!$this->in['province']) {
            return array('status' => 0, 'info' => '省份不能为空');
        }

        if (!$this->in['city']) {
            return array('status' => 0, 'info' => '城市不能为空');
        }

        if (!$this->in['country']) {
            return array('status' => 0, 'info' => '地区不能为空');
        }
        $this->in['is_default'] = $this->in['is_default'] ? 1: 0;
        if ($this->in['id']) {
            $where['id'] = $this->in['id'];
            $where['master_id'] = $this->uinfo['id'];
            $r = $this->user_add->where($where)->save($this->in);
            $aid = $this->in['id'];
        } else {
            $this->in['master_id'] = $this->uinfo['id'];
            $this->in['user_id'] = '';
            $aid = $this->user_add->insertGetId($this->in);
        }
        $this->user_add->set_default($this->in['is_default'], $aid, $this->uinfo['id']);
        return array('status' => 1, 'info' => '保存成功');
    }



//  设为默认（列表）
    public function set_default() {
        $aid = $this->in['id'];
        if (!$aid) {
            return array('status' => 0, 'info' => '获取地址信息失败');
        }
        $this->in['is_default'] = 1;
        $this->user_add->set_default($this->in['is_default'], $aid, $this->uinfo['id']);
        $where['id'] = $this->in['id'];
        $where['master_id'] = $this->uinfo['id'];
        $this->user_add->where($where)->save($this->in);
        return array('status' => 1, 'info' => '设置成功');
    }

}
