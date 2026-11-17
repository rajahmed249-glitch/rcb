@extends('layouts.app')

@section('title', __('site.records.title') . ' - RCB')

@section('content')
    <div class="rec-wrap">
        <div class="rec-stats">
            <div class="rec-stat purple">
                <p class="rec-stat-label">{{ __('site.records.total_matches') }}</p>
                <p class="rec-stat-num">{{ $totalMatches }}</p>
                <p class="rec-stat-hint">{{ __('site.records.all_opponents') }}</p>
            </div>
            <div class="rec-stat green">
                <p class="rec-stat-label">{{ __('site.records.total_wins') }}</p>
                <p class="rec-stat-num">{{ $totalWins }}</p>
                <p class="rec-stat-hint">{{ __('site.records.across_formats') }}</p>
            </div>
            <div class="rec-stat red">
                <p class="rec-stat-label">{{ __('site.records.total_losses') }}</p>
                <p class="rec-stat-num">{{ $totalLosses }}</p>
                <p class="rec-stat-hint">{{ $totalDraws }} {{ __('site.records.no_result') }}</p>
            </div>
            <div class="rec-stat blue">
                <p class="rec-stat-label">{{ __('site.records.win_percentage') }}</p>
                <p class="rec-stat-num">{{ $winPercentage }}%</p>
                <p class="rec-stat-hint">{{ $totalWins }} {{ __('site.records.wins_in') }} {{ $totalMatches }} {{ __('site.records.matches') }}</p>
            </div>
        </div>

        <div class="rec-panel">
            <div class="rec-panel-head">
                <h2>
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>
                    {{ __('site.records.results_summary') }}
                </h2>
                <span class="rec-updated">{{ __('site.records.last_updated') }}: {{ now()->translatedFormat('d M Y') }}</span>
            </div>

            <div class="rec-table-scroll">
                <table class="rec-table">
                    <thead>
                        <tr>
                            <th>{{ __('site.records.opponent') }}</th>
                            <th class="c">{{ __('site.records.mat') }}</th>
                            <th class="c">{{ __('site.records.won') }}</th>
                            <th class="c">{{ __('site.records.lost') }}</th>
                            <th class="c">{{ __('site.records.nr') }}</th>
                            <th class="c">{{ __('site.records.w_l') }}</th>
                            <th class="c">{{ __('site.records.wins') }}%</th>
                            <th class="c">{{ __('site.records.losses') }}%</th>
                            <th class="c">{{ __('site.records.nr') }}%</th>
                            <th class="c">{{ __('site.records.details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($opponents as $op)
                            @php($i = $loop->index)
                            <tr class="rec-row" onclick="toggleRow({{ $i }})">
                                <td>
                                    <div class="rec-opp">
                                        @if ($op['logo'])
                                            <img src="{{ asset($op['logo']) }}" alt="{{ $op['name'] }}">
                                        @else
                                            <span class="rec-opp-fallback">{{ mb_strtoupper(mb_substr($op['name'], 0, 2)) }}</span>
                                        @endif
                                        @if ($op['slug'])
                                            <a href="{{ route('pages.team-profile', $op['slug']) }}" onclick="event.stopPropagation()">{{ $op['name'] }}</a>
                                        @else
                                            <span>{{ $op['name'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="c strong">{{ $op['played'] }}</td>
                                <td class="c win">{{ $op['won'] }}</td>
                                <td class="c loss">{{ $op['lost'] }}</td>
                                <td class="c muted">{{ $op['draw'] }}</td>
                                <td class="c mono">{{ $op['w_l'] }}</td>
                                <td><div class="rec-bar"><span class="mono">{{ $op['w_percent'] }}%</span><div class="rec-bar-track"><div class="rec-bar-fill green" style="width: {{ $op['w_percent'] }}%"></div></div></div></td>
                                <td><div class="rec-bar"><span class="mono">{{ $op['l_percent'] }}%</span><div class="rec-bar-track"><div class="rec-bar-fill red" style="width: {{ $op['l_percent'] }}%"></div></div></div></td>
                                <td><div class="rec-bar"><span class="mono">{{ $op['d_percent'] }}%</span><div class="rec-bar-track"><div class="rec-bar-fill yellow" style="width: {{ $op['d_percent'] }}%"></div></div></div></td>
                                <td class="c">
                                    <button type="button" class="rec-toggle" onclick="event.stopPropagation(); toggleRow({{ $i }})" aria-label="{{ __('site.records.details') }}">
                                        <svg id="arrow-{{ $i }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr id="row-{{ $i }}" class="rec-expanded hidden">
                                <td colspan="10">
                                    <div class="rec-h2h">
                                        <div class="rec-h2h-head">
                                            <h3><span class="dot"></span>{{ __('site.records.head_to_head') }} <em>{{ $op['name'] }}</em></h3>
                                        </div>
                                        @if ($op['matches']->count())
                                            <table class="rec-h2h-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('site.records.scorecard') }}</th>
                                                        <th>{{ __('site.records.result') }}</th>
                                                        <th>{{ __('site.records.date') }}</th>
                                                        <th class="c">{{ __('site.records.scoreboard') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($op['matches'] as $m)
                                                        <tr>
                                                            <td class="mono">{{ $m->id }}</td>
                                                            <td>
                                                                <div class="rec-score-lines">
                                                                    <span>RCB: {{ $m->rcb_score ?? '-' }}{{ $m->rcb_overs ? ' (' . $m->rcb_overs . ' ov)' : '' }}</span>
                                                                    <span>{{ $m->display_opponent_name }}: {{ $m->opponent_score ?? '-' }}{{ $m->opponent_overs ? ' (' . $m->opponent_overs . ' ov)' : '' }}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="rec-result {{ $m->won ? 'win' : 'loss' }}">{{ $m->result_text ?: ($m->won ? __('site.records.won') : __('site.records.lost')) }}</span>
                                                            </td>
                                                            <td class="muted">{{ $m->match_date?->translatedFormat('d M Y') }}</td>
                                                            <td class="c">
                                                                <a href="{{ route('matches.show', $m) }}" class="rec-view" onclick="event.stopPropagation()">
                                                                    {{ __('site.records.view') }}
                                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="rec-h2h-empty">{{ __('site.records.no_match_details') }}</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="rec-none">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="rec-none-title">{{ __('site.records.no_opponents') }}</p>
                                    <p class="rec-none-sub">{{ __('site.records.no_match_records') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($opponents->lastPage() > 1)
            <div class="pgal-pager">
                <nav class="pgal-pager-nav">
                    @if ($opponents->onFirstPage())
                        <span class="pgal-page-btn disabled">‹ {{ __('site.gallery.prev') }}</span>
                    @else
                        <a href="{{ $opponents->previousPageUrl() }}" class="pgal-page-btn">‹ {{ __('site.gallery.prev') }}</a>
                    @endif
                    <div class="pgal-pages">
                        @foreach ($opponents->getUrlRange(1, $opponents->lastPage()) as $p => $url)
                            @if ($p == $opponents->currentPage())
                                <span class="pgal-page-num current">{{ $p }}</span>
                            @else
                                <a href="{{ $url }}" class="pgal-page-num">{{ $p }}</a>
                            @endif
                        @endforeach
                    </div>
                    <span class="pgal-page-info">{{ __('site.gallery.page') }} {{ $opponents->currentPage() }}/{{ $opponents->lastPage() }}</span>
                    @if ($opponents->hasMorePages())
                        <a href="{{ $opponents->nextPageUrl() }}" class="pgal-page-btn">{{ __('site.gallery.next') }} ›</a>
                    @else
                        <span class="pgal-page-btn disabled">{{ __('site.gallery.next') }} ›</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function toggleRow(index) {
            var row = document.getElementById('row-' + index);
            var arrow = document.getElementById('arrow-' + index);

            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                if (arrow) arrow.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />';
            } else {
                row.classList.add('hidden');
                if (arrow) arrow.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />';
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('tr[id^="row-"]').forEach(function (row) {
                    if (!row.classList.contains('hidden')) toggleRow(parseInt(row.id.replace('row-', ''), 10));
                });
            }
        });
    </script>
@endpush
