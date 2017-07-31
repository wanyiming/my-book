@extends('wap.layouts.base')
@section('styles')
    <link type="text/css" rel="stylesheet" href="{{ STATIC_SITE }}home/css/outer.css">
@endsection
@section('content')
<!--主体内容-->
<div class="w1200 clearfix">
    <!--分类导航-->
    <div class="b_nav f12">
        <a class="color_66" href="/" title="问问我建材首页">首页</a>
        <i></i>
        <span id="span_id">{{$active['name']}}</span>
    </div>
    <!--主体-->
    <div class="fix_top clearfix">
        <!--左nav-->
        <div class="nav_help fl">
            <ul class="nav_list">
                @foreach($list as $id => $name)
                    <li id="{{$id}}" @if($active['id'] == $id) class="active" @endif>
                        <input type="hidden" class="{{$id}}" value="{{$name['information_name']}}">
                        <a href="{{to_route('home.protocol_information.information',[$id])}}" title="{{$name['information_name']}}">{{$name['information_name']}}</a>
                    </li>
                @endforeach
                <li class="link">
                    <a href="{{to_route('home.protocol_information.link')}}" title="友情链接">友情链接</a>
                </li>
            </ul>
        </div>
        <!--右main-->
        <div class="m_help fr">
            @if($active['img'])
                <div class="head mb20">
                    <img src="{!! qiniu_domain($active['img']) !!}" alt="{{$active['name']}}" title="{{$active['name']}}">
                </div>
            @endif
            {!! $content['content'] ?? '' !!}
        </div>
    </div>
</div>
<!-- footer -->
@endsection
@section('scripts')
<script type="text/javascript" src="{{ STATIC_SITE }}home/js/easyscroll.js"></script>
<script type="text/javascript" src="{{ STATIC_SITE }}home/js/Panel_Location.js"></script>
<script src="{{STATIC_SITE.('home/js/jquery.lazyload.js')}}"></script>
<script type="text/javascript">
    $("img.lazy").lazyload({effect: "fadeIn"});

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
        });
</script>
@endsection