@extends('admin.layout')

@section('title', __('site.admin.location'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-map-marker-alt apl-title-ico"></i> {{ __('site.admin.location') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.location') }}</li>
        </ol>
    </nav>
</div>

@php
    $isThana = $tab === 'thana';
    $addLabel = $isThana ? __('site.admin.add_thana') : __('site.admin.add_district');
    $addUrl = route('admin.locations.create', $isThana ? ['type' => 'thana'] : []);
@endphp

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.locations.index') }}" class="apl-tab {{ !$isThana ? 'active' : '' }}">
            <i class="fas fa-list"></i> {{ __('site.admin.districts') }}
        </a>
        <a href="{{ route('admin.locations.index', ['tab' => 'thana']) }}" class="apl-tab {{ $isThana ? 'active' : '' }}">
            <i class="fas fa-map-pin"></i> {{ __('site.admin.thanas') }}
        </a>
        <a href="{{ $addUrl }}" class="apl-tab">
            <i class="fas fa-plus-circle"></i> {{ $addLabel }}
        </a>
    </div>
    <a href="{{ $addUrl }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ $addLabel }}
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
            <label for="locLimit" class="apl-label">{{ __('site.admin.show') }}</label>
            <select id="locLimit" class="apl-entries-select">
                @foreach ([10, 25, 50, 100] as $opt)
                    <option value="{{ $opt }}" @selected($filterLimit == $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="apl-label">{{ __('site.admin.entries') }}</span>
            <span class="apl-divider"></span>
            @if ($isThana)
                <select id="locParent" class="apl-filter-select">
                    <option value="">{{ __('site.admin.all_districts') }}</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" @selected($filterParent == $district->id)>{{ $district->name }}</option>
                    @endforeach
                </select>
            @endif
            <select id="locStatus" class="apl-filter-select">
                <option value="">{{ __('site.admin.all_status') }}</option>
                <option value="1" @selected($filterStatus === '1')>{{ __('site.admin.active') }}</option>
                <option value="0" @selected($filterStatus === '0')>{{ __('site.admin.inactive') }}</option>
            </select>
        </div>
        <div class="apl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="search" id="locSearch" class="apl-search-input" placeholder="{{ __('site.admin.search_by_name') }}" value="{{ $filterSearch }}">
        </div>
    </div>

    <div class="apl-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    @if ($isThana)
                        <th>{{ __('site.admin.thana_name_en') }}</th>
                        <th>{{ __('site.admin.thana_name_bn') }}</th>
                        <th>{{ __('site.admin.district') }}</th>
                    @else
                        <th>{{ __('site.admin.district_name_en') }}</th>
                        <th>{{ __('site.admin.district_name_bn') }}</th>
                    @endif
                    <th>{{ __('site.admin.status') }}</th>
                    <th>{{ __('site.admin.action') }}</th>
                </tr>
            </thead>
            <tbody id="locTableBody">
                @if ($locations->count())
                    @foreach ($locations as $location)
                        <tr>
                            <td>{{ $locations->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="apl-pname">{{ $location->name }}</div>
                            </td>
                            <td class="apl-muted">{{ $location->name_bn ?: '—' }}</td>
                            @if ($isThana)
                                <td class="apl-muted">{{ $location->parent?->name ?? '—' }}</td>
                            @endif
                            <td>
                                <span class="apl-status-badge {{ $location->status ? 'active' : 'inactive' }}">
                                    {{ $location->status ? __('site.admin.active') : __('site.admin.inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="apl-acts">
                                    @if (!$isThana)
                                        <a href="{{ route('admin.locations.index', ['tab' => 'thana', 'parent' => $location->id]) }}" class="apl-act apl-act-view" title="{{ __('site.admin.thanas') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4-5.4 6-9 6c-3.6 0-6.6-2-9-6c2.4-4 5.4-6 9-6c3.6 0 6.6 2 9 6"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="apl-act apl-act-edit" title="{{ __('site.admin.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" class="apl-act-form" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
                        <td colspan="{{ $isThana ? 6 : 5 }}" class="apl-empty">
                            <i class="fas fa-map-marker-alt"></i>
                            <p>{{ __('site.admin.no_locations') }}</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="apl-foot">
        <div id="locEntriesInfo">
            <p class="apl-info">
                {{ __('site.admin.showing') }}
                <span>{{ $locations->firstItem() ?: 0 }}</span>
                {{ __('site.admin.to') }}
                <span>{{ $locations->lastItem() ?: 0 }}</span>
                {{ __('site.admin.of') }}
                <span>{{ $locations->total() }}</span>
                {{ __('site.admin.entries') }}
            </p>
        </div>
        <div id="locPaginationNav">
            @include('admin.partials.pagination', ['paginator' => $locations])
        </div>
    </div>
</div>

<script>
(function () {
    var searchTimer;

    function buildUrl(page) {
        var search = document.getElementById('locSearch')?.value.trim() || '';
        var status = document.getElementById('locStatus')?.value || '';
        var parent = document.getElementById('locParent')?.value || '';
        var limit = document.getElementById('locLimit')?.value || '10';
        var url = window.location.pathname + '?';
        url += 'tab={{ $tab }}&';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (status) url += 'status=' + encodeURIComponent(status) + '&';
        if (parent) url += 'parent=' + encodeURIComponent(parent) + '&';
        url += 'limit=' + limit + '&page=' + (page || 1);
        return url;
    }

    function applyFilters() {
        var url = buildUrl(1);
        var tbody = document.getElementById('locTableBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="{{ $isThana ? 6 : 5 }}" class="apl-empty"><i class="fas fa-spinner fa-spin"></i><p>...</p></td></tr>';
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var pairs = [
                    ['#locTableBody', '#locTableBody'],
                    ['#locPaginationNav', '#locPaginationNav'],
                    ['#locEntriesInfo', '#locEntriesInfo']
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

    document.getElementById('locSearch')?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var wrap = this.closest('.apl-search-wrap');
        var icon = wrap ? wrap.querySelector('i') : null;
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        searchTimer = setTimeout(function () {
            applyFilters();
            if (icon) icon.className = 'fas fa-search';
        }, 300);
    });

    ['locStatus', 'locParent', 'locLimit'].forEach(function (id) {
        document.getElementById(id)?.addEventListener('change', applyFilters);
    });

    window.addEventListener('popstate', function () { window.location.reload(); });
})();
</script>
@endsection
