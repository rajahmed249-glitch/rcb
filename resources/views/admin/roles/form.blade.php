@extends('admin.layout')

@section('title', $role->exists ? __('site.admin.edit') . ' — ' . $role->name : __('site.admin.add_role'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-user-tag apl-title-ico"></i> {{ $role->exists ? __('site.admin.edit') : __('site.admin.add_role') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.roles.index') }}">{{ __('site.admin.roles') }} /</a></li>
            <li class="apl-crumb-current">{{ $role->exists ? __('site.admin.edit') : __('site.admin.add_role') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.roles.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_roles') }}
        </a>
        <a href="{{ route('admin.roles.create') }}" class="apl-tab {{ !$role->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_role') }}
        </a>
    </div>
</div>

<form method="POST"
      action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
      class="apf-card">
    @csrf
    @if ($role->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" required placeholder="Enter name in English">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $role->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $role->sort_order) }}">
        </div>
    </div>

    <div class="apf-heading">{{ __('site.admin.role_permissions') }}</div>

    <div class="apf-perm-grid">
        @foreach ($permissions as $permission)
            <label class="apf-perm-item">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', $selectedPermissions)))>
                <span class="apf-perm-name">{{ $permission->display_name }}</span>
                <span class="apf-perm-slug">{{ $permission->slug }}</span>
            </label>
        @endforeach
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.roles.index') }}" class="apf-cancel">
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
@endsection
