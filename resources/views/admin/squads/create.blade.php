@extends('admin.layout')

@section('title', __('site.admin.add_squad'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-users-viewfinder apl-title-ico"></i> {{ __('site.admin.add_squad') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.squads.index') }}">{{ __('site.admin.squad') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.add_squad') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.squads.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_squads') }}
        </a>
        <a href="{{ route('admin.squads.create') }}" class="apl-tab active">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_squad') }}
        </a>
    </div>
    <a href="{{ route('admin.squads.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_squad') }}
    </a>
</div>

<div class="apf-card">
    <div class="apf-card-title">{{ __('site.admin.squad') }}</div>

    <div class="apf-group" style="max-width:520px;">
        <label>{{ __('site.admin.match') }} <span class="apf-required">*</span></label>
        <select id="sqdMatchSelect">
            <option value="">{{ __('site.admin.select_match') }}</option>
            @foreach ($matches as $m)
                <option value="{{ $m->id }}" data-url="{{ route('admin.squads.edit', $m) }}">
                    {{ $m->match_number ? str_pad($m->match_number, 4, '0', STR_PAD_LEFT) : '—' }} — {{ $m->match_name ?: ('RCB vs ' . $m->display_opponent_name) }} ({{ $m->match_date->format('d M Y') }})
                </option>
            @endforeach
        </select>
        <p class="apf-hint">{{ __('site.admin.squad_continue_hint') }}</p>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.squads.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="button" class="apf-submit" id="sqdGoBtn">
            <i class="fas fa-arrow-right"></i> {{ __('site.admin.squad_continue') }}
        </button>
    </div>
</div>

<script>
(function () {
    var select = document.getElementById('sqdMatchSelect');
    var btn = document.getElementById('sqdGoBtn');
    btn.addEventListener('click', function () {
        var opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            window.location.href = opt.getAttribute('data-url');
        } else {
            select.focus();
        }
    });
})();
</script>
@endsection
