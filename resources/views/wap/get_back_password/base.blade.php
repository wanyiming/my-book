<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
		{!! SEO::generate() !!}
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="renderer" content="webkit" />
		<meta name="format-detection" content="telephone=no">
		<link rel="shortcut icon" href="http://s1.music.126.net/music.ico?v1" />
		<link type="text/css" rel="stylesheet" href="{{STATIC_SITE}}member/css/common.css">
		<link type="text/css" rel="stylesheet" href="{{STATIC_SITE}}member/css/center.css">
		<link type="text/css" rel="stylesheet" href="{{STATIC_SITE.('home/css/jcmall_index.css')}}">
		<script type="text/javascript" src="{{STATIC_SITE}}member/js/jQuery.js"></script>
		<script src="{{ STATIC_SITE }}member/layer/layer.js"></script>
</head>
<body class="ui_seller bg_grey">
<!-- top_bar -->
@include('wap.get_back_password.top_bar')
<!-- middle_bar -->
@include('wap.get_back_password.middle_bar')
@yield('content')
@yield('scripts')
@include('wap.layouts.footer')