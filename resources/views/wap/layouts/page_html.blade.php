@if ($paginator->hasPages())
<div class="page_btn">
    <div class="inner_page">
        @if ($paginator->onFirstPage())
            <a class="disabled prev">上一页</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"  class="prev">上一页</a>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
               {{--<span>{{ $element }}</span>--}}
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                            <strong>{{ $page }}</strong>
                    @else
                        {{--<a href="{{ $url }}" class="r_border">{{ $page }}</a>--}}
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="next">下一页</a>
        @else
            <a class="disabled r_border next">下一页</a>
        @endif
    </div>
</div>
@endif