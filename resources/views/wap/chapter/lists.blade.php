@extends('wap.layouts.base')
@section('styles')
    <link rel="stylesheet" href="{!! asset('/wap/css/page.css') !!}" type="text/css" media="all">
@endsection
@section('scripts')
    <script>
        var previousPage = {!! $page !!};
        $(function () {
            $("input[name='page']").keydown(function (event) {
                if (event.keyCode == 13) {
                    var page = $(this).val();
                    if (!isNaN(page) && page > 0) {
                        location.href = location.href.replace(previousPage+".html", page +'.html');
                    }
                }
            }).focus(function () {
            }).blur(function () {
                var page = $(this).val();
                if (isNaN(page)) {
                    $(this).val(1);
                }
                if (!page) {
                    $(this).val(1);
                }
            });
            $(".goTo").click(function () {
                var page = $("input[name='page']").val();
                if (!isNaN(page) && page > 0) {
                    location.href = location.href.replace(previousPage+".html", page +'.html');
                }
            })
        })
    </script>
@endsection
@section('content')
    <div class="pagetitle cf">
        <a href="javascript:history.back(-1)"><i class="iconfont fl">&#61033;</i></a>
        <a class="fr" href="{!! to_route('home.chapter.lists',['bookid' => $bookinfo['id'], 'order' => ($orderFiled == 'desc' ? 'asc' : 'desc'), 'page' => 1]) !!}">{{$orderName}}&nbsp;</a>
        作品目录
    </div>
    <div class="main">
        <ul class="tabb tab3 cf mb">
            <li><a href="{!! to_route('home.book.detaile',['id'=>$bookinfo['id']]) !!}">信息</a></li>
            <li><a href="javascript:void(0);" class="selected">目录</a></li>
            <li><a href="{!! to_route('home.chapter.comment',['bookid'=>$bookinfo['id']]) !!}">书评</a></li>
        </ul>
        <div class="atitle">
            {{$bookinfo['title']}}
            <span class="ainfo">
                <a href="javascript:;">{{$bookinfo['author']}}</a> 著
            </span>
        </div>
        <dl class="index" id="jieqi_page_contents">
            <div class="pages">
                <div class="pagelink cf" id="pagelink">
                    <a href="{!! $url_prev !!}" class="prev">上一页</a>
                    <em id="pagestats">
                        <kbd>
                            <input name="page" type="text" size="3" value="{!! $page !!}">
                        </kbd>
                        /{{$total}}
                        <a href="javascript:;" class="goTo">GO</a>
                    </em>
                    <a href="{!! $url_next !!}" class="next">下一页</a>
                </div>
            </div>
            @foreach($chapterList as $key=>$value)
                <dd>
                    <a class="db" href="{!! to_route('home.chapter.detaile', ['bookid' => $bookinfo['id'], 'chapterid' => $key]) !!}" title="{!! $value !!}">{{$value}}</a>
                </dd>
            @endforeach
        </dl>
        <div class="pages">
            <div class="pagelink cf" id="pagelink">
                <a href="{!! $url_prev !!}" class="prev">上一页</a>
                <em id="pagestats">
                    <kbd>
                        <input name="page" type="text" size="3" value="{!! $page !!}">
                    </kbd>
                    /{{$total}}
                    <a href="javascript:;" class="goTo">GO</a>
                </em>
                <a href="{!! $url_next !!}" class="next">下一页</a>
            </div>
        </div>
    </div>
@endsection