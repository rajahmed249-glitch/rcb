@extends('admin.layout')

@section('title', __('site.admin.users'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-users apl-title-ico"></i> {{ __('site.admin.users') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.users') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.users.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_users') }}
        </a>
        <a href="{{ route('admin.users.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_user') }}
        </a>
    </div>
    <a href="{{ route('admin.users.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_user') }}
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
            <label for="usrLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="usrLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
            <span class="apl-divider"></span>
            <select id="usrRole" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_roles') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected($filterRole == $role->id)>{{ $role->display_name }}</option>
                @endforeach
            </select>
            <select id="usrStatus" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_status') }}</option>
                <option value="1" @selected($filterStatus === '1')>{{ __('site.admin.active') }}</option>
                <option value="0" @selected($filterStatus === '0')>{{ __('site.admin.inactive') }}</option>
            </select>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="usrSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_users') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.avatar') }}</th>
                    <th>{{ __('site.admin.name') }}</th>
                    <th>{{ __('site.admin.email') }}</th>
                    <th>{{ __('site.admin.role') }}</th>
                    <th>{{ __('site.admin.status') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="usrTableBody">
                @if ($users->count())
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-avatar-wrap">
                                    <div class="usr-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="apl-pname">{{ $user->name }}</div>
                            </td>
                            <td class="apl-muted">{{ $user->email }}</td>
                            <td><span class="apl-role-badge">{{ $user->role?->display_name ?? '—' }}</span></td>
                            <td>
                                <span class="apl-status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    {{ $user->is_active ? __('site.admin.active') : __('site.admin.inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
                        <td colspan="7" class="apl-empty">
                            <i class="fas fa-users"></i>
                            <p>{{ __('site.admin.no_users') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="usrEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $users->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $users->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $users->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="usrPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $users])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('usrSearch')?.value.trim() || '';
        var role = document.getElementById('usrRole')?.value || '';
        var status = document.getElementById('usrStatus')?.value || '';
        var limit = document.getElementById('usrLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (role) url += 'role=' + encodeURIComponent(role) + '&';
        if (status) url += 'status=' + encodeURIComponent(status) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('usrTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="7" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#usrTableBody', '#usrTableBody'],
                    ['#usrPaginationNav', '#usrPaginationNav'],
                    ['#usrEntriesInfo', '#usrEntriesInfo']
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

    document.getElementById('usrSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    ['usrRole', 'usrStatus', 'usrLimit'].forEach(function (id) {
        document.getElementById(id)?.addEventListener('change', applyFilters);
    });

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
