@extends('admin.layout')

@section('title', $permission->exists ? __('site.admin.edit') . ' — ' . $permission->name : __('site.admin.add_permission'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-key apl-title-ico"></i> {{ $permission->exists ? __('site.admin.edit') : __('site.admin.add_permission') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.permissions.index') }}">{{ __('site.admin.permissions') }} /</a></li>
            <li class="apl-crumb-current">{{ $permission->exists ? __('site.admin.edit') : __('site.admin.add_permission') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.permissions.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.manage_permissions') }}
        </a>
        <a href="{{ route('admin.permissions.create') }}" class="apl-tab {{ !$permission->exists ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_permission') }}
        </a>
    </div>
</div>

<form method="POST"
      action="{{ $permission->exists ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}"
      class="apf-card">
    @csrf
    @if ($permission->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.name_en') }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $permission->name) }}" required placeholder="Enter name in English">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.name_bn') }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $permission->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.slug') }}</label>
            <input type="text" name="slug" value="{{ old('slug', $permission->slug) }}" placeholder="leave blank to auto-generate">
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $permission->sort_order) }}">
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.permissions.index') }}" class="apf-cancel">
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
