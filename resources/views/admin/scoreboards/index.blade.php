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
        <a href="{{ route('admin.scoreboards.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_scoreboard') }}
        </a>
    </div>
    <a href="{{ route('admin.scoreboards.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_scoreboard') }}
    </a>
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
                    <th>{{ __('site.admin.match_no') }}</th>
                    <th>{{ __('site.admin.match_name') }}</th>
                    <th>{{ __('site.admin.venue') }}</th>
                    <th>{{ __('site.admin.date_time') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="scbTableBody">
                @if ($matches->count())
                    @foreach ($matches as $match)
                        <tr>
                            <td>{{ $matches->firstItem() + $loop->index }}</td>
                            <td><span class="apl-match-no">{{ $match->match_number ?: '—' }}</span></td>
                            <td><span class="apl-venue-text">{!! collect(mb_str_split($match->match_name ?: ('RCB vs ' . $match->display_opponent_name), 25))->map(fn ($c) => e($c))->implode('<br>') !!}</span></td>
                            <td><span class="apl-venue-text">{!! collect(mb_str_split($match->venue ?: 'TBD', 25))->map(fn ($c) => e($c))->implode('<br>') !!}</span></td>
                            <td>
                                <div class="apl-date-text">{{ $match->match_date->format('d M Y') }}</div>
                                <div class="apl-time-text">{{ $match->match_date->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="apl-team-row">
                                    <a href="{{ route('admin.scoreboards.edit', $match) }}?team=rcb" class="scb-team-pill rcb" title="RCB {{ __('site.admin.edit_score') }}">RCB</a>
                                    <span class="apl-vs-badge">VS</span>
                                    <a href="{{ route('admin.scoreboards.edit', $match) }}?team=opponent" class="scb-team-pill opp" title="{{ $match->display_opponent_name }} {{ __('site.admin.edit_score') }}">{{ $match->display_opponent_name }}</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="apl-empty">
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
            tbody.innerHTML = '<tr><td colspan="6" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
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
