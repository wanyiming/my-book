{{--<script>--}}
    {{--window.ppSettings = {--}}
        {{--// app_uuid，必填字段，是客服团队的uuid，可在PPConsole的 团队设置-基本信息 中找到--}}
        {{--app_uuid: "3520e060-b521-11e6-a1a8-00163e161af4",--}}
        {{--@if(!empty(get_user_session_info('mobile')))--}}
        {{--// 第三方用户email，可选字段，不填则会以匿名用户身份启动PPCom，否则以具名用户身份启动PPCom--}}
        {{--user_email: "{{get_user_session_info('mobile')}}@wenwenwo.com",--}}
        {{--// 用户姓名，可选字段，客服看到的PPCom用户的名称--}}
        {{--user_name: "{{get_user_session_info('nick_name')}}",--}}
        {{--// 用户头像，可选字段，客服看到的PPCom用户的头像--}}
        {{--user_icon: "{{qiniu_domain(get_user_session_info('head_image'),['w'=>200,'h'=>200])}}",--}}
        {{--@else--}}
        {{--user_name:"{{get_client_location()}}.用户",--}}
        {{--@endif--}}
        {{--// 语言配置，可选字段，zh-CN:"中文"，en:"英文", 默认为中文，决定PPCom界面显示语言--}}
        {{--language: "zh-CN"--}}
    {{--};--}}
    {{--(function(){var w=window,d=document;function l(){var a=d.createElement('script');a.type='text/javascript';a.async=!0;a.src='http://60.205.58.225:8945/ppcom/assets/pp-library.min.js?v=2017062601';var b=d.getElementsByTagName('script')[0];b.parentNode.insertBefore(a,b)}w.attachEvent?w.attachEvent('onload',l):w.addEventListener('load',l,!1);})()--}}
{{--</script>--}}
<script>
    /*$(function(){
        $(document).on("click",".customer-service-btn",function(){
            $("#pp-launcher-button").trigger("click");
        });
    });*/
    //底部悬浮
    $(document).on('click', '.www-scroll-top-button-container',function() {
        $('html,body').animate({
            scrollTop:0,
        })
    });
    $(window).scroll(function () {
        if($(window).scrollTop()>0){
            $('.www-scroll-top-button-container').show();
        }
        else{
            $('.www-scroll-top-button-container').hide();
        }
    })
</script>