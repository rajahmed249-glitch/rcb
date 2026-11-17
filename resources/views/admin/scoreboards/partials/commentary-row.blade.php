@php
    $on = $row?->over_number ?? (is_array($row) ? ($row['over_number'] ?? '') : '');
    $sa = $row?->score_at ?? (is_array($row) ? ($row['score_at'] ?? '') : '');
    $bn = $row?->ball_number ?? (is_array($row) ? ($row['ball_number'] ?? '') : '');
    $br = $row?->ball_per_run ?? (is_array($row) ? ($row['ball_per_run'] ?? '') : '');
    $ds = $row?->description ?? (is_array($row) ? ($row['description'] ?? '') : '');
@endphp
<tr>
    <td><input type="text" name="commentary[{{ $side }}][{{ $i }}][over_number]" value="{{ $on }}" placeholder="12.3"></td>
    <td><input type="text" name="commentary[{{ $side }}][{{ $i }}][score_at]" value="{{ $sa }}" placeholder="120/3"></td>
    <td><input type="text" name="commentary[{{ $side }}][{{ $i }}][ball_number]" value="{{ $bn }}" placeholder="12.3"></td>
    <td><input type="text" name="commentary[{{ $side }}][{{ $i }}][ball_per_run]" value="{{ $br }}" placeholder="0, 1, 4, 6"></td>
    <td class="apf-st-wide"><textarea name="commentary[{{ $side }}][{{ $i }}][description]" rows="2" placeholder="...">{{ $ds }}</textarea></td>
    <td><button type="button" class="apf-stat-del" onclick="removeStatRow(this)"><i class="fas fa-times"></i></button></td>
</tr>
