@extends('admin.layout')

@section('title', __('site.admin.total_teams'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-shield-alt apl-title-ico"></i> {{ __('site.admin.total_teams') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.total_teams') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.teams.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_teams') }}
        </a>
        <a href="{{ route('admin.teams.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_team') }}
        </a>
    </div>
    <a href="{{ route('admin.teams.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_team') }}
    </a>
</div>

<div class="apl-card">
    <div class="apl-filter-row">
        <div class="apl-filters">
            <label for="teamLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="teamLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="teamSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_by_name') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.logo') }}</th>
                    <th>{{ __('site.admin.name_en') }}</th>
                    <th>{{ __('site.admin.name_bn') }}</th>
                    <th>{{ __('site.admin.short_name') }}</th>
                    <th>{{ __('site.admin.location') }}</th>
                    <th>{{ __('site.admin.sort_order') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="teamTableBody">
                @if ($teams->count())
                    @foreach ($teams as $team)
                        <tr>
                            <td>{{ $teams->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-avatar-wrap">
                                    @if ($team->logo_url)
                                        <img src="{{ $team->logo_url }}" class="apl-avatar" alt="{{ $team->name }}">
                                    @else
                                        <div class="apl-avatar-placeholder"><i class="fas fa-shield-alt"></i></div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="apl-pname">{{ $team->name }}@if ($team->is_rcb)<span class="apl-role-badge role-c1" style="margin-left:8px;">RCB</span>@endif</div>
                            </td>
                            <td class="apl-muted">{{ $team->name_bn ?: '—' }}</td>
                            <td class="apl-muted">{{ $team->short_name ?: '—' }}</td>
                            <td class="apl-muted">{{ $team->location ?: '—' }}</td>
                            <td class="apl-muted">{{ $team->sort_order }}</td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('admin.teams.edit', $team) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
                            <i class="fas fa-shield-alt"></i>
                            <p>{{ __('site.admin.no_data') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="teamEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $teams->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $teams->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $teams->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="teamPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $teams])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('teamSearch')?.value.trim() || '';
        var limit = document.getElementById('teamLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('teamTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="8" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#teamTableBody', '#teamTableBody'],
                    ['#teamPaginationNav', '#teamPaginationNav'],
                    ['#teamEntriesInfo', '#teamEntriesInfo']
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

    document.getElementById('teamSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    document.getElementById('teamLimit')?.addEventListener('change', applyFilters);

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
