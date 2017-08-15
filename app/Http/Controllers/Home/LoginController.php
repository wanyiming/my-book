<?php

namespace App\Http\Controllers\Home;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Throwable;

/**
 * @author wym
 * Class LoginController
 * @package App\Http\Controllers\Home
 */
class LoginController extends Controller
{

    public function login()
    {
        return view('wap.login.homeLogin', ['scoureUrl' => \Route::currentRouteName()]);
    }

    public function register () {
        return view('wap.login.register');
    }

    /**
     * 账号登录
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginPost(UserLoginRequest $request)
    {
        try {
            $username = htmlspecialchars(trim($request->get('username')));
            $password = htmlspecialchars(trim($request->get('password')));
            return (new User())->hasLogin($username, $password);
        } catch (Throwable $e) {
            \Log::warning($e);
        }
        return response_error($e->getMessage());
    }

    public function QQ (Request $request) {
        return \Socialite::driver('qq')->redirect();
    }

    public function QQLogin (Request $request) {
        $user = \Socialite::driver('qq')->user();
        dd($user);
    }


    /**
     * 退出登录
     */
    public function firmLogout(Request $request)
    {
        if ($request->session()->has('member')) {
            $request->session()->forget('member');
            return redirect()->to(to_route('home.login'));
        }
    }
}
