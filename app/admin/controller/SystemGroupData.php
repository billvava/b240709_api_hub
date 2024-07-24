<?php
namespace app\admin\controller;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
* 组合数据详情
* @auto true
* @auth true
* @menu false
*/
class SystemGroupData extends Common{

    public $model;
    public $name;
    public $pk;
    public $db_name;
    public $group_info;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\SystemGroupData();
        $this->db_name=$this->model->dbName();

        $this->name = '组合数据详情';
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
        $this->create_seo($this->name);
         $all_lan = $this->model->getLan();
        View::assign('all_lan', $all_lan);

        if($this->in['group_id']){
            $ginfo = (new \app\admin\model\SystemGroup())->getInfo($this->in['group_id']);
//            p($ginfo);
            $this->group_info = $ginfo;
            View::assign('group_info', $ginfo);
            View::assign('group_id', $ginfo['id']);
            $this->name = $ginfo['group_name'] .'  数据列表';
            View::assign('name', $this->name);
        }else{
            if( in_array(request()->action(),['index','add'])){
                $this->error('错误',url('SystemGroup/index'));
            }
        }
    }

    /**
    * 组合数据详情列表
    * @auto true
    * @auth true
    * @menu false
    */
    public function index() {
        $in = input();
        $where = array();
        
        unset($in['p']);
        $search_field = lang('search_field');
        $dateAttr = $this->model->dateAttr();
        foreach ($in as $key => $value) {
            if ($value !== '' && !in_array($key, $dateAttr)) {
                if (in_array($key, $search_field)) {

                    $where[] =['a.' . $key,'like',"%{$value}%"];
                } else {
                    $where[] =['a.' . $key,'=',$value] ;
                }
            }
            if ( $value !== '' &&  in_array($key, $dateAttr)) {
                $cs = explode(' - ', $value);
                $where[] = [$key, 'between', ["{$cs[0]} 00:00:00","{$cs[1]} 23:59:59",]];
            }
            
        }
      
        $count = $this->model->alias('a')->where($where)->count();
        tool()->classs('PageForAdmin');
        $Page = new \PageForAdmin($count, 20);
        $data['page'] = $Page->show();
        $order = "sort asc,id desc";
        $data['list'] = Db::name($this->db_name)
                ->alias('a')
                ->field('a.*')
                ->where($where)
                ->limit($Page->firstRow , $Page->listRows)
                ->order($order)
                ->select()->toArray();
        foreach($data['list'] as &$v){
            $v = $this->model->handle($v);
        }
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
    public function xls() {
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
    * 添加组合数据详情
    * @auto true
    * @auth true
    * @menu false
    */
    public function add(){
        $in = input();
        if ($in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }
    
    /**
    * 编辑组合数据详情
    * @auto true
    * @auth true
    * @menu false
    */
    public function edit(){
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }


    /**
     * 编辑
     */
    private function item() {
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
            $dateAttr = $this->model->dateAttr();
            if ($dateAttr) {
                foreach ($dateAttr as $v) {
                    if(! $in[$v]){
                        unset( $in[$v]);
                    }
                }
            }
//            p($this->group_info,0);
//            p($in);
            $vs = [];
            foreach($this->group_info['fields'] as $v){
                $vs[$v['field']] =  $this->in['value_'.$v['field']];
            }
            $in['value'] = json_encode($vs,323);
            if ($in[$this->pk]) {
                $r = $this->model->where($this->pk,$in[$this->pk])->save($in);
                $r = $in[$this->pk];
            } else {
                $in['create_time'] = date('Y-m-d H:i:s');
                $r = $this->model->insertGetId($in);
            }
            (new \app\admin\model\SystemGroup())->clearCache($in['group_id']);

            $this->model->clear($r);
            if ($r === 0 || $r) {
                $this->success(lang('s'), url('index',['group_id'=>$in['group_id']]));
            } else {
                $this->error(lang('e'), url('index',['group_id'=>$in['group_id']]));
            }
        }

        if ($in[$this->pk]) {
            $info =$this->model->getInfo($in[$this->pk],false);
            if (!$info) {
                $this->error('该信息不存在');
            }
            View::assign('info', $info);
        }

        return $this->display('item');
    }

    /**
    * 删除组合数据详情
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
        $group_id = $this->model->where($where)->value('group_id');
        (new \app\admin\model\SystemGroup())->clearCache($group_id);
        $r = $this->model->where($where)->delete();
        if ($r) {
            $this->success(lang('操作成功'), url('index',['group_id'=>$group_id]));
        } else {
            $this->error(lang('操作失败'), url('index',['group_id'=>$group_id]));
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
        $group_id = 0;
        foreach ($ks as $v) {
            $info = Db::name($this->db_name)->where(array($this->pk => $v))->find();
            $group_id = $info['group_id'];
            unset($info[$this->pk]);
            Db::name($this->db_name)->insert($info);
        }
         (new \app\admin\model\SystemGroup())->clearCache($group_id);

         $this->success(lang('操作成功'), url('index',['group_id'=>$group_id]));
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
