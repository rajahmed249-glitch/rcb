@if ($paginator->hasPages())
    <div class="admin-pagination">
        @if ($paginator->onFirstPage())
            <span class="admin-btn small ghost disabled">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="admin-btn small ghost">&laquo;</a>
        @endif

        @foreach ($paginator->elements() as $element)
            @if (is_string($element))
                <span class="admin-page-dots">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="admin-btn small page-current">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="admin-btn small ghost">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="admin-btn small ghost">&raquo;</a>
        @else
            <span class="admin-btn small ghost disabled">&raquo;</span>
        @endif
    </div>
@endif
