<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class SystemAreas extends Model
{

    protected $name='system_areas';

    public function dbName()
    {
       return $this->name;
    }

    /**
     * 根据id获取地区名称
     * @param $id
     * @return string
     */
    public function getNameById($id) {
        $region = $this->getCacheAll();
        return $region[$id]['name'];
    }

    /**
     * 获取所有地区(树状结构)
     * @return mixed
     */
    public function getCacheTree() {
        $a = $this->regionCache();
        return $a['tree'];
    }

    /**
     * 获取所有地区
     * @return mixed
     */
    public function getCacheAll() {
        $a = $this->regionCache();
        return $a['all'];
    }

    /**
     * 获取地区缓存
     * @return mixed
     */
    private function regionCache() {
        $cache_name = "sys_region";
        if ((!$data = cache($cache_name))) {
            // 所有地区
            $all = $allData = Db::name($this->dbName())->select()->toArray();

            // 格式化
            $tree = array();
            foreach ($allData as $pKey => $province) {
                if ($province['level'] == 1) {    // 省份
                    $tree[$province['id']] = $province;
                    unset($allData[$pKey]);
                    foreach ($allData as $cKey => $city) {
                        if ($city['level'] == 2 && $city['pid'] == $province['id']) {    // 城市
                            $tree[$province['id']]['city'][$city['id']] = $city;
                            unset($allData[$cKey]);
                            foreach ($allData as $rKey => $region) {
                                if ($region['level'] == 3 && $region['pid'] == $city['id']) {    // 地区
                                    $tree[$province['id']]['city'][$city['id']]['region'][$region['id']] = $region;
                                    unset($allData[$rKey]);
                                }
                            }
                        }
                    }
                }
            }
            $all=array_reduce($all,  function ($v,$w){
                $v[$w['id']]=$w;
                return $v;
            } );
            cache($cache_name, compact('all', 'tree'));
        }
        return $data;
    }

    public function getAll() {
        return Db::name($this->dbName())->cache(true)->select()->toArray();
    }

    public function setVal($id, $key, $val) {
        $pk = $this->getPk();
        if ($pk) {
            return Db::name($this->dbName())->where(array($pk => $id))->save([$key=>$val]);
        }
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'id' => '系统编号',
            'pid' => '上级ID',
            'name' => '名称',
            'sort' => '排序',
        );
    }


    /**
     * 规则
     * @return type
     */
    public function rules()
    {
        return [
            'rule' => [
                'id|系统编号' => 'require|integer',
                'keyid|上级ID' => 'require|integer',
                'name|名称' => 'require|length:0,100',
                'letter|首字母' => 'length:0,1',
            ],
            'message' => []
        ];

    }


}