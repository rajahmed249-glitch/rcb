@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.gallery.title') }}</h1>
            <p>{{ __('site.gallery.subtitle') }}</p>
        </div>

        <div class="gallery-grid">
            <a href="{{ asset('images/banner1.png') }}" target="_blank" class="gallery-item">
                <img src="{{ asset('images/banner1.png') }}" alt="RCB team on the field" loading="lazy">
            </a>
            <a href="{{ asset('images/banner2.webp') }}" target="_blank" class="gallery-item">
                <img src="{{ asset('images/banner2.webp') }}" alt="RCB team photo" loading="lazy">
            </a>
            <a href="{{ asset('images/banner3.webp') }}" target="_blank" class="gallery-item">
                <img src="{{ asset('images/banner3.webp') }}" alt="RCB celebration" loading="lazy">
            </a>
            <a href="{{ asset('images/team-photo-2.webp') }}" target="_blank" class="gallery-item">
                <img src="{{ asset('images/team-photo-2.webp') }}" alt="RCB team group photo" loading="lazy">
            </a>
            <a href="{{ asset('images/team-photo-3.webp') }}" target="_blank" class="gallery-item">
                <img src="{{ asset('images/team-photo-3.webp') }}" alt="RCB team group photo" loading="lazy">
            </a>
            <a href="{{ asset('images/logo.png') }}" target="_blank" class="gallery-item">
                <img src="{{ asset('images/logo.png') }}" alt="RCB logo" loading="lazy">
            </a>
        </div>
    </div>
@endsection
