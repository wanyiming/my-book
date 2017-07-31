<?php

namespace App\Providers;

use App\Models\SysNode;
use Illuminate\Support\ServiceProvider;
use Route;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.layouts.base',function($view){
            $routeName = Route::currentRouteName();

            $sysNodes = SysNode::all();

            $currentThird = $sysNodes->where('route',$routeName)->where('parent_id','>',0)->first();

            if(empty($currentThird)){
                $currentThird = $sysNodes->where('route','admin.node')->first();
            }

            list($currentFirstId,$currentSecondId,$currentThirdId) = $this->tree2arr($currentThird['tree']);

            $firstNodes = $sysNodes->where('parent_id',0);

            $view->with([ 'layout' => compact('firstNodes','currentFirstId','sysNodes','currentThirdId','currentSecondId') ]);
        });
    }

    private function tree2arr($tree){
        $treeArr = explode(',',$tree);

        //默认值
        return [$treeArr[0] ?? 1,$treeArr[1] ?? 8,$treeArr[2] ?? 9];
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
