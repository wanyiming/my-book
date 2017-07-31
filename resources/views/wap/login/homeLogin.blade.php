<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>用户登录 - 问问我建材商城</title>
	<meta name="keywords" content="" />
	<meta name="description" content=""/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="renderer" content="webkit" />
	<meta name="format-detection" content="telephone=no">
	<meta name="baidu-site-verification" content="i4d9dln0Kv" />
	<meta name="360-site-verification" content="6ba5aef7095cc2af474b8a9c41fe7b77" />
	<meta name="google-site-verification" content="L9lL0B1T2uMeAwJa0TuNLPbR4Ob2IBQ5ZXz3xlutgjc" />
	<link rel="shortcut icon" href="https://static.wwwjcsc.com/home/images/jiancai.ico" />
	<link type="text/css" rel="stylesheet" href="{{ STATIC_SITE }}home/css/common.css">
	<link type="text/css" rel="stylesheet" href="{{ STATIC_SITE }}home/css/login.css">
	<script type="text/javascript" src="{{ STATIC_SITE }}home/js/jQuery.js"></script>
	{{--<script>var IS_GET_USERINFO = true;</script>--}}
	<script type="text/javascript" src="{{ STATIC_SITE }}home/js/public.js"></script>
	<script src="/js/custom_validation.js"></script>
	<script src="{{ STATIC_SITE }}member/layer/layer.js"></script>
</head>
<body>
	<div class="top_bar f12 bg_grey">
		<div class="w1200">
			<span class="dark_grey fl">问问我建材商城欢迎您！</span>
			<div class="user fl">
				<a href="{!! to_route('home.login') !!}" title="请登录" class="login dark_grey">请登录</a>
				<a href="{!! to_route('home.register') !!}" title="请注册" class="register dark_grey">免费注册</a>
			</div>
			<ul class="fr">
				@if(get_user_session_info('user_uuid',1))
				<li class="message">
					<span class="color_66 pr10">消息</span>
					<ul class="ul_down">
						<li><a href="{{to_route('member.trade.index')}}" class="hover_txt">交易提醒<b class="color_main fn">{{array_values(get_session_notify_info())[1]}}</b></a></li>
						<li><a href="{{to_route('member.letter.index')}}" class="hover_txt">系统通知<b class="color_main fn">{{array_values(get_session_notify_info())[0]}}</b></a></li>
					</ul>
				</li>
				@endif
				<li><a href="{!! to_route('member.order.lists') !!}" title="我的订单">我的订单</a></li>
				<li class="color_main"><a href="{!! to_route('shopping.cart.lists') !!}" title="我的进货单">我的进货单</a>(<b class="color_main fn cart_qty">{!! (new \App\Models\ShoppingCart())->cartTotal() !!}</b>)</li>
				<li><a href="{!! to_route('member.collection.goods') !!}" title="我的收藏">我的收藏</a></li>
				<li class="color_66">400-138-6066</li>
				<li class="sell"><span class="mr5 bg_main">卖</span><a href="{!! to_route('home.firm_login') !!}">卖家管理入口</a></li>
			</ul>
		</div>
	</div>
	<div class="header bg_ff">
		<div class="w1200">
			<h1>
				<a href="/" class="logo" title="问问我建材商城"><img src="{{ STATIC_SITE }}home/images/logo.png" alt="问问我建材商城" title="问问我建材商城" width="290" height="57"></a>
				<span>登录</span>
			</h1>
		</div>
	</div>
	<div class="login_warp">
		<div class="w1200">
			<div class="show_new pr fl">
				<h2 class="text_hide">问问我建材商城 全新升级 重磅来袭 只为等你 等你体验</h2>
				<div class="text4 pa"></div>
				<div class="text3 pa"></div>
				<div class="text1 pa"></div>
				<div class="text2 pa"></div>
			</div>
			<div class="login_box fr">
				<h2 class="clearfix">
					<span class="active type1">账号密码登录</span>
					<span class="type2">手机动态登录</span>
				</h2>
				<div class="show active" data-type="1">
					<form>
						{!! csrf_field() !!}
						<input type="hidden" name="login_type" value="1"/>
						<label for="">
							<input placeholder="手机号" type="text" name="username" class="username" autocomplete="off">
						</label>
						<p class="notice"><span class="error username_error"></span></p>
						<label for="">
							<input placeholder="请输入密码" type="password" name="password" class="password" autocomplete="off">
						</label>
						<p class="notice"><span class="error password_error"></span></p>
					</form>
				</div>

				<div class="show" data-type="2">
					<form>
						{!! csrf_field() !!}
						<input type="hidden" name="login_type" value="2">
						<label for="">
							<input placeholder="手机号" type="text" name="username" class="username_two username">
						</label>
						<p class="notice"><span class="error username_error_two username_error"></span></p>
						<div class="yan">
							<input placeholder="验证码" type="text" name="code" class="code captcha">
							<img src="{{ captcha_src() }}" alt="" class="captcha_get" style="cursor:pointer;">
						</div>
						<p class="notice"><span class="error code_error"></span></p>
						<div class="yan">
							<input placeholder="动态码" type="text" name="key" class="key">
							<span class="recive ui_no_select get-dynamic-code">获取动态码</span>
						</div>
						<p class="notice"><span class="error key_error"></span></p>
					</form>
				</div>

				<input value="立即登录" class="enter submit_btn" type="submit">
				<p class="other">
					<a href="{!! to_route('home.register') !!}">注册新账户</a>
					<a href="http://sso.jsjzjz.com/passport/forget/1?broker=jsjzjz">忘记密码?</a>
				</p>
			</div>
		</div>
	</div>
	<script>
        $(function () {
            $('.login_box h2 span').table_card($('.login_box .show'));
        });

		$('.username').blur(function () {
            checkValidate(check_mobile, $(this).val(), $(this).parents('form').find('.username_error'));
            return false;
		});

		$('.username_two').blur(function () {
            checkValidate(check_mobile, $(this).val(), $(this).parents('form').find('.username_error_two'));
            return false;
		});

		$('.password').blur(function () {
			var _login_type = $('.login_box').find('.show.active').attr('data-type');
			if(_login_type == 1){
                checkValidate(check_password, $(this).val(), $(this).parents('form').find('.password_error'));
				return false;
			}
		});

		$('.code').blur(function () {
			var _login_type = $('.login_box').find('.show.active').attr('data-type');
			if(_login_type == 2){
                checkValidate(checkCode, $(this).val(), $(this).parents('form').find('.code_error'));
				return false;
			}
		});

		$('.key').blur(function () {
			var _login_type = $('.login_box').find('.show.active').attr('data-type');
			if(_login_type == 2){
				var key = $(this).val();
				var _this = $(this).parents('form').find('.key_error');
				if(!key){
					error_tips(_this,'动态码不能为空！');
					return false;
				}else{
					doreg_tips_hide(_this);
				}
			}
		});

		// 验证码倒计时
		function settime(obj, countdown) {
			if (countdown == 0) {
				obj.removeAttr('disabled').html("重新获取验证码");
				return;
			} else {
				obj.attr('disabled','true').html(countdown + "之后重新发送");
				countdown--;
			}
			setTimeout(function() {settime(obj, countdown) },1000)
		}

		// 发送验证码
		$('.get-dynamic-code').click(function(){
			var _this = $(this);
			var checkSate = checkValidate(check_mobile, _this.parents('form').find('.username_two').val(), _this.parents('form').find('.username_error_two'));
			if(checkSate != 200){
				return false;
			}
			if(_this.attr('disabled')){
				return false;
			}else{
				var _forminfo = $(this).parents('form').serialize();
				var _tourl = "{!! to_route('home.get_code') !!}";
				var _countdown = "{!! config('sendmobile.RESEND_TIME') !!}";
				$.post(_tourl,_forminfo,function (data) {
					if(data['status'] > 0){
						settime($('.get-dynamic-code'),_countdown);
					}else{
					    $("."+data['data']).text(data['msg']);
						return false;
					}
				});
			}
		});

		{{--登录只检测账号密码是否为空--}}
		$('.submit_btn').click(function(){
			var _login_type = $('.login_box').find('.show.active').attr('data-type');
			var _obj = $(this).parent().find('.show.active form');

            var checkState = checkValidate(check_mobile, _obj.find('.username').val(), _obj.find('.username_error'));
            if (checkState != 200) {
                return false;
            }
			if(_login_type == 1){
                checkState = checkValidate(check_password, $(this).parent().find('.show.active form .password').val(), _obj.find('.password_error'));
                if (checkState != 200) {
                    return false;
                }
			}else if(_login_type == 2){
				var key = $(this).parent().find('.show.active form .key').val();
				if(!key){
					var _key_this = _obj.find('.key_error');
					error_tips(_key_this,'动态码不能为空!');
					return false;
				}
			}
			var _forminfo = $(this).parent().find('.show.active form').serialize();
			var _tourl = "{!! to_route('home.post_home_login') !!}";
			$.post(_tourl,_forminfo,function (data) {
				if(data['status'] < 0){
				    if (data['data']) {
						$("."+data['data']).text(data['msg']);
					} else {
                        layer.msg(data['msg']);
					}
					return false;
				}else{
					//登录成功后跳转至
					var _url = "{{$fromurl}}";
					location.href= _url;
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

		document.onkeydown=function(event){
			var e = event || window.event || arguments.callee.caller.arguments[0];
			if(e && e.keyCode==27){ // 按 Esc
				//要做的事情
			}
			if(e && e.keyCode==113){ // 按 F2

			}
			if(e && e.keyCode==13){ // enter 键
				$('.submit_btn').click();
			}
		};
	</script>
	@include('wap.layouts.footer')
</body>
</html>