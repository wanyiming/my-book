@if ($paginator->hasPages())
<div class="page_btn">
    <div class="inner_page">
        @if ($paginator->onFirstPage())
            <a class="disabled">上一页</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" >上一页</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
               <span>{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active r_border">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="r_border">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="r_border">下一页</a>
        @else
            <a class="disabled r_border">下一页</a>
        @endif
            <span>到</span>
            <input type="text" name="page" id="page_jump" value="" placeholder="1">
            <span>页</span>
            <a href="javascript:;" data-href="{{$paginator->url(1)}}" class="r_border" id="jump_to_page">Go</a>
    </div>
</div>
<script language=javascript runat="server">

    var _btn = document.getElementById("jump_to_page");
    _btn.onclick = function(){
        var _page_num = document.getElementById("page_jump").value;
        var _url = document.getElementById("jump_to_page").getAttribute("data-href");
        if(!_page_num){
            _page_num = 1;
        }
        var jump_url = _url.replace(/page=(\d+)/, "page=" + _page_num);
        window.location.href = jump_url;
    };
</script>
@endif