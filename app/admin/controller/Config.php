<?php
namespace app\admin\controller;
use think\App;
use think\facade\View;
/**
 * 网站设置
 * @auto true
 * @auth true
 * @menu true
 */
class Config extends Common
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\Config();
        $this->title = lang('config_title');

    }

    /**
     * 网站设置
     * @auto false
     * @auth true
     * @menu false
     */
    public function index() {
        $data = $this->model->where(array('is_show' => 1))->order("sort asc,id asc")->select()->toArray();
        View::assign('data', $data);
        $where = array('status' => 1);
        if($this->is_dev){
            $where = array();
        }
        $cate = (new \app\admin\model\SystemConfigCate())->getTree($where);
        View::assign('cate', $cate);
        return $this->display();
    }

    /**
     * 修改配置值
     * @auto true
     * @auth true
     * @menu false
     */
    public function set_val() {
        $this->model->where(array('field' => $this->in['key']))->save(['val'=>$this->in['val']]);
        $this->model->clear();
        $this->success(lang('s'));
    }

    public function set_val_id() {
        $this->model->where(array('field' => $this->in['id']))->save(['val'=>$this->in['val']]);
        $this->model->clear();
        $this->success(lang('s'));
    }


}