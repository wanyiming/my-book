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
        <a href="http://m.2shuwo.com/" title="爱书窝"><i class="iconfont fr"></i></a>
        <h1>{{$filedStr['file_name'] ?? '点击榜'}}</h1>
    </div>
    <div id="content">
        <table class="grid" width="100%" align="center">
            <tbody><tr align="center">
                <th width="15%">分类</th>
                <th width="50%">书名/作者</th>
                <th width="20%">{!! $filedStr['filed'] == 'update_time' ? '点击量' : mb_substr($filedStr['file_name'],0,2).'量' !!}</th>
                <th width="15%">状态</th>
            </tr>
            </tbody>
            <tbody id="jieqi_page_contents">
                @foreach($data as $item)
                <tr>
                    <td align="center">{!! mb_substr((new \App\Models\BookType())->getTypeName($item->book_type), 0, 2) !!}</td>
                    <td><a class="db nw" title="点击查看：{{$item->update_fild}}" href="{{to_route('home.book.detaile',['bookid'=>$item->id])}}">{{$item->title}}<span class="gray fss">/{{$item->author}}</span></a></td>
                    <td align="center">
                        @if($filedStr['filed'] =='update_time')
                            {{$item->read_num}}
                            @else
                            <?php $filed = $filedStr['filed'];?>
                            {{$item->$filed}}
                        @endif
                    </td>
                    <td align="center">{{\App\Models\Books::TYPES_ALL[$item->type_id]['name']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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