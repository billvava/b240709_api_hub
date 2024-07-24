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
class UserLogin extends Model
{
    protected $name = 'user_login';



}