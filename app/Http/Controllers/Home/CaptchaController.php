<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\Captcha\Captcha;
use App\Libraries\Debug;

class CaptchaController extends Controller
{
    public function getCaptcha(Captcha $captcha, $config = 'default')
    {
        return $captcha->create($config);
    }
}
