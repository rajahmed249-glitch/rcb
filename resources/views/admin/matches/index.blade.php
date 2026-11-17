@extends('admin.layout')

@section('title', __('site.admin.matches'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-calendar-alt apl-title-ico"></i> {{ __('site.admin.matches') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.matches') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.matches.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_matches') }}
        </a>
        <a href="{{ route('admin.matches.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_match') }}
        </a>
    </div>
    <a href="{{ route('admin.matches.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_match') }}
    </a>
</div>

@if (session('success'))
    <div class="apl-alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.closest('.apl-alert-success').remove()" class="apl-alert-close">&times;</button>
    </div>
@endif

<div class="apl-card">
    <div class="apl-filter-row">
        <div class="apl-filters">
            <label for="mtcLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="mtcLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
            <span class="apl-divider"></span>
            <select id="mtcStatus" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_status') }}</option>
                <option value="upcoming" @selected($filterStatus === 'upcoming')>{{ __('site.admin.upcoming') }}</option>
                <option value="completed" @selected($filterStatus === 'completed')>{{ __('site.admin.completed') }}</option>
            </select>
            <select id="mtcTournament" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_tournaments') }}</option>
                @foreach ($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" @selected($filterTournament === (int) $tournament->id)>{{ $tournament->display_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="mtcSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_by_name') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>{{ __('site.admin.sn') }}</th>
                    <th>{{ __('site.admin.match_no') }}</th>
                    <th>{{ __('site.admin.match_name') }}</th>
                    <th>{{ __('site.admin.venue') }}</th>
                    <th>{{ __('site.admin.team') }}</th>
                    <th>{{ __('site.admin.date_time') }}</th>
                    <th>{{ __('site.admin.status') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="mtcTableBody">
                @if ($matches->count())
                    @foreach ($matches as $match)
                        <tr>
                            <td>{{ $matches->firstItem() + $loop->index }}</td>
                            <td>{{ $match->match_number ?: '—' }}</td>
                            <td>
                                <div class="apl-match-name">RCB vs {{ $match->display_opponent_name }}</div>
                                @if ($match->status === 'completed' && $match->result_text)
                                    <div class="apl-result-text">{{ $match->result_text }}</div>
                                @endif
                            </td>
                            <td><span class="apl-venue-text">{{ $match->venue ?: 'TBD' }}</span></td>
                            <td>
                                <div class="apl-team-row">
                                    <span class="apl-team-name">RCB</span>
                                    <span class="apl-vs-badge">VS</span>
                                    @if ($match->opponent_team_id)
                                        <a href="{{ route('pages.team-profile', $match->opponentTeam->slug) }}" target="_blank" class="apl-team-name apl-team-link" title="{{ $match->display_opponent_name }}">{{ $match->display_opponent_name }}</a>
                                    @else
                                        <span class="apl-team-name">{{ $match->display_opponent_name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="apl-date-text">{{ $match->match_date->format('d M Y') }}</div>
                                <div class="apl-time-text">{{ $match->match_date->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="apl-status-badge {{ $match->status }}">
                                    <span class="status-dot {{ $match->status }}"></span>{{ ucfirst($match->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('matches.show', $match) }}" target="_blank" class="apl-act apl-act-view" title="{{ __('site.admin.view') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4-5.4 6-9 6c-3.6 0-6.6-2-9-6c2.4-4 5.4-6 9-6c3.6 0 6.6 2 9 6"/></svg>
                                    </a>
                                    <a href="{{ route('admin.matches.edit', $match) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.matches.destroy', $match) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="apl-act apl-act-delete" title="{{ __('site.admin.delete') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="apl-empty">
                            <i class="fas fa-calendar-alt"></i>
                            <p>{{ __('site.admin.no_data') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="mtcEntriesInfo">
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
        <div id="mtcPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $matches])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('mtcSearch')?.value.trim() || '';
        var status = document.getElementById('mtcStatus')?.value || '';
        var tournament = document.getElementById('mtcTournament')?.value || '';
        var limit = document.getElementById('mtcLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (status) url += 'status=' + encodeURIComponent(status) + '&';
        if (tournament) url += 'tournament=' + encodeURIComponent(tournament) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('mtcTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="8" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#mtcTableBody', '#mtcTableBody'],
                    ['#mtcPaginationNav', '#mtcPaginationNav'],
                    ['#mtcEntriesInfo', '#mtcEntriesInfo']
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

    document.getElementById('mtcSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    ['mtcStatus', 'mtcTournament', 'mtcLimit'].forEach(function (id) {
        document.getElementById(id)?.addEventListener('change', applyFilters);
    });

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
