<?php

namespace App\Http\Controllers\Admin;

use App\Models\SysNode;
use App\Models\SysRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;

class RoleController extends Controller
{
    /**
     * 角色列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author dch
     */
    public function lists()
    {
        $roleList = SysRole::orderBy('sort', 'desc')->paginate(30);
        return view('admin.role.lists', compact('roleList'));
    }

    //修改权限信息
    public function edit($id)
    {
        $nodes = SysNode::all();
        $parentId = 0;
        $role = SysRole::find($id);
        return view('admin.role.edit', compact('role','nodes','parentId'));
    }

    //新增权限组
    public function add()
    {
        $nodes = SysNode::all();
        $parentId = 0;

        return view('admin.role.edit',compact('nodes','parentId'));
    }

    public function save(Request $request)
    {
        $id = $request->get('id');
        $name = $request->get('name');
        $remark = $request->get('remark','');
        $status = $request->get('status','');
        $nodes = (array)$request->get('node');
        $nodes = array_filter(array_flip(array_flip($nodes)));

        if (empty($name)) {
            return response_error('角色名称必须');
        }

        DB::beginTransaction();
        $role = SysRole::firstOrNew(['id'=>$id]);
        $retSave = $role->fill(['name' => $name,'remark' => $remark,'authority' => $nodes,'status' => $status])->save();
        if($retSave){
            DB::commit();

            return response_success();
        }else{
            DB::rollBack();

            Log::warning('保存角色失败');
            return response_error('信息保存失败,请稍后再试');
        }

    }
}
