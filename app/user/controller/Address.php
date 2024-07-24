<?php

namespace app\user\controller;



use app\common\model\O;
use app\common\model\User;
use app\user\model\UserAddress;
use app\user\model\UserRank;
use think\App;
use think\facade\Db;
use think\facade\View;


/**
 * 用户地址
 * @auto true
 * @auth true
 * @menu true
 */
class Address extends Common {
    public $ho;

    public function __construct(App $app)
    {
        parent::__construct($app);
        View::assign('title', '用户地址');
        $this->model = new UserAddress();
        $this->ho=new O();
        View::assign('ho', $this->ho);

    }
    /**
     * 地址列表
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
        $where = array();
        $this->model=Db::name('user_address');
        if ($this->in['user_id']) {
            $where[] = ['a.user_id','=',$this->in['user_id']];
        }
        if ($this->in['name']) {
            $where[] = array('a.name','like', "%{$this->in['name']}%");
        }
        if ($this->in['tel']) {
            $where[] = array('a.tel','like', "%{$this->in['tel']}%");
        }
        if ($this->in['address']) {
            $where[] = array('a.address','like', "%{$this->in['address']}%");
        }
        $count = $this->model->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $data['list'] = $this->model->alias('a')->where($where)
            ->leftJoin("user b","b.id=a.user_id")
            ->order('a.id desc')
            ->field("a.*,b.username")
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select()->toArray();
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 添加地址
     * @auto true
     * @auth true
     * @menu true
     */
    public function item() {
        if ($this->request->isPost()) {
            $in = input();
            if ($in['id'])
                $r = $this->model->update($in);
            else
                $r = $this->model->insertGetId($in);
            if ($r === 0 || $r) {
                $this->success(lang('s'), url('index'));
            } else {
                $this->error(lang('e'));
            }
            die;
        }
        if (input('id')) {
            $info = $this->model->find(input('id'));
            View::assign('info', $info);
        }

        return $this->display();
    }

    /**
     * 删除地址
     * @auto true
     * @auth true
     * @menu false
     */
    public function del() {

        if ($this->model->where(['id'=>$this->in['id']])->delete()) {
            $this->success(lang('s'));
        } else {
            $this->error(lang('e'));
        }
    }

}