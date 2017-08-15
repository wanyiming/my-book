@extends('wap.layouts.base')
@section('styles')
    <style type="text/css">
        #book_type ul li  {
            padding: 25px 8px 10px 8px; float: left;
        }
        #book_type ul li a {
            padding: 5px; border: 1px solid; background: #00C1B3; color: #ffffff;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $(function () {
            $(".mainnav li").click(function () {
                if( $(this).index() == 1 ) {
                    $(".book_type").css('display','black');
                    layer.open({
                        type: 1,
                        title: '分类选择',
                        closeBtn: 0, //不显示关闭按钮
                        shade: [0],
                        area: ['340px', '200px'],
                        time: 200000, //2秒后自动关闭
                        anim: 2,
                        content:$(".openHtml").html(), //iframe的url，no代表不显示滚动条
                        end:function () {
                            $(".openHtml").css('display','none');
                        }
                    });
                }
            })
        })
    </script>
@endsection
@section('content')
<div class="header cf">
    <div class="logo">
        <a href="/"><img src="/wap/image/logo.jpg" border="0" alt="爱书窝小说网"></a>
    </div>
    <div class="banner">
        <a href="{{to_route('home.login')}}" class="iconfont" title="登录">&#60961;</a>
        <a href="javascript:;" class="iconfont" title="书架">&#60995;</a>
    </div>
</div>
<div class="openHtml" style="display: none;">
    <div id="book_type">
        <ul>
            @foreach($book_types as $tKey => $tVal)
                <li><a href="" title="">{{$tVal->name}}</a></li>
            @endforeach
        </ul>
    </div>
</div>
<div class="mainnav cf">
    <ul>
        <li><a href="{!! to_route('home.book.sort',['seo'=>'update', 'page'=>1]) !!}">书库</a></li>
        <li><a href="javascript:;">分类</a></li>
        <li><a href="{!! to_route('home.book.sort',['seo'=>'click', 'page'=>1]) !!}">排行</a></li>
    </ul>
</div>
<div id="content">
    <div class="blockc">
        <div class="topsearch">
            <form name="t_frmsearch" method="post" action="http://m.5du5.net/modules/article/search.php" class="ts_form" onsubmit="if(document.getElementById(&#39;t_searchkey&#39;).value == &#39;&#39;){alert(&#39;请输入搜索内容！&#39;); document.getElementById(&#39;t_searchkey&#39;).focus(); return false;}">
                <div class="ts_input">
                    <input name="searchkey" id="t_searchkey" type="text" class="ts_key"><input name="searchtype" type="hidden" value="all">
                </div>
                <div class="ts_post">
                    <button type="submit" name="t_btnsearch" class="ts_submit iconfont">&#60968;</button>
                </div>
            </form>
        </div>
    </div>
    <div class="blockc">
        <div class="blockcontent">
            <div class="row">
                @foreach($rec_data as $bookKey => $bookValue)
                    <div class="tc mbs col4 @if($bookKey == 2) last @endif" >
                        <a class="db" href="{!! to_route('home.book.detaile',['id' => $bookValue['id']]) !!}"  title="点击查看：{!! to_route('home.book.detaile',['id' => $bookValue['id']]) !!}" >
                            <img class="cover_s" title="{{$bookValue['title']}}"  alt="{{$bookValue['title']}}" src="{{$bookValue['book_cover']}}">
                            <p class="nw"><span class="nw">{{$bookValue['title']}}</span><br></p>
                            <p class="nw lhs fss">{{$bookValue['author']}}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="block">
        <div class="blocktitle">更新推荐</div>
        <div class="blockcontent">
            <a href="{!! to_route('home.book.sort',['seo'=>'update', 'page'=>1]) !!}" class="moretop"><i class="iconfont">&#61034;</i></a>
            <div class="c_row cf">
                <a class="db cf" title="点击查看：{!! to_route('home.book.detaile',['id' => $upt_data[0]->id]) !!}" href="{!! to_route('home.book.detaile',['id' => $upt_data[0]->id]) !!}">
                    <div class="row_cover">
                        <img class="cover_i" title="{{$upt_data[0]->title}}" alt="{{$upt_data[0]->title}}" src="{{$upt_data[0]->book_cover}}">
                    </div>
                    <div class="row_text">
                        <h4>{{$upt_data[0]->title}}</h4>
                        <p class="gray fss">{!! $book_types[$upt_data[0]->book_type]->name !!} | {{$upt_data[0]->author}}<br> {{ msubstr($upt_data[0]->profiles,0,40)}}</p>
                    </div>
                </a>
            </div>
            <ul class="ullist">
                @foreach($upt_data as $uptKey => $uptValue)
                    @if($uptKey > 0)
                        <li><a class="db" title="点击查看：{!! to_route('home.book.detaile',['id' => $uptValue['id']]) !!}" href="{!! to_route('home.book.detaile',['id' => $uptValue['id']]) !!}">{{$uptValue['title']}}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="block">
        <div class="blocktitle">点击榜</div>
        <div class="blockcontent">
            <div class="c_row cf">
                <a href="{!! to_route('home.book.sort',['seo'=>'click', 'page'=>1]) !!}" class="moretop"><i class="iconfont">&#61034;</i></a>
                <div>
                    <ul class="ulnum">
						@foreach($click_data as $key=>$clickValue)
                            <li>
                                <a class="db cf"  title="点击查看：{!! to_route('home.book.detaile',['id' => $clickValue->id]) !!}" href="{!! to_route('home.book.detaile',['id' => $clickValue->id]) !!}">
                                    <em>{{$clickValue->read_num}}</em>
                                    @if($key > 2)
                                        <i>{{$key+1}}</i>
                                        @else
                                        <b>{{$key+1}}</b>
                                    @endif
                                    {{$clickValue->title}}:{{$clickValue->update_fild}}
                                </a>
                            </li>
						@endforeach
                    </ul>
                    <div class="tc">
                        <a href="{!! to_route('home.book.sort',['seo'=>'click', 'page'=>1]) !!}" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block">
        <div class="blocktitle">推荐榜</div>
        <div class="blockcontent">
            <div class="c_row cf">
                <a href="{!! to_route('home.book.sort',['seo'=>'recommend', 'page'=>1]) !!}" class="moretop"><i class="iconfont">&#61034;</i></a>
                <div>
                    <ul class="ulnum">
                        @foreach($recomment_data as $key=>$recmValue)
                            <li>
                                <a class="db cf"  title="点击查看：{!! to_route('home.book.detaile',['id' => $recmValue->id]) !!}" href="{!! to_route('home.book.detaile',['id' => $recmValue->id]) !!}">
                                    <em>{{$recmValue->read_num}}</em>
                                    @if($key > 2)
                                        <i>{{$key+1}}</i>
                                    @else
                                        <b>{{$key+1}}</b>
                                    @endif
                                    {{$recmValue->title}}:{{$clickValue->update_fild}}
                                </a>
                            </li>
						@endforeach
                    </ul>
                    <div class="tc">
                        <a href="{!! to_route('home.book.sort',['seo'=>'recommend', 'page'=>1]) !!}" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block">
        <div class="blocktitle">收藏榜</div>
        <div class="blockcontent">
            <a href="{!! to_route('home.book.sort',['seo'=>'collect', 'page'=>1]) !!}" class="moretop"><i class="iconfont">&#61034;</i></a>
			@foreach($collection_data as $collData)
				<div class="c_row cf">
					<a class="db cf" href="{!! to_route('home.book.detaile',['id' => $collData->id]) !!}"
					title="点击查看：{{$collData->title}}" >
						<div class="row_cover">
							<img class="cover_i" title="{{$collData->title}}" src="{{$collData->book_cover}}">
						</div>
						<div class="row_text">
							<h4>{{$collData->update_fild}}</h4>
							<p class="gray fss">{!! $book_types[$collData->book_type]->name !!} | {{$collData->author}}<br>    {!! msubstr(strip_tags($collData->profiles),0,45) !!}</p>
						</div>
					</a>
				</div>
			@endforeach
        </div>
    </div>
</div>
@endsection