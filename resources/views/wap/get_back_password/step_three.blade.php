@extends('wap.get_back_password.base')
@section('scripts')
@endsection
@section('content')
	<div class="bg_grey">
		<div class="w1200 retrieve">
			<div class="finish">
				<p>恭喜您，密码设置成功！</p>
				<a class="red" href="{{ to_route('home.firm_login') }}" title="重新登录">重新登录</a>
			</div>
		</div>
	</div>
@endsection
