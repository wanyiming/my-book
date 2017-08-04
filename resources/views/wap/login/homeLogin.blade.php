@extends('wap.layouts.base')
@section('scripts')
	<style>
		td p {
			padding: 5px;
		}
	</style>
@endsection
@section('content')
	<div class="pagetitle cf">
		<a href="javascript:if(history.length > 1) history.back(); else document.location.href='/';"><i class="iconfont fl">&#xee69;</i></a>
		<a href="/"><i class="iconfont fr">&#xee27;</i></a>登录
	</div>
	<div id="content">
		<form class="form cf" name="frmlogin" method="post" style="text-align: center" action="http://m.5du5.net/login.php?do=submit&jumpurl=http%3A%2F%2Fm.5du5.net%2Fbook%2F2438.html">
			<input type="hidden" name="usecookie" value="{{time()}}"/>
			<input type="hidden" value="{!! csrf_token() !!}" name="_token">
			<fieldset>
				<table>
					<tr>
						<td>用户名：</td>
						<td><input type="text" class="text" size="20" maxlength="30" style="width:120px" name="username" onKeyPress="javascript: if (event.keyCode == 32 || event.which == 32) return false;"></td>
					</tr>
					<tr>
						<td>密　码：</td>
						<td><input type="text" class="text" size="20" maxlength="30" style="width:120px" name="password"></td>
					</tr>
					{{--<tr>
						<td>验证码：</td>
						<td>
							<input type="password" class="text" size="20" maxlength="30" style="width:120px" name="password">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<img src="{{ captcha_src() }}" alt="" class="code_img fr captcha_get" style="=cursor:pointer;">
						</td>
					</tr>--}}
					<tr>
						<td colspan="2">
							<p><button type="submit" class="button" name="submit">登　录</button></p>
							<p><a href="/api/qq/login.php?jumpurl=http%3A%2F%2Fm.5du5.net%2Fbook%2F2438.html"><img src="{!! asset('wap/image/qq_login.gif') !!}" alt="用QQ账号登录" border="0"></a></p>
						</td>
					</tr>
				</table>
				<div class="frow foot">
					<a href="http://m.5du5.net/register.php">注册账号</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="http://m.5du5.net/getpass.php">忘记密码？</a>
				</div>
			</fieldset>
		</form></div>


@endsection