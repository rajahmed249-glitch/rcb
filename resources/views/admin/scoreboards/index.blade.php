@extends('admin.layout')

@section('title', __('site.admin.scoreboard'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-chart-line apl-title-ico"></i> {{ __('site.admin.scoreboard') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.scoreboard') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.scoreboards.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_scoreboards') }}
        </a>
    </div>
</div>

<div class="apl-card">
    <div class="apl-filter-row">
        <div class="apl-filters">
            <label for="scbLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="scbLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
            <span class="apl-divider"></span>
            <select id="scbStatus" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_status') }}</option>
                <option value="upcoming" @selected($filterStatus === 'upcoming')>{{ __('site.admin.upcoming') }}</option>
                <option value="completed" @selected($filterStatus === 'completed')>{{ __('site.admin.completed') }}</option>
            </select>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="scbSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_by_name') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.match') }}</th>
                    <th>{{ __('site.admin.match_date') }}</th>
                    <th>{{ __('site.admin.rcb_score') }} - {{ __('site.admin.opponent_score') }}</th>
                    <th>{{ __('site.admin.status') }}</th>
                    <th>{{ __('site.admin.result_text') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="scbTableBody">
                @if ($matches->count())
                    @foreach ($matches as $match)
                        <tr>
                            <td>{{ $matches->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-pname">RCB vs {{ $match->display_opponent_name }}</div>
                            </td>
                            <td class="apl-muted">{{ $match->match_date->format('d M Y') }}</td>
                            <td class="apl-muted">{{ $match->rcb_score ?? '-' }} - {{ $match->opponent_score ?? '-' }}</td>
                            <td>
                                <span class="apl-status-badge {{ $match->status }}">
                                    <span class="status-dot {{ $match->status }}"></span>{{ ucfirst($match->status) }}
                                </span>
                            </td>
                            <td class="apl-muted">{{ $match->result_text ?: '—' }}</td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('admin.scoreboards.edit', $match) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit_score') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="apl-empty">
                            <i class="fas fa-chart-line"></i>
                            <p>{{ __('site.admin.no_data') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="scbEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $matches->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $matches->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $matches->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="scbPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $matches])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('scbSearch')?.value.trim() || '';
        var status = document.getElementById('scbStatus')?.value || '';
        var limit = document.getElementById('scbLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (status) url += 'status=' + encodeURIComponent(status) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('scbTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="7" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#scbTableBody', '#scbTableBody'],
                    ['#scbPaginationNav', '#scbPaginationNav'],
                    ['#scbEntriesInfo', '#scbEntriesInfo']
                ];
                pairs.forEach(function (pair) {
                    var next = doc.querySelector(pair[0]);
                    var cur = document.querySelector(pair[1]);
                    if (next && cur) cur.innerHTML = next.innerHTML;
                });
                window.history.pushState({}, '', url);
            })
            .catch(function () { window.location.href = url; });
    }

    document.getElementById('scbSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    ['scbStatus', 'scbLimit'].forEach(function (id) {
        document.getElementById(id)?.addEventListener('change', applyFilters);
    });

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
