@extends('admin.layout')

@section('title', $user->exists ? __('site.admin.edit') . ' — ' . $user->name : __('site.admin.users'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
    @csrf
    @if ($user->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div>
            <label>Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
    </div>

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.admin.password') }} @if ($user->exists)({{ __('site.admin.leave_blank') }})@else *@endif</label>
            <input type="password" name="password" {{ $user->exists ? '' : 'required' }}>
        </div>
        <div>
            <label>{{ __('site.admin.role') }}</label>
            <select name="role_id">
                <option value="">—</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-checks" style="align-self:end;">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                {{ __('site.admin.active') }}
            </label>
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.users.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection
