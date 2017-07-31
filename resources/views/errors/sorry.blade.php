<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>sorry - 爱书窝</title>
    <meta name="keywords" content="" />
    <meta name="description" content=""/>
    <meta name = "format-detection" content = "telephone=no">
    <meta name="renderer"  content="webkit"  />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="{{ STATIC_SITE }}home/images/wen.ico" />
    <link type="text/css" rel="stylesheet" href="{{ STATIC_SITE }}home/css/state.css">
    <script type="text/javascript" src="{{ STATIC_SITE }}home/js/jQuery.js"></script>
</head>
<body>
<div class="w1200">
    <div class="state_warp">
        <div class="sorry_pic">
            <p>{{$msgData['msg'] ?? '操作频繁，请稍后再试，再等等吧~~'}}</p>
            <p>该页面将在<span>3</span>秒后自动跳转</p>
            <p><a href="{{$msgData['url'] ?? 'javascript:history.back(-1)'}}">返回</a></p>
        </div>
    </div>
</div>
<script>
    var setTime = '{{$msgData['time']}}';
    function setSpanText() {
        if (setTime < 1) {
            var url  = '{{$msgData['url']}}' ? '{{$msgData['url']}}' :  history.back(-1);
            window.location.href = url;
        } else {
            $("span").text(setTime);
            setTime--;
        }
        setTimeout(function() {
            setSpanText()
        },1000)
    }
    $(function(){
        setSpanText();
    })
</script>
</body>
</html>