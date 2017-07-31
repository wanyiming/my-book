<?php

namespace App\Http\Controllers\Admin;
use App\Models\SysAdmin;
use Hash;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\UserOperationLog;

class PublicController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function login(){
        return view('admin.public.login');
    }

    public function register(){
        return view('admin.public.register');
    }

    /**
     * 登录
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(UserLoginRequest $request){
        $param = $request->all();

        if (empty($param['code'])){
            return  response_error('请输入图形验证码');
        }
        //验证图形验证码是否正确
        if (!captcha_check($param['code'])){
            return  response_error('请输入图形验证码');
        }
        $where['name'] = $param['username'];
        if (detection_password(trim($param['password'])) <  SysAdmin::MIN_PASSWORD_LEVEL) {
            return response_error('账号或密码错误');
        }
        $userInfo = SysAdmin::where($where)->select('id','lock','name','password','phone')->get();

        if($userInfo->isEmpty()){
            return response_error('账号不存在');
        }
        $user_info_arr = head($userInfo->toArray());
        if(2 == $user_info_arr['lock']){
            return response_error('帐号被锁定,请联系部门主管');
        }
        $admin_session_prefix = config('admin_config.SESSION_ADMIN_PREFIX');
        if (Hash::check($param['password'], $user_info_arr['password'])) {
            SysAdmin::where(['id'=>$user_info_arr['id']])->update(['login_ip'=>$_SERVER['REMOTE_ADDR']]);
            $login_time = date('Y-m-d H:i:s',time());
            $login_ip = get_client_ip();
            $login_city = ip2add($login_ip,false);
            $from_url = url()->previous();
            $now_url = url()->current();
            UserOperationLog::addLog($user_info_arr['id'],1,3,$login_time,'后台员工登录',$login_ip,$login_city,$from_url,$now_url);
            session([$admin_session_prefix=>$user_info_arr]);
            if (!Cookie::get('admin_login')){
                Cookie::queue('admin_login',md5('daxi88886666'),60);
            }
            return response_error('登录成功',1);
        }else{
            return response_error('账号或密码错误');
        }
    }

    // 登出
    public function logout()
    {
        $admin_session_prefix = config('admin_config.SESSION_ADMIN_PREFIX');
        session([$admin_session_prefix=>null]);
        return redirect()->route('admin.public.login');
    }
}
