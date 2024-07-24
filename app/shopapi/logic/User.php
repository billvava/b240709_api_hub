<?php

namespace app\shopapi\logic;

use app\shopapi\controller\Common;
use think\facade\Db;
use app\shopapi\logic\Goods;

use app\shopapi\model\{GoodsHistory, Order, MallCoupon, User as MallUser};
use app\user\model\{UserAddress, UserMsg, UserBrodj, UserBank};

use app\common\model\{
    Weixin, SuoMaster
};

use app\shopapi\model\Rule;

class User
{

    public $clear;
    public $uinfo;
    public $data;
    public $model;

    public function __construct()
    {
        $this->goodsLogicModel = new Goods;
        $this->orderModel = new Order;
        $this->model = new SuoMaster();

    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }

    public function index()
    {
        //站内信
        $is_new_msg = Db::name('user_msg')->where(array('master_id' => $this->uinfo['id'], 'is_read' => 0))->value('id');
        $this->data['is_new_msg'] = $is_new_msg ? 1 : 0;
        $this->data['coupon_num'] = (new MallCoupon)->getCanCount($this->uinfo['id']);
        $atn = new Atn();
        $atn->config([
            'data' => $this->data,
            'in' => $this->in,
            'uinfo' => $this->uinfo,
        ]);
        $this->data['atn_count'] = $atn->atn_count();

        $History = new GoodsHistory();
        $this->data['History_count'] = $History->getCount($this->uinfo['id']);


        $this->data['finfo'] = $this->model->getFinance($this->uinfo['id']);
        $this->data['uinfo'] = get_arr_field($this->uinfo, array('headimgurl', 'nickname', 'rank_name', 'rank'));
        $this->data['orderCountInfo'] = $this->orderModel->census(array('wait_pay_count', 'wait_send_count', 'wait_get_count',
            'after_count', 'wait_finish_count', 'wait_com_count'), array('master_id' => $this->uinfo['id'], 'is_u_del' => 0));

        $com_cdn = C('com_cdn');
        $this->data['order_nav'] = [
            [
                'name' => '待付款',
                'count' => $this->data['orderCountInfo']['wait_pay_count'],
                'url' => '/pages/order/index/index?flag=wait_pay',
                'ico' => "{$com_cdn}user/my_order_icon1.png",
            ],
            [
                'name' => '待发货',
                'count' => $this->data['orderCountInfo']['wait_send_count'],
                'url' => '/pages/order/index/index?flag=wait_send',
                'ico' => "{$com_cdn}user/my_order_icon2.png",
            ],
            [
                'name' => '待收货',
                'count' => $this->data['orderCountInfo']['wait_get_count'],
                'url' => '/pages/order/index/index?flag=wait_get',
                'ico' => "{$com_cdn}user/my_order_icon3.png",
            ],
            [
                'name' => '待评价',
                'count' => $this->data['orderCountInfo']['wait_com_count'],
                'url' => '/pages/order/index/index?flag=wait_com',
                'ico' => "{$com_cdn}user/my_order_icon4.png",
            ],
            [
                'name' => '售后',
                'count' => $this->data['orderCountInfo']['after_count'],
                'url' => '/pages/order/index/index?flag=after',
                'ico' => "{$com_cdn}user/my_order_icon5.png",
            ],
        ];
        return array('status' => 1, 'data' => $this->data);
    }

    //设置
    public function set()
    {
        if ($this->in['flag'] == 'get') {
            $address_text = "";
            if ($this->uinfo['province']) {
                $o = new \app\home\model\O();
                $province_name = $o->getAreas($this->uinfo['province']);
                $address_text .= "$province_name";
                if ($this->uinfo['city']) {
                    $name = $o->getAreas($this->uinfo['city']);
                    $address_text .= "-{$name}";

                    if ($this->uinfo['country']) {
                        $name = $o->getAreas($this->uinfo['country']);
                        $address_text .= "-{$name}";

                    }
                }

            }


            $this->data['uinfo'] = get_arr_field($this->uinfo, array('headimgurl', 'nickname', 'rank_name', 'rank', 'tel', 'sex', 'birthday', 'realname'));
            $this->data['address_text'] = $address_text ? $address_text : '请选择';
            return array('status' => 1, 'data' => $this->data);
        }
        if ($this->in['flag'] == 'sub') {
            $field = "nickname,headimgurl,sex,birthday,tel,realname";
            $add_model = new UserAddress();
            if ($this->in['province_name'] && $this->in['province_name'] <= 0) {
                $this->in['province'] = $add_model->getCityByName($this->in['province_name'], 1);
                $this->in['city'] = $add_model->getCityByName($this->in['city_name'], 2);
                $this->in['country'] = $add_model->getCityByName($this->in['country_name'], 3);
                $field .= ",province,city,country";
            }
            $this->model->where(array('id' => $this->uinfo['id']))->field($field)->update($this->in);
            $this->model->clearCache($this->uinfo['id']);
            return array('status' => 1, 'info' => lang('s'));
        }
    }

    //dot money bro日志
    public function f_log()
    {
        $a = array(
            'bro', 'money', 'dot',
        );
        $flag = $this->in['flag'];
        if (in_array($flag, $a)) {
            $arr = array(
                'bro' => array(
                    'cate' => 'user_bro_cate',
                    'name' => '佣金',
                ),
                'money' => array(
                    'cate' => 'user_money_cate',
                    'name' => '余额',
                ),
                'dot' => array(
                    'cate' => 'user_dot_cate',
                    'name' => '积分',
                ),
            );
            $item = $arr[$flag];

            $finfo = $this->model->getFinance($this->uinfo['id']);
            $in = array('master_id' => $this->uinfo['id'], 'cate' => $this->in['cate']);
            $in['p'] = $this->in['page'] ? $this->in['page'] : 1;
            $res = $this->model->getUserLog($flag, $in);
            $cate = lang($item['cate']);
            foreach ($res['list'] as &$v) {
                $v['cate_str'] = $cate[$v['cate']];
            }
            $this->data['list'] = $res['list'];
            $this->data['can_total'] = $finfo[$flag];
            $this->data['count'] = count($res['list']);
            $this->data['name'] = $item['name'];
        }
        return array('status' => 1, 'data' => $this->data);
    }

    //微信手机
    public function get_tel()
    {
        include_once INCLUDE_PATH . 'weixin/wxBizDataCrypt.php';
        $pc = new \WXBizDataCrypt(C('appid'), $this->in['session_key']);
        $errCode = $pc->decryptData($this->in['encryptedData'], $this->in['iv'], $data2);
        if ($errCode == 0) {
            $data2 = json_decode($data2, true);
            $this->model->where(array('id' => $this->uinfo['id']))->update(array('tel' => $data2['phoneNumber']));
            $this->model->clearCache($this->uinfo['id']);
            $this->data['tel'] = $data2['phoneNumber'];
            return array('status' => 1, 'info' => lang('s'), 'data' => $this->data);
        } else {
            return array('status' => -1, 'info' => '请重新登陆');
        }
    }

    //站内信
    public function msg()
    {
        $UserMsg = new UserMsg;
        $where = array('master_id' => $this->uinfo['id'], 'is_del' => 0);
        if (isset($this->in['is_read'])) {
            $where['is_read'] = $this->in['is_read'];
        }
        if (isset($this->in['type']) && $this->in['type'] != '') {
            $where['type'] = $this->in['type'];
        }
        $this->data['list'] = $UserMsg->getList($where, $this->in['page']);
        $this->data['count'] = count($this->data['list']);
        foreach ($this->data['list'] as $v) {
            if ($v['is_read'] == 0) {
                $UserMsg->where(array('id' => $v['id']))->update(array('is_read' => 1));
            }
        }
        return array('status' => 1, 'data' => $this->data);
    }

    //删除站内信
    public function msg_del()
    {
        $UserMsg = new UserMsg;
        $where = array('master_id' => $this->uinfo['id'], 'id' => $this->in['id']);
        $UserMsg->where($where)->update(array('is_del' => 1));
        return array('status' => 1, 'info' => lang('s'));
    }


    //修改密码
    public function change_pwd()
    {
        if ($this->in['flag'] == 'get') {
            $this->data['need_old_pwd'] = 1;
            if (!$this->uinfo['pwd']) {
                $this->data['need_old_pwd'] = 0;

            }
            return ['status' => 1, 'data' => $this->data];
        }
        if ($this->uinfo['pwd'] && !$this->in['old_pwd']) {
            return ['status' => 0, 'info' => '缺少旧密码'];
        }
        if (!$this->in['new_pwd']) {
            return ['status' => 0, 'info' => '缺少新密码'];
        }
        if (!$this->in['repwd']) {
            return ['status' => 0, 'info' => '缺少确认密码'];
        }
        if ($this->in['new_pwd'] != $this->in['repwd']) {
            return ['status' => 0, 'info' => '新密码跟确认密码不一致'];
        }
        if ($this->uinfo['pwd']) {
            if (xf_md5($this->in['old_pwd']) != $this->uinfo['pwd']) {
                return ['status' => 0, 'info' => '旧密码不正确'];
            }
        }
        $this->model->where(
            [
                ['id', '=', $this->uinfo['id']]
            ]
        )->save(
            [
                'pwd' => xf_md5($this->in['new_pwd'])
            ]
        );
        $this->model->clearCache($this->uinfo['id']);
        return ['status' => 1, 'info' => lang('s')];
    }

}
