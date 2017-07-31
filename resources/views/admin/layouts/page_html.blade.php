<ul>
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <li class="disabled"><a>首页</a></li>
    @else
        <li><a href="{{ $paginator->previousPageUrl() }}" >上一页</a></li>
        @endif

                <!-- Pagination Elements -->
        @foreach ($elements as $element)
                <!-- "Three Dots" Separator -->
        @if (is_string($element))
            <li class="disabled"><a>{{ $element }}</a></li>
            @endif

                    <!-- Array Of Links -->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a>{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                        @endforeach
                        @endif
                        @endforeach

                                <!-- Next Page Link -->
                        @if ($paginator->hasMorePages())
                            <li><a href="{{ $paginator->nextPageUrl() }}" >下一页</a></li>
                        @else
                            <li class="disabled"><a>尾页</a></li>
                        @endif
</ul>