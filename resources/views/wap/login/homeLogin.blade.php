@extends('wap.layouts.base')
@section('styles')
	<style>
		td p {
			padding: 5px;
		}
	</style>
@endsection
@section('scripts')
	<script>
		$(function () {
			$('.button').click(function () {
                _fromSubmit($(".form"), 'post');
            })
        })
	</script>
@endsection
@section('content')
	<div class="pagetitle cf">
		<a href="javascript:if(history.length > 1) history.back(); else document.location.href='/';"><i class="iconfont fl">&#xee69;</i></a>
		<a href="/"><i class="iconfont fr">&#xee27;</i></a>登录
	</div>
	<div id="content">
		<form class="form cf" name="frmlogin" method="post" style="text-align: center" action="{!! to_route('home.post.login') !!}">
			<input type="hidden" name="usecookie" value="{{time()}}"/>
			<input type="hidden" value="{!! csrf_token() !!}" name="_token">
			<input type="hidden" value="{!! $scoureUrl ?? '/' !!}" name="scoureUrl">
			<fieldset>
				<table>
					<tr>
						<td>用户名：</td>
						<td>
							<input type="text" class="text" size="20" maxlength="30" style="width:120px" name="username" onKeyPress="javascript: if (event.keyCode == 32 || event.which == 32) return false;">
						</td>
					</tr>
					<tr>
						<td>密　码：</td>
						<td>
							<input type="password" class="text" size="20" maxlength="30" style="width:120px" name="password">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p><button type="button" class="button" name="submit">登　录</button></p>
							{{--<p><a href="{!! to_route('home.qq.login') !!}?scoureUrl={{$scoureUrl}}"><img src="{!! asset('wap/image/qq_login.gif') !!}" alt="用QQ账号登录" border="0"></a></p>--}}
						</td>
					</tr>
				</table>
				<div class="frow foot">
					<a href="{!! to_route('home.register') !!}">注册账号</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="http://m.5du5.net/getpass.php">忘记密码？</a>
				</div>
			</fieldset>
		</form>
	</div>
@endsection