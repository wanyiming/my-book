<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">

    <title>{{config('admin_config.SITE_TITLE')}}</title>

    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/style-responsive.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{('wap/image/icon.ico')}}" />
    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->
    @yield('styles')
</head>
<body class="sticky-header">
<section>
    <div class="horizontal-menu-page"  style="padding-left: 225px;">
        <div class="navbar navbar-default">
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    @foreach($layout['firstNodes'] as $node)
                        @if($node['is_show'])
                        <li @if($layout['currentFirstId'] == $node['id']) class="active" @endif ><a href="{{$node['route'] ? to_route($node['route']) : 'javascript:;'}}">{{$node['title']}}</a></li>
                        @endif
                    @endforeach
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <form class="navbar-form navbar-left" role="search" action="{{to_route('admin.node.search')}}">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Search" list="menu_list">
                                <datalist id="menu_list">
                                    @foreach($layout['sysNodes'] as $node)
                                        @if(substr_count($node['tree'],',') == 2)
                                            <option href="{{to_route($node['route'])}}">{{$node['title']}}</option>
                                        @endif
                                    @endforeach
                                </datalist>
                            </div>
                        </form>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> {{get_admin_session_info('name')}} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{to_route('admin.public.logout')}}">登出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="left-side sticky-left-side" style="background: #424f63">
        <div class="logo" style="text-align: center">
            <label for="" style="color: #ffffff; font-size: 18px;line-height: 40px;">{!! config('admin_config.SITE_TITLE') !!}</label>
        </div>
        <div class="logo-icon text-center">
            <a href="/"><img src="/assets/images/logo_icon.png" alt=""></a>
        </div>
        <div class="left-side-inner">
            <ul class="nav nav-pills nav-stacked custom-nav">
                @foreach($layout['sysNodes']->where('parent_id',$layout['currentFirstId']) as $node)
                    @if($node['is_show'])
                    <li class="menu-list  @if($node['id'] == $layout['currentSecondId']) nav-active @endif">
                        <a href="javascript:;">
                            @if(!empty($node['ico'])) <i class="{{$node['ico']}}"></i> @endif
                            <span>{{$node['title']}}</span>
                        </a>
                        <ul class="sub-menu-list">
                            @foreach($layout['sysNodes']->where('parent_id',$node['id']) as $subNode)
                                @if($subNode['is_show'])
                                <li @if($layout['currentThirdId'] == $subNode['id']) class="active" @endif>
                                    <a href="{{empty($subNode['route']) ? 'javascript:;' : to_route($subNode['route'])}}">
                                        @if(!empty($subNode['ico'])) <i class="{{$subNode['ico']}}"></i> @endif
                                        {{$subNode['title']}}
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="main-content" style="padding-top:0px">
        @if(Session::has('error'))
            <div class="alert-msg alert alert-block alert-danger fade in" style="width:100%;">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <strong>消息失败提示!</strong> {{Session::get('error')}}
            </div>
        @endif
        @if(Session::has('success'))
            <div class="alert-msg alert alert-success fade in" style="position: fixed;width:89.11%;z-index: 555">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <strong>消息成功提示!</strong> {{Session::get('success')}}
            </div>
        @endif
        <div class="wrapper">
            @yield('content')
        </div>
        <footer class="sticky-footer">
            <center>2017 &copy 2020 爱书窝</center>
        </footer>
    </div>
</section>
<script src="/assets/js/jquery-1.10.2.min.js"></script>
<script src="/assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/modernizr.min.js"></script>
<script src="/assets/js/jquery.nicescroll.js"></script>
<script src="{{asset('layer/layer.js')}}"></script>
<script src="{{asset('assets/common.js')}}"></script>
<script src="{{asset('assets/js/scripts.js')}}"></script>
@yield('scripts')
</body>
</html>