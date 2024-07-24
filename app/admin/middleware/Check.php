<?php
/**
 * 后台验证权限中间件
 * ============================================================================
 * * COPYRIGHT 2016-2019 xhadmin.com , and all rights reserved.
 * * WEBSITE: http://www.xhadmin.com;
 * ----------------------------------------------------------------------------
 * This is not a free software!You have not used for commercial purposes in the
 * premise of the program code to modify and use; and publication does not allow
 * any form of code for any purpose.
 * ============================================================================
 * Author: 寒塘冷月 QQ：274363574
 */

namespace app\admin\middleware;

use think\facade\Config;
use think\facade\Cache;
use app\admin\logic\LoginLogic;

class Check
{
	
    public function handle($request, \Closure $next)
    {

		//写入配置
        return $next($request);
    }
}