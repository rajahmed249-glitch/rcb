@php
    $pid = $row?->player_id ?? (is_array($row) ? ($row['player_id'] ?? '') : '');
    $nm = $row?->name ?? (is_array($row) ? ($row['name'] ?? '') : '');
    $rn = $row?->runs ?? (is_array($row) ? ($row['runs'] ?? '') : '');
    $bl = $row?->balls ?? (is_array($row) ? ($row['balls'] ?? '') : '');
    $fr = $row?->fours ?? (is_array($row) ? ($row['fours'] ?? '') : '');
    $sx = $row?->sixes ?? (is_array($row) ? ($row['sixes'] ?? '') : '');
    $ds = $row?->dismissal ?? (is_array($row) ? ($row['dismissal'] ?? '') : '');
    $flId = $row?->fielder_id ?? (is_array($row) ? ($row['fielder_id'] ?? '') : '');
    $bwId = $row?->bowler_id ?? (is_array($row) ? ($row['bowler_id'] ?? '') : '');
    $dsLower = mb_strtolower(trim((string) $ds));
    if ($dsLower === 'not out') {
        $dsKey = 'notout';
    } elseif (str_starts_with($dsLower, 'c & b')) {
        $dsKey = 'c&b';
    } elseif (str_starts_with($dsLower, 'lbw')) {
        $dsKey = 'lbw';
    } elseif (str_starts_with($dsLower, 'run out')) {
        $dsKey = 'runout';
    } elseif (str_starts_with($dsLower, 'st ')) {
        $dsKey = 'stumped';
    } elseif (str_starts_with($dsLower, 'hit wicket')) {
        $dsKey = 'hitwicket';
    } elseif (str_starts_with($dsLower, 'did not bat')) {
        $dsKey = 'didnotbat';
    } elseif (str_starts_with($dsLower, 'b ')) {
        $dsKey = 'bowled';
    } elseif (str_starts_with($dsLower, 'c ')) {
        $dsKey = 'caught';
    } else {
        $dsKey = '';
    }
@endphp
<tr>
    <td class="apf-st-player">
        @if ($side === 'rcb')
            <select name="stats[{{ $side }}][batting][{{ $i }}][player_id]">
                <option value="">-- {{ __('site.admin.select_batter') }} --</option>
                @foreach ($players as $pl)
                    <option value="{{ $pl->id }}" @selected($pid == $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" name="stats[{{ $side }}][batting][{{ $i }}][name]" value="{{ $nm }}" placeholder="{{ __('site.admin.player_name') }}">
        @endif
    </td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][runs]" value="{{ $rn }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][balls]" value="{{ $bl }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][fours]" value="{{ $fr }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][sixes]" value="{{ $sx }}" min="0" placeholder="0"></td>
    <td>
        <select name="stats[{{ $side }}][batting][{{ $i }}][dismissal]">
            <option value="">-- {{ __('site.admin.select_one') }} --</option>
            <option value="notout" @selected($dsKey === 'notout')>{{ __('site.admin.d_not_out') }}</option>
            <option value="bowled" @selected($dsKey === 'bowled')>{{ __('site.admin.d_bowled') }}</option>
            <option value="caught" @selected($dsKey === 'caught')>{{ __('site.admin.d_caught') }}</option>
            <option value="lbw" @selected($dsKey === 'lbw')>{{ __('site.admin.d_lbw') }}</option>
            <option value="runout" @selected($dsKey === 'runout')>{{ __('site.admin.d_run_out') }}</option>
            <option value="stumped" @selected($dsKey === 'stumped')>{{ __('site.admin.d_stumped') }}</option>
            <option value="c&b" @selected($dsKey === 'c&b')>{{ __('site.admin.d_caught_bowled') }}</option>
            <option value="hitwicket" @selected($dsKey === 'hitwicket')>{{ __('site.admin.d_hit_wicket') }}</option>
            <option value="didnotbat" @selected($dsKey === 'didnotbat')>{{ __('site.admin.d_did_not_bat') }}</option>
        </select>
    </td>
    <td class="apf-st-player">
        @if ($side === 'rcb')
            <select name="stats[{{ $side }}][batting][{{ $i }}][fielder_id]">
                <option value="">-- {{ __('site.admin.select_one') }} --</option>
                @foreach ($players as $pl)
                    <option value="{{ $pl->id }}" @selected($flId == $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" name="stats[{{ $side }}][batting][{{ $i }}][fielder_name]" value="" placeholder="{{ __('site.admin.fielder') }}">
        @endif
    </td>
    <td class="apf-st-player">
        @if ($side === 'rcb')
            <select name="stats[{{ $side }}][batting][{{ $i }}][bowler_id]">
                <option value="">-- {{ __('site.admin.select_one') }} --</option>
                @foreach ($players as $pl)
                    <option value="{{ $pl->id }}" @selected($bwId == $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" name="stats[{{ $side }}][batting][{{ $i }}][bowler_name]" value="" placeholder="{{ __('site.admin.bowler') }}">
        @endif
    </td>
    <td><button type="button" class="apf-stat-del" onclick="removeStatRow(this)"><i class="fas fa-times"></i></button></td>
</tr>
