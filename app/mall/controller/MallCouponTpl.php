<?php
namespace app\mall\controller;
use app\common\controller\Admin as BCOM;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
* 优惠券模板管理
* @auto true
* @auth true
* @menu true
*/
class MallCouponTpl extends BCOM{

    public $model;
    public $name;
    public $pk;
    public $db_name;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\mall\model\MallCouponTpl();
        $this->db_name=$this->model->dbName();

        $this->name = '优惠券模板';
        $this->pk = $this->model->get_pk();
        if(is_array($this->pk)){
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        if (!$this->pk && in_array(request()->action(), ['show', 'edit', 'delete'])) {
            $this->error('缺少主键');
        }

        View::assign('coupon_type', lang('coupon_type'));
        View::assign('coupon_range', lang('coupon_range'));
        View::assign('coupon_end_type', lang('coupon_end_type'));
        $this->create_seo($this->name);
    }

    /**
    * 优惠券模板列表
    * @auto true
    * @auth true
    * @menu false
    */
    public function index() {
        $in = input();
        $where = array();
        
        unset($in['p']);
        $search_field = lang('search_field');
        foreach ($in as $key => $value) {
            if ($value !== '') {
                if (in_array($key, $search_field)) {

                    $where[] =['a.' . $key,'like',"%{$value}%"];
                } else {
                    $where[] =['a.' . $key,'=',$value] ;
                }
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
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order($order)
                ->select()->toArray();
        View::assign('is_add', 1);           
        View::assign('is_xls', 0);   
        View::assign('is_search', 1);   
        View::assign('is_del', 1); 
        View::assign('is_edit', 1);
        View::assign('data', $data);
        return $this->display();
    }

    /**
    * xls
    * @auto true
    * @auth true
    * @menu false
    */
    public function xls(){
        $in = input();
        $where = array();
        $as = $this->model->attributeLabels();
        $fields = array_keys($as);
        foreach ($in as $key => $value) {
            if ($value !== '' && in_array($key, $fields)) {
                $where[] =['a.' . $key,'like',"%{$value}%"];
            }
        }
       $data =  $this->model->alias('a')
                ->field($fields)
                ->where($where)
                ->limit(500)
                ->select()->toArray();
        $all_lan = $this->model->getLan();
        foreach($data as &$v){
            foreach($all_lan as $ak=>$av){
                if(isset($v[$ak])){
                    $v[$ak] = $all_lan[$ak][$v[$ak]];
                }
            }
            if(isset($v['user_id'])){
                $v['user_id'] = getname($v['user_id']);
            }
        }

        (new \app\common\lib\Util())->xls($this->name,array_values($as),$data);
        
    }


    public function getgoods() {
        if ($this->in['name']) {
            $data =(new \app\mall\model\Goods())->get_data(array(
                'name' => $this->in['name'],
                'status' => 1,
                'page_num' => 20,
                'cache' => true
            ));
            $temp = array();
            foreach ($data['list'] as $key => $value) {
                $temp[] = array(
                    'id' => $value['goods_id'],
                    'text' => $value['name']
                );
            }
            echo json_encode($temp);
        }
    }
    

    /**
     * 编辑优惠券模板
     * @auto true
     * @auth true
     * @menu false
     */
    public function item() {
        $in = input();

        if ($this->request->isPost()) {
            $rule = $this->model->rules();
            $this->validate($this->in,$rule['rule']??[],$rule['message']??[]);

            $jsonAttr = $this->model->jsonAttr();
            if($jsonAttr){
                foreach($jsonAttr as $v){
                    $in[$v] = $in[$v]?json_encode($in[$v]):'';
                }
            }

            $in['end'] = $in['end']?$in['end']:null;

            if ($in['is_base_money'] == 0) {
                $in['base_money'] = 0;
            }
            if ($in['end_type'] == 1 && strtotime($in['end']) <= 0) {
                $this->error('请选择日期范围');
            }
            if ($in['end_type'] == 2 && $in['day'] <= 0) {
                $this->error('请输入天数');
            }
            if ($in['range'] == 2) {
                if (!$in['goods_id']) {
                    $this->error('缺少商品');
                }
                $in['goods_id'] = trim($in['goods_id'], ',');
            }
            if ($in['range'] == 3) {
                if (!$in['category_id']) {
                    $this->error('缺少分类');
                }
                $in['category_id'] = trim($in['category_id'], ',');
            }
            if ($in[$this->pk]) {
                $r = $this->model->where($this->pk,$in[$this->pk])->save($in);
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
    * 删除优惠券模板
    * @auto true
    * @auth true
    * @menu false
    */
    public function delete() {
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('缺少主键参数');
        }
        if (is_array($in[$this->pk])) {
            $where[] = [$this->pk,'in',$in[$this->pk]];
            foreach ($in[$this->pk] as $v) {
                $this->model->clear($v);
            }
        } else {
            $where[] = [$this->pk,'=',$in[$this->pk]];
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
     public function copy() {
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
    public function set_val() {
         $in = input();
        if (!$in['key']) {
            $this->error('缺少主键参数');
        }
        if (!$in['val'] && $in['val'] == '') {
            $this->error('值不能为空');
        }
        $where[$in['key']] = $in['keyid'];
        $this->model->clear();
        $this->model->where($where)->save([$in['field']=>$in['val']]);
        $this->success(lang('s'));
    }
    
    /**
     * 多选设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function mul_set() {
        if (!$this->in[$this->pk]) {
            $this->error('请先选择');
        }
        $where[] = [$this->pk,'in',$this->in[$this->pk]];
        Db::name($this->db_name)->where($where)->save([$this->in['field']=>$this->in['val']]);
        $this->success(lang('s'));
    }

}
