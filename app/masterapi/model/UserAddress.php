<?php

declare (strict_types=1);

namespace app\masterapi\model;

use app\common\model\O;
use think\facade\Config;
use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class UserAddress extends Model
{

    protected $name = 'user_address';

    //默认地址
    public function get_def_address($master_id)
    {
        $info = $this->removeOption()->where(array('master_id' => $master_id))->order('is_default desc')->find();
        if ($info) {
            $info = $info->toArray();
            $ho = new O();
            $info['province_name'] = $ho->getAreas($info['province']);
            $info['city_name'] = $ho->getAreas($info['city']);
            $info['country_name'] = $ho->getAreas($info['country']);
        }
        return $info;
    }

    public function get_info($master_id, $id)
    {
        $info = $this->removeOption()->where(array('master_id' => $master_id, 'id' => $id))->find();
        if ($info) {
            $info = $info->toArray();
            $ho = new O();
            $info['province_name'] = $ho->getAreas($info['province']);
            $info['city_name'] = $ho->getAreas($info['city']);
            $info['country_name'] = $ho->getAreas($info['country']);
        }
        return $info;
    }

    //地址列表
    public function user_list($master_id, $page = 1)
    {
        $ho = new O();
        $model = Db::name('user_address');
        $where['master_id'] = $master_id;
        $order = 'is_default desc,id desc';
        $page = $page + 0;
        $data = $model->where($where)->page($page, 10)->order($order)->select()->toArray();
        $data = $data ?: [];
        foreach ($data as &$value) {
            $value['province_name'] = $ho->getAreas($value['province']);
            $value['city_name'] = $ho->getAreas($value['city']);
            $value['country_name'] = $ho->getAreas($value['country']);
        }
        return $data;
    }

    /**
     * 修改默认地址
     * @param type $data
     * @param type $options
     */
    public function set_default($is_default, $id, $master_id)
    {

        if ($is_default == 1) {

            $where = [
                ['master_id','=',$master_id],
                ['id','not in',[$id]],
            ];
            $this->where($where)->save(['is_default' => 0]);
        }
    }

    //获取列表
    public function getList($in)
    {
        $cache_name = "user_address_" . md5(serialize($in));
        if ($in['cache'] == true) {
            $data = cache($cache_name);
            if ($data) {
                return $data;
            }
        }
        $where = array();
        $like_arr = array('address', 'tel', 'name');
        $status_arr = array('master_id', 'is_default', 'province', 'city', 'country');
        foreach ($in as $k => $v) {
            if (in_array($k, $like_arr) && $v) {
                $where[] = array('a.' . $k, 'like', "%{$v}%");
            }
            if (in_array($k, $status_arr) && $v !== '') {
                $where[] = ['a.' . $k, '=', $v];
            }
        }

        if ($in['order']) {
            $order = $in['order'];
        } else {
            $order = "a.is_default desc";
        }

        //是否需要分页
        $page_num = $in['page_num'] ? $in['page_num'] : 10;
        if ($in['page_type']) {
            $count = Db::name('user_address')->alias('a')->where($where)->count();
            if ($in['page_type'] == 'admin') {
                tool()->classs('PageForAdmin');
                $Page = new \PageForAdmin($count, $page_num);
            } else {
                tool()->classs('Page');
                $Page = new \Page($count, $page_num);
            }
            $data['page'] = $Page->show();
            $data['total'] = $count;
            $start = $Page->firstRow;
        } else {
            $page = $in['page'] ? $in['page'] : 1;
            $start = ($page - 1) * $page_num;
        }
        $data['list'] = Db::name('user_address')
            ->alias('a')
            ->field('a.*')
            ->where($where)
            ->limit($start, $page_num)
            ->order($order)
            ->select()->toArray();
        $data['num'] = count($data['list']);
        if ($in['cache'] == true) {
            cache($cache_name, $data);
        }
        return $data;
    }

    public function getAll()
    {
        return $this->cache(true)->select()->toArray();
    }

    public function setVal($id, $key, $val)
    {
        $pk = $this->getPk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key => $val]);
        }
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels()
    {
        return array(
            'id' => '主键ID',
            'master_id' => '用户ID',
            'name' => '收货人姓名',
            'tel' => '手机',
            'province' => '省份',
            'city' => '城市',
            'country' => '区域',
            'address' => '详细地址',
            'is_default' => '1=默认，0=非默认',
            'postcode' => '邮政编码',
            'telephone' => '固定电话',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules()
    {
        return array(
            array('id', 'require', '主键ID不能是空！', 0),
            array('id', 'checkIsNotInt', '主键ID必须是正整数', 0, 'callback'),
            array('master_id', 'require', '用户ID不能是空！', 0),
            array('master_id', 'checkIsInt', '用户ID必须是整数', 0, 'callback'),
            array('name', 'require', '收货人姓名不能是空！', 0),
            array('name', '0,25', '收货人姓名长度最大25个字符', 0, 'length'),
            array('tel', '0,11', '手机长度最大11个字符', 0, 'length'),
            array('province', 'require', '省份不能是空！', 0),
            array('province', 'checkIsInt', '省份必须是整数', 0, 'callback'),
            array('city', 'require', '城市不能是空！', 0),
            array('city', 'checkIsInt', '城市必须是整数', 0, 'callback'),
            array('country', 'require', '区域不能是空！', 0),
            array('country', 'checkIsInt', '区域必须是整数', 0, 'callback'),
            array('address', 'require', '详细地址不能是空！', 0),
            array('address', '0,255', '详细地址长度最大255个字符', 0, 'length'),
            array('is_default', 'require', '1=默认，0=非默认不能是空！', 0),
            array('is_default', 'checkIsInt', '1=默认，0=非默认必须是整数', 0, 'callback'),
            array('postcode', 'require', '邮政编码不能是空！', 0),
            array('postcode', '0,6', '邮政编码长度最大6个字符', 0, 'length'),
            array('telephone', 'checkIsInt', '固定电话必须是整数', 0, 'callback'),
        );
    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField()
    {
        return "id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue()
    {
        return array(
            'id' => '',
            'master_id' => '',
            'tel' => '',
            'province' => '',
            'city' => '',
            'country' => '',
            'address' => '',
            'is_default' => '0',
            'telephone' => '',
        );
    }


    /*
     * 根据编码获取我们的数据库ID
     */
    public function getCityByCode($code, $lev = 1)
    {
        return Db::name('system_areas')->where([
            ['code', '=', $code],
            ['level', '=', $lev],

        ])
            ->cache(true)
            ->value('id');
    }
    
      /*
    * 根据名称获取我们的数据库ID
    */
    public function getCityByName($code, $lev = 1)
    {
        return Db::name('system_areas')->where([
            ['name', 'like', "%{$code}%"],
            ['level', '=', $lev],

        ])
            ->cache(true)
            ->value('id');
    }
}
