@extends('admin.layout')

@section('title', __('site.admin.add_scoreboard'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-chart-line apl-title-ico"></i> {{ __('site.admin.add_scoreboard') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.scoreboards.index') }}">{{ __('site.admin.scoreboard') }} /</a></li>
            <li class="apl-crumb-current">{{ __('site.admin.add_scoreboard') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.scoreboards.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_scoreboards') }}
        </a>
        <a href="{{ route('admin.scoreboards.create') }}" class="apl-tab active">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_scoreboard') }}
        </a>
    </div>
    <a href="{{ route('admin.scoreboards.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_scoreboard') }}
    </a>
</div>

<div class="apf-card">
    <div class="apf-heading">{{ __('site.admin.scoreboard_information') }}</div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.match') }} <span class="apf-required">*</span></label>
            <select id="scbMatch" required>
                <option value="">{{ __('site.admin.select_match') }}</option>
                @foreach ($matches as $match)
                    <option value="{{ $match['id'] }}">{{ $match['label'] }}</option>
                @endforeach
            </select>
            <p class="apf-hint">{{ __('site.admin.scoreboard_continue_hint') }}</p>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.scoreboards.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="button" class="apf-submit" id="scbContinue">
            <i class="fas fa-arrow-right"></i> {{ __('site.admin.squad_continue') }}
        </button>
    </div>
</div>

<script>
(function () {
    var select = document.getElementById('scbMatch');
    var btn = document.getElementById('scbContinue');

    btn?.addEventListener('click', function () {
        if (!select.value) {
            select.focus();
            return;
        }
        window.location.href = '{{ url('admin/scoreboards') }}/' + select.value + '/edit';
    });

    select?.addEventListener('change', function () {
        if (select.value) {
            window.location.href = '{{ url('admin/scoreboards') }}/' + select.value + '/edit';
        }
    });
})();
</script>
@endsection
