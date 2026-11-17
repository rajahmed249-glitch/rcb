@extends('admin.layout')

@section('title', __('site.admin.roles'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-user-tag apl-title-ico"></i> {{ __('site.admin.roles') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.roles') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.roles.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_roles') }}
        </a>
        <a href="{{ route('admin.roles.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_role') }}
        </a>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_role') }}
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
            <label for="rolLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="rolLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="rolSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_items') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.name_en') }}</th>
                    <th>{{ __('site.admin.name_bn') }}</th>
                    <th>{{ __('site.admin.sort_order') }}</th>
                    <th>{{ __('site.admin.users_count') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="rolTableBody">
                @if ($roles->count())
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $roles->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-pname">{{ $role->name }}</div>
                            </td>
                            <td class="apl-muted">{{ $role->name_bn ?: '—' }}</td>
                            <td class="apl-muted">{{ $role->sort_order }}</td>
                            <td><span class="apl-role-badge">{{ $role->users_count }}</span></td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
                        <td colspan="6" class="apl-empty">
                            <i class="fas fa-user-tag"></i>
                            <p>{{ __('site.admin.no_roles') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="rolEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $roles->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $roles->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $roles->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="rolPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $roles])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('rolSearch')?.value.trim() || '';
        var limit = document.getElementById('rolLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('rolTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="6" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#rolTableBody', '#rolTableBody'],
                    ['#rolPaginationNav', '#rolPaginationNav'],
                    ['#rolEntriesInfo', '#rolEntriesInfo']
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

    document.getElementById('rolSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    document.getElementById('rolLimit')?.addEventListener('change', applyFilters);

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
