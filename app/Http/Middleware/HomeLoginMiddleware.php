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
            $check_login = resolve('SSOBroker')->getUserInfo();
            if($check_login){
                session(['user_type'=>1]);
                /* 获取用户收到的新消息数-开始*/
                /*$current_user = session('current_user_notify_info_1');
                $updated_at = $current_user->updated_at  ?? 0;
                $now_time = time();
                $diff_time = bcsub($now_time,strtotime($updated_at));
                if(!$current_user || bccomp($diff_time,10) == 1){
                    $current_user = SmsCount::getNotifyCount();
                }
                session(['current_user_notify_info_1'=>$current_user]);*/
                /* 获取用户收到的新消息数-结束*/
                //验证是否有权限访问
                $route=Route::currentRouteName(); //当前路由别名
                //获取当前路由所属路由组
                foreach(config('member_config.ROUTE') as $key=>$value){
                    if ($value['url'] == $route){
                        if(!in_array(get_user_session_info('grade'),$value['g'])){
                            return redirect()->to(to_route('member.account.info'));
                        }
                    }elseif (!empty($value['_'])){
                        foreach($value['_'] as $k=>$v){
                            if ($value['url'] == $route){
                                if(!in_array(get_user_session_info('grade'),$v['g'])){
                                    return redirect()->to(to_route('member.account.info'));
                                }
                            }elseif (!empty($v['_'])) {
                                foreach($v['_'] as $ke=>$vo){
                                    if ($vo['url'] == $route){
                                        if(!in_array(get_user_session_info('grade'),$vo['g']) ){
                                            return redirect()->to(to_route('member.account.info'));
                                        }
                                    }else if (!empty($vo['_'])){
                                        foreach($vo['_'] as $ky=>$va){
                                            if ($va['url'] == $route){
                                                if(!in_array(get_user_session_info('grade'),$va['g'])){
                                                    return redirect()->to(to_route('member.account.info'));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                session()->save();
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
