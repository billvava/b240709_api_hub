<?php
namespace app\admin\controller;
use app\common\controller\Admin as BCOM;
use app\common\model\User;
use think\App;
use think\facade\View;
use think\facade\Db;


class SuoBaoming extends BCOM{

    public $model;
    public $name;
    public $pk;
    public $db_name;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\SuoBaoming();
        $this->db_name=$this->model->dbName();

        $this->name = '报名申请';
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
    }
    public function chuli(){
       $info =  $this->model->where([
            ['status','=',0],
            ['id','=',$this->in['id']],

        ])->find();
       if(!$info){
           $this->error('状态错误');
       }

        if($info['type'] == 2 && $this->in['status'] == 1 ){

            $level = 1;
            if( strpos($info['ruzhu_type'],'店') !== false || strpos($info['ruzhu_type'],'商') !== false){
                $level = 2;
            }

            $is = Db::name('suo_master')->where([
                ['tel','=',$info['tel']],
                ['level','=',$level],
            ])->find();
            if($is){
                $this->error('手机号已经存在，生成失败');
            }
            $uinfo =  (new User())->getUserInfo($info['user_id']);
            if($uinfo['pid']){
                $haoyou_bro = C('master_bro');
                if($haoyou_bro > 0){
                    $bro =  ($haoyou_bro);
                    $umodel = (new User());
                    $user_id = $info['user_id'];
                    $uinfo = $umodel->getUserInfo($user_id);
                    if($uinfo['pid']){
                        $umodel->handleUser('bro',$uinfo['pid'],$bro,1,['ordernum'=>$info['ordernum'],'cate'=>6]);
                        Db::name('user_ext')->where(['user_id'=>$uinfo['pid']])->update([
                            'share_master_num'=>Db::raw('share_master_num+1')
                        ]);
                    }
                }
            }
            $pwd = mb_substr($info['tel'],-4);
//            Db::name('suo_master')->insert([
//               'tel'=>$info['tel'],
//                'pwd'=>xf_md5($pwd),
//                'realname'=>$info['realname'],
//                'status'=>1,
//                'linkman'=>$info['realname'],
//                'user_id'=>$info['user_id'],
//                'user_pid'=>$uinfo['pid'],
//                'key'=>$info['realname'],
//                'level'=>$level,
//                'type'=>$level,
//
//            ]);
        }
        $this->model->where([
            ['status','=',0],
            ['id','=',$this->in['id']],

        ])->save([
            'status'=>$this->in['status'],
            'up_time'=>date('Y-m-d H:i:s')
        ]);
        $this->success(lang('s'));

    }

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
        View::assign('is_add', 0);
        View::assign('is_xls', 1);   
        View::assign('is_search', 1);   
        View::assign('is_del', 1); 
        View::assign('is_edit', 1);
        View::assign('data', $data);
        return $this->display();
    }

  

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
            if($in['type']==2){
                unset($v['user_id']);
            }
        }
        //type 1=锁匠培训,2=锁匠入驻,3=渠道商入驻,4=代理商入驻
        $type_array = ['锁匠培训','锁匠入驻','渠道商入驻','代理商入驻'];
        $this->name = $type_array[$in['type']-1]??'';
        if($in['type']==2){
            unset($as['user_id']);
        }
        (new \app\common\lib\Util())->xls($this->name, array_values($as), $data);
    }




    public function add(){
        $in = input();
        if ($in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }
    

    public function edit(){
        $in = input();
        if (!$in[$this->pk]) {
            $this->error('错误');
        }
        return $this->item();
    }



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


            if ($in[$this->pk]) {
                $r = $this->model->where($this->pk,$in[$this->pk])->save($in);
                $r = $in[$this->pk];
            } else {
                $r = $this->model->insertGetId($in);
            }
            $this->model->clear($r);
            if ($r === 0 || $r) {
                $this->success(lang('s'));
            } else {
                $this->error(lang('e'));
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
            $this->success(lang('操作成功'));
        } else {
            $this->error(lang('操作失败'));
        }
    }



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
        $this->success(lang('操作成功'));
    }


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

    public function mul_set() {
        if (!$this->in[$this->pk]) {
            $this->error('请先选择');
        }
        $where[] = [$this->pk,'in',$this->in[$this->pk]];
        Db::name($this->db_name)->where($where)->save([$this->in['field']=>$this->in['val']]);
        $this->success(lang('s'));
    }

}
