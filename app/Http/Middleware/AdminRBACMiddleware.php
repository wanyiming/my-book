<?php

namespace App\Http\Middleware;

use App\Models\SysAdmin;
use App\Models\SysNode;
use App\Models\SysRole;
use Closure;
class AdminRBACMiddleware
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
        $routeName = $request->route()->getName();
        $node = SysNode::where('route',$routeName)->first();
        $adminId = get_admin_session_info('id');
        if (empty($adminId)) {
            return abort(405,'管理员向你致敬');
        }
        //超管理不受权限控制
        if($adminId == SysAdmin::SUPER_ADMIN_ID){
            return $next($request);
        }
        $roleIds = SysAdmin::where('id',$adminId)->value('role_ids');
        if(empty($roleIds)){
            return abort(405,'没有权限访问');
        }
        $authorities = SysRole::whereIn('id',$roleIds)->pluck('authority');
        $nodeIds = array_unique(array_merge(...$authorities->all()));

        view()->composer('admin.layouts.base',function($view) use ($nodeIds){
            if(empty($view->getData()['layout']['sysNodes']) || empty($view->getData()['layout']['firstNodes'])){
                return;
            }
            $forgetKeys = [];
            foreach($view->getData()['layout']['sysNodes'] as $key => $nodeItem){
                if(!in_array($nodeItem['id'],$nodeIds)){
                    $forgetKeys[] = $key;
                }
            }

            $view->getData()['layout']['firstNodes']->forget($forgetKeys);
            $view->getData()['layout']['sysNodes']->forget($forgetKeys);
        });

        //默认开放
        if(empty($node)){
            return $next($request);
        }

        if(!in_array($node['id'],$nodeIds)){
            return abort(500,'权限不足');
        }

        return $next($request);
    }
}
