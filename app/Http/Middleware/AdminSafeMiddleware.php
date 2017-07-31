<?php

namespace App\Http\Middleware;

use Closure;

class AdminSafeMiddleware
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
        if(app()->environment() == 'production'){
            if(0 !== strpos($request->server('HTTP_USER_AGENT'),'Www')){
                abort(404);
            }
        }
        $requestUrl  = session('requestUrl');
        if (empty($requestUrl)) {
            session(['requestUrl'=>[$_SERVER['REQUEST_URI']]]);
        } else {
            if (count($requestUrl) > 99) {
                session(['requestUrl'=>null]);
                session(['requestUrl'=>[$_SERVER['REQUEST_URI']]]);
            } else {
                $requestUrl[] = $_SERVER['REQUEST_URI'];
                session(['requestUrl'=>$requestUrl]);
            }
        }
        return $next($request);
    }
}
