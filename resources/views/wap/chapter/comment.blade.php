@extends('wap.layouts.base')
@section('styles')
@endsection
@section('scripts')

@endsection
@section('content')
    <div class="pagetitle cf">
        <a href="javascript:if(history.length &gt; 1) history.back(); else document.location.href='/'"><i class="iconfont fl">&#61033;</i></a>
        <a href="/" title="爱书窝"><i class="iconfont fr">&#60967;</i></a>
        书评列表
    </div>
    <div id="content">
        <ul class="tabb tab3 cf mb">
            <li><a href="{!! to_route('home.book.detaile',['id'=>$bookinfo['id']]) !!}">信息</a></li>
            <li><a href="{!! to_route('home.chapter.lists',['bookid' => $bookinfo['id'], 'order' => 'desc', 'page' => 1]) !!}">目录</a></li>
            <li><a href="javascript:;" class="selected">书评</a></li>
        </ul>
        <div class="blockc mt">
            <div class="tc"><a class="hot f_l" href="{!! to_route('home.book.detaile',['id'=>$bookinfo['id']]) !!}">{{$bookinfo['title']}}</a></div>
            <ul class="ullist">
                @if($comment->isEmpty() === true)
                    <li>
                        <a class="db cf" href="http://m.5du5.net/hf/55355/1.html">
                            <em>2017-07-22 06:28:00</em><b>一个人，湿：</b>
                            <p>主神的女怎么这么，总算南宫分开了。</p>
                        </a>
                    </li>
                    @else
                    @foreach($comment as $value)
                        <li>
                            <a class="db cf" href="javascript:;">
                                <em>{{$value->create_time}}</em><b>{{$value->name}}：</b>
                                <p>{{$value->content}}</p>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="pages">
                <div class="pagelink" id="pagelink">
                    {{ $comment->links('wap.layouts.page_html') }}
                </div>
            </div>
        </div>
    </div>
@endsection