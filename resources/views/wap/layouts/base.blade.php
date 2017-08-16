<!DOCTYPE html>
<html lang="en">
<head>
    {!! SEO::generate() !!}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-title" content="爱书窝小说网">
    <meta name="layoutmode" content="standard">
    <meta http-equiv="Cache-Control" content="no-transform ">
	<link rel="shortcut icon" href="{{asset('wap/image/icon.ico')}}" />
    <link rel="stylesheet" href="{!! asset('/wap/css/style.css') !!}" type="text/css" media="all">
    @yield('styles')<script src="/assets/js/jquery-1.10.2.min.js"></script>
    <script src="/assets/js/jquery-ui-1.9.2.custom.min.js"></script>
</head>
<body>
@yield('content')
@if(empty($hasFooter))
@include('wap.layouts.footer')
@endif
<script type="text/javascript"  src="{{asset('layer/layer.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/common.js')}}"></script>
<script type="text/javascript" src="{!! asset('/wap/js/hm.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/wap/js/common.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/wap/js/theme.js') !!}"></script>
@yield('scripts')
</body>
</html>