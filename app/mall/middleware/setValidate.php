<?php
/**
 *
 */

namespace app\mall\middleware;

use think\facade\Config;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Validate;

class setValidate
{
	
    public function handle($request, \Closure $next)
    {

        Validate::maker(function($validate) {
            $validate->extend('is_price','is_price','必须大于或等于0.01！');
        });

        Validate::maker(function($validate) {
            $validate->extend('specNameRequire','specNameRequire','请输入规格名称');
        });
        Validate::maker(function($validate) {
            $validate->extend('specNameRepetition','specNameRepetition','规格名称重复');
        });
        Validate::maker(function($validate) {
            $validate->extend('specValueRequire','specValueRequire','请输入规格值！');
        });
        Validate::maker(function($validate) {
            $validate->extend('specValueRepetition','specValueRepetition','同一规格的规格值不能重复！');
        });
        Validate::maker(function($validate) {
            $validate->extend('specPrice','specPrice','价格必须大于或等于0.01！');
        });


        return $next($request);
    }





}