<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePasswordRequest;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\SysAdmin;
/**
 * 修改密码
 * Class AccessController
 * @package App\Http\Controllers\Admin
 */
class AccessController extends Controller
{

    public function passwordEdit(){
        return view('admin.access.password_edit');
    }

    /**
     * 保存密码
     * @param SavePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordSave(SavePasswordRequest $request){
        $post = $request->except('_token');
        try {
            $adminUserID = get_admin_session_info('id');

            $userinfo = SysAdmin::where(['id'=>$adminUserID])->select('id','name','password')->first()->toArray();

            if (empty($userinfo)) {
                return response_error('请求异常');
            }
            if (Hash::check($post['old_pass'], $userinfo['password'])) {
                if (detection_password(trim($post['password'])) < SysAdmin::MIN_PASSWORD_LEVEL) {
                    return response_error('密码不能是弱密码');
                }
                if (Hash::check($post['password'], $userinfo['password'])){
                    return response_error('新密码不能与原密码相同');
                }else{
                    $new_password = bcrypt($post['password']);
                    $result = SysAdmin::where(['id'=>$adminUserID])->update(['password'=>$new_password,'public_password'=>trim($post['password'])]);
                    if($result){
                        return response_message('修改成功, 请重新的登录！',1);
                    }else{
                        return response_error('修改失败');
                    }
                }
            }else{
                return response_error('原密码错误');
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return response_error($exception->getMessage());
    }
}
