@extends('wap.layouts.base')
@section('scripts')
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
@endsection
@section('styles')
	<style>
		.col8 input {
			width: 160px;
		}
	</style>
@endsection
@section('content')
	<div class="pagetitle cf">
		<a href="javascript:if(history.length > 1) history.back(); else document.location.href='/';"><i class="iconfont fl">&#xee69;</i></a>
		<a href="/"><i class="iconfont fr">&#xee27;</i></a>注册
	</div>
	<div id="content">
		<form class="form" name="frmregister" id="frmregister" action="http://m.5du5.net/register.php?do=submit" method="post">
			<fieldset>
				<div class="frow">
					<label class="col4 flabel"><span class="hot">*</span>用  户 名：</label>
					<div class="col8 last">
						<input type="text" class="text" name="username" id="username" size="25" maxlength="30" value="" onblur="Ajax.Tip('http://m.5du5.net/regcheck.php?item=u&amp;username='+this.value);">
					</div>
				</div>
				<div class="frow">
					<label class="col4 flabel"><span class="hot">*</span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
					<div class="col8 last">
						<input type="password" class="text" name="password" id="password" size="25" maxlength="20" value="" onblur="Ajax.Tip('http://m.5du5.net/regcheck.php?item=p&amp;password='+this.value);">
					</div>
				</div>
				<div class="frow">
					<label class="col4 flabel"><span class="hot">*</span>确认密码：</label>
					<div class="col8 last">
						<input type="password" class="text" name="repassword" id="repassword" size="25" maxlength="20"value="" onblur="Ajax.Tip('http://m.5du5.net/regcheck.php?item=r&amp;password='+password.value+'&amp;repassword='+this.value);">
					</div>
				</div>
				<div class="frow">
					<label class="col4 flabel"><span class="hot">*</span>Email：</label>
					<div class="col8 last">
						<input type="text" class="text" name="email" id="email" size="25" maxlength="60" value="" onblur="Ajax.Tip('http://m.5du5.net/regcheck.php?item=m&amp;email='+this.value);">
					</div>
				</div>
				<div class="frow">
					<label class="col4 flabel">验 证 码：</label>
					<div class="col8 last">
						<input type="text" class="text" size="8" maxlength="8" name="checkcode" onfocus="if($_('reg_imgccode').style.display == 'none'){$_('reg_imgccode').src = '/checkcode.php';$_('reg_imgccode').style.display = '';}" title="点击显示验证码"><img id="reg_imgccode" src="" style="cursor:pointer;vertical-align:middle;margin-left:3px;display:none;" onclick="this.src='/checkcode.php?rand='+Math.random();" title="点击刷新验证码">
					</div>
				</div>
				<div class="frow">
					<label class="col4 flabel">&nbsp;</label>
					<div class="col8 last">
						<button type="submit" class="button" name="submit">注　册</button>
						已有账号？请点击 <a class="hot" href="{!! to_route('home.login') !!}">登录</a>
						<input type="hidden" name="act" value="newuser">
					</div>
				</div>
			</fieldset>
		</form>
	</div>
@endsection

