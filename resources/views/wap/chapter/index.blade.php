@extends('wap.layouts.base')
@section('styles')
    <link rel="stylesheet" href="{!! asset('/wap/css/info.css') !!}" type="text/css" media="all">
    <link rel="stylesheet" href="{!! asset('/wap/css/page.css') !!}" type="text/css" media="all">
@endsection
@section('scripts')
    <script type="text/javascript">
        var url_previous = "/book/1191/4003418/";
        var url_next = "/book/1191/4006421/";
        var url_index = "/1191/chapter_asc/1.html";
        var url_articleinfo = "/book/1191.html";
        var url_home = "/";
        var articleid = "1191";
        var articlename = "邪王追妻：废材逆天小姐";
        var chapterid = "4006420";
        var chaptername = " 第9469章 南宫流云1";
        var userid = "4003418";
        var egoldname = "4006421";
    </script>
    <script type="text/javascript" src="{{asset('wap/js/cm.js')}}"></script>
    <script type="text/javascript" src="{{asset('wap/js/readtools.js')}}"></script>
    <script type="text/javascript" src="{{asset('wap/js/json2.js')}}"></script>
    <script type="text/javascript" src="{{asset('wap/js/readlog.js')}}"></script>
@endsection
@section('content')
    {{--<div class="pagetitle cf"><a href="/book/1191.html"><i class="iconfont fl">&#xee69;</i></a><a href="/"><i class="iconfont fr">&#xee27;</i></a>章节阅读</div>--}}
    <div id="aread" class="main cf" style="background-color: rgb(240, 240, 240); color: rgb(0, 0, 0); font-size: 1em;">
        <div class="cb"></div>
        <div id="abox" class="abox">
            <div id="apage" class="apage">
                <div id="atitle" class="atitle"> {{$chapter['title']}}</div>
                <div id="acontent" class="acontent">&nbsp;&nbsp;&nbsp;&nbsp;
                    {!! html_entity_decode($chapter['content']) !!}
                </div>
                <div id="footlink" class="footlink">
                    <a id="syy" href="http://m.5du5.net/book/1191/4003418/">上一页</a> &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="http://m.5du5.net/1191/chapter_asc/1.html">返回目录</a> &nbsp;&nbsp;&nbsp;&nbsp;
                    <a id="xyz" href="http://m.5du5.net/book/1191/4006421/">下一页</a>
                </div>
            </div>
        </div>
        <div id="toptext" class="toptext" style="display:none;"></div>
        <div id="bottomtext" class="bottomtext" style="display:none;"></div>
        <div id="operatetip" class="operatetip" style="display:none;" onclick="this.style.display='none'">
            <div class="tipl"><p>翻上页</p></div>
            <div class="tipc"><p>呼出功能</p></div>
            <div class="tipr"><p>翻下页</p></div>
        </div>
    </div>
@endsection