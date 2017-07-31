<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SysPosition;
use App\Models\SysRole;

class PositionController extends Controller
{
    //
    public function position_list(){
        $list = SysPosition::orderBy('sort','desc')->paginate(10);
//        $list = SysPosition::get()->toArray();
//        print_r($list);die;
        $where[] = ['id','!=',SysRole::SUPER_ROLE_ID];
        $role_list = SysRole::getSysRoleInfo($where);
        return view('admin.position.position_list',compact('list','role_list'));
    }

    //修改权限信息
    public function positionEdit($id){
        //获取当前权限组的权限节点
        $info = SysPosition::where(['id'=>$id])->first()->toArray();
//        print_r($info);die;
        //获取所有权限组信息
        $where[] = ['id','!=',SysRole::SUPER_ROLE_ID];
        $role_list = SysRole::getSysRoleInfo($where);
        return view('admin.position.positionEdit',compact('info','role_list'));
    }

    //新增权限组
    public function positionAdd(){
        //获取所有权限组信息
        $where[] = ['id','!=',SysRole::SUPER_ROLE_ID];
        $role_list = SysRole::getSysRoleInfo($where);
        return view('admin.position.positionAdd',compact('role_list'));
    }

    public function savePosition(Request $request){
        $post = $request->except('_token');
        if(isset($post['id'])){
            $id = (int) $post['id'];
        }
        $post['sort'] = empty($post['sort'])?0:$post['sort'];
        $name = $post['name'];
        if(empty($name)){
            return ajax_returns('职位名称必须');
        }
        if(isset($id) && $id > 0){
            //修改权限组
            $result = SysPosition::where(['id'=>$id])->update($post);
            if($result){
                return ajax_returns('修改成功',1);
            }
            return ajax_returns('修改失败');
        }else{
            //新增权限组
            $result = SysPosition::create($post);
            if($result){
                return ajax_returns('添加成功',1);
            }
            return ajax_returns('添加失败');
        }
    }
}
