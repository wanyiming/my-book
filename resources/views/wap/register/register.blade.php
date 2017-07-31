<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>用户注册 - 问问我建材商城</title>
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
	<script src="{{ STATIC_SITE }}member/layer/layer.js"></script>
	<style type="text/css">
		.send_msg_error {
			color: red;font-size: 12px;
		}
	</style>
</head>
<body>
<div class="top_bar f12 bg_f5">
	<div class="w1200">
		<span class="color_66 fl pr20">问问我建材商城欢迎您！</span>
		@if(get_user_session_info('user_uuid',1))
			<div class="user fl color_main">
				<a href="{!! to_route('member.account.info') !!}" class="color_main">您好，{!! substr_replace(get_user_session_info('mobile',1),'*****',3,5) !!}</a><span>（<a href="{!! to_route('home.logout') !!}" class="color_main">退出</a>）</span>
			</div>
		@else
			<div class="user fl">
				<span class="red">问问我用户</span>
				<a href="{!! to_route('home.login') !!}" class="login color_66">请登录</a>
				<a href="{!! to_route('home.register') !!}" class="register color_66">免费注册</a>
			</div>
		@endif
		<ul class="fr">
			<li class="message">
				<span class="color_66 pr10">消息</span>
				<ul class="ul_down">
					<li><a href="{{to_route('member.trade.index')}}" class="hover_txt">交易提醒<b class="color_main fn">{{array_values(get_session_notify_info())[1]}}</b></a></li>
					<li><a href="{{to_route('member.letter.index')}}" class="hover_txt">系统通知<b class="color_main fn">{{array_values(get_session_notify_info())[0]}}</b></a></li>
				</ul>
			</li>
			<li><a href="{!! to_route('member.order.lists') !!}">我的订单</a></li>
			<li class="color_main"><a href="{!! to_route('shopping.cart.lists') !!}">我的进货单</a>(<b class="color_main fn cart_qty">{!! (new \App\Models\ShoppingCart())->cartTotal() !!}</b>)</li>
			<li><a href="{!! to_route('member.collection.goods') !!}">我的收藏</a></li>
			<li class="color_66">400-138-6066</li>
			<li class="sell"><span class="mr5 bg_main">卖</span><a href="{!! to_route('home.firm_login') !!}">卖家管理入口</a></li>
		</ul>
	</div>
</div>
<div class="header bg_ff">
	<div class="w1200">
		<h1>
			<a href="/" class="logo" title="问问我建材商城"><img src="{{ STATIC_SITE }}home/images/logo.png" alt="问问我建材商城" title="问问我建材商城" width="290" height="57"></a>
			<span>注册</span>
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
			<div class="login_box fr register">
				<h2>
					手机注册
				</h2>
				<div class="show active">
					<form>
						{!! csrf_field() !!}
					<label for="">
						<input type="text" placeholder="手机号" name="phone" class="mobile" />
					</label>
					<p class="notice"><span class="error mobile_error"></span></p>
					<label for="">
						<input type="password" placeholder="密码" name="password" class="password" />
					</label>
					<p class="notice"><span class="error password_error"></span></p>
					<div class="yan">
						<input type="text" placeholder="验证码" name="captcha" />
						<img src="{{captcha_src()}}" alt="" class="captcha_get">
					</div>
						<p class="notice"><span class="error captcha_error"></span></p>
					</form>
				</div>
				<p class="read f12 color_66">
					<span class="ui_checkbox"><span><i></i><input type="checkbox" value="1" name="agreement" id="agreement">我已阅读并同意</span></span><a href="http://www.wwwjcsc.com/protocol_information/information/10/" target="_blank">《问问我平台服务协议》</a>
					<p class="notice"><span class="error agreement_error"></span></p>
				</p>

				<input value="注册" class="enter submit_btn" type="submit">
				<p class="other tr">
					已有账号？
					<a href="{!! to_route('home.login') !!}">立即登录</a>
				</p>
			</div>
		</div>
	</div>
<div class="dialog_tel ui_dialog" style="display: none;">
	<div class="text send_parent">
		<b>验证手机号码，完成注册！</b>
		<p class="ui_gray2 mt10">短信验证码将发至<span class="orange send_mobile"></span></p>
		<span id="send_return_status">
			<p class="success f12">发送成功</p>
		</span>
		<form>
			{!! csrf_field() !!}
			<input type="text" placeholder="请输入验证码" name="ticket" class="ticket" >
			<input type="button" class="btn ml5 check_info" value="验证">
		</form>
		<p class="send_tips">
		</p>
	</div>
</div>
<script src="/js/custom_validation.js"></script>
<script>
    $('.ui_checkbox').ui_checkbox();
	$('.login .read').ui_checkbox();

	// 倒计时
	function settime(obj, countdown) {
		var _app_str = '';
		if (countdown == 0) {
			_app_str += '<span class="more ui_no_select f12 send_again">重新发送</span>';
			$('body #send_return_status').empty();
			$('body .send_tips').removeClass('f12 ui_gray1').empty().append(_app_str);
			return;
		} else {
			_app_str += '<span class="red">'+countdown+'</span>秒后重新发送';
			$('body .send_tips').addClass('f12 ui_gray1').empty().append(_app_str);
			countdown--;
		}
		setTimeout(function() {settime(obj, countdown) },1000)
	}
	
	// 发送之前验证
	function check_all(_obj) {
        var checkState = checkValidate(check_mobile, _obj.find('.mobile').val(), _obj.find('.mobile_error'));
        if (checkState != 200) {
			return false;
		}
        checkState = checkValidate(check_password, _obj.find('.password').val(), _obj.find('.password_error'));
        if (checkState != 200) {
            return false;
        }
        checkState = checkValidate(checkCode, _obj.find("input[name='captcha']").val(), _obj.find('.captcha_error'));
        if (checkState != 200) {
            return false;
        }
		var _agreement = $('input[name="agreement"]:checked').val();
		if(!_agreement){
			error_tips($('.agreement_error'),'请先同意注册协议！');
			return false;
		}else{
			doreg_tips_hide($('.agreement_error'));
		}
		$.post("{{to_route('passport.doreg')}}", _obj.serialize(), function (data) {
			if (data['status'] < 0) {
				error_tips(_obj.find('.'+data['data']+'_error'),data['msg']);
				if (data['data'] == 'captcha') {
					$.get("{{url('captcha-test')}}",function (pic_url) {
						$('.captcha_get').attr('src',pic_url);
					});
				}
				return false;
			} else {
				{{--先替换电话号码和再次发送倒计时，再弹窗--}}
				$('.send_mobile').text(data['msg']);
				var _this = $('body .send_tips');
				var _countdown = {{config('sendmobile.RESEND_TIME')}};
				settime(_this,_countdown);
				layer.open({
					content: $('.dialog_tel').html(),
					area: '420px',
					title: '手机验证',
					skin: 'dialog_tel ui_dialog ui_dialog_icon1',
					btn:''
				},function (index) {
					layer.close(index);
				});
			}
		},'json');
	}

	$('.submit_btn').click(function () {
		var _this = $('.login_box').find('.show.active form');
		check_all(_this);
	});


	{{--再次发送验证码--}}
	$('body').on('click','.send_again',function () {
		var _obj = $('.login_box').find('.show.active form');
		var _error_this = $(this).parents('.send_parent').find('#send_return_status').addClass('send_msg_error');
        var checkState = checkValidate(check_mobile, _obj.find('.mobile').val(), _error_this);
        if (checkState != 200) {
            return false;
        }
        checkState = checkValidate(check_password, _obj.find('.password').val(), _error_this);
        if (checkState != 200) {
            return false;
        }
        checkState = checkValidate(checkCode, _obj.find("input[name='captcha']").val(), _error_this);
        if (checkState != 200) {
            return false;
        }
		var _agreement = $('input[name="agreement"]:checked').val();
		if(!_agreement){
			error_tips(_error_this,'请先同意注册协议！');
			return false;
		}else{
			doreg_tips_hide(_error_this);
		}

		{{--检测手机号是否已经注册--}}
		$.post("{{to_route('passport.doreg')}}", _obj.serialize(), function (data) {
			if(data['status'] < 0){
				error_tips(_error_this,data['msg']);
				return false;
			}else{
				{{--再次发送倒计时--}}
				var _str = '<p class="success f12">'+data['msg']+'</p>';
				$('body #send_return_status').removeClass('send_msg_error').empty().append(_str);
				var _this = $('body .send_tips');
				var _countdown = "{{config('sendmobile.RESEND_TIME')}}";
				settime(_this,_countdown);
			}
		},'json');
	});

	// 验证手机短信码
	$('body').on('click','.check_info',function () {
		{{--验证成功会直接发送验证码--}}
		var _obj = $('.login_box').find('.show.active form');
		var _error_this = $(this).parents('.send_parent').find('#send_return_status').addClass('send_msg_error');

        var checkState = checkValidate(check_mobile, _obj.find('.mobile').val(), _error_this);
        if (checkState != 200) {
            return false;
        }
        checkState = checkValidate(check_password, _obj.find('.password').val(), _error_this);
        if (checkState != 200) {
            return false;
        }
        checkState = checkValidate(checkCode, _obj.find("input[name='captcha']").val(), _error_this);
        if (checkState != 200) {
            return false;
        }
		var _agreement = $('input[name="agreement"]:checked').val();
		if(!_agreement){
			error_tips(_error_this,'请先同意注册协议！');
			return false;
		}else{
			doreg_tips_hide(_error_this);
		}
		var _token = $(this).parent().find('input[name="_token"]').val();
		var _ticket = $(this).parent().find('.ticket').val();
		if(!_ticket){
			error_tips(_error_this,'请输入您收到的验证码！');
			return false;
		}else{
			doreg_tips_hide(_error_this);
		}
		var $url = "/";
		var $search = window.location.search;
		$.post("{{to_route('passport.register')}}",{phone:_obj.find('.mobile').val(),password:_obj.find('.password').val(),ticket:_ticket,_token:_token},function (data) {
			if(data['status'] < 0){
				error_tips(_error_this,data['msg']);
				return false;
			}else{
				layer.close();
				layer.msg(data['msg'],{icon:1},function(){
					if ($search) {
						$url = $search.replace("?",'');
					}
					window.location.href= $url;
				});
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
			$('.check_info').click();
		}
	};
</script>
	<!-- footer -->
	@include('wap.layouts.footer')
</body>
</html>

