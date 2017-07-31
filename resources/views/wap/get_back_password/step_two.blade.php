@extends('wap.get_back_password.base')
@section('scripts')
	<script>
		//跳转到经销商-我的店铺-修改密码-完成.html
		$('.submit_btn').click(function () {

			var password = $('.password').val();
			var passwords = $('.passwords').val();
			if (password == ''){
				$('.notice').text('').removeClass('show');
				$('.notice').eq(0).text('请填写新密码').addClass('show');
				return false;
			}
			if (passwords == ''){
				$('.notice').text('').removeClass('show');
				$('.notice').eq(1).text('请填写确认新密码').addClass('show');
				return false;
			}
			$.post("{!! to_route('home.get_back_password.verification_step_two') !!}",$('#form').serialize(),function(result){
				if(result.status == 1){
					window.location.href="{{ $stepThree }}";
				}else {
					$('.notice').text('').removeClass('show');
					$('.notice').eq(1).text(result.msg).addClass('show');
					return false;
				}
			},'json');
		})
	</script>
@endsection
@section('content')
	<style>
		.retrieve li input[type=password] {
			height: 38px;
			width: 368px;
		}
	</style>
	<div class="bg_grey">
		<div class="w1200 retrieve">
			<form id="form" onsubmit="return false;">
				{!! csrf_field() !!}
				<ul>
					<li>
						<span>新密码：</span>
						<input type="password" name="password" class="password">
						<em class="f12 dark_grey">密码长度6-8个字符，应包含数字、字母。</em>
						<p class="notice"></p>
					</li>
					<li>
						<span>确认新密码：</span>
						<input type="password" name="passwords" class="passwords">
						<p class="notice"></p>
					</li>
					<li>
						<span>&emsp;</span>
						<input type="button" value="确   定" class="btn submit_btn">
					</li>
				</ul>
			</form>
		</div>
	</div>
@endsection
