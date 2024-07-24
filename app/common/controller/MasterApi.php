<?php

namespace app\common\controller;

use app\admin\model\City;
use app\BaseController;
use app\common\Lib\Util;
use app\common\model\SuoMaster;
use think\App;
use think\exception\HttpResponseException;
use think\facade\Db;
use think\facade\Log;
use think\facade\View;

class MasterApi extends BaseController {

    public $in;
    //注意：该变量未安全过滤
    public $request;
    public $uinfo;
    public $model;
    public $user_id;
    public $data;

    public function __construct(App $app) {
        parent::__construct($app);
        $xcx_status = C('xcx_status');
        if ($xcx_status != 1) {
            return json(array('status' => 0, 'info' => '系统维护中！'));
        }
        
        $this->in = request()->param();
        if (request()->isPost()) {
            $this->in = cinputFilter(json_decode(file_get_contents('php://input', 'r'), true));
            //注意：该变量未安全过滤,不能拿来数据库操作
            $this->request = json_decode(file_get_contents('php://input', 'r'), true);
        } else {
            $this->in = request()->param();
            //注意：该变量未安全过滤,不能拿来数据库操作
            $this->request = $_REQUEST;
        }
        if (!$this->in) {
            $this->in = request()->param();
        }
        $this->model = new SuoMaster();
        $this->data['cart_num'] = 0;
        $this->data['site_status'] = C('xcx_shenhe');

        $city_info = (new City())->getInfoOne($this->in['city_id']);
        $this->data['city_id'] = $city_info['id'];
        $this->data['city_name'] = $city_info['name'];

        $this->is_login();
    }

    public function is_login() {
        $user_id = 0;

        if ($this->in['token']) {
            $user_id = $this->model->token_check($this->in['token']);
        }

        if ($user_id <= 0) {
            
        } else {
            $this->user_id = $user_id;
            //缓存的会员数据
            $this->uinfo = $this->model->getUserInfo($user_id);
            if (!$this->uinfo) {
                return json(array('status' => -1, 'info' => '请重新登陆！'));
            }
            if ($this->uinfo['status'] != 1) {
                return json(array('status' => -1, 'info' => '账户被冻结！'));
            }
        }
    }


}
