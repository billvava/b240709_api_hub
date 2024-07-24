<?php

namespace app\suoapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoBaoming;
use app\admin\model\SuoCate;
use app\admin\model\SuoOrder;
use app\admin\model\SuoOrderJia;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use app\com\model\HelpMsg;
use think\App;
use think\facade\Db;

class User
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }


    public function comment(){
        $model = (new SuoOrder());
        $map = [
            'page'=>$this->in['page'],
            'status'=>1,
            'user_id'=>$this->uinfo['id']
        ];
        if($this->in['id']){
            $map['id'] = $this->in['id'];
            $map['find'] = 1;

        }
        $res =  $model->getcommentlist($map);
        $this->data = array_merge($this->data,$res);
        return ['status' => 1,'data'=>$this->data];
    }
    public function baoming_type1(){
        $this->data['peixun_xm'] = ((new SystemGroup())->getCache('peixun_xm'));
        $this->data['peixun_ys'] = ((new SystemGroup())->getCache('peixun_ys'));

        $this->data['peixun_zz'] = ((new SystemGroup())->getCache('peixun_zz'));
        foreach( $this->data['peixun_zz'] as &$v){
            $v['thumb'] = get_img_url($v['thumb']);
        }

        $this->data['peixun_fc'] = ((new SystemGroup())->getCache('peixun_fc'));
        foreach( $this->data['peixun_fc'] as &$v){
            $v['thumb'] = get_img_url($v['thumb']);
        }

        return ['status' => 1, 'data' => $this->data];

    }
    public function baoming_type2(){
        $res =  (new SuoBaoming())->getMapList([
            'user_id'=>$this->uinfo['id'],
            'type'=>2,
            'find'=>1,
            'status'=>0
        ]);
        if($res){
            return ['status' => 0, 'data' =>['url'=> '/pages/baoming/log/log']];
        }
        $this->data['ruzhuleixing'] = ((new SystemGroup())->getCache('ruzhuleixing'));
        array_unshift($this->data['ruzhuleixing'] ,['name'=>'请选择']);
        return ['status' => 1, 'data' => $this->data];

    }
    public function baoming(){
        $Common = new \app\suoapi\controller\Rule(app());
        $type = $this->in['type']?$this->in['type']:1;
        $Common->check("type{$type}");
        $field = ['type','create_time','user_id'];
        $this->in['create_time'] = date('Y-m-d H:i:s');
        $this->in['user_id'] = $this->uinfo['id'];
        $arr = [
            1=>['realname','tel'],
            2=>['realname','tel','weixin','ruzhu_type','city'],
            3=>['realname','tel','weixin','company'],
            4=>['realname','tel','weixin','daili_city'],
        ];
        $ff =  $arr[$type];
        if(!$ff){
            return ['status'=>0,'info'=>'88'];
        }
        $field = array_merge($field,$ff);
        Db::name('suo_baoming')->field($field)->insert($this->in);
        return ['status' => 1,'data'=>['info'=>'报名成功，请等待客服审核','url'=>'/pages/baoming/log/log']];

    }

    public function baoming_list(){
       $res =  (new SuoBaoming())->getMapList([
            'page'=>$this->in['page'],
            'user_id'=>$this->uinfo['id'],
           'type'=>2
        ]);
        $this->data = array_merge( $this->data,$res);
        return ['status' => 1,'data'=>  $this->data];

    }
}
