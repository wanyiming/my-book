@extends('wap.layouts.base')
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
        <a href="javascript:if(history.length > 1) history.back(); else document.location.href='/'">
            <i class="iconfont fl"></i>
        </a>
        <a href="http://m.2shuwo.com/" title="爱书窝：搜索结果"><i class="iconfont fr"></i></a>
        <h1>搜索结果</h1>
    </div>
    <div id="content">
        <div class="blockb">
            <div class="blocktitle">搜索“<b class="hot">{{$keyword}}</b>”有<b class="hot"> {{$total}} </b>条记录</div>
            <div class="blockcontent" id="jieqi_page_contents">
                @if($data->isEmpty() === true)
                    <div class="c_row cf">暂无数据</div>
                    @else
                    @foreach($data as $searchKey=>$searchTiem)
                        <div class="c_row cf">
                            <a class="db cf"  href="{!! to_route('home.book.detaile',['id' => $searchTiem->id]) !!}"  title="点击查看：{!! to_route('home.book.detaile',['id' => $searchTiem->id]) !!}" >
                                <div class="row_cover">
                                    <img class="cover_m" title="{{$searchTiem->title}}"  alt="{{$searchTiem->title}}"  src="{{$searchTiem->book_cover}}">
                                </div>
                                <div class="row_text">
                                    <h4>{!! htmlspecialchars_decode(str_replace($keyword, '<span class="hot">'.$keyword.'</span>', $searchTiem->title)) !!}</h4>
                                    <p class="gray">{!! (new \App\Models\BookType())->getTypeName($searchTiem->book_type) !!} | {!! htmlspecialchars_decode(str_replace($keyword, '<span class="hot">'.$keyword.'</span>', $searchTiem->author)) !!}<br>    {{ msubstr($searchTiem->profiles,0,65)}}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
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
@section('scripts')
@endsection