<?php

namespace App\Http\Controllers\Admin;

use App\Models\SysRole;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\SysAdmin;
use Hash;

/**
 * 后台用户列表
 *
 * Class AdminListController
 * @package App\Http\Controllers\Admin
 * @author dch
 */
class AdminListController extends Controller
{
    //后台员工列表
    public function index()
    {
        //获取用户信息
        $adminList = SysAdmin::where('id', '<>', SysAdmin::SUPER_ADMIN_ID)->select('id', 'name', 'code', 'role_ids',
            'position_id', 'user_pin', 'phone', 'lock', 'login_ip', 'sort')->paginate();

        $roles = array_pluck(SysRole::all(),'name','id');

        foreach ($adminList as $admin) {
            $admin['role_name'] = implode(',',array_intersect_key($roles,array_flip((array)$admin['role_ids'])));
            if ($admin['position_id'] == 0) {
                $admin['position_name'] = '无';
            } else {
                $admin['position_name'] = '';
            }
        }

        return view('admin.admin_list.index', compact('adminList'));
    }

    //修改员工信息
    public function edit($id,Request $request)
    {
        $adminInfo = SysAdmin::where(['id' => $id])->select('id', 'name', 'code', 'role_ids', 'position_id', 'user_name',
            'user_pin', 'phone', 'lock', 'login_ip', 'sort')->first();

        if(empty($adminInfo)){
            $this->buildFailedValidationResponse($request,['message'=>'参数错误']);
        }
        $roles = SysRole::all();

        return view('admin.admin_list.edit', compact('adminInfo','roles'));
    }

    //添加员工信息
    public function add()
    {
        $roles = SysRole::all();
        return view('admin.admin_list.add',compact('roles'));
    }

    //保存信息
    public function save(Request $request)
    {

        $params = $request->only('name', 'code','role_ids', 'user_name', 'user_pin', 'phone', 'sort','password');
        $id = (int)$request->get('id');
        $params['sort'] = empty($params['sort']) ? 0 : $params['sort'];

        $params['role_ids'] = array_unique(array_filter((array)$params['role_ids']));

        $adminInfo = SysAdmin::firstOrNew(['id'=>$id]);

        if (!empty($id)) {
            unset($params['password']);
            //检测登录账号是否唯一
            if ($this->checkNameUnique($params['name'], $id)) {
                return ajax_returns('登录账号已存在');
            }
            //修改员工信息
            $result = $adminInfo->fill($params)->save();
            if ($result !== false) {
                return ajax_returns('修改成功', 1);
            }

            return ajax_returns('修改失败');
        } else {
            //检查是都为弱密码
            if (empty($params['password'])) {
                return ajax_returns('请填写登录密码');
            }
            if (detection_password(trim($params['password'])) < SysAdmin::MIN_PASSWORD_LEVEL) {
                return ajax_returns('输入的密码是弱密码');
            }
            //检测登录账号是否唯一
            if ($this->checkNameUnique($params['name'])) {
                return ajax_returns('登录账号已存在');
            }
            $params['public_password'] = trim($params['password']); //明文的登录密码
            $params['password'] = bcrypt($params['password']);

            $result = $adminInfo->fill($params)->save();
            if ($result) {
                return ajax_returns('添加成功', 1);
            }

            return ajax_returns('添加失败');
        }
    }

    /**
     * 删除账号信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author dch
     */
    public function adminDelete(Request $request)
    {
        $id = (int)$request->input('id', '');
        if ($id == 0) {
            return ajax_returns('参数错误');
        }
        $adminInfo = SysAdmin::where('id', $id)->first();
        if (empty($adminInfo)) {
            return ajax_returns('数据不存在');
        }
        $result = SysAdmin::where('id', $id)->delete();
        if ($result) {
            return ajax_returns('删除成功', 1);
        }

        return ajax_returns('删除失败');
    }

    /**
     * 锁定、解锁
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author dch
     */
    public function saveStatus(Request $request)
    {
        $id = (int)$request->input('id', '');
        if ($id == 0) {
            return ajax_returns('参数错误');
        }

        $adminInfo = SysAdmin::where('id', $id)->first();
        if (empty($adminInfo)) {
            return ajax_returns('数据不存在');
        }

        $lockStatus = 2;
        $returnName = '锁定';
        if ($adminInfo['lock'] == 2) {
            $lockStatus = 1;
            $returnName = '解锁';
        }
        $result = SysAdmin::where('id', $id)->update(['lock' => $lockStatus]);
        if ($result) {
            return ajax_returns($returnName . '成功', 1);
        }

        return ajax_returns($returnName . '失败');
    }

    /**
     * 重置密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordReset(Request $request)
    {
        $id = (int)$request->input('id', '');
        if ($id == 0) {
            return ajax_returns('参数错误');
        }
        //检测数据库中是否存在该id的数据
        $info = SysAdmin::where(['id' => $id])->first();
        if (empty($info)) {
            return ajax_returns('数据不存在');
        }
        $password = trim($request->get('password'));
        if (detection_password($password) < SysAdmin::MIN_PASSWORD_LEVEL) {
            return ajax_returns('输入的密码是弱密码，请输入至少8-16位有效密码为了您的安全性请输入（其中包括大小写、数字、以及特殊字符）');
        }
        //生成重置后的密码
        $result = SysAdmin::where('id',$id)->update([
            'password'        => bcrypt($password),
            'public_password' => $password
        ]);

        if ($result) {
            return ajax_returns('重置成功', 1);
        }

        return ajax_returns('重置失败');
    }


    /*
     * 检测用户登录名是否已存在，添加的时候检测是否已存在该账号，修改的时候检测是否存在id不为该信息id，但账号与修改后的一样的
     */
    public function checkNameUnique($name, $id = '')
    {
        if ($id) {
            $count = SysAdmin::where('name', $name)->where('id', '<>', $id)->count();
        } else {
            $count = SysAdmin::where('name', $name)->count();
        }

        return ($count > 0);
    }

    //修改密码
    public function passwordEdit(){
        return view('admin.access.password_edit');
    }

    //保存密码
    public function passwordSave(Request $request){
        $post = $request->except('_token');
        $userId = get_admin_session_info('id');
        $userInfo = SysAdmin::where(['id'=>$userId])->select('id','name','password')->first()->toArray();
        if (Hash::check($post['old_pass'], $userInfo['password'])) {
            if (detection_password(trim($post['password'])) < SysAdmin::MIN_PASSWORD_LEVEL) {
                return ajax_returns('密码不能是弱密码');
            }
            if (Hash::check($post['password'], $userInfo['password'])){
                return ajax_returns('新密码不能与原密码相同');
            }else{
                $new_password = bcrypt($post['password']);
                $result = SysAdmin::where(['id'=>$userId])->update(['password'=>$new_password,'public_password'=>trim($post['password'])]);
                if($result){
                    return ajax_returns('修改成功',1);
                }else{
                    return ajax_returns('修改失败');
                }
            }

        }else{
            return ajax_returns('原密码错误');
        }
    }
}
