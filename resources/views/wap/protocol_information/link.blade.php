@extends('wap.layouts.base')
@section('styles')
    <link type="text/css" rel="stylesheet" href="{{ STATIC_SITE }}home/css/outer.css">
@endsection
@section('content')
<!--主体内容-->
<div class="w1200 clearfix">
    <!--分类导航-->
    <div class="b_nav f12">
        <a class="color_66" href="/">首页</a>
        <i></i>
        <span>友情链接</span>
    </div>
    <!--主体-->
    <div class="fix_top clearfix">
        <!--左nav-->
        <div class="nav_help fl">
            <ul class="nav_list">
                @foreach($list as $id => $name)
                    <li id="{{$id}}">
                        <a href="{{to_route('home.protocol_information.information',[$id])}}" title="{{$name}}">{{$name}}</a>
                    </li>
                @endforeach
                <li class="link">
                    <a href="{{to_route('home.protocol_information.link')}}" title="友情链接">友情链接</a>
                </li>
            </ul>
        </div>
        <!--右main-->
        <div class="m_help fr">
            <div class="head mb20">
                <img src="{{ STATIC_SITE }}home/images/help/help_6.png" alt="">
            </div>
            <div class="clearfix friend_link">
                <div class="f_top bg_ff mb20">
                    <h6>友情链接</h6>
                    <table cellpadding="0" cellspacing="0" class="f12">
                        <tbody>
                        <tr>
                            @if($links)
                                @foreach($links as $url =>$name)
                                    <td> <a href="{{$name->weburl}}" title="{{$name->website}}" target="_blank">{{$name->website}}</a></td>
                                @endforeach
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="f_bottom bg_ff clearfix mb30">
                    <h6 class="mb20">申请友情链接</h6>
                    <div class="text fl">
                        <b>申请步骤：</b>
                        <ul class="f12 ui_gray1">
                            <li>
                                请先在贵网站做好问问我的文字友情链接：
                                <p>  链接文字：问问我，链接地址：<a href="http://www.jsjzjz.com" target="_blank">www.jsjzjz.com</a></p>
                            </li>
                            <li>做好链接后，请在右侧填写申请信息。问问我只接受申请文字友情链接。</li>
                            <li>已经开通我站友情链接且内容健康，符合本站友情链接要求的网站，经问问我管理员审核后，    可以显示在此友情链接页面。</li>
                            <li>请通过右侧提交申请，注明：友情链接申请。</li>
                        </ul>
                    </div>
                    <div class="write fr">
                        <b>申请信息：</b>
                        <form role="form" class="form-horizontal adminex-form" method="post" action="http://www.jsjzjz.com/link">
                            <input type="hidden" name="_token" value="Bsha7WvC1vzzqaxh68RqqbTtH9tJrJTvgDf4442c">
                            <ul>
                                <li>
                                    <span>网站名称：</span>
                                    <input type="text" name="name">
                                </li>
                                <li>
                                    <span>网  址：</span>
                                    <input type="text" name="url">
                                </li>
                                <li>
                                    <span>电子邮箱：</span>
                                    <input type="text" name="email">
                                </li>
                                <li>
                                    <span>网站介绍：</span>
                                    <textarea name="describe"></textarea>
                                </li>
                                <li>
                                    <input type="button" class="btn submit_btn" value="提交">
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer -->
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('js/easyscroll.js')}}"></script>
<script type="text/javascript" src="{{asset('js/Panel_Location.js')}}"></script>
<script src="{{asset('js/jquery.lazyload.js')}}"></script>
<script>
    $(function () {
        //如果scroll_absolute方法不存在,会报错
        try {
            //自定义滚动条
            if($('.cate_menu .div_scroll').length){
                $('.cate_menu .div_scroll').scroll_absolute({
                    arrows:false
                });
            }
        }catch (e){

        }
        //分类导航
        $('.first_menu li').mouseover(function() {
            $(this).addClass('active').siblings().removeClass('active');
            $('.cate_menu').addClass('on');
            $('.second_menu').eq($(this).index()).css('visibility','visible');
        });
        $('.cate_menu').mouseover(function() {
            $('.cate_menu').addClass('on');
        }).mouseleave(function() {
            $('.cate_menu').removeClass('on');
            $('.first_menu li, .second_menu').removeClass('active');
        });

        $(document).ready(function() {
            $('.link').addClass("active");
        })
    })

</script>
@endsection