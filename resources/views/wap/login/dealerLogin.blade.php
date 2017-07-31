<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>卖家后台管理系统登录-问问我建材商城</title>
	<meta name="keywords" content="" />
	<meta name="description" content=""/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="renderer" content="webkit" />
	<meta name="format-detection" content="telephone=no">
	<meta name="baidu-site-verification" content="i4d9dln0Kv" />
	<meta name="360-site-verification" content="6ba5aef7095cc2af474b8a9c41fe7b77" />
	<meta name="google-site-verification" content="L9lL0B1T2uMeAwJa0TuNLPbR4Ob2IBQ5ZXz3xlutgjc" />
	<link rel="shortcut icon" href="https://static.wwwjcsc.com/home/images/jiancai.ico" />
	<link type="text/css" rel="stylesheet" href="{{STATIC_SITE.('member/css/common.css')}}">
	<link type="text/css" rel="stylesheet" href="{{STATIC_SITE.('member/css/inside.css')}}">
	<link type="text/css" rel="stylesheet" href="{{STATIC_SITE.('home/css/jcmall_index.css')}}">
	<script type="text/javascript" src="{{ STATIC_SITE.('home/js/jQuery.js') }}"></script>
	<script src="/js/custom_validation.js"></script>
</head>
<style>
	.login_box input[type="password"] {
		height: 42px;
		line-height: 42px;
	}
	input[type=password] {
		border: 1px solid #cccccc;
		line-height: 32px;
		height: 32px;
		text-indent: 10px;
		font-size: 12px;
		font-family: sans-serif;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-o-box-sizing: border-box;
		-ms-box-sizing: border-box;
		box-sizing: border-box;
		-webkit-tap-highlight-color: rgba(0,0,0,0);
		-webkit-overflow-scrolling: touch;
		-webkit-appearance: none;
	}
</style>
<body class="ui_seller bg_grey">
	<div class="top_bar f12">
		<div class="w1200">
			<span class="dark_grey fl">问问我建材商城，欢迎您！</span>
			<ul class="fr">
				<li class="message">
					<span>消息</span>
					<ul class="ul_down">
						<li><a href="{{to_route('seller.trade.index')}}">交易提醒<b class="red">{{array_values(get_session_notify_info())[1]}}</b></a></li>
						<li><a href="{{to_route('seller.letter.index')}}">系统通知<b class="red">{{array_values(get_session_notify_info())[0]}}</b></a></li>
					</ul>
				</li>
				<li class="li_index"><a href="/">建材商城首页</a></li>
				{{--<li class="custom customer-service-btn"><a href="javascript:void(0);">联系客服</a></li>--}}
				<li class=""><a href="javascript:void(0);">400-138-6066</a></li>
				<li class="watch">
					<span>关注问问我</span>
					<div class="iphone_box">
						<img src="{{STATIC_SITE}}member/images/wechat_wenwenwo.jpg" title="问问我公众号二维码" alt="问问我公众号二维码" width="88px" height="88px">
						<p>问问我公众号</p>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="bg_white middle_bar">
		<div class="w1200">
			<h1>
				<a href="/" title="问问我建材商城" class="logo	 fl"><img src="{{STATIC_SITE}}member/images/logo1.png" title="问问我建材商城" alt="问问我建材商城" width="119px" height="61px"></a>
				<span class="logo_title">卖家后台管理系统</span>
			</h1>
		</div>
	</div>
	<form id="form" onsubmit="return false;">
		{!! csrf_field() !!}
		<input type="hidden" name="login_type" value="{!! \App\Models\User::DEALER !!}"/>
		<div class="login">
			<div class="w1200">
				<div class="login_box fr">
					<label for=""><input type="text" placeholder="手机号" class="w340 username" name="username"></label>
					<p class="notice"><span class="error username_error"></span></p>
					<label for=""><input type="password" placeholder="请输入密码" class="w340 password" name="password"></label>
					<p class="notice"><span class="error password_error"></span></p>
					<label for="">
						<input type="text" placeholder="验证码" class="w208 fl code" name="code">
						<img src="{{ captcha_src() }}" alt="" class="code_img fr captcha_get" style="=cursor:pointer;">
					</label>
					<p class="notice"><span class="error code_error"></span></p>
					<input type="submit" value="立即登录" class="btn_red submit_button">
					<p class="tr"><a href="{!! to_route('home.dealer_get_back_password') !!}" class="forget_btn f12">忘记密码？</a></p>
				</div>
			</div>
		</div>
	</form>
	<script src="{{STATIC_SITE.('member/layer/layer.js')}}"></script>
	<script>

		{{--失去焦点的时候验证当前输入框的信息--}}
		$('.username').blur(function () {
            checkValidate(check_mobile, $(this).val(), $('.username_error'));
            return false;
		});

		{{--密码只验证长度在6-16位--}}
		$('.password').blur(function () {
            checkValidate(check_password, $(this).val(), $('.password_error'));
            return false;
		});

		//失去焦点的时候验证当前输入框的图形验证码是否为空
		$('.code').blur(function () {
            checkValidate(checkCode, $(this).val(), $('.code_error'));
            return false;
		});

		$('.submit_button').click(function(){
			// 账号
            var checkState = checkValidate(check_mobile, $("input[name='username']").val(), $('.username_error'));
            if (checkState != 200) {
                return false;
            }
            // 密码
            checkState = checkValidate(check_password, $("input[name='password']").val(), $('.password_error'));
            if (checkState != 200) {
                return false;
            }
            // 验证码
            checkState = checkValidate(checkCode, $("input[name='code']").val(), $('.code_error'));
            if (checkState != 200) {
				return false;
			}
			$.post("{!! to_route('home.post_firm_login') !!}",$('#form').serialize(),function(result){
				if (result.status == 1){
					//登录成功后跳转至
					var _url = "{{$formUrl}}";
					location.href= _url;
				}else {
				    if (result['data']) {
						$("."+result.data).text(result['msg']);
					} else {
                        layer.alert(result['msg'], {icon:2});
                        return false;
					}
				}
			},'json');
		});
	</script>
	<script>
		$('.captcha_get').click(function () {
			var _this = $(this);
			var _url = "{{url('captcha-test')}}";
			$.get(_url,function (data) {
				_this.attr('src',data);
			});
		})
	</script>
	@include('wap.layouts.footer')
</body>
</html>