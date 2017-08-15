<?php

namespace App\Http\Middleware;

use App\Models\SmsCount;
use Closure;
use Illuminate\Support\Facades\Route;

class HomeLoginMiddleware
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
        try {
            $check_login = get_user_session_info('id');
            if($check_login){
                return $next($request);
            }else{
                if ($request->ajax() || $request->wantsJson()) {
                    return response_error('请先登录');
                }
                return redirect()->guest(to_route('home.login'));
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response_error('请先登录');
        }
        return  redirect()->guest(to_route('home.login'));
    }
}
