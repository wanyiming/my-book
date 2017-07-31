<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 用户管理
 * Class BuyerController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * 列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request){
        $condition = $request->only('status', 'name', 'alias_name', 'sex');
        $lists = User::where(function($query) use($condition){
            if (!empty($condition['status'])) {
                $query->where('status', intval($condition['status']));
            }
            if (!empty($condition['name'])) {
                $query->where('name', 'like', '%'.htmlentities(trim($condition['name'])).'%');
            }
            if (!empty($condition['alias_name'])) {
                $query->where('alias_name', 'like', '%'.htmlentities(trim($condition['alias_name'])).'%');
            }
            if (!empty($condition['sex'])) {
                $query->where('sex', $condition['sex']);
            }
        })->orderBy('id', 'desc')-> paginate();
        $lists->appends($condition);
        return view('admin.user.lists',['lists'=>$lists,'where'=>$condition, 'status_' => User::STATUS_ALL, 'sex_' => User::SEX_ALL]);
    }


    /**
     *设置数据状态
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function setStatus(Request $request){
        $uuid = $request->get('uuid');
        if (empty($uuid)) return response_error('参数错误');
        $checkInfo = User::where('uuid', $uuid)->first();
        if($checkInfo) {
            if (!in_array($checkInfo->status,[1,2])) {
                return response_error('当前请求数据不允许启用或禁用');
            }else {
                switch ($checkInfo->status){
                    case 1://当前数据是启用状态，设置为禁用\
                        $result = User::where('uuid',$uuid) -> update(['status' => 2]);
                        $action = '禁用';
                        break;
                    case 2://当前数据是禁用状态，设置为启用
                        $result = User::where('uuid',$uuid) -> update(['status' => 1]);
                        $action = '启用';
                        break;
                }
                if ($result){
                    $logArray = [
                        'ip' => get_client_ip(),
                        'worker'=>get_admin_session_info('name'),
                        'worker_id' => get_admin_session_info('id'),
                        'action' => $action,
                        'id'=>$checkInfo->id
                    ];
                    admin_log('user',$logArray);
                    return response_success([],'操作成功');
                }else {
                    return response_error('操作失败');
                }
            }
        }else {
            return response_error('请求的数据信息不存在');
        }
    }
}