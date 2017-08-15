<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="#" type="image/png">

    <title>{{config('admin_config.SITE_TITLE')}}</title>

    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style-responsive.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{asset('assets/js/html5shiv.js')}}"></script>
    <script src="{{asset('assets/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body class="login-body">
<div class="container">
    @include('admin.public.errors')
    <form class="form-signin" action="{{to_route('admin.public.post.login')}}" method="post" id="login-form">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">{{config('admin_config.SITE_TITLE')}}</h1>
            {{--<img src="{{asset('assets/images/login-logo.png')}}" alt=""/>--}}
        </div>
        <div class="login-wrap">
            {!! csrf_field() !!}
            <input type="text" class="form-control" name="username" placeholder="用户名" autofocus>
            <input type="password" class="form-control" name="password" placeholder="密码">
            <div class="yan">
                <input type="text" placeholder="验证码" name="code" class="code" style="min-width: 165px; height: 39px;">
                <img src="{{captcha_src()}}" class="captcha_get" style="min-width: 120px; cursor:pointer;"/>
            </div>
            <button class="btn btn-lg btn-login btn-block submit_btn" type="button">
                登录
            </button>
            <div class="registration">
                Not a member yet?
                <a class="" href="#myModel">
                    Signup
                </a>
            </div>
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
                </span>
            </label>
        </div>
    </form>
</div>

<!-- Placed js at the end of the document so the pages load faster -->
<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{asset('assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/modernizr.min.js')}}"></script>
<script src="/layer/layer.js"></script>
<script>
    $(".submit_btn").click(function(){
        login();
    });

    $(document).keyup(function(event){
        if(event.keyCode ==13){
            login();
        }
    });
    function  login() {
        $.post($('#login-form').attr('action'),$('#login-form').serialize(),function(data){
            if(data['status'] > 0){
                window.location.href="{{to_route('admin.index')}}";
            }else{
                layer.msg(data['msg'],{icon:2});
            }
        },'json').error(function(){
            layer.msg('请求异常',{icon:2});
        });
    }
</script>
<script>
    function refreshCaptcha(){
        $(".captcha_get").attr("src","{{captcha_src()}}?r="+Math.random());
    }

    $('.captcha_get').click(function () {
        refreshCaptcha();
    })
</script>
</body>
</html>
