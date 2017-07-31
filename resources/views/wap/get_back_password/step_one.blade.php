@extends('wap.get_back_password.base')
@section('scripts')
	<script>
		$('.submit_btn').click(function(){
			var account = $('.account').val();
			if (account == ''){
				$('.notice').text('').removeClass('show');
				$('.notice').eq(0).text('请输入用户名').addClass('show');
				return false;
			}
			var mobile = $('.mobile').val();
			if(mobile == ''){
				$('.notice').text('').removeClass('show');
				$('.notice').eq(1).text('请输入手机号码').addClass('show');
				return false;
			}

			var mobile_code = $('.mobile_code').val();
			if (mobile_code == ''){
				$('.notice').text('').removeClass('show');
				$('.notice').eq(2).text('请输入手机验证码').addClass('show');
				return false;
			}

			$.post("{!! to_route('home.get_back_password.verification_step_one') !!}",$('#form').serialize(),function(result){
				if (result.status == 1){
					window.location.href="{{ $stepTwo }}";
				}else {
					$('.notice').text('').removeClass('show');
					$('.notice').eq(2).text(result.msg).addClass('show');
					return false;
				}
			},'json');
		});

		$(function(){
			$('.get_code_btn').click(function () {
				var account = $('.account').val();
				if (account == ''){
					$('.notice').text('').removeClass('show');
					$('.notice').eq(0).text('请输入用户名').addClass('show');
					return false;
				}
				var mobile = $('.mobile').val();
				if(mobile == ''){
					$('.notice').text('').removeClass('show');
					$('.notice').eq(1).text('请输入手机号码').addClass('show');
					return false;
				}
				var _this = $(this);
				$.post("{!! to_route('home.get_back_password.get_mobile_code') !!}",{'_token':"{!! csrf_token() !!}",'mobile':mobile},function(result){
					if (result.status == 1){
						//验证码
						var _this = $('.get_code_btn');
						_this.addClass('no');
						_this.html('<i class="red">'+"{!! config('sendmobile.RESEND_TIME') !!}"+'</i>秒后重新获取');
						var i="{!! config('sendmobile.RESEND_TIME') !!}";
						var t=setInterval(function () {
							if(i>1){
								i--;
								_this.html('<i class="red">'+i+'</i>秒后重新获取');
							}
							else{
								_this.html('获取短信验证码');
								_this.removeClass('no');
							}
						},1000);
					}else {
						$('.notice').text('').removeClass('show');
						$('.notice').eq(1).text(result.msg).addClass('show');
						return false;
					}
				},'json');
			});
		});
	</script>
@endsection
@section('content')
	<div class="bg_grey">
		<form id="form" onsubmit="return false;">
			{!! csrf_field() !!}
			<div class="w1200 retrieve">
				<ul>
					<li>
						<span>用户名：</span>
						<input type="text" name="account" class="account">
						<p class="notice"></p>
					</li>
					<li>
						<span>手机号码：</span>
						<input type="text" name="mobile" class="mobile">
						<p class="notice"></p>
					</li>
					<li>
						<span>手机验证码：</span>
						<input type="text" class="w245 mobile_code" name="mobile_code">
						<a href="javascript:void(0);" class="code get_code_btn">获取短信验证码</a>
						<p class="notice"></p>
					</li>
					<li>
						<span>&emsp;</span>
						<input type="button" value="提   交" class="btn submit_btn">
					</li>
				</ul>
			</div>
		</form>
	</div>
@endsection