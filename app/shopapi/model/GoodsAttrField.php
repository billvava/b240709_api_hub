<?php
namespace app\shopapi\model;

use think\facade\Db;
use think\Model;

/**
 * 代码自动生成所需参数
 * moudleName:模块名称
 * tableName:表名
 * modelName:模型名称
 * */
class GoodsAttrField extends Model {

    protected $name = 'mall_goods_attr_field';

  

    /**
     * 删除后操作
     * @param type $data
     * @param type $options
     */
    public static function onAfterDelete($model) {
        $data=$model->toArray();
        (new GoodsAttrRecord())->where('field_id',$data['field_id'])->delete();
    }


    public function getType() {
        return array(
            1 => '文本框',
            2 => '下拉框',
            3 => '多选器',
            4 => '多行文本',
        );
    }

}
