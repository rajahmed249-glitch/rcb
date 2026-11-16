@extends('admin.layout')

@section('title', __('site.admin.players'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-users apl-title-ico"></i> {{ __('site.admin.players') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.players') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.players.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_players') }}
        </a>
        <a href="{{ route('admin.players.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_player') }}
        </a>
    </div>
    <a href="{{ route('admin.players.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_player') }}
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
            <label for="aplLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="aplLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
            <span class="apl-divider"></span>
            <select id="aplRole" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_roles') }}</option>
                @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper', 'Captain', 'Vice Captain'] as $opt)
                    <option value="{{ $opt }}" @selected($filterRole === $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <select id="aplStatus" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_status') }}</option>
                <option value="1" @selected($filterStatus === '1')>{{ __('site.admin.active') }}</option>
                <option value="0" @selected($filterStatus === '0')>{{ __('site.admin.inactive') }}</option>
            </select>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="aplSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_by_name') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.image') }}</th>
                    <th>{{ __('site.admin.full_name') }}</th>
                    <th>{{ __('site.admin.name_bn') }}</th>
                    <th>{{ __('site.admin.team') }}</th>
                    <th>{{ __('site.admin.playing_role') }}</th>
                    <th>{{ __('site.admin.status') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="aplTableBody">
                @if ($players->count())
                    @foreach ($players as $player)
                        <tr>
                            <td>{{ $players->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-avatar-wrap">
                                    @if ($player->photo)
                                        <img src="{{ asset($player->photo) }}" class="apl-avatar" alt="{{ $player->name }}">
                                    @else
                                        <div class="apl-avatar-placeholder"><i class="fas fa-user"></i></div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="apl-pname">{{ $player->name }}</div>
                                @if ($player->jersey_number)
                                    <div class="apl-psub">#{{ $player->jersey_number }}</div>
                                @endif
                            </td>
                            <td class="apl-muted">{{ $player->name_bn ?: '—' }}</td>
                            <td class="apl-muted">{{ $player->team?->name ?? '—' }}</td>
                            <td><span class="apl-role-badge">{{ $player->role }}</span></td>
                            <td>
                                <span class="apl-status-badge {{ $player->team_id ? 'active' : 'inactive' }}">
                                    {{ $player->team_id ? __('site.admin.active') : __('site.admin.inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('players.show', $player) }}" target="_blank" class="apl-act apl-act-view" title="{{ __('site.admin.view') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4-5.4 6-9 6c-3.6 0-6.6-2-9-6c2.4-4 5.4-6 9-6c3.6 0 6.6 2 9 6"/></svg>
                                    </a>
                                    <a href="{{ route('admin.players.edit', $player) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.players.destroy', $player) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
                            <i class="fas fa-users"></i>
                            <p>{{ __('site.admin.no_players') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="aplEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $players->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $players->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $players->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="aplPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $players])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('aplSearch')?.value.trim() || '';
        var role = document.getElementById('aplRole')?.value || '';
        var status = document.getElementById('aplStatus')?.value || '';
        var limit = document.getElementById('aplLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (role) url += 'role=' + encodeURIComponent(role) + '&';
        if (status) url += 'status=' + encodeURIComponent(status) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('aplTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="8" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#aplTableBody', '#aplTableBody'],
                    ['#aplPaginationNav', '#aplPaginationNav'],
                    ['#aplEntriesInfo', '#aplEntriesInfo']
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

    document.getElementById('aplSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    ['aplRole', 'aplStatus', 'aplLimit'].forEach(function (id) {
        document.getElementById(id)?.addEventListener('change', applyFilters);
    });

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
