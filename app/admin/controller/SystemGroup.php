<?php
namespace app\admin\controller;
use think\App;
use think\facade\View;
use think\facade\Db;

/**
* 组合数据
* @auto true
* @auth true
* @menu true
*/
class SystemGroup extends Common{

    public $model;
    public $name;
    public $pk;
    public $db_name;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\SystemGroup();
        $this->db_name=$this->model->dbName();

        $this->name = '组合数据';
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
        $zhcn = include APP_PATH.'gii/lang/zh-cn.php';
        unset($zhcn['field_type']['editor']);
        foreach($zhcn['field_type'] as $k=>$v){
            if( !in_array($k,['input','thumb','ftime','textarea'])){
                unset($zhcn['field_type'][$k]);
            }
        }

        View::assign('type_list', $zhcn['field_type']);

//        p( $zhcn['field_type']);
//        field_type
    }

    /**
    * 组合数据列表
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
        $order = "id desc";
        $data['list'] = Db::name($this->db_name)
                ->alias('a')
                ->field('a.*')
                ->where($where)
                ->limit($Page->firstRow , $Page->listRows)
                ->order($order)
                ->select()->toArray();
        View::assign('is_add', 1);           
        View::assign('is_xls', 0);
        View::assign('is_search', 1);   
        View::assign('is_del', 0);
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
    * 添加组合数据
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
    * 编辑组合数据
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
            if(!$in['list']){
                $in['fields'] = json_encode([],323);
            }else{
                $count = count($in['list']['type']);
                $tmp = [];
                for($i=0;$i<$count;$i++){
                    if($in['list']['type'][$i] && $in['list']['name'][$i] && $in['list']['field'][$i]){
                        $tmp[] = [
                            'type'=>$in['list']['type'][$i],
                            'name'=>$in['list']['name'][$i],
                            'field'=>$in['list']['field'][$i],
                            'param'=>$in['list']['param'][$i],
                            'props'=>$in['list']['props'][$i],
                        ];
                    }

                }

                $in['fields'] = json_encode($tmp,323);
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
            $info = $this->model->getInfo($in[$this->pk],false);
            if (!$info) {
                $this->error('该信息不存在');
            }
//            p($info);
            View::assign('info', $info);
        }

        return $this->display('item');
    }

    /**
    * 删除组合数据
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
