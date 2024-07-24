<?php
namespace app\admin\controller;

use app\admin\model\AdminUser;
use think\App;
use think\facade\Db;
use think\facade\View;


class SystemAdImg extends Common {

    public $model;
    public $name;
    public $pk;
    public $SystemAd;
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\SystemAdImg();
        $this->SystemAd = new \app\admin\model\SystemAd();
        $this->name = '广告图片';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array($this->request->action(), array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }


    }

    /**
     * 列表
     */
    public function index() {
        $in = $this->request->param();
        $where = array();

        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        foreach ($in as $key => $value) {
            if ($value !== '' && in_array($key, $fields)) {
                $where[] = ['a.' . $key,'=',"{$value}"];
            }
        }
        $count = $this->model->alias('a')->where($where)->count();

        $ad_id = $in['ad_id'];
        $ad_info = $this->SystemAd->getInfo($ad_id);
        View::assign('ad_info', $ad_info);

        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $order = "id desc";
        $data['list'] = $this->model
            ->alias('a')
            ->field('a.*')
            ->where($where)
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order($order)
            ->select()->toArray();

        View::assign('is_add', 1);
        View::assign('is_xls', 0);
        View::assign('is_search', 0);
        View::assign('is_del', 1);
        View::assign('is_edit', 1);
        View::assign('data', $data);
        View::assign('title', '列表');
        return $this->display();
    }

    public function xls() {
        $in = $this->request->param();
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        foreach ($in as $key => $value) {
            if ($value !== '' && in_array($key, $fields)) {
                $where['a.' . $key] = array('like', "%{$value}%");
            }
        }
        $data = $this->model
            ->alias('a')
            ->field($fields)
            ->where($where)
            ->limit(500)
            ->select()->toArray();
        (new \app\common\lib\Util())->xls($this->name, array_values($as), $data);
    }

    /**
     * 显示
     */
    public function show() {
        $in = $this->request->param();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        $where[$this->pk] = $in[$this->pk];
        $info = $this->model->where($where)->find()->toArray();
        View::assign('info', $info);
        View::assign('title', '查看');
        return $this->display();
    }

    /**
     * 编辑
     */
    public function item() {
        $in = $this->request->param();

        if ($this->request->isPost()) {
//            $rule = $this->model->rules();
//
//            $this->validate($this->in,$rule['rule'],$rule['message']?$rule['message']:[]);
            if ($in[$this->pk]) {
                $r = $this->model->update($in);
                $r = $in[$this->pk];
            } else {
                $r = $this->model->save($in);
            }
            $this->model->clear($r);
            if ($r === 0 || $r) {
                $this->SystemAd->clear_ad($in['ad_id']);
                $this->success(lang('s'), url('index', array('ad_id' => $in['ad_id'])));
            } else {
                $this->error(lang('e'), url('index'));
            }
        }

        if ($in[$this->pk]) {
            $where[$this->pk] = $in[$this->pk];
            $info = $this->model->where($where)->find()->toArray();
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }
        $ad_id = $info['ad_id'] ? $info['ad_id'] : $in['ad_id'];
        $ad_info = $this->SystemAd->getInfo($ad_id);
        View::assign('ad_info', $ad_info);

        View::assign('title', '信息');
        return $this->display();
    }

  /**
     * 删除
     */
    public function delete() {
        $in = $this->request->param();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        $where=[];
        if (is_array($in[$this->pk])) {
            $where[] = [$this->pk,'in',$in[$this->pk]];
            foreach ($in[$this->pk] as $v) {
                $this->model->clear($v);
            }
        } else {
            $where[] = [$this->pk,'=',$in[$this->pk]];
            $this->model->clear($in[$this->pk]);
        }
    
        $ad_id = $this->model->where($where)->value('ad_id');
        $r = $this->model->where($where)->delete();
    
        if ($r) {
            $this->SystemAd->clear_ad($ad_id);
            $this->success(lang('操作成功'), url('index', array('ad_id' => $ad_id)));
        } else {
            $this->error(lang('操作失败'), url('index', array('ad_id' => $ad_id)));
        }
    }

    /**
     * 快捷设置
     */
    public function set_val() {
        $in = $this->request->param();
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
        $this->model->clear( $in['keyid']);
        $this->model->where($where)->update([$in['field']=> $in['val']]);
        $this->success(lang('s'));
    }



    public function link() {
        $this->name = '链接选择';
        View::assign('name', $this->name);
        return $this->display();
    }

    public function copy(){
        $in = input();
        if (is_array($in[$this->pk])) {
            $ks = $in[$this->pk];
        } else {
            $ks = array($in[$this->pk]);
        }
        foreach ($ks as $v) {
            $info = $this->model->where(array($this->pk => $v))->find()->toArray();
            unset($info[$this->pk]);
            $this->model->insert($info);
        }
        $this->success(lang('s'));
    }


}
