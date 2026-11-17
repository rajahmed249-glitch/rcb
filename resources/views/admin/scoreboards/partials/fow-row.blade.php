@php
    $pid = $row?->player_id ?? (is_array($row) ? ($row['player_id'] ?? '') : '');
    $nm = $row?->name ?? (is_array($row) ? ($row['name'] ?? '') : '');
    $sc = $row?->score ?? (is_array($row) ? ($row['score'] ?? '') : '');
    $wn = $row?->wicket_no ?? (is_array($row) ? ($row['wicket_no'] ?? '') : '');
    $ov = $row?->over ?? (is_array($row) ? ($row['over'] ?? '') : '');
@endphp
<tr>
    <td><input type="number" name="fow[{{ $side }}][{{ $i }}][score]" value="{{ $sc }}" min="0" placeholder="0"></td>
    <td><input type="number" name="fow[{{ $side }}][{{ $i }}][wicket_no]" value="{{ $wn }}" min="1" placeholder="1"></td>
    <td class="apf-st-player">
        @if ($side === 'rcb')
            <select name="fow[{{ $side }}][{{ $i }}][player_id]">
                <option value="">-- {{ __('site.admin.select_one') }} --</option>
                @foreach ($players as $pl)
                    <option value="{{ $pl->id }}" @selected($pid == $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        @else
            <input type="text" name="fow[{{ $side }}][{{ $i }}][name]" value="{{ $nm }}" placeholder="{{ __('site.admin.player_out') }}">
        @endif
    </td>
    <td><input type="text" name="fow[{{ $side }}][{{ $i }}][over]" value="{{ $ov }}" placeholder="12.3"></td>
    <td><button type="button" class="apf-stat-del" onclick="removeStatRow(this)"><i class="fas fa-times"></i></button></td>
</tr>
