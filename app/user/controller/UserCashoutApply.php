<?php

namespace app\user\controller;

use app\common\controller\Admin as BCOM;
use app\common\model\User;
use app\common\model\Weixin;
use app\common\service\Alipay;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
 * 提现申请管理
 * @auto true
 * @auth true
 * @menu true
 */
class UserCashoutApply extends BCOM
{

    public $model;
    public $name;
    public $pk;
    public $db_name;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\user\model\UserCashoutApply();
        $this->db_name = $this->model->dbName();

        $this->name = '提现申请';
        $this->pk = $this->model->get_pk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array(request()->action(), ['show', 'edit', 'delete'])) {
            $this->error('缺少主键');
        }
        $this->create_seo($this->name);
        $all_lan = $this->model->getLan();
        View::assign('all_lan', $all_lan);
    }

    /**
     * 提现申请列表
     * @auto true
     * @auth true
     * @menu false
     */
    public function index()
    {
        $in = input();
        $where = array();

        unset($in['p']);
        $search_field = lang('search_field');
        $dateAttr = $this->model->dateAttr();
        foreach ($in as $key => $value) {
            if ($value !== '' && !in_array($key, $dateAttr)) {
                if (in_array($key, $search_field)) {

                    $where[] = ['a.' . $key, 'like', "%{$value}%"];
                } else {
                    $where[] = ['a.' . $key, '=', $value];
                }
            }
            if ($value !== '' && in_array($key, $dateAttr)) {
                $cs = explode(' - ', $value);
                $where[] = [$key, 'between', ["{$cs[0]} 00:00:00", "{$cs[0]} 23:59:59",]];
            }

        }

        $count = $this->model->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $order = "id desc";
        $data['list'] = Db::name($this->db_name)
            ->alias('a')
            ->field('a.*')
            ->where($where)
            ->limit($Page->firstRow, $Page->listRows)
            ->order($order)
            ->select()->toArray();
        View::assign('is_add', 0);
        View::assign('is_xls', 1);
        View::assign('is_search', 1);
        View::assign('is_del', 0);
        View::assign('is_edit', 0);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 状态更改
     * @auto true
     * @auth true
     * @menu false
     */
    public function change_status()
    {
        $in = input();
        $where = [
            ['id', '=', $in['id']],
            ['status', 'in', [0, 3]],
        ];
        $info = $this->model->removeOption()->where($where)->find();
        if (!$info) {
            $this->error('数据不存在');
        }
        $this->model->removeOption()->where($where)->save(array('status' => $in['status'], 'update_time' => date('Y-m-d H:i:s')));
        if ($in['status'] == 2) {
            $user = new User();
            $user->handleUser($info['cate'], $info['user_id'], $info['money'], 1, ['cate' => 3]);
        }
        $this->success(lang('s'));
    }

    public function set_num() {
        $this->model->where(array('id' => $this->in['id']))->update(['num'=>$this->in['num']]);
        $this->model->clear();
        $this->success(lang('s'));
    }
    /**
     * 直接转账
     * @auto true
     * @auth true
     * @menu false
     */
    public function transfer()
    {
        $in = input();
        $where = [
            ['id', '=', $in['id']],
            ['status', 'in', [0]],
            ['channel_cate', 'in', ['weixin', 'alipay']],
        ];
        $caname = "transfer_{$in['id']}";
        $is = cache($caname);
        if ($is) {
            $this->error('请勿频繁操作');
        }
        cache($caname, 1, 5);
        $info = $this->model->removeOption()->where($where)->find();
        if (!$info) {
            $this->error('数据不存在');
        }
        $user = new User();
        if ($info['channel_cate'] == 'weixin') {
            $res = $this->transfer_weixin($info);
        }
        if ($info['channel_cate'] == 'alipay') {
            $res = $this->transfer_alipay($info);
        }
        if ($res['status'] != 1) {
            $this->error($res['info']);
        } else {
            $this->success($res['info']);
        }


    }

    private function transfer_alipay($info)
    {
        $alipay = new Alipay();
        $result = $alipay->transfer($info);
        $w = ['id' => $info['id']];
        $res = $result['alipay_fund_trans_uni_transfer_response'];
        $this->model->removeOption()->where($w)->save(array(
            'api_json' => json_encode($result, JSON_UNESCAPED_UNICODE),
            'update_time' => date('Y-m-d H:i:s'),
        ));
        if ($res['code'] != 10000 && isset($result['status']) && $result['status'] == 0) {
            //失败
            return ['status' => 0, 'info' => $result['info']];
        } else {

            $this->model->removeOption()->where($w)->save([
                'order_num' => $res['out_biz_no'],
                'partner_trade_no' => $res['pay_fund_order_id'],
                'status' => 1,
            ]);
            return ['status' => 1, 'info' => lang('s')];

        }
    }

    private function transfer_weixin($info)
    {
        $user = new User();
        $wx = new Weixin;
        $uinfo = $user->getUserInfo($info['user_id']);
        if (!$uinfo['openid']) {
            return ['status' => 0, 'info' => '该用户未绑定openid'];
        }
        $w = ['id' => $info['id']];
        //先把他设成待核实先
        $this->model->removeOption()->where($w)->save(array(
            'status' => 3,
            'update_time' => date('Y-m-d H:i:s'),

        ));
        $res = $wx->sendMoney($uinfo['openid'], $info['real_total']);

        $this->model->removeOption()->where($w)->save(array(
            'partner_trade_no' => $res['partner_trade_no'],
            'api_json' => json_encode($res, JSON_UNESCAPED_UNICODE)
        ));
        $status = 1;
        if ($res['result_code'] == 'SUCCESS') {
            //成功了
        } else {
            if ($res['err_code'] == 'SYSTEMERROR') {
                $query_res = $wx->queryTixian($res['partner_trade_no']);
                if ($query_res['status'] == 'SUCCESS') {
                    //成功了

                } else if ($query_res['status'] == 'FAILED') {
                    //明确转账失败了才返回金额回去
                    $status = 2;
                } else {
                    //待明确
                    $status = 3;
                }
            } else {
                //待明确
                $status = 3;
            }
        }
        if ($status == 2) {
            $user->handleUser($info['cate'], $info['user_id'], $info['money'], 1, array(
                    'cate' => 3,
                )
            );
        }
        $this->model->removeOption()->where($w)->save(array(
            'status' => $status,
        ));
        $return_status = ($status == 1) ? 1 : 0;
        $return_info = ($status == 1) ? lang('s') : '打款失败';
        return ['status' => $return_status, 'info' => $return_info];
    }


    /**
     * xls
     * @auto true
     * @auth true
     * @menu false
     */
    public function xls()
    {
        $in = input();
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        unset($in['p']);
        $search_field = lang('search_field');
        $dateAttr = $this->model->dateAttr();
        foreach ($in as $key => $value) {
            if ($value !== '' && !in_array($key, $dateAttr)) {
                if (in_array($key, $search_field)) {

                    $where[] = ['a.' . $key, 'like', "%{$value}%"];
                } else {
                    $where[] = ['a.' . $key, '=', $value];
                }
            }
            if ($value !== '' && in_array($key, $dateAttr)) {
                $cs = explode(' - ', $value);
                $where[] = [$key, 'between', ["{$cs[0]} 00:00:00", "{$cs[0]} 23:59:59",]];
            }
        }
        $data = $this->model->alias('a')
            ->field($fields)
            ->where($where)
            ->limit(500)
            ->select()->toArray();
        $all_lan = $this->model->getLan();
        foreach ($data as &$v) {
            foreach ($all_lan as $ak => $av) {
                if (isset($v[$ak])) {
                    $v[$ak] = $all_lan[$ak][$v[$ak]];
                }
            }
            if (isset($v['user_id'])) {
                $v['user_id'] = getname($v['user_id']);
            }
        }

        (new \app\common\lib\Util())->xls($this->name, array_values($as), $data);
    }


    /**
     * 添加提现申请
     * @auto true
     * @auth true
     * @menu false
     */
    public function add()
    {
        $in = input();
        if ($in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }

    /**
     * 编辑提现申请
     * @auto true
     * @auth true
     * @menu false
     */
    public function edit()
    {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }


    /**
     * 编辑
     */
    private function item()
    {
        $in = input();

        if ($this->request->isPost()) {
            $rule = $this->model->rules();
            $this->validate($this->in, $rule['rule'] ?? [], $rule['message'] ?? []);

            $jsonAttr = $this->model->jsonAttr();
            if ($jsonAttr) {
                foreach ($jsonAttr as $v) {
                    $in[$v] = $in[$v] ? json_encode($in[$v]) : '';
                }
            }
            $dateAttr = $this->model->dateAttr();
            if ($dateAttr) {
                foreach ($dateAttr as $v) {
                    if (!$in[$v]) {
                        unset($in[$v]);
                    }
                }
            }


            if ($in[$this->pk]) {
                $r = $this->model->where($this->pk, $in[$this->pk])->save($in);
                $r = $in[$this->pk];
            } else {
                $r = $this->model->insertGetId($in);
            }
            $this->model->clear($r);
            if ($r === 0 || $r) {
                $this->success(lang('s'), url('index'));
            } else {
                $this->error(lang('e'), url('index'));
            }
        }

        if ($in[$this->pk]) {
            $where[$this->pk] = $in[$this->pk];
            $info = Db::name($this->db_name)->where($where)->find();
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }

        return $this->display('item');
    }

    /**
     * 删除提现申请
     * @auto true
     * @auth true
     * @menu false
     */
    public function delete()
    {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $where[] = [$this->pk, 'in', $in[$this->pk]];
            foreach ($in[$this->pk] as $v) {
                $this->model->clear($v);
            }
        } else {
            $where[] = [$this->pk, '=', $in[$this->pk]];
            $this->model->clear($in[$this->pk]);
        }
        $r = $this->model->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), url('index'));
        } else {
            $this->error(lang('操作失败'), url('index'));
        }
    }


    /**
     * 复制
     * @auto true
     * @auth true
     * @menu false
     */
    public function copy()
    {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $ks = $in[$this->pk];
        } else {
            $ks = array($in[$this->pk]);
        }
        foreach ($ks as $v) {
            $info = Db::name($this->db_name)->where(array($this->pk => $v))->find();
            unset($info[$this->pk]);
            Db::name($this->db_name)->insert($info);
        }
        $this->success(lang('操作成功'), url('index'));
    }

    /**
     * 快捷设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val()
    {
        $in = input();
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
        $this->model->clear();
        $this->model->where($where)->save([$in['field'] => $in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 多选设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function mul_set()
    {
        if (!$this->in[$this->pk]) {
            $this->error('请先选择');
        }
        $where[] = [$this->pk, 'in', $this->in[$this->pk]];
        Db::name($this->db_name)->where($where)->save([$this->in['field'] => $this->in['val']]);
        $this->success(lang('s'));
    }

}
