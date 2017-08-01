<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-title" content="爱书窝小说网">
    <meta name="keywords" content="爱书窝小说网,小说阅读网,免费小说,TXT小说免费下载,2shuwo">
    <meta name="description" content="爱书窝小说网是广大书友最值得收藏的网络小说阅读网，网站收录了当前最火热的网络小说，免费提供高质量的小说最新章节，是广大网络小说爱好者必备的小说阅读网。">
    <meta name="layoutmode" content="standard">
    <meta http-equiv="Cache-Control" content="no-transform ">
    <title>{!! SEO::generate() !!}</title>
    @yield('styles')
    <link rel="stylesheet" href="{!! asset('/wap/css/style.css') !!}" type="text/css" media="all">
</head>
<body>
@yield('content')
@if(empty($hasFooter))
@include('wap.layouts.footer')
@endif
<script type="text/javascript" src="{!! asset('/wap/js/hm.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/wap/js/common.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/wap/js/theme.js') !!}"></script>
@yield('scripts')
</body>
</html>