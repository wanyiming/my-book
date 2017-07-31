<?php

namespace App\Http\Controllers\Home;

use App\Libraries\SSO\Exception;
use App\Models\DxCaptcha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 前台用户注册
 * @author  liufangyuan
 * Class RegisterController
 * @package App\Http\Controllers\Home
 */
class RegisterController extends Controller
{
    /**
     * 前台用户注册
     * @author: liufangyuan
     * @date: 2016-12-8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home.register.register');
    }

    /**
     * 验证提交的手机号，以及验证码是否正确
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostCaptcha(Request $request){
        $post = $request->except('_token');
        // 检查手机号码和验证码
        $captcha = htmlspecialchars(trim($post['ticket']));
        $mobile  = htmlspecialchars(trim($post['phone']));
        $password  = htmlspecialchars(trim($post['password']));
        try {
            $returnStatus = DxCaptcha::checkMobileCapRight($mobile,$captcha);
            if($returnStatus != 10004){
                return response_error(DxCaptcha::checkCapReturnStatusInfo($returnStatus));
            }
            //检查密码
            $checkPassStatus = DxCaptcha::checkPassword($password);
            if($checkPassStatus != 20004){
                $return_info = DxCaptcha::checkMobileReturnStatusInfo($checkPassStatus);
                return response_error($return_info);
            }
            $userId = sso_user_register($mobile, $password, 1);
            Log::info(sprintf('sso_user_register:user_id=%s',$userId));
            $broker = resolve('SSOBroker');
            if ($user = $broker->login($mobile, $post['password'], 1)) {
                return response_success('', '注册成功');
            }
            return response_error('系统繁忙,请稍后再试');
        } catch (Throwable $e) {
            Log::warning($e);
        }
        return response_error($e->getMessage());
    }

    /**
     * 发送短信验证码，并且验证手机号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doreg(Request $request){
        $post = $request->except('_token');
        try {
            $picCaptchaCheckStatus = DxCaptcha::validatorPicCaptcha($post['captcha']);
            if($picCaptchaCheckStatus != 10010){
                return response_error(DxCaptcha::checkCapReturnStatusInfo($picCaptchaCheckStatus), -1, 'captcha');
            }
            //检查验证码是否达到发送要求
            $checkSendStatus = DxCaptcha::checkSendAgainStatus(htmlspecialchars(trim($post['phone'])), 1);
            if($checkSendStatus != 10004){
                return response_error(DxCaptcha::checkCapReturnStatusInfo($checkSendStatus), -1, 'captcha');
            }
            $status = send_messign(htmlspecialchars(trim($post['phone'])));
            if($status){
                return response_success('', hide_str($post['phone'],3,5));
            }
            return response_error('短信发送失败，请重试', -1, 'captcha');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return  response_error('短信发送失败，请重试', -1, 'captcha');
    }

}
