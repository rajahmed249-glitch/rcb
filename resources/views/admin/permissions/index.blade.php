@extends('admin.layout')

@section('title', __('site.admin.permissions'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-key apl-title-ico"></i> {{ __('site.admin.permissions') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.permissions') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.permissions.index') }}" class="apl-tab active">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_permissions') }}
        </a>
        <a href="{{ route('admin.permissions.create') }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_permission') }}
        </a>
    </div>
    <a href="{{ route('admin.permissions.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_permission') }}
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
            <label for="prmLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="prmLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="prmSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_items') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.name_en') }}</th>
                    <th>{{ __('site.admin.name_bn') }}</th>
                    <th>Slug</th>
                    <th>{{ __('site.admin.sort_order') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="prmTableBody">
                @if ($permissions->count())
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permissions->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-pname">{{ $permission->name }}</div>
                            </td>
                            <td class="apl-muted">{{ $permission->name_bn ?: '—' }}</td>
                            <td class="apl-muted">{{ $permission->slug ?: '—' }}</td>
                            <td class="apl-muted">{{ $permission->sort_order }}</td>
                            <td>
                                <div class="apl-acts">
                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
                            <i class="fas fa-key"></i>
                            <p>{{ __('site.admin.no_permissions') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="prmEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $permissions->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $permissions->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $permissions->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="prmPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $permissions])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl() {
        var search = document.getElementById('prmSearch')?.value.trim() || '';
        var limit = document.getElementById('prmLimit')?.value || '10';
        var url = window.location.pathname + '?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        url += 'limit=' + limit + '&page=1';
        return url;
    }

    function applyFilters() {
        var url = buildUrl();
        var tbody = document.getElementById('prmTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="6" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#prmTableBody', '#prmTableBody'],
                    ['#prmPaginationNav', '#prmPaginationNav'],
                    ['#prmEntriesInfo', '#prmEntriesInfo']
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

    document.getElementById('prmSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    document.getElementById('prmLimit')?.addEventListener('change', applyFilters);

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
