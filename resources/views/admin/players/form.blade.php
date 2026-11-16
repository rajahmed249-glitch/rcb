@extends('admin.layout')

@section('title', $player->exists ? __('site.admin.edit') . ' — ' . $player->name : __('site.admin.add_player'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-user-plus apl-title-ico"></i> {{ $player->exists ? __('site.admin.edit') : __('site.admin.add_player') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.players.index') }}">{{ __('site.admin.players') }} /</a></li>
            <li class="apl-crumb-current">{{ $player->exists ? __('site.admin.edit') : __('site.admin.add') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.players.index') }}" class="apl-tab {{ $player->exists ? 'active' : '' }}">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_players') }}
        </a>
        <a href="{{ route('admin.players.create') }}" class="apl-tab {{ $player->exists ? '' : 'active' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_player') }}
        </a>
    </div>
    <a href="{{ route('admin.players.create') }}" class="apl-addbtn">
        <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_player') }}
    </a>
</div>

<div class="apf-card">
    <h3 class="apf-heading"><i class="fas fa-info-circle apf-heading-ico"></i> {{ __('site.admin.player_information') }}</h3>

    <form method="POST" enctype="multipart/form-data" class="apf-form"
          action="{{ $player->exists ? route('admin.players.update', $player) : route('admin.players.store') }}">
        @csrf
        @if ($player->exists)
            @method('PUT')
        @endif

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.player_image') }}</label>
                <div class="apf-upload">
                    <div class="apf-upload-btn-wrap">
                        <div class="apf-upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>{{ __('site.admin.choose_image') }}</span>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="apf-preview-wrap">
                        <img id="imagePreview" src="{{ $player->photo ? asset($player->photo) : asset('images/favicon.png') }}" alt="Preview" class="apf-preview">
                    </div>
                </div>
                <div class="apf-file-name" id="photoFileName"></div>
            </div>

            <div class="apf-group">
                <label>{{ __('site.admin.select_role_icon') }}</label>
                <div class="apf-icon-grid">
                    @forelse ($roleIcons as $icon)
                        <label class="apf-icon-option {{ old('role_icon_id', $player->role_icon_id) == $icon->id ? 'selected' : '' }}">
                            <input type="radio" name="role_icon_id" value="{{ $icon->id }}" @checked(old('role_icon_id', $player->role_icon_id) == $icon->id)>
                            <span class="apf-radio"></span>
                            <span class="apf-icon-box"><i class="{{ $icon->icon ?: 'fas fa-user-tie' }}"></i></span>
                        </label>
                    @empty
                        <p class="apf-empty-note">{{ __('site.admin.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $player->name) }}" placeholder="Enter name in English" required>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.name_bn') }}</label>
                <input type="text" name="name_bn" value="{{ old('name_bn', $player->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.team') }} *</label>
                <select name="team_id" required>
                    <option value="">--</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" @selected(old('team_id', $player->team_id) == $team->id)>{{ $team->name }}@if($team->name_bn) / {{ $team->name_bn }}@endif</option>
                    @endforeach
                </select>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.playing_role') }} <span class="apf-required">*</span></label>
                <select name="role" required>
                    @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'] as $role)
                        <option value="{{ $role }}" @selected(old('role', $player->role) === $role)>{{ __('site.roles.' . str_replace(['-', ' '], '_', strtolower($role))) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.dob') }}</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $player->date_of_birth?->format('Y-m-d')) }}">
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.jersey_number') }}</label>
                <input type="number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}">
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.batting_style') }}</label>
                @php
                    $battingOptions = ['Right Handed', 'Left Handed'];
                    $currentBatting = old('batting_style', $player->batting_style);
                @endphp
                <select name="batting_style">
                    <option value="">--</option>
                    @foreach (array_merge($battingOptions, ($currentBatting && ! in_array($currentBatting, $battingOptions)) ? [$currentBatting] : []) as $opt)
                        <option value="{{ $opt }}" @selected($currentBatting === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.bowling_style') }}</label>
                @php
                    $bowlingOptions = ['Right Arm Fast', 'Right Arm Medium', 'Left Arm Fast', 'Left Arm Medium', 'Right Arm Off Break', 'Right Arm Leg Break', 'Left Arm Orthodox Spin', 'Left Arm Wrist Spin'];
                    $currentBowling = old('bowling_style', $player->bowling_style);
                @endphp
                <select name="bowling_style">
                    <option value="">-- Select Bowling Style --</option>
                    @foreach (array_merge($bowlingOptions, ($currentBowling && ! in_array($currentBowling, $bowlingOptions)) ? [$currentBowling] : []) as $opt)
                        <option value="{{ $opt }}" @selected($currentBowling === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="apf-row apf-row-3">
            <div class="apf-group">
                <label>{{ __('site.admin.matches_played') }}</label>
                <input type="number" name="matches_played" value="{{ old('matches_played', $player->matches_played) }}">
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.runs') }}</label>
                <input type="number" name="runs" value="{{ old('runs', $player->runs) }}">
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.wickets') }}</label>
                <input type="number" name="wickets" value="{{ old('wickets', $player->wickets) }}">
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.is_captain') }}</label>
                <div class="apf-toggle">
                    <label class="apf-toggle-option {{ old('is_captain', $player->is_captain) ? 'active' : '' }}" id="captainLabel">
                        <input type="checkbox" name="is_captain" value="1" @checked(old('is_captain', $player->is_captain)) onchange="document.getElementById('captainLabel').classList.toggle('active', this.checked)">
                        <span class="apf-toggle-icon">✅</span>
                        <span>{{ __('site.admin.yes') }}</span>
                    </label>
                </div>
            </div>
            <div class="apf-group">
                <label>{{ __('site.admin.is_vice_captain') }}</label>
                <div class="apf-toggle">
                    <label class="apf-toggle-option {{ old('is_vice_captain', $player->is_vice_captain) ? 'active' : '' }}" id="viceCaptainLabel">
                        <input type="checkbox" name="is_vice_captain" value="1" @checked(old('is_vice_captain', $player->is_vice_captain)) onchange="document.getElementById('viceCaptainLabel').classList.toggle('active', this.checked)">
                        <span class="apf-toggle-icon">⭐</span>
                        <span>{{ __('site.admin.yes') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.bio') }}</label>
                <textarea name="bio" placeholder="Enter player description in English">{{ old('bio', $player->bio) }}</textarea>
            </div>
        </div>

        <div class="apf-row">
            <div class="apf-group">
                <label>{{ __('site.admin.bio_bn') }}</label>
                <textarea name="bio_bn" placeholder="বাংলায় প্লেয়ারের বর্ণনা লিখুন">{{ old('bio_bn', $player->bio_bn) }}</textarea>
            </div>
        </div>

        <div class="apf-actions">
            <a href="{{ route('admin.players.index') }}" class="apf-cancel">
                <i class="fas fa-arrow-left"></i> {{ __('site.admin.cancel') }}
            </a>
            <button type="reset" class="apf-reset">
                <i class="fas fa-undo-alt"></i> {{ __('site.admin.reset') }}
            </button>
            <button type="submit" class="apf-submit">
                <i class="fas fa-save"></i> {{ $player->exists ? __('site.admin.save') : __('site.admin.save_player') }}
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    var preview = document.getElementById('imagePreview');
    var fileNameDisplay = document.getElementById('photoFileName');
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) { preview.src = e.target.result; };
        reader.readAsDataURL(file);
        if (fileNameDisplay) {
            fileNameDisplay.textContent = 'Selected: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        }
    }
}
</script>
@endsection
