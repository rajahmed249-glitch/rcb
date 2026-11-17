@extends('admin.layout')

@section('title', $team->exists ? __('site.admin.edit') . ' — ' . $team->name : __('site.admin.add_team'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-shield-alt apl-title-ico"></i> {{ $team->exists ? __('site.admin.edit') : __('site.admin.add_team') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.teams.index') }}">{{ __('site.admin.total_teams') }} /</a></li>
            <li class="apl-crumb-current">{{ $team->exists ? __('site.admin.edit') : __('site.admin.add_team') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.teams.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_teams') }}
        </a>
        <a href="{{ route('admin.teams.create') }}" class="apl-tab {{ !$team->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_team') }}
        </a>
    </div>
</div>

<form method="POST" enctype="multipart/form-data"
      action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}"
      class="apf-card">
    @csrf
    @if ($team->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $team->name) }}" required placeholder="Enter team name in English">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $team->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.short_name') }}</label>
            <input type="text" name="short_name" maxlength="20" value="{{ old('short_name', $team->short_name) }}" placeholder="RCB">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.location') }}</label>
            <input type="text" name="location" value="{{ old('location', $team->location) }}" placeholder="Patuakhali">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.slug') }}</label>
            <input type="text" name="slug" value="{{ old('slug', $team->slug) }}" placeholder="real-challenger-boys">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $team->sort_order) }}">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.logo') }}</label>
            <div class="apf-upload">
                <div class="apf-upload-btn-wrap">
                    <div class="apf-upload-btn">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>{{ __('site.admin.choose_image') }}</span>
                    </div>
                    <input type="file" name="logo" id="logo" accept="image/*" onchange="previewLogo(event)">
                </div>
                <div class="apf-preview-wrap">
                    @if ($team->logo_url)
                        <img id="logoPreview" src="{{ $team->logo_url }}" alt="Preview" class="apf-preview">
                    @else
                        <img id="logoPreview" src="{{ asset('images/favicon.png') }}" alt="Preview" class="apf-preview">
                    @endif
                </div>
            </div>
            <div class="apf-file-name" id="logoFileName"></div>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.status') }}</label>
            <div class="admin-checks" style="margin-top:10px;">
                <label>
                    <input type="checkbox" name="is_rcb" value="1" {{ old('is_rcb', $team->is_rcb) ? 'checked' : '' }}>
                    {{ __('site.admin.is_rcb_team') }}
                </label>
            </div>
        </div>
    </div>

    <div class="apf-heading">{{ __('site.admin.squad_select') }}</div>

    <div class="apf-check-grid">
        @foreach ($players as $player)
            <label class="apf-perm-item">
                <input type="checkbox" name="player_ids[]" value="{{ $player->id }}" {{ $player->team_id === $team->id ? 'checked' : '' }}>
                <span class="apf-perm-name">{{ $player->name }}</span>
                @if ($player->team && $player->team_id !== $team->id)
                    <span class="apf-perm-slug">{{ $player->team->name }}</span>
                @endif
            </label>
        @endforeach
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.teams.index') }}" class="apf-cancel">
            <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
        </a>
        <button type="reset" class="apf-reset">
            <i class="fas fa-undo-alt"></i> {{ __('site.admin.reset') }}
        </button>
        <button type="submit" class="apf-submit">
            <i class="fas fa-save"></i> {{ __('site.admin.save') }}
        </button>
    </div>
</form>

<script>
function previewLogo(event) {
    var input = event.target;
    var preview = document.getElementById('logoPreview');
    var name = document.getElementById('logoFileName');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        if (name) name.textContent = input.files[0].name;
    }
}
</script>
@endsection
