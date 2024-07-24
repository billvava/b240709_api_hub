<?php
namespace app\mall\model;

use think\facade\Db;
use think\Model;

/**
 * 代码自动生成所需参数
 * moudleName:模块名称
 * tableName:表名
 * modelName:模型名称
 * */
class GoodsAttr extends Model {

    protected $name = 'mall_goods_attr';
    protected $pk = 'attr_id';


    public static function onAfterDelete($model) {
        $data=$model->toArray();
        (new GoodsAttrField())->where('attr_id',$data['attr_id'])->delete();
    }

    public function clear($attr_id) {
        $name = "GoodsAttrGetAllField{$attr_id}";
        cache($name, null);
    }

    public function getAllField($attr_id) {
        $name = "GoodsAttrGetAllField{$attr_id}";

        if (!$data = cache($name)) {
            $data = Db::name('mall_goods_attr_field')->where(array('attr_id' => $attr_id))->order("sort asc")->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'attr_id' => '属性ID',
            'name' => '属性组名称',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return array(
            array('attr_id', 'require', '属性ID不能是空！', 0),
            array('attr_id', 'checkIsNotInt', '属性ID必须是正整数', 0, 'callback'),
            array('name', '0,25', '属性组名称长度最大25个字符', 0, 'length'),
        );
    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField() {
        return "attr_id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue() {
        return array(
            'attr_id' => '',
            'name' => '',
        );
    }

    public function getAll() {
        return $this->cache(true)->select()->toArray();
    }



}
