<?php

namespace App\Http\Controllers\Home;

use App\Libraries\SSO\Exception;
use App\Models\DxCaptcha;
use App\Models\HomeUser;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 前台登录（厂商/经销商/普通用户（普通用户、普通项目经理、高级项目经理））
 * @author  liufangyuan
 * Class LoginController
 * @package App\Http\Controllers\Home
 */
class LoginController extends Controller
{

    /**
     * 获取普通用户登录页面
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeLogin()
    {
        $redirectUrl = request()->query('redirect_url');
        $fromUrl = empty($redirectUrl) ? (url()->previous() ?? url('/')) : $redirectUrl;
        if (preg_match('~(default|home/login)?~',$fromUrl)) {
            $fromUrl = url('/');
        }
        return view('home.login.homeLogin',['fromurl' => $fromUrl]);
    }

    /**
     * 获取厂商经销商登录页面
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firmLogin()
    {
        $redirectUrl = request()->query('redirect_url');
        $fromUrl = empty($redirectUrl) ? (url()->previous() ?? url('/')) : $redirectUrl;
        if (preg_match('~(default|login)?~',$fromUrl) || (($_SERVER['HTTP_REFERER'] ?? '') == url('/').'/')) {
            $fromUrl = to_route('seller.index');
        }
        if (get_user_session_info('user_uuid',3) || get_user_session_info('user_uuid',2)){
            return redirect()->to(to_route('seller.index'));
        }
        return view('home.login.dealerLogin',['formUrl' => $fromUrl]);
    }

    public function dealerLogin()
    {
        return self::firmLogin();
    }

    /**
     * 前台普通用户登录（SSO）
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostHomeLogin(Request $request)
    {
        $post = $request->except('_token');
        $loginType = intval($request->input('login_type'));
        switch($loginType){
            case 2: // 动态码登录
                // 验证码
                $picCaptchaCheckStatus = DxCaptcha::validatorPicCaptcha(htmlspecialchars(trim($post['code'])));
                if($picCaptchaCheckStatus != 10010){
                    return response_error(DxCaptcha::checkCapReturnStatusInfo($picCaptchaCheckStatus), -1, 'code_error');
                }
                // 动态码
                $checkResult = DxCaptcha::checkMobileCapRight(htmlspecialchars(trim($post['username'])),htmlspecialchars(trim($post['key'])));
                if ($checkResult != 10004){
                    return response_error(DxCaptcha::checkCapReturnStatusInfo($checkResult),-1,'key_error');
                }
                $loginType = 5;
                break;
            default: // 账号登录
                $post['password'];
                break;
        }
        try {
            $broker = resolve('SSOBroker');
            $username = htmlspecialchars(trim($request->get('username')));
            $password = htmlspecialchars(trim($request->get('password') ?? $request->get('key')));
            if ($user = $broker->login($username, $password, $loginType)) {
                if ($user = $broker->getUserInfo()) {
                    //验证是否存在本地
                    $condition = [
                        'mobile' => $user['mobile'],
                        'sso_id' => $user['id'],
                        'type'   => 1,
                    ];
                    $checkInfo = DB::table('user_association') -> where($condition)->first();
                    if ($checkInfo){//存在
                        $userInfo = DB::table('home_user')->where('user_uuid',$checkInfo->uuid)->first();//获取用户信息
                        //验证是否已经被禁用
                        if ($userInfo->status != HomeUser::STATUS_YES && $userInfo->status != 3){//被禁用
                            //退出登录
                            resolve('SSOBroker')->logout();
                            return response_error('账号已被禁用',-1, 'username_error');
                        }
                        if ($checkInfo->mobile != $user['mobile']){
                            DB::table('user_association')->where($condition)->update(['mobile'=>$user['mobile']]);
                        }
                    }else {//不存在
                        $insertArray = [//关联表
                            'mobile' => $user['mobile'],
                            'sso_id' => $user['id'],
                            'type'   => 1,
                            'uuid'   => Uuid::uuid()
                        ];
                        DB::table('user_association')-> insert($insertArray);
                        $homeUserInsertArray = [
                            'user_uuid'  => $insertArray['uuid'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'status'     => 1,
                            'grade'      => 1,
                            'nick_name'  => $user['mobile']
                        ];
                        DB::table('home_user') -> insert($homeUserInsertArray);
                    }
                    return response_success('','登录成功');
                }
            }else {
                return response_error('账户或密码错误', -1, 'username_error');
            }
        } catch (Throwable $e) {
            Log::warning($e);
            return response_error($e->getMessage(), -1, 'username_error');
        }
    }

    /**
     * 动态登录发送短信验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DynamicCode(Request $request){
        $post = $request->except('_token');
        $post['phone'] = htmlspecialchars(trim($post['username']));
        if(trim($post['phone']) === ''){
            return response_error('请输入新的手机号码',-1,'username_error');
        }
        if(!validate($post['phone'],'mobile')){
            return response_error('手机号格式错误',-1,'username_error');
        }
        try {
            //检查验证码是否达到发送要求
            $checkSendStatus = DxCaptcha::checkSendAgainStatus($post['phone'],2);
            if($checkSendStatus != 10004){
                return response_error(DxCaptcha::checkCapReturnStatusInfo($checkSendStatus));
            }
            $status = send_messign($post['phone']);
            if($status == 1){
                return response_message('验证码已发送，请查收');
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return response_error('发送失败，请点击重新发送',-1,'key_error');
    }

    /**
     * 前台厂商/经销商登录（后台分配的账号）
     * @author: liufangyuan
     * @date: 2016-12-8
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostSellerLogin(Request $request)
    {
        try {
            //获取登录参数
            $userName = htmlspecialchars(trim($request->get('username')));
            $password = (string)htmlspecialchars(trim($request->get('password')));
            $code = (string)$request->get('code');
            // $loginType = (int)$request->get('login_type');
            if (!validate($userName, 'm')) {
                return response_error('请输入正确的手机账号', -1, 'username_error');
            }
            if (empty($password)) {
                return response_error('请输入登录密码', -1, 'password_error');
            }
            //验证图形验证码
            $picCaptchaCheckStatus = DxCaptcha::validatorPicCaptcha($code);
            if($picCaptchaCheckStatus != 10010){
                //图形验证码验证失败
                return response_error( DxCaptcha::checkCapReturnStatusInfo($picCaptchaCheckStatus), -1, 'code_error');
            }
            //验证当前登录手机号码是否存在
            $checkInfo = DB::table('firm_and_dealer as f')->where('mobile',$userName)->join('user_association as u','u.uuid','=','f.user_uuid')->first();
            if (!$checkInfo){
                return response_error('账号不存在', -1, 'username_error');
            }
            //验证密码是否正确
            if (!dx_hash_bcrypt_check($password,$checkInfo->hash_str,$checkInfo->password)){
                return response_error('密码输入错误', -1, 'password_error');
            }
            //验证当前登录用户的状态是否正常
            if ($checkInfo->status != 1 && $checkInfo->status != 3){
                return response_error('你的账号已被停用，请联系管理员', -1, 'username_error');
            }
            //存入session
            $array = [User::MANUFACTOR=>'manufactor',User::DEALER=>'dealer'];
            $checkInfo->nick_name = $checkInfo->shop_name;
            $checkInfo->head_image = $checkInfo->store_image;
            session([$array[$checkInfo->type]=>object2array($checkInfo)]);
            session()->save();
            if (get_user_session_info('user_uuid',$checkInfo->type)){
                DB::table('firm_and_dealer')->where('user_uuid',get_user_session_info('user_uuid'))->update(['login_time'=>date('Y-m-d H:i:s')]);
                //登录日志
                return response_success('','登录成功');
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return response_error('登录失败');
    }

    /**
     * 经销商退出登录
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function dealerLogout(Request $request)
    {
        if ($request->session()->has('dealer')) {
            $request->session()->forget('dealer');
            return redirect()->to(to_route('home.firm_login'));
        }
    }

    /**
     * 厂商退出登录
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function firmLogout(Request $request)
    {
        if ($request->session()->has('manufactor')) {
            $request->session()->forget('manufactor');
            return redirect()->to(to_route('home.firm_login'));
        }
    }

    /**
     * 普通用户退出登录
     * @author: liufangyuan
     * @date: 2016-12-8
     */
    public function logout(Request $request)
    {
        try{
            resolve('SSOBroker')->logout();
            if ($request->session()->has('member')) {
                $request->session()->forget('member');
                return redirect()->to(to_route('home.login'));
            }
        }catch (\Exception $e){
            Log::warning($e);
        }
        return redirect()->to(to_route('home.login'));
    }

}
