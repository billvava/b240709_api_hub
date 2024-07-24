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
class GoodsBrand extends Model {

    protected $name = 'mall_goods_brand';
    protected $pk = 'brand_id';
    /**
     * 删除关联表
     * @param type $data
     * @param type $options
     */
    public static function onAfterDelete($model) {
        $data=$model->toArray();
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'brand_id' => '品牌ID',
            'name' => '品牌名称',
            'desc' => '品牌描述',
            'sort' => '排序',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return array(
            array('name', '1,20', '品牌名称长度最大20个字符', 0, 'length'),
            array('desc', '0,255', '品牌描述长度最大255个字符', 0, 'length'),
            array('thumb', '0,255', 'logo长度最大255个字符', 0, 'length'),
        );
    }

    public function getAllOptionHtml($brand_id = null) {
        $se = $this->cache(true)->select()->toArray();
        $html = '';
        foreach ($se as $key => $value) {
            $check = '';
            if ($brand_id == $value['brand_id']) {
                $check = "selected=''";
            }
            $html.="<option value='{$value['brand_id']}' {$check} >{$value['name']}</option>";
        }
        return $html;
    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField() {
        return "brand_id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue() {
        return array(
            'brand_id' => '',
            'sort' => '99',
        );
    }



}
