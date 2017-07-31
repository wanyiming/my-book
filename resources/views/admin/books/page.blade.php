
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <li class="disabled"><a>首页</a></li>
    @else
        <li><a href="javascript:void(0);"  onclick="get_demand('{{ $paginator->currentPage()-1 }}');">上一页</a></li>
    @endif
    <li class="active"><a>{{ $paginator->currentPage() }}</a></li>
    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <li><a href="javascript:void(0);"  onclick="get_demand('{{ $paginator->currentPage()+1 }}');">下一页</a></li>
    @else
        <li class="disabled"><a>尾页</a></li>
    @endif
