@extends('admin.layout')

@section('title', $location->exists ? __('site.admin.edit') . ' — ' . $location->name : __('site.admin.location'))

@section('content')
<div class="apl-head">
    <h2 class="apl-title"><i class="fas fa-map-marker-alt apl-title-ico"></i> {{ $location->exists ? __('site.admin.edit') : __('site.admin.location') }}</h2>
    <nav>
        <ol class="apl-crumb">
            <li><a class="apl-crumb-link" href="{{ route('admin.dashboard') }}">{{ __('site.admin.dashboard') }} /</a></li>
            <li><a class="apl-crumb-link" href="{{ route('admin.locations.index') }}">{{ __('site.admin.location') }} /</a></li>
            <li class="apl-crumb-current">{{ $location->exists ? __('site.admin.edit') : __('site.admin.add') }}</li>
        </ol>
    </nav>
</div>

<div class="apl-tabs-row">
    <div class="apl-tabs">
        <a href="{{ route('admin.locations.index') }}" class="apl-tab">
            <i class="fas fa-list"></i> {{ __('site.admin.districts') }}
        </a>
        <a href="{{ route('admin.locations.index', ['tab' => 'thana']) }}" class="apl-tab">
            <i class="fas fa-map-pin"></i> {{ __('site.admin.thanas') }}
        </a>
        <a href="{{ route('admin.locations.create') }}" class="apl-tab {{ !$location->exists && $location->type === 'district' ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_district') }}
        </a>
        <a href="{{ route('admin.locations.create', ['type' => 'thana']) }}" class="apl-tab {{ !$location->exists && $location->type === 'thana' ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> {{ __('site.admin.add_thana') }}
        </a>
    </div>
</div>

<form method="POST"
      action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}"
      class="apf-card">
    @csrf
    @if ($location->exists)
        @method('PUT')
    @endif

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ ($location->type === 'thana' ? __('site.admin.thana_name_en') : __('site.admin.district_name_en')) }} <span class="apf-required">*</span></label>
            <input type="text" name="name" value="{{ old('name', $location->name) }}" required placeholder="Enter name in English">
        </div>
        <div class="apf-group">
            <label>{{ ($location->type === 'thana' ? __('site.admin.thana_name_bn') : __('site.admin.district_name_bn')) }}</label>
            <input type="text" name="name_bn" value="{{ old('name_bn', $location->name_bn) }}" placeholder="বাংলায় নাম লিখুন">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.type') }} <span class="apf-required">*</span></label>
            <select name="type" id="locType" required onchange="toggleParent()">
                <option value="district" @selected(old('type', $location->type) === 'district')>{{ __('site.admin.district') }}</option>
                <option value="thana" @selected(old('type', $location->type) === 'thana')>{{ __('site.admin.thana') }}</option>
            </select>
        </div>
        <div class="apf-group" id="parentGroup">
            <label>{{ __('site.admin.district') }}</label>
            <select name="parent_id">
                <option value="">-- {{ __('site.admin.select_district') }} --</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $location->parent_id) == $parent->id)>{{ $parent->name }}@if($parent->name_bn) / {{ $parent->name_bn }}@endif</option>
                @endforeach
            </select>
        </div>
        <div class="apf-group">
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $location->sort_order) }}">
        </div>
    </div>

    <div class="apf-row">
        <div class="apf-group">
            <label>{{ __('site.admin.status') }}</label>
            <div class="apf-toggle">
                <label class="apf-toggle-option {{ old('status', $location->status ?? true) ? 'active' : '' }}" id="statusActiveLabel">
                    <input type="radio" name="status" value="1" @checked(old('status', $location->status ?? true)) onchange="toggleStatus(true)">
                    <span class="apf-toggle-icon">✅</span>
                    <span>{{ __('site.admin.active') }}</span>
                </label>
                <label class="apf-toggle-option {{ old('status', $location->status ?? true) ? '' : 'active' }}" id="statusInactiveLabel">
                    <input type="radio" name="status" value="0" @checked(! old('status', $location->status ?? true)) onchange="toggleStatus(false)">
                    <span class="apf-toggle-icon">⛔</span>
                    <span>{{ __('site.admin.inactive') }}</span>
                </label>
            </div>
        </div>
    </div>

    <div class="apf-actions">
        <a href="{{ route('admin.locations.index') }}" class="apf-cancel">
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
function toggleParent() {
    var type = document.getElementById('locType')?.value;
    var group = document.getElementById('parentGroup');
    if (group) group.style.display = type === 'thana' ? '' : 'none';
}
toggleParent();

function toggleStatus(active) {
    var a = document.getElementById('statusActiveLabel');
    var i = document.getElementById('statusInactiveLabel');
    if (a) a.classList.toggle('active', active);
    if (i) i.classList.toggle('active', !active);
}
</script>
@endsection
