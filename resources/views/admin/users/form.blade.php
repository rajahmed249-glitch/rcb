@extends('admin.layout')

@section('title', $user->exists ? __('site.admin.edit') . ' — ' . $user->name : __('site.admin.add_user'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-user apl-title-ico"></i> {{ $user->exists ? __('site.admin.edit') : __('site.admin.add_user') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.users.index') }}">{{ __('site.admin.users') }} /</a></li>
            <li class="apl-crumb-current">{{ $user->exists ? __('site.admin.edit') : __('site.admin.add_user') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.users.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_users') }}
        </a>
        <a href="{{ route('admin.users.create') }}" class="apl-tab {{ !$user->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_user') }}
        </a>
    </div>
</div>

<form method="POST" enctype="multipart/form-data"
      action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
      class="apf-card">
    @csrf
    @if ($user->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.avatar') }}</label>
            <div class="apf-upload">
                <div class="apf-upload-btn-wrap">
                    <div class="apf-upload-btn">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>{{ __('site.admin.choose_image') }}</span>
                    </div>
                    <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewImage(event)">
                </div>
                <div class="apf-preview-wrap">
                    @if ($user->avatar)
                        <img id="avatarPreview" src="{{ asset($user->avatar) }}" alt="Preview" class="apf-preview apf-preview-round">
                    @else
                        <img id="avatarPreview" src="{{ asset('images/favicon.png') }}" alt="Preview" class="apf-preview apf-preview-round">
                    @endif
                </div>
            </div>
            <div class="apf-file-name" id="avatarFileName"></div>
        </div>

        <div class="apf-group">
            <label>{{ __('site.admin.name') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="{{ __('site.admin.enter_full_name') }}">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.email') }} <span class="apf-required">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="Enter email address">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.password') }} @if (!$user->exists)<span class="apf-required">*</span>@endif</label>
            <input type="password" name="password" placeholder="{{ $user->exists ? __('site.admin.leave_blank') : 'min 6 characters' }}">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.role') }}</label>
            <select name="role_id">
                <option value="">-- {{ __('site.admin.select_role') }} --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.status') }}</label>
            <div class="apf-toggle">
                <label class="apf-toggle-option {{ old('is_active', $user->is_active ?? true) ? 'active' : '' }}" id="statusActiveLabel">
                    <input type="radio" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) onchange="toggleStatus(true)">
                    <span class="apf-toggle-icon">✅</span>
                    <span>{{ __('site.admin.active') }}</span>
                </label>
                <label class="apf-toggle-option {{ old('is_active', $user->is_active ?? true) ? '' : 'active' }}" id="statusInactiveLabel">
                    <input type="radio" name="is_active" value="0" @checked(! old('is_active', $user->is_active ?? true)) onchange="toggleStatus(false)">
                    <span class="apf-toggle-icon">⛔</span>
                    <span>{{ __('site.admin.inactive') }}</span>
                </label>
            </div>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.users.index') }}" class="apf-cancel">
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
function toggleStatus(active) {
    var a = document.getElementById('statusActiveLabel');
    var i = document.getElementById('statusInactiveLabel');
    if (a) a.classList.toggle('active', active);
    if (i) i.classList.toggle('active', !active);
}

function previewImage(event) {
    var input = event.target;
    var preview = document.getElementById('avatarPreview');
    var name = document.getElementById('avatarFileName');
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
