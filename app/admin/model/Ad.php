<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\facade\Validate;
use think\Model;

/**
 * @mixin think\Model
 */
class Ad extends Model{

    protected $name='system_ad';

    public function rules() {

        return [
            'rule'=>[
                'title|广告名称'=>'require|length:2,20',
                'msg|提示信息' =>  'max:100',
            ],
            'message'=>[

            ]
        ];
    }



    /**
     * 获取广告一条
     * @param type $id
     * @return type
     */
    public function get_one($id) {
        $r = $this->get_ad($id);
        return $r[0];
    }

    /**
     * 获取广告
     * @param type $id
     * @return type
     */
    public function get_ad($id) {
        return (new SystemAd())->get_ad($id);
    }

}
