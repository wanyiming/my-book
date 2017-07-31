@extends('wap.layouts.base')
@section('content')
<div class="header cf">
    <div class="logo">
        <a href="http://m.5du5.net/"><img src="./吾读小说网-吾读小说网手机版_files/logo.png" border="0" alt="吾读小说网"></a>
    </div>
    <div class="banner">
        <a href="http://m.5du5.net/login.php" class="iconfont" title="登录">&#60961;</a>
        <a href="http://m.5du5.net/modules/article/bookcase.php" class="iconfont" title="书架">&#60995;</a>
    </div>
</div>
<div class="mainnav cf">
    <ul>
        <li><a href="http://m.5du5.net/shuku">书库</a></li>
        <li><a href="http://m.5du5.net/sort">分类</a></li>
        <li><a href="http://m.5du5.net/top/1_0_1.html">排行</a></li>
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
                        <a class="db" href="http://m.5du5.net/book/1191.html"><img class="cover_s" title="{{$bookValue->title}}"  alt="{{$bookValue->title}}" src="{{$bookValue->book_cover}}">
                            <p class="nw"><span class="nw">{{$bookValue->title}}</span><br></p>
                            <p class="nw lhs fss">{{$bookValue->author}}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="block">
        <div class="blocktitle">更新推荐</div>
        <div class="blockcontent">
            <a href="http://m.5du5.net/top/dayvisit/1.html" class="moretop"><i class="iconfont">&#61034;</i></a>
            <div class="c_row cf">
                <a class="db cf" href="http://m.5du5.net/book/1192.html">
                    <div class="row_cover">
                        <img class="cover_i" title="{{$upt_data[0]->title}}" alt="{{$upt_data[0]->title}}" src="{{$upt_data[0]->book_cover}}">
                    </div>
                    <div class="row_text">
                        <h4>{{$upt_data[0]->title}}</h4>
                        <p class="gray fss">{!! (new \App\Models\BookType())->getTypeName($upt_data[0]->book_type)!!} | {{$upt_data[0]->author}}<br> {{$upt_data[0]->profiles}}</p>
                    </div>
                </a>
            </div>
            <ul class="ullist">
                @foreach($upt_data as $uptKey => $uptValue)
                    @if($uptKey > 0)
                        <li><a class="db" href="http://m.5du5.net/book/1191.html">{{$uptValue['title']}}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <script>index_2();</script>
    <div class="block">
        <div class="blocktitle">点击榜</div>
        <div class="blockcontent">
            <div class="cf mb">
                <ul class="tabb tab3">
                    <li><a href="javascript:void(0)" onclick="selecttab(this);" class="selected">周点击</a></li>
                    <li><a href="javascript:void(0)" onclick="selecttab(this);">月点击</a></li>
                    <li><a href="javascript:void(0)" onclick="selecttab(this);">总点击</a></li>
                </ul>
            </div>
            <div>
                <div>
                    <ul class="ulnum">
                        <li><a class="db cf" href="http://m.5du5.net/book/1192.html"><em>3005</em> <b>1</b>爆笑宠妃：爷我等你休妻</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1191.html"><em>2584</em> <b>2</b>邪王追妻：废材逆天小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2648.html"><em>1966</em> <b>3</b>神医弃女：鬼帝的驭兽狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3109.html"><em>1046</em> <i>4</i>隐婚100分：惹火娇妻嫁一送一</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2989.html"><em>995</em> <i>5</i>绝色妖娆：鬼医至尊</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3641.html"><em>897</em> <i>6</i>劈天斩神</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1079.html"><em>672</em> <i>7</i>拒嫁豪门：少夫人99次出逃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2545.html"><em>645</em> <i>8</i>一世倾城：冷宫弃妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3168.html"><em>634</em> <i>9</i>绝世炼丹师：纨绔九小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3416.html"><em>632</em> <i>10</i>大神引入怀：101个深吻</a></li>
                    </ul>
                    <div class="tc">
                        <a href="http://m.5du5.net/top/weekvisit/1.html" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
                <div style="display:none;">
                    <ul class="ulnum">
                        <li><a class="db cf" href="http://m.5du5.net/book/1192.html"><em>128751</em> <b>1</b>爆笑宠妃：爷我等你休妻</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1191.html"><em>84811</em> <b>2</b>邪王追妻：废材逆天小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2648.html"><em>58786</em> <b>3</b>神医弃女：鬼帝的驭兽狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2989.html"><em>25421</em> <i>4</i>绝色妖娆：鬼医至尊</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1079.html"><em>19467</em> <i>5</i>拒嫁豪门：少夫人99次出逃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2545.html"><em>19328</em> <i>6</i>一世倾城：冷宫弃妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/352.html"><em>18276</em> <i>7</i>我的老婆是双胞胎</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/570.html"><em>17459</em> <i>8</i>我的贴身校花</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3109.html"><em>16057</em> <i>9</i>隐婚100分：惹火娇妻嫁一送一</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1199.html"><em>14389</em> <i>10</i>绝世神偷:废柴七小姐</a></li>
                    </ul>
                    <div class="tc">
                        <a href="http://m.5du5.net/top/monthvisit/1.html" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
                <div style="display:none;">
                    <ul class="ulnum">
                        <li><a class="db cf" href="http://m.5du5.net/book/1192.html"><em>128770</em> <b>1</b>爆笑宠妃：爷我等你休妻</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1191.html"><em>84821</em> <b>2</b>邪王追妻：废材逆天小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2648.html"><em>58803</em> <b>3</b>神医弃女：鬼帝的驭兽狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2989.html"><em>25426</em> <i>4</i>绝色妖娆：鬼医至尊</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1079.html"><em>19468</em> <i>5</i>拒嫁豪门：少夫人99次出逃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2545.html"><em>19335</em> <i>6</i>一世倾城：冷宫弃妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/352.html"><em>18279</em> <i>7</i>我的老婆是双胞胎</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/570.html"><em>17461</em> <i>8</i>我的贴身校花</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3109.html"><em>16063</em> <i>9</i>隐婚100分：惹火娇妻嫁一送一</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1199.html"><em>14394</em> <i>10</i>绝世神偷:废柴七小姐</a></li>
                    </ul>
                    <div class="tc">
                        <a href="http://m.5du5.net/top/allvisit/1.html" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>index_3();</script>
    <div class="block">
        <div class="blocktitle">推荐榜</div>
        <div class="blockcontent">
            <div class="cf mb">
                <ul class="tabb tab3">
                    <li><a href="javascript:void(0)" onclick="selecttab(this);" class="selected">周推荐</a></li>
                    <li><a href="javascript:void(0)" onclick="selecttab(this);">月推荐</a></li>
                    <li><a href="javascript:void(0)" onclick="selecttab(this);">总推荐</a></li>
                </ul>
            </div>
            <div>
                <div>
                    <ul class="ulnum">
                        <li><a class="db cf" href="http://m.5du5.net/book/3109.html"><em>26</em> <b>1</b>隐婚100分：惹火娇妻嫁一送一</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1191.html"><em>23</em> <b>2</b>邪王追妻：废材逆天小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2648.html"><em>10</em> <b>3</b>神医弃女：鬼帝的驭兽狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2280.html"><em>7</em> <i>4</i>佣兵的战争</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2910.html"><em>6</em> <i>5</i>不朽凡人</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1657.html"><em>5</em> <i>6</i>逆天腹黑狂女：绝世狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1409.html"><em>5</em> <i>7</i>都市逍遥修神</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2693.html"><em>5</em> <i>8</i>百炼飞升录</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2987.html"><em>5</em> <i>9</i>魔帝缠身：神医九小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2739.html"><em>5</em> <i>10</i>龙王传说</a></li>
                    </ul>
                    <div class="tc">
                        <a href="http://m.5du5.net/top/weekvote/1.html" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
                <div style="display:none;">
                    <ul class="ulnum">
                        <li><a class="db cf" href="http://m.5du5.net/book/1191.html"><em>1624</em> <b>1</b>邪王追妻：废材逆天小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3109.html"><em>1323</em> <b>2</b>隐婚100分：惹火娇妻嫁一送一</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2648.html"><em>1187</em> <b>3</b>神医弃女：鬼帝的驭兽狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3168.html"><em>702</em> <i>4</i>绝世炼丹师：纨绔九小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2910.html"><em>456</em> <i>5</i>不朽凡人</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3324.html"><em>354</em> <i>6</i>帝少心头宠：国民校草是女生</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1192.html"><em>237</em> <i>7</i>爆笑宠妃：爷我等你休妻</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2739.html"><em>220</em> <i>8</i>龙王传说</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3600.html"><em>169</em> <i>9</i>神医凰后：傲娇暴君，强势宠！</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3190.html"><em>167</em> <i>10</i>都市超级医圣</a></li>
                    </ul>
                    <div class="tc">
                        <a href="http://m.5du5.net/top/monthvote/1.html" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
                <div style="display:none;">
                    <ul class="ulnum">
                        <li><a class="db cf" href="http://m.5du5.net/book/1191.html"><em>68762</em> <b>1</b>邪王追妻：废材逆天小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2648.html"><em>16202</em> <b>2</b>神医弃女：鬼帝的驭兽狂妃</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/1700.html"><em>12307</em> <b>3</b>天域苍穹</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2247.html"><em>9861</em> <i>4</i>绝世神医：腹黑大小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3109.html"><em>6435</em> <i>5</i>隐婚100分：惹火娇妻嫁一送一</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/3168.html"><em>6079</em> <i>6</i>绝世炼丹师：纨绔九小姐</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/967.html"><em>5787</em> <i>7</i>都市无上仙医</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/352.html"><em>5705</em> <i>8</i>我的老婆是双胞胎</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/500.html"><em>5590</em> <i>9</i>武炼巅峰</a></li>
                        <li><a class="db cf" href="http://m.5du5.net/book/2739.html"><em>3669</em> <i>10</i>龙王传说</a></li>
                    </ul>
                    <div class="tc">
                        <a href="http://m.5du5.net/top/allvote/1.html" class="db btnlink b_gray">显示更多...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>index_4();</script>
    <div class="block">
        <div class="blocktitle">收藏榜</div>
        <div class="blockcontent">
            <a href="http://m.5du5.net/top/goodnum/1.html" class="moretop"><i class="iconfont">&#61034;</i></a>
            <div class="c_row cf">
                <a class="db cf" href="http://m.5du5.net/book/1191.html">
                    <div class="row_cover">
                        <img class="cover_i" src="./吾读小说网-吾读小说网手机版_files/1191s.jpg">
                    </div>
                    <div class="row_text">
                        <h4>邪王追妻：废材逆天小姐</h4>
                        <p class="gray fss">都市言情 | 苏小暖<br>    .她，21世纪金牌杀手，却穿为苏府最无用的废柴四小姐身上。他，帝国晋王殿下，..</p>
                    </div>
                </a>
            </div>
            <div class="c_row cf">
                <a class="db cf" href="http://m.5du5.net/book/2247.html">
                    <div class="row_cover">
                        <img class="cover_i" src="./吾读小说网-吾读小说网手机版_files/2247s.jpg">
                    </div>
                    <div class="row_text">
                        <h4>绝世神医：腹黑大小姐</h4>
                        <p class="gray fss">都市言情 | 夜北<br>    她是二十四世纪神医，一支银针，活死人，肉白骨。一夕穿越，成为王府人人..</p>
                    </div>
                </a>
            </div>
            <div class="c_row cf">
                <a class="db cf" href="http://m.5du5.net/book/2648.html">
                    <div class="row_cover">
                        <img class="cover_i" src="./吾读小说网-吾读小说网手机版_files/2648s.jpg">
                    </div>
                    <div class="row_text">
                        <h4>神医弃女：鬼帝的驭兽狂妃</h4>
                        <p class="gray fss">玄幻魔法 | MS芙子<br>    13岁的叶家傻女，一朝重生！坐拥万能神鼎，身怀灵植空间，她不再是人见人..</p>
                    </div>
                </a>
            </div>
            <div class="c_row cf">
                <a class="db cf" href="http://m.5du5.net/book/1079.html">
                    <div class="row_cover">
                        <img class="cover_i" src="./吾读小说网-吾读小说网手机版_files/1079s.jpg">
                    </div>
                    <div class="row_text">
                        <h4>拒嫁豪门：少夫人99次出逃</h4>
                        <p class="gray fss">都市言情 | 西门龙霆<br>    她惹上豪门恶霸，“少爷，少夫人又跑了…”该死，她竟敢嫁给别人！“教堂外有99架大..</p>
                    </div>
                </a>
            </div>
            <div class="c_row cf">
                <a class="db cf" href="http://m.5du5.net/book/1965.html">
                    <div class="row_cover">
                        <img class="cover_i" src="./吾读小说网-吾读小说网手机版_files/1965s.jpg">
                    </div>
                    <div class="row_text">
                        <h4>太古神王</h4>
                        <p class="gray fss">玄幻魔法 | 净无痕<br>    九天大陆，天穹之上有九条星河，亿万星辰，皆为武命星辰，武道之人，可沟通星辰..</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection