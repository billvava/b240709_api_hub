<?php
declare (strict_types = 1);

namespace app\user\model;

use app\common\model\O;
use think\facade\Config;
use think\facade\Db;
use think\Model;


/**
 * @mixin think\Model
 */
class UserRank extends Model{
    protected $name='user_rank';

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "user_rank_info_34653463_{$pk}";
        $data = cache($name);
        if (!$data) {
            $mypk = $this->getPk();
            if (!$mypk) {
                return null;
            }
            $data = $this->find($pk);
            cache($name, $data);
        }
        return $data;
    }

    public function clear($pk = '') {
        $name = "user_rank_info_34653463";
        cache($name, null);
        if ($pk) {
            $name = "user_rank_info_34653463_{$pk}";
            cache($name, null);
        }
    }



    public function setVal($id, $key, $val) {
        $pk = $this->getPk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key=> $val]);
        }
    }


}