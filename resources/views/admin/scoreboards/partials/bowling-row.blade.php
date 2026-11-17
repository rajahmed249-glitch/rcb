@php
    $pid = $row?->player_id ?? (is_array($row) ? ($row['player_id'] ?? '') : '');
    $nm = $row?->name ?? (is_array($row) ? ($row['name'] ?? '') : '');
    $ov = $row?->overs ?? (is_array($row) ? ($row['overs'] ?? '') : '');
    $bl = $row?->balls ?? (is_array($row) ? ($row['balls'] ?? '') : '');
    $md = $row?->maidens ?? (is_array($row) ? ($row['maidens'] ?? '') : '');
    $rn = $row?->runs ?? (is_array($row) ? ($row['runs'] ?? '') : '');
    $wk = $row?->wickets ?? (is_array($row) ? ($row['wickets'] ?? '') : '');
    $wd = $row?->wides ?? (is_array($row) ? ($row['wides'] ?? '') : '');
    $nb = $row?->noballs ?? (is_array($row) ? ($row['noballs'] ?? '') : '');
@endphp
<tr>
    <td class="apf-st-player">
        @if ($side === 'rcb')
            <select name="stats[{{ $side }}][bowling][{{ $i }}][player_id]">
                <option value="">-- {{ __('site.admin.select_bowler') }} --</option>
                @foreach ($players as $pl)
                    <option value="{{ $pl->id }}" @selected($pid == $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" name="stats[{{ $side }}][bowling][{{ $i }}][name]" value="{{ $nm }}" placeholder="{{ __('site.admin.player_name') }}">
        @endif
    </td>
    <td><input type="text" name="stats[{{ $side }}][bowling][{{ $i }}][overs]" value="{{ $ov }}" placeholder="3.2"></td>
    <td><input type="number" name="stats[{{ $side }}][bowling][{{ $i }}][balls]" value="{{ $bl }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][bowling][{{ $i }}][maidens]" value="{{ $md }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][bowling][{{ $i }}][runs]" value="{{ $rn }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][bowling][{{ $i }}][wickets]" value="{{ $wk }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][bowling][{{ $i }}][wides]" value="{{ $wd }}" min="0" placeholder="0"></td>
    <td><input type="number" name="stats[{{ $side }}][bowling][{{ $i }}][noballs]" value="{{ $nb }}" min="0" placeholder="0"></td>
    <td><button type="button" class="apf-stat-del" onclick="removeStatRow(this)"><i class="fas fa-times"></i></button></td>
</tr>
