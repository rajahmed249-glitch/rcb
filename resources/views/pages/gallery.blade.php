@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.gallery.title') }}</h1>
            <p>{{ __('site.gallery.subtitle') }}</p>
        </div>

        <div class="gallery-grid">
            @foreach ($photos as $photo)
                <a href="{{ asset($photo->image) }}" target="_blank" class="gallery-item">
                    <img src="{{ asset($photo->image) }}" alt="{{ $photo->display_title ?? ($photo->alt ?? 'RCB') }}" loading="lazy">
                </a>
            @endforeach
        </div>
    </div>
@endsection
