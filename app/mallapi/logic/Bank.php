<?php

namespace app\mallapi\logic;

use app\user\model\UserBank;
use think\facade\Db;

class Bank
{

    public $clear;
    public $uinfo;
    public $data;
    public $model;

    public function __construct()
    {
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }

    private function filter_name()
    {
        $this->data['card_name'] = '银行卡';
        $this->data['bank_name'] = '银行';
        $this->data['khh_name'] = '开户行';
        $this->data['card_num'] = '银行卡号';
        $this->data['khr_name'] = '开户人';


    }

    //银行卡
    public function index()
    {
        $UserRank = new UserBank;
        $where = array('user_id' => $this->uinfo['id']);
        $this->data['list'] = $UserRank->getList($where, $this->in['page'] ? $this->in['page'] : 1);
        $this->filter_name();

        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    public function item()
    {
        $UserRank = new UserBank();
        if ($this->in['flag'] == 'sub') {


            (new \app\mallapi\controller\Rule(app()))->check('bank', $this->in);


            if ($this->in['id']) {
                Db::name('user_bank')->where(array('id' => $this->in['id'], 'user_id' => $this->uinfo['id']))->update($this->in);
            } else {
                $this->in['user_id'] = $this->uinfo['id'];
                Db::name('user_bank')->insert($this->in);
            }
            return array('status' => 1, 'info' => '操作成功', 'data' => $this->data);

        }
        if ($this->in['id'] && $this->in['flag'] == 'get') {
            $info = $UserRank->getInfo($this->in['id']);
            if ($info['user_id'] != $this->uinfo['id'] || !$info['user_id']) {
                unset($info);
            } else {
                $this->data['info'] = $info;
            }
        }
        $this->filter_name();
        return array('status' => 1, 'data' => $this->data);
    }

    public function del()
    {
        $UserRank = new UserBank;
        $where = array('user_id' => $this->uinfo['id'], 'id' => $this->in['id']);
        $UserRank->where($where)->delete();
        return array('status' => 1);
    }


}
