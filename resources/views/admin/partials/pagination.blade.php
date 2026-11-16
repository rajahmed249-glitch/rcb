@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp
    <div class="admin-pagination">
        @if ($paginator->onFirstPage())
            <span class="admin-btn small ghost disabled">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="admin-btn small ghost">&laquo;</a>
        @endif

        @if ($start > 1)
            <a href="{{ $paginator->url(1) }}" class="admin-btn small ghost">1</a>
            @if ($start > 2)
                <span class="admin-page-dots">...</span>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $current)
                <span class="admin-btn small page-current">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}" class="admin-btn small ghost">{{ $i }}</a>
            @endif
        @endfor

        @if ($end < $last)
            @if ($end < $last - 1)
                <span class="admin-page-dots">...</span>
            @endif
            <a href="{{ $paginator->url($last) }}" class="admin-btn small ghost">{{ $last }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="admin-btn small ghost">&raquo;</a>
        @else
            <span class="admin-btn small ghost disabled">&raquo;</span>
        @endif
    </div>
@endif
