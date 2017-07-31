<?php

namespace App\Http\Middleware;

use App\Libraries\SSO\Exception;
use Closure;

class HasLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 前台普通用户登录
        $check_login = [];
        try {
            $check_login = resolve('SSOBroker')->getUserInfo();
        } catch (Exception $exception) {
            \Log::error($exception->getMessage());
        }
        // 厂家登录
        $checkManufactorLogin = session('manufactor');
        // 经销商登录
        $checkDealerLogin = session('dealer');
        // 后台登录
        $adminLogin = get_admin_session_info('id');
        if(!$check_login && !$checkManufactorLogin && !$checkDealerLogin && !$adminLogin){
            if ($request->ajax() || $request->wantsJson()) {
                return response_error('请先登录');
            }
            return error_show_msg('请先登录');
        }
        return $next($request);
    }
}
