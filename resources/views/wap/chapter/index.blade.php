@extends('wap.layouts.base')
@section('styles')
    <link rel="stylesheet" href="{!! asset('/wap/css/info.css') !!}" type="text/css" media="all">
    <link rel="stylesheet" href="{!! asset('/wap/css/page.css') !!}" type="text/css" media="all">
@endsection
@section('scripts')
    <script type="text/javascript">
        var url_previous = '{!! $url_previous !!}';
        var url_next = '{!! $url_next !!}';
        var url_first = '{!! $url_first !!}';
        var url_index = '{!! to_route('home.chapter.lists',['bookid'=>$bookinfo['id'],'order'=>'desc','chapterid'=>$chapter['id']]) !!}'; // 目录地址
        var url_book_info = "{!! to_route('home.book.detaile', ['id' => $bookinfo['id']]) !!}"; // 书本详情地址
        var url_home = "/"; // 首页
        var book_id = "{{$bookinfo['id']}}"; // 书本ID
        var book_name = "{{$bookinfo['title']}}"; // 书本名
        var chapterid = "{{$chapter['id']}}"; // 章节id
        var chaptername = " {{$chapter['title']}}"; // 章节名
        var userid = "4003418";
        var egoldname = "4006421";
    </script>
    <script type="text/javascript" src="{{asset('wap/js/cm.js')}}"></script>
    <script type="text/javascript" src="{{asset('wap/js/readtools.js')}}"></script>
    <script type="text/javascript" src="{{asset('wap/js/json2.js')}}"></script>
    <script type="text/javascript" src="{{asset('wap/js/readlog.js')}}"></script>
@endsection
@section('content')
    {{--<div class="pagetitle cf"><a href="javascript:;"><i class="iconfont fl">&#xee69;</i></a><a href="/"><i class="iconfont fr">&#xee27;</i></a>章节阅读</div>--}}
    <div id="aread" class="main cf" style="">
        <div class="cb"></div>
        <div id="abox" class="abox">
            <div id="apage" class="apage">
                <div id="atitle" class="atitle"> {{$chapter['title']}}</div>
                <div id="acontent" class="acontent">&nbsp;&nbsp;&nbsp;&nbsp;
                    {!! html_entity_decode($chapter['content']) !!}
                </div>
                <div id="footlink" class="footlink">
                    <a id="syy" href="{!! $url_first !!}">上一页</a> &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{!! to_route('home.chapter.lists',['bookid'=>$bookinfo['id'],'order'=>'desc','chapterid'=>$chapter['id']]) !!}">返回目录</a> &nbsp;&nbsp;&nbsp;&nbsp;
                    <a id="xyz" href="{!! $url_next !!}">下一页</a>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div id="toptext" class="toptext" style="display:none;"></div>
        <div id="bottomtext" class="bottomtext" style="display:none;"></div>
        <div id="operatetip" class="operatetip" style="display:none;" onclick="this.style.display='none'">
            <div class="tipl"><p>翻上页</p></div>
            <div class="tipc"><p>呼出功能</p></div>
            <div class="tipr"><p>翻下页</p></div>
        </div>
    </div>
@endsection