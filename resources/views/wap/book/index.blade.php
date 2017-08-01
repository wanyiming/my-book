@extends('wap.layouts.base')
@section('scripts')
    <script type="text/javascript" src="{{asset('/wap/js/json2.js')}}"></script>
    <script type="text/javascript" src="{{asset('/wap/js/logininfo.js')}}"></script>
    <script type="text/javascript" src="{{asset('/wap/js/readchapter.js')}}"></script>
@endsection
@section('content')
    <div class="pagetitle cf">
        <a href="javascript:if(history.length &gt; 1) history.back(); else document.location.href=&#39;/&#39;;">
            <i class="iconfont fl">&#61033;</i>
        </a>
        <a href="http://m.5du5.net/"><i class="iconfont fr">&#60967;</i></a>
        {{$bookinfo['title']}}
    </div>
    <div id="content">
        <ul class="tabb tab3 cf mb">
            <li><a href="javascript:void(0);" class="selected">信息</a></li>
            <li><a href="http://m.5du5.net/1191/chapter_asc/1.html">目录</a></li>
            <li><a href="http://m.5du5.net/ly/1191/1.html">书评</a></li>
        </ul>
        <div class="blockc mt">
            <div class="c_row cf">
                <div class="row_coverl">
                    <img class="cover_l" src="{{$bookinfo['book_cover']}}">
                </div>
                <div class="row_textl">
                    <h4 class="mbs"><span class="fr fss">[{!! (new \App\Models\BookType())->getTypeName($bookinfo['book_type']) !!}]</span>{{$bookinfo['title']}}</h4>
                    <p class="gray fss">
                        <span class="fr fss">{{$bookinfo['font_size']}}字</span>{{$bookinfo['author']}} 著<br>
                        <span class="fr fss">{{$bookinfo['read_num']}}人看过</span>状态 : {!! \App\Models\Books::TYPES_ALL[$bookinfo['status']]['name'] !!}<br>
                    </p>
                    <div class="tc mt">
                        <a class="btnlink b_hot" href="javascript:read_chapter(1191);"><span class="iconfont">&#61008;</span>追书</a>
                        <a class="btnlink b_s" id="a_addbookcase" href="javascript:;" onclick="Ajax.Tip(&#39;/modules/article/addbookcase.php?bid=1191&#39;, {method: &#39;POST&#39;});"><span class="iconfont">&#61057;</span>收藏</a>
                        <a class="btnlink b_s" id="a_uservote" href="javascript:;" onclick="Ajax.Tip(&#39;/modules/article/uservote.php?id=1191&#39;, {method: &#39;POST&#39;});"><span class="iconfont">&#61021;</span>推荐</a>
                        &#8195;&#8195;&#8195;&#8195;&#8195;<a class="btnlink b_s" id="a_flower" href="javascript:;" onclick="openDialog(&#39;/modules/article/gift.php?type=flower&amp;id=1191&amp;ajax_gets=jieqi_contents&#39;, false);"><span class="iconfont">&#61036;</span>鲜花</a>
                        <a class="btnlink b_s" id="a_egg" href="javascript:;" onclick="openDialog(&#39;/modules/article/gift.php?type=egg&amp;id=1191&amp;ajax_gets=jieqi_contents&#39;, false);"><span class="iconfont">&#61037;</span>鸡蛋</a>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="blockcontent fsss"><font color="red">已启用缓存技术，最新章节可能会延时显示，登录书架即可实时查看</font></div>
            </div>
            <div class="c_row nw">
                <span class="note">最新：</span><a href="http://m.5du5.net/book/1191/4007710/">{{$bookinfo['update_fild']}}</a>
                ({!! date('Y-m-d',strtotime($bookinfo['update_time'])) !!})
            </div>
        </div>
        <div class="block">
            <div class="blocktitle">内容简介</div>
            <div class="blockcontent fss">
                <div id="introl">&nbsp;&nbsp;&nbsp;&nbsp;{!! msubstr($bookinfo['profiles'], 0, 80) !!}<a href="javascript:;" onclick="$_('introl').style.display = 'none';$_('introa').style.display = '';$_('introd').style.height = 'auto';" class="hot">[显示全部]</a></div>
                @if(mb_strlen($bookinfo['profiles']) > 80)
                    <div id="introa" style="display:none;">
                        &nbsp;&nbsp;&nbsp;&nbsp;{!! msubstr($bookinfo['profiles'], 80, 300) !!}
                        <a href="javascript:;" onclick="$_('introl').style.display = '';$_('introa').style.display = 'none';$_('introd').style.height = '4.5em;';" class="hot">[收起内容]</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="block">
            <div class="blockcontent">
                <div class="cf">
                    <ul class="tabb tab2">
                        <li><a href="javascript:void(0)" onclick="selecttab(this)" class="selected">最新章节</a></li>
                        <li><a href="javascript:void(0)" onclick="selecttab(this)">开始章节</a></li>
                    </ul>
                </div>
                <div class="mts">
                    <div>
                        <ul class="ullist">
                            @foreach($chapter['newData'] as $newKey => $newValue)
                                <li><a href="{!! to_route('home.chapter.detaile',['bookid'=>$bookinfo['id'],'chapterid'=>$newKey]) !!}"> {{$newValue}} </a></li>
                            @endforeach
                        </ul>
                        <a href="{!! to_route('home.chapter.lists',['bookid'=>$bookinfo['id'], 'order' => 'desc', 'page' => 1]) !!}" class="more">显示全部章节<i class="iconfont">&#61034;</i></a>
                    </div>
                    <div style="display:none;">
                        <ul class="ullist">
                            @foreach($chapter['firstData'] as $firstKey => $firstValue)
                                <li><a href="{!! to_route('home.chapter.detaile',['bookid'=>$bookinfo['id'],'chapterid'=>$firstKey]) !!}"> {{$firstValue}} </a></li>
                            @endforeach
                        </ul>
                        <a href="{!! to_route('home.chapter.lists',['bookid'=>$bookinfo['id'], 'order' => 'asc', 'page' => 1]) !!}" class="more">显示全部章节<i class="iconfont">&#61034;</i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="block">
            <div class="blocktitle">最新书评</div>
            <div class="blockcontent">
                <a class="moretop" href="http://m.5du5.net/ly/1191/1.html">显示更多<i class="iconfont">&#61034;</i></a>
                <ul class="ullist">
                    @if($comment)
                        @foreach($comment as $comKey => $comValue)
                            <li>
                                <a class="db cf" href="http://m.5du5.net/hf/55355/1.html">
                                    <em>{!! $comValue['create_time'] !!}</em><b>{{$comValue['name']}}：</b>
                                    <p>{{$comValue['content']}}</p>
                                </a>
                            </li>
                        @endforeach
                        @else
                        <li>
                            暂无评价，快去做第一个评价的人把！！！
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="block">
            <div class="blocktitle">发表书评</div>
            <div class="blockcontent">
                <script type="text/javascript">
                    if(jieqiUserInfo['jieqiUserId'] > 0 && document.cookie.indexOf('PHPSESSID') != -1){
                        document.write('\
		<form class="cf" name="frmreview" id="frmreview" method="post" action="/modules/article/reviews.php?aid=1191">\
		<fieldset>\
		<div class="frow">\
		<textarea class="textarea" name="pcontent" id="pcontent" style="width:96%;height:5em;"></textarea>\
		</div>\
		\
		<div class="frow">验证码：<input type="text" class="text" size="8" maxlength="8" name="checkcode" onfocus="if($_(\'p_imgccode\').style.display == \'none\'){$_(\'p_imgccode\').src = \'/checkcode.php\';$_(\'p_imgccode\').style.display = \'\';}" title="点击显示验证码"><img id="p_imgccode" src="" style="cursor:pointer;vertical-align:middle;margin-left:3px;display:none;" onclick="this.src=\'/checkcode.php?rand=\'+Math.random();" title="点击刷新验证码"></div>\
		\
		<div class="frow">\
		<input type="hidden" name="act" value="newpost" />\
		<button type="button" name="Submit" class="button" style="cursor:pointer;" onclick="Ajax.Request(\'frmreview\',{onComplete:function(){alert(this.response.replace(/<br[^<>]*>/g,\'\\n\'));if(this.response.indexOf(\'验证码错误\') != -1){$_(\'checkcode\').value = \'\';$_(\'checkcode\').focus();} else Form.reset(\'frmreview\');}});"> 发表书评 </button>\
		</div>\
		</fieldset>\
		</form>');
                    }else{
                        document.write('<br />您需要 <a href="/login.php">登录</a> 才能发表书评！<br /><br />');
                    }
                </script><br>您需要 <a href="http://m.5du5.net/login.php">登录</a> 才能发表书评！<br><br>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection