@php
    $pid = $row?->player_id ?? (is_array($row) ? ($row['player_id'] ?? '') : '');
    $nm = $row?->name ?? (is_array($row) ? ($row['name'] ?? '') : '');
    $rn = $row?->runs ?? (is_array($row) ? ($row['runs'] ?? '') : '');
    $bl = $row?->balls ?? (is_array($row) ? ($row['balls'] ?? '') : '');
    $fr = $row?->fours ?? (is_array($row) ? ($row['fours'] ?? '') : '');
    $sx = $row?->sixes ?? (is_array($row) ? ($row['sixes'] ?? '') : '');
    $ds = $row?->dismissal ?? (is_array($row) ? ($row['dismissal'] ?? '') : '');
@endphp
<tr>
    <td class="apf-st-player">
        @if ($side === 'rcb')
            <select name="stats[rcb][batting][{{ $i }}][player_id]">
                <option value="">-- {{ __('site.admin.player') }} --</option>
                @foreach ($players as $pl)
                    <option value="{{ $pl->id }}" @selected($pid == $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" name="stats[opponent][batting][{{ $i }}][name]" value="{{ $nm }}" placeholder="{{ __('site.admin.player_name') }}">
        @endif
    </td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][runs]" value="{{ $rn }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][balls]" value="{{ $bl }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][fours]" value="{{ $fr }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][batting][{{ $i }}][sixes]" value="{{ $sx }}" min="0" placeholder="0"></td>
    <td class="apf-st-wide"><input type="text" name="stats[{{ $side }}][batting][{{ $i }}][dismissal]" value="{{ $ds }}" placeholder="{{ __('site.admin.dismissal_hint') }}"></td>
    <td><button type="button" class="apf-stat-del" onclick="removeStatRow(this)"><i class="fas fa-times"></i></button></td>
</tr>
