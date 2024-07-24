<?php

namespace app\mall\controller;

use app\admin\model\Shop;
use app\mall\model\GoodsCategory;
use app\mall\model\GoodsExt;
use app\mall\logic\GoodsAdmin;
use think\App;
use think\facade\Db;
use think\facade\View;

/**
 * 商品管理
 * @auto true
 * @auth true
 * @menu true
 * @icon icon-select_fill
 */
class Goods extends Common {

    public $model;
    public $name;
    public $pk;

    public function __construct(App $app) {
        parent::__construct($app);

        $this->model = new \app\mall\model\Goods();

        $this->name = '商品管理';
        $this->pk = $this->model->getPk();
        if (is_array($this->pk)) {
            $this->pk = $this->pk[0];
        }
        View::assign('name', $this->name);
        View::assign('pk', $this->pk);
        View::assign('model', $this->model);
        View::assign('goods_status', lang('goods_status'));
        if (!$this->pk && in_array($this->request->action(), array('show', 'edit', 'delete'))) {
            $this->error('缺少主键');
        }
        $goods_spec_field = lang('goods_spec_field');
        View::assign('goods_spec_field', $goods_spec_field);

        $cate_list = (new GoodsCategory())->getOption();
         View::assign('cate_list', $cate_list);

        $shop_list = (new Shop())->getOption();
        View::assign('shop_list', $shop_list);
    }

    /**
     * 商品列表
     * @auto true
     * @auth true
     * @menu true
     */
    public function index() {
        $this->in['page_type'] = 'admin';
        $data = $this->model->get_data($this->in);
//        p($data);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 商品回收站
     * @auto true
     * @auth true
     * @menu true
     */
    public function trash() {
        $this->in['page_type'] = 'admin';
        $this->in['status'] = -1;
        $data = $this->model->get_data($this->in);
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 添加商品
     * @auto true
     * @auth true
     * @menu true
     */
    public function item() {

        if (app()->request->isPost()) {
//            p($this->in);

            $in = $this->in;
            $this->check('goodsItem');

            //录入的数据
            $clear = $this->in;
            //单规格验证
            if ($in['spec_type'] == 1) {
                $this->check('GoodsOneSpec');
                $clear['min_price'] = $in['one_price'];
                $clear['min_market_price'] = $in['one_market_price'];

                $clear['min_price2'] = $in['one_price2'];
            }
             // dump($clear);exit;
          
            //多规格验证
            $spec_lists = [];
            if ($in['spec_type'] == 2) {
                $form_arr = [
                    'spec_value_str' => $in['spec_value_str'],
                    'item_id' => $in['item_id'],
                ];
                $goods_spec_field = lang('goods_spec_field');

                foreach ($goods_spec_field as $v) {
                    $form_arr[$v['field']] = $in[$v['field']];
                }

                $spec_lists = form_to_linear($form_arr);

                //规格验证
                if (empty($spec_lists)) {
                    $this->error('至少添加一个规格');
                }
                $this->check('GoodsMoreSpec');
                $clear['min_price'] = min($in['price']);
                $clear['min_price2'] = min($in['price2']);

                $clear['min_market_price'] = min($in['market_price']);
            }

            $GoodsExt = new GoodsExt();
            $GoodsLogic = new GoodsAdmin();
            //准备数据

            $extInfo = array(
                'bro_id' => $in['bro_id'],
                'bro' => $in['bro'],
                'images' => json_encode($this->in['images']),
                'content' => $this->in['content']
            );
            //开始处理
            $goods_id = null;
            if ($goods_id = $this->in['goods_id']) {
                $old_spec_type = $this->model->where(['goods_id' => $in['goods_id']])->value('spec_type');
                $GoodsLogic->edit_spec_rela($in['goods_id'], $in, $spec_lists, $old_spec_type);

                $r = $this->model->where(array('goods_id' => $goods_id))->save($clear);
                $GoodsExt->where(array('goods_id' => $goods_id))->save($extInfo);
                (new \app\mall\logic\Goods())->clearInfo($goods_id);
                $this->model->clear($goods_id);
            } else {
                $goods_id = $this->model->insertGetId($clear);
                $GoodsLogic->set_spec_rela($goods_id, $in, $spec_lists);
                $extInfo['goods_id'] = $goods_id;
                $GoodsExt->insertGetId($extInfo);
            }
            (new GoodsCategory())->updateGoodsNum();

            if ($this->in['is_up_attr'] == 1) {
                $GoodsLogic->set_spec_attr($goods_id);
            }
            $this->success(lang('操作成功'), url('index', array('p' => $this->in['p'])));
        }
        if ($goods_id = $this->in['goods_id']) {
            $goodsInfo = $this->model->getInfo($goods_id, 1);
            // $specInfo = $this->model->getSpec($goods_id);
            // $specThumb = $this->model->getSpecThumb($goods_id);
//            View::assign('specThumb', $specThumb);
            View::assign('goodsInfo', $goodsInfo);
            View::assign('specInfo', (new GoodsAdmin())->spec_info($goods_id));
        }
        View::assign('title', '商品设置');
        return $this->display();
    }

    /**
     * 快速设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function set() {
        $this->check('set');
        $this->model->where(array($this->in['key'] => $this->in['keyid']))->save([$this->in['field'] => $this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 多选设置
     * @auto true
     * @auth true
     * @menu false
     */
    public function dataset() {
        if (!$this->in['goods_ids']) {
            $this->error('请先选择');
        }
        $this->model->where('goods_id', 'in', $this->in['goods_ids'])->save([$this->in['field'] => $this->in['val']]);
        $this->success(lang('s'));
    }

    /**
     * 永久删除
     * @auto true
     * @auth true
     * @menu false
     */
    public function forever_del() {
        $this->model->foreverDel($this->in['goods_ids']);
        $this->success(lang('s'));
    }

    //属性
    public function get_attr_data() {
        $this->in['attr_id'] || die;
        $as = (new \app\mall\model\GoodsAttr())->getAllField($this->in['attr_id']);
        if ($this->in['goods_id']) {
            $table = strtoupper('mall_goods_attr_field');
            $rs = Db::name('mall_goods_attr_record')->where(array(
                                'a.goods_id' => $this->in['goods_id'],
                                'a.attr_id' => $this->in['attr_id'],
                            ))->alias('a')
                            ->join("mall_goods_attr_field b", "a.field_id=b.field_id")
                            ->field("a.*,b.type")
                            ->select()->toArray();

            $newRs = array();
            foreach ($rs as $v) {
                if ($v['type'] == 3) {
                    $newRs[$v['field_id']][] = $v;
                } else {
                    $newRs[$v['field_id']] = $v;
                }
            }

            View::assign('newRs', $newRs);
            View::assign('newRsK', array_keys($newRs));
        }

        View::assign('as', $as);
        return $this->display();
    }

    /**
     * 复制
     * @auto true
     * @auth true
     * @menu false
     */
    public function copy() {

        foreach ($this->in['goods_ids'] as $v) {
            $info = Db::name('mall_goods')->where(array('goods_id' => $v))->find();
            if (!$info) {
                continue;
            }
            unset($info['goods_id']);
            $goods_id = Db::name('mall_goods')->insertGetId($info);
            $ext_info = Db::name('mall_goods_ext')->where(array('goods_id' => $v))->find();
            $ext_info['goods_id'] = $goods_id;
            Db::name('mall_goods_ext')->insert($ext_info);

            //规格  未完成
            $mall_goods_spec = Db::name('mall_goods_spec');
            $mall_goods_spec_value = Db::name('mall_goods_spec_value');
            $mall_goods_item = Db::name('mall_goods_item');
            $goods_spec = $mall_goods_spec->where(array('goods_id' => $v))->select()->toArray();
            $spec_value_arr = array();
            foreach ($goods_spec as $sv) {
                $new_sid = $mall_goods_spec->insert(array('goods_id' => $goods_id, 'name' => $sv['name']));
                $goods_spec_val = $mall_goods_spec_value->where(array(
                            'goods_id' => $sv['goods_id'],
                            'spec_id' => $sv['id']
                        ))->select()->toArray();
                foreach ($goods_spec_val as $vv) {
                    $mall_goods_spec_value->insert(array(
                        'goods_id' => $goods_id,
                        'spec_id' => $new_sid,
                        'value' => $vv['value']
                    ));
                }
            }
            $spec_data = $mall_goods_item->where(array('goods_id' => $v))->select()->toArray();
            foreach ($spec_data as $key => $datum) {
                $spec_value_arr = explode(',', $datum['spec_value_str']);
                $valueStr = '';
                $idsStr = '';
                for ($r = 0; $r < count($spec_value_arr); ++$r) {
                    $v = $spec_value_arr[$r];
                    $mall_goods_spec = $mall_goods_spec_value->where(['goods_id' => $goods_id, 'value' => $v])->find();
                    $valueStr .= $mall_goods_spec['value'] . ',';
                    $idsStr .= $mall_goods_spec['id'] . ',';
                }
                $mall_goods_item->insert(
                        array(
                            'goods_id' => $goods_id,
                            'image' => $datum['image'],
                            'spec_value_ids' => trim($idsStr, ','),
                            'spec_value_str' => trim($valueStr, ','),
                            'market_price' => $datum['market_price'],
                            'price' => $datum['price'],
                            'cost_price' => $datum['cost_price'],
                            'stock' => $datum['stock'],
                            'volume' => $datum['volume'],
                            'weight' => $datum['weight'],
                            'bar_code' => $datum['bar_code'],
                        )
                );
            }

            //属性
            $attr_data = Db::name('mall_goods_attr_record')->where(array('goods_id' => $v))->select()->toArray();
            foreach ($attr_data as $av) {
                unset($av['record_id']);
                $av['goods_id'] = $goods_id;
                Db::name('mall_goods_attr_record')->insertGetId($av);
            }
        }
        $this->success(lang('s'));
    }

    /**
     * 库存预警
     * @auto true
     * @auth true
     * @menu true
     */
    public function stock_warn() {
        $num = $this->in['num'] ? $this->in['num'] : 20;
        $goods_ids = Db::name('mall_goods_item')->field(array("DISTINCT goods_id as goods_id", 'stock'))->where('stock', '<=', $num)->select()->toArray();
        $data = array();
        if ($goods_ids) {
            foreach ($goods_ids as $v) {
                $name = Db::name('mall_goods')->where(array('goods_id' => $v['goods_id']))->value('name');
                $data[] = array(
                    'num' => $v['stock'],
                    'name' => $name,
                    'goods_id' => $v['goods_id'],
                );
            }
        }
        View::assign('name', "警戒库存：{$num}");
        View::assign('data', $data);
        return $this->display();
    }

    /**
     * 加载商品
     */
    public function getgoods() {
        if ($this->in['key']) {
            $data = (new \app\mall\model\Goods())->get_data(array(
                'name' => $this->in['key'],
                'status' => 1,
                'page_num' => 20,
                'cache' => true
            ));
            $temp = array();
            foreach ($data['list'] as $key => $value) {
                $temp[] = array(
                    'id' => $value['goods_id'],
                    'text' => "【{$value['goods_id']}】{$value['name']}"
                );
            }
            echo json_encode($temp);
        }
    }

}
