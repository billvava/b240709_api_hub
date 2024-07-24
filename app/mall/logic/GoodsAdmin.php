<?php

namespace app\mall\logic;
use think\facade\Db;

Class GoodsAdmin {

    private $model;

    public function __construct() {
        $this->model = Db::name('mall_goods');
    }

    //处理商品规格图
    public function set_spec_thumb($goods_id) {
        if (!$goods_id) {
            return false;
        }
        $in = input();
        //spec_thumb
        Db::name('mall_goods_spec_thumb')->where(array('goods_id' => $goods_id))->delete();
        foreach ($in['spec_tu'] as $v) {
            if ($v) {
                Db::name('mall_goods_spec_thumb')->insert(array(
                    'thumb' => $v,
                    'goods_id' => $goods_id
                ));
            }
        }
    }

    /**
     * 获取商品信息
     * @param $goods_id
     * @return array
     */
    public static function spec_info($goods_id)
    {
        $info['item'] = Db::name('mall_goods_item')
            ->where(['goods_id' => $goods_id])
            ->select()->toArray();
        $info['spec'] = Db::name('mall_goods_spec')
            ->where(['goods_id' => $goods_id])
            ->select()->toArray();
        $spec_value = Db::name('mall_goods_spec_value')
            ->where(['goods_id' => $goods_id])
            ->select()->toArray();
        $data = [];
        foreach ($spec_value as $k => $v) {
            $data[$v['spec_id']][] = $v;
        }
        foreach ($info['spec'] as $k => $v) {
            $info['spec'][$k]['values'] = isset($data[$v['id']]) ? $data[$v['id']] : [];
        }
        return $info;
    }

    /**
     * 修改商品规格
     *
     * @param   [type]  $goods_id       [$goods_id description]
     * @param   [type]  $post           [$post description]
     * @param   [type]  $spec_lists     [$spec_lists description]
     * @param   [type]  $old_spec_type  [$old_spec_type description]
     *
     * @return  [type]                  [return description]
     */
    public function edit_spec_rela ($goods_id, $post, $spec_lists, $old_spec_type) {

        if (!$goods_id || empty($post)) {
            return false;
        }


        //写入规格表
		 $goods_spec_field = lang('goods_spec_field');
        if ($post['spec_type'] == 1) {

            //单规格写入
            if ($old_spec_type == 1) {
                //原来是单规格
                 $data = [
                ];

                foreach ($goods_spec_field as $v) {
                    if ($v['field'] == 'spec_image') {
                        $data['image'] = $post['one_spec_image'];
                    } else {
                        $data[$v['field']] = $post["one_{$v['field']}"];
                    }
                }
                Db::name('mall_goods_item')
                    ->where(['goods_id' => $post['goods_id']])
                    ->save($data);
            } else {
                //原来非单规格
                //删除多规格
                Db::name('mall_goods_spec')
                    ->where(['goods_id' => $post['goods_id']])
                    ->delete();
                Db::name('mall_goods_spec_value')
                    ->where(['goods_id' => $post['goods_id']])
                    ->delete();
                Db::name('mall_goods_item')
                    ->where(['goods_id' => $post['goods_id']])
                    ->delete();
                $goods_spec_id = Db::name('mall_goods_spec')
                    ->insertGetId(['goods_id' => $post['goods_id'], 'name' => '默认']);
                $goods_spec_value_id = Db::name('mall_goods_spec_value')
                    ->insertGetId(['spec_id' => $goods_spec_id, 'goods_id' => $post['goods_id'], 'value' => '默认']);
                $data = [
                    'image' => $post['one_spec_image'],
                    'goods_id' => $post['goods_id'],
                    'spec_value_ids' => $goods_spec_value_id,
                    'spec_value_str' => '默认',
                ];
                foreach ($goods_spec_field as $v) {
                    if ($v['field'] == 'spec_image') {
                        $data['image'] = $post['one_spec_image'];
                    } else {
                        $data[$v['field']] = $post["one_{$v['field']}"];
                    }
                }
                Db::name('mall_goods_item')
                    ->insertGetId($data);
            }

        } else {

            $goods_specs = [];
            foreach ($post['spec_name'] as $k => $v) {
                $temp = ['goods_id' => $post['goods_id'], 'name' => $v, 'spec_id' => $post['spec_id'][$k]];
                $goods_specs[] = $temp;
            }
            $new_spec_name_ids = [];
            foreach ($goods_specs as $k => $v) {
                if ($v['spec_id']) {
                    //更新规格名
                    Db::name('mall_goods_spec')
                        ->where(['goods_id' => $post['goods_id'], 'id' => $v['spec_id']])
                        ->save(['name' => $v['name']]);
                    $new_spec_name_ids[] = $v['spec_id'];
                } else {
                    //添加规格名
                    $new_spec_name_ids[] = Db::name('mall_goods_spec')
                        ->insertGetId(['goods_id' => $post['goods_id'], 'name' => $v['name']]);
                }
            }
            //删除规格名
            $all_spec_ids = Db::name('mall_goods_spec')
                ->where(['goods_id' => $post['goods_id']])
                ->column('id');
            $del_spec_name_ids = array_diff($all_spec_ids, $new_spec_name_ids);
            if (!empty($del_spec_name_ids)) {
                Db::name('mall_goods_spec')
                    ->where([['goods_id','=',$post['goods_id']],['id','in',$del_spec_name_ids]])
                    ->delete();
            }


            $new_spec_value_ids = [];
            $goods_spec_name_key_id = Db::name('mall_goods_spec')
                ->where([['goods_id','=',$post['goods_id']],['name','in',$post['spec_name']]])
                ->column('id', 'name');
            foreach ($post['spec_values'] as $k => $v) {
                $value_id_row = explode(',', $post['spec_value_ids'][$k]);
                $value_row = explode(',', $v);
                foreach ($value_row as $k2 => $v2) {
                    $temp = [
                        'goods_id' => $post['goods_id'],
                        'spec_id' => $goods_spec_name_key_id[$post['spec_name'][$k]],
                        'value' => $v2,
                    ];
                    if ($value_id_row[$k2]) {
                        //更新规格值
                        Db::name('mall_goods_spec_value')
                            ->where(['id' => $value_id_row[$k2]])
                            ->save($temp);
                        $new_spec_value_ids[] = $value_id_row[$k2];
                    } else {
                        //添加规格值
                        $new_spec_value_ids[] = Db::name('mall_goods_spec_value')
                            ->insertGetId($temp);
                    }
                }
            }
            $all_spec_value_ids = Db::name('mall_goods_spec_value')
                ->where(['goods_id' => $post['goods_id']])
                ->column('id');
            $del_spec_value_ids = array_diff($all_spec_value_ids, $new_spec_value_ids);
            if (!empty($del_spec_value_ids)) {
                //删除规格值
                Db::name('mall_goods_spec_value')
                    ->where([['goods_id','=',$post['goods_id']],['id','in',$del_spec_value_ids]])
                    ->delete();
            }

            $new_item_id = [];
            $goods_spec_name_value_id = Db::name('mall_goods_spec_value')
                ->where(['goods_id' => $post['goods_id']])
                ->column('id', 'value');
            foreach ($spec_lists as $k => $v) {
                $spec_lists[$k]['spec_value_ids'] = '';
                $temp = explode(',', $v['spec_value_str']);
                foreach ($temp as $k2 => $v2) {
                    $spec_lists[$k]['spec_value_ids'] .= $goods_spec_name_value_id[$v2] . ',';
                }
                $spec_lists[$k]['spec_value_ids'] = trim($spec_lists[$k]['spec_value_ids'], ',');
                $spec_lists[$k]['image'] = $spec_lists[$k]['spec_image'];
                unset($spec_lists[$k]['spec_image']);
                $spec_lists[$k]['goods_id'] = $post['goods_id'];
                unset($spec_lists[$k]['spec_id']);
                $item_id = $spec_lists[$k]['item_id'];
                unset($spec_lists[$k]['item_id']);
                if ($item_id) {
                    Db::name('mall_goods_item')
                        ->where(['id' => $item_id])
                        ->save($spec_lists[$k]);
                    $new_item_id[] = $item_id;
                } else {
                    $new_item_id[] = Db::name('mall_goods_item')
                        ->insertGetId($spec_lists[$k]);
                }
            }
            $all_item_id = Db::name('mall_goods_item')
                ->where(['goods_id' => $post['goods_id']])
                ->column('id');
            $del_item_ids = array_diff($all_item_id, $new_item_id);
            if (!empty($del_item_ids)) {
                //删除规格值
                Db::name('mall_goods_item')
                    ->where([['goods_id','=',$post['goods_id']],['id','in',$del_item_ids]])
                    ->delete();
            }
        }
    }

    //添加处理商品与规格
    public function set_spec_rela($goods_id, $post, $spec_lists) {
        if (!$goods_id || empty($post)) {
            return false;
        }


        //写入规格表
	$goods_spec_field = lang('goods_spec_field');
        if ($post['spec_type'] == 1) {
            //单规格写入
            $goods_spec_id = Db::name('mall_goods_spec')->insertGetId(['goods_id' => $goods_id, 'name' => '默认']);
            $goods_spec_value_id = Db::name('mall_goods_spec_value')->insertGetId(['spec_id' => $goods_spec_id, 'goods_id' => $goods_id, 'value' => '默认']);

            $data = [
                'goods_id' => $goods_id,
                'spec_value_ids' => $goods_spec_value_id,
                'spec_value_str' => '默认',
            ];
            foreach ($goods_spec_field as $v) {
                if ($v['field'] == 'spec_image') {
                    $data['image'] = $post['one_spec_image'];
                } else {
                    $data[$v['field']] = $post["one_{$v['field']}"];
                }
            }
            Db::name('mall_goods_item')->insertGetId($data);
        } else {
            //多规格写入
            $goods_specs = [];
            foreach ($post['spec_name'] as $k => $v) {
                $temp = ['goods_id' => $goods_id, 'name' => $v];
                $goods_specs[] = $temp;
            }
            Db::name('mall_goods_spec')->insertAll($goods_specs);
            $goods_spec_name_key_id = Db::name('mall_goods_spec')

                ->where([['goods_id','=',$goods_id],['name','in',$post['spec_name']]])
                ->column('id', 'name');

            $data = [];
            foreach ($post['spec_values'] as $k => $v) {
                $row = explode(',', $v);
                foreach ($row as $k2 => $v2) {
                    $temp = [
                        'goods_id' => $goods_id,
                        'spec_id' => $goods_spec_name_key_id[$post['spec_name'][$k]],
                        'value' => $v2,
                    ];
                    $data[] = $temp;
                }
            }

            Db::name('mall_goods_spec_value')->insertAll($data);
            $goods_spec_name_value_id = Db::name('mall_goods_spec_value')
                ->where(['goods_id' => $goods_id])
                ->column('id', 'value');
            foreach ($spec_lists as $k => $v) {
                $spec_lists[$k]['spec_value_ids'] = '';
                $temp = explode(',', $v['spec_value_str']);

                foreach ($temp as $k2 => $v2) {
                    $spec_lists[$k]['spec_value_ids'] .= $goods_spec_name_value_id[$v2] . ',';
                }
                $spec_lists[$k]['spec_value_ids'] = trim($spec_lists[$k]['spec_value_ids'], ',');
                $spec_lists[$k]['image'] = $spec_lists[$k]['spec_image'];
                $spec_lists[$k]['goods_id'] = $goods_id;
                unset($spec_lists[$k]['spec_image']);
                unset($spec_lists[$k]['spec_id']);
                unset($spec_lists[$k]['item_id']);
            }
            Db::name('mall_goods_item')->insertAll($spec_lists);
        }
    }

    private function getSpecChildThumb($tdata, $spec_id, $spec_name) {
        $info = null;
        foreach ($tdata as $k => $v) {
            if ($v['spec_id'] == $spec_id) {
                $info = $v;
                break;
            }
        }
        $thumb = "";
        foreach ($info['spec_child'] as $k => $v) {
            if ($v == $spec_name) {
                $thumb = $info['spec_thumb'][$k] . '';
                break;
            }
        }
        return $thumb;
    }

    private function getSpecItem($goods_id, $k = 0, $gids = '') {
        $in = input();
        return array(
            'gids' => $gids,
            'price' => $in['spec_price'][$k] ? $in['spec_price'][$k] : 0,
            'market_price' => $in['spec_mktPrice'][$k] ? $in['spec_mktPrice'][$k] : 0,
            'num' => $in['spec_num'][$k] ? $in['spec_num'][$k] : 0,
            'goods_id' => $goods_id,
            'weight' => $in['spec_weight'][$k] ? $in['spec_weight'][$k] : 0,
            'kill_price' => $in['spec_kill_price'][$k] ? $in['spec_kill_price'][$k] : 0,
            'sku' => $in['spec_sku'][$k] ? $in['spec_sku'][$k] : '',
            'thumb' => $in['spec_thumb'][$k] ? $in['spec_thumb'][$k] : '',
        );
    }



    //处理商品和属性的关系
    public function set_spec_attr($goods_id) {
        $in = input();
        $mall_goods_attr_record = Db::name('mall_goods_attr_record');
        $mall_goods_attr_record->where(array('goods_id' => $goods_id, 'attr_id' => $in['attr_id']))->delete();
        $temp = array();
        $i = 0;
        $in['attr_field'] = filter_arr($in['attr_field']);
        foreach ($in['attr_field'] as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $temp[$i] = array(
                        'goods_id' => $goods_id,
                        'field_id' => $key,
                        'attr_id' => $in['attr_id'],
                        'val' => $v,
                    );
                    $i++;
                }
            } else {
                $temp[$i] = array(
                    'goods_id' => $goods_id,
                    'field_id' => $key,
                    'attr_id' => $in['attr_id'],
                    'val' => $value,
                );
                $i++;
            }
        }
        if ($i > 0) {
            Db::name('mall_goods_attr_record')->insertAll($temp);
        }
    }

    //设置佣金规则
    public function set_bro_rule($goods_id, $bro_id) {
        if (C('is_sale') == 1) {
            Db::name('mall_goods_ext')->where(array('goods_id' => $goods_id))->save(['bro_id'=>$bro_id]);
        }
    }

}
