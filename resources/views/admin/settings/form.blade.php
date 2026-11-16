@extends('admin.layout')

@section('title', __('site.admin.settings'))

@section('content')
<p class="admin-note">{{ __('site.admin.site_settings_note') }}</p>

<form method="POST" class="admin-form" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    <h2 class="admin-section-title">{{ __('site.admin.settings_about') }}</h2>

    @foreach ($aboutFields as $field)
        <div class="admin-grid2">
            <div>
                <label>{{ $field['label'] }} (EN)</label>
                <textarea name="{{ $field['key'] }}" rows="3">{{ old($field['key'], $field['en']) }}</textarea>
            </div>
            <div>
                <label>{{ $field['label'] }} (BN)</label>
                <textarea name="{{ $field['key'] }}_bn" rows="3">{{ old($field['key'] . '_bn', $field['bn']) }}</textarea>
            </div>
        </div>
    @endforeach

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.about.since') }}</label>
            <input type="text" name="about_since" value="{{ old('about_since', $since) }}">
        </div>
    </div>

    <h2 class="admin-section-title">{{ __('site.admin.settings_contact') }}</h2>

    @foreach ($contactFields as $field)
        <div class="admin-grid2">
            <div>
                <label>{{ $field['label'] }} (EN)</label>
                <textarea name="{{ $field['key'] }}" rows="2">{{ old($field['key'], $field['en']) }}</textarea>
            </div>
            <div>
                <label>{{ $field['label'] }} (BN)</label>
                <textarea name="{{ $field['key'] }}_bn" rows="2">{{ old($field['key'] . '_bn', $field['bn']) }}</textarea>
            </div>
        </div>
    @endforeach

    <div class="admin-grid3">
        <div>
            <label>{{ __('site.contact.phone') }}</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $phone) }}">
        </div>
        <div>
            <label>{{ __('site.contact.email') }}</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $email) }}">
        </div>
        <div>
            <label>{{ __('site.contact.whatsapp') }}</label>
            <input type="url" name="contact_whatsapp" value="{{ old('contact_whatsapp', $whatsapp) }}">
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.dashboard') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection
