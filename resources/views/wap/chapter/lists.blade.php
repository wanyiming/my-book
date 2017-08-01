@extends('wap.layouts.base')
@section('styles')
    <link rel="stylesheet" href="{!! asset('/wap/css/page.css') !!}" type="text/css" media="all">
@endsection
@section('scripts')
@endsection
@section('content')
    <div class="pagetitle cf">
        <a href="http://m.5du5.net/book/1191.html"><i class="iconfont fl">&#61033;</i></a>
        <a class="fr" href="{!! to_route('home.chapter.lists',['bookid' => $bookinfo['id'], 'order' => ($orderFiled == 'desc' ? 'asc' : 'desc'), 'page' => 1]) !!}">{{$orderName}}&nbsp;</a>
        作品目录
    </div>
    <div class="main">
        <ul class="tabb tab3 cf mb">
            <li><a href="{!! to_route('home.book.detaile',['id'=>$bookinfo['id']]) !!}">信息</a></li>
            <li><a href="javascript:void(0);" class="selected">目录</a></li>
            <li><a href="http://m.5du5.net/modules/article/reviews.php?aid=1191">书评</a></li>
        </ul>
        <div class="atitle">
            {{$bookinfo['title']}}
            <span class="ainfo">
                <a href="http://m.5du5.net/modules/article/authorarticle.php?author=%CB%D5%D0%A1%C5%AF">{{$bookinfo['author']}}</a> 著
            </span>
        </div>
        <dl class="index" id="jieqi_page_contents">
            <div class="pages">
                <div class="pagelink cf" id="pagelink">
                    <a href="http://m.5du5.net/1191/chapter_asc/1.html#" class="prev">上一页</a>
                    <em id="pagestats">
                        <kbd>
                            <input name="page" type="text" size="3" value="1" onkeydown="if(event.keyCode==13){window.location.href='/1191/chapter_asc/<{$page}>.html'.replace('<{$page|subdirectory}>', '/' + Math.floor(this.parentNode.getElementsByTagName('input')[0].value / 1000)).replace('<{$page}>', this.parentNode.getElementsByTagName('input')[0].value); return false;}" onfocus="if(this.value==this.getAttribute('dftval'))this.value='';" onblur="if(this.value=='')this.value=this.getAttribute('dftval');" dftval="1">
                        </kbd>
                        /{{$total}}
                        <a href="javascript:;" onclick="window.location.href='/1191/chapter_asc/<{$page}>.html'.replace('<{$page|subdirectory}>', '/' + Math.floor(this.parentNode.getElementsByTagName('input')[0].value / 1000)).replace('<{$page}>', this.parentNode.getElementsByTagName('input')[0].value);">GO</a>
                    </em>
                    <a href="http://m.5du5.net/1191/chapter_asc/2.html" class="next">下一页</a>
                </div>
            </div>
            @foreach($chapterList as $key=>$value)
                <dd>
                    <a class="db" href="{!! to_route('home.chapter.detaile', ['bookid' => $bookinfo['id'], 'chapterid' => $key]) !!}" title="2014-06-16 20:31更新，共1385字">{{$value}}</a>
                </dd>
            @endforeach
        </dl>
        <div class="pages">
            <div class="pagelink cf" id="pagelink">
                <a href="http://m.5du5.net/1191/chapter_asc/1.html#" class="prev">上一页</a>
                <em id="pagestats">
                    <kbd>
                        <input name="page" type="text" size="3" value="1" onkeydown="if(event.keyCode==13){window.location.href='/1191/chapter_asc/<{$page}>.html'.replace('<{$page|subdirectory}>', '/' + Math.floor(this.parentNode.getElementsByTagName('input')[0].value / 1000)).replace('<{$page}>', this.parentNode.getElementsByTagName('input')[0].value); return false;}" onfocus="if(this.value==this.getAttribute('dftval'))this.value='';" onblur="if(this.value=='')this.value=this.getAttribute('dftval');" dftval="1">
                    </kbd>
                    /{{$total}}
                    <a href="javascript:;" onclick="window.location.href='/1191/chapter_asc/<{$page}>.html'.replace('<{$page|subdirectory}>', '/' + Math.floor(this.parentNode.getElementsByTagName('input')[0].value / 1000)).replace('<{$page}>', this.parentNode.getElementsByTagName('input')[0].value);">GO</a>
                </em>
                <a href="http://m.5du5.net/1191/chapter_asc/2.html" class="next">下一页</a>
            </div>
        </div>
    </div>
@endsection