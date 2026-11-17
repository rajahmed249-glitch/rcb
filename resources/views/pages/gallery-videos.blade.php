@extends('layouts.app')

@section('title', __('site.gallery.video_gallery') . ' - RCB')

@section('content')
    <div class="pgal-wrap">
        <div class="pgal-head">
            <div>
                <h2 class="pgal-title">🏆 {{ __('site.gallery.sports_highlights') }}</h2>
                <p class="pgal-sub">{{ __('site.gallery.relive_moments') }}</p>
            </div>
            <div class="pgal-tabs">
                <a href="{{ route('pages.gallery') }}" class="pgal-tab">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('site.gallery.photo_gallery') }}
                </a>
                <a href="{{ route('pages.gallery-videos') }}" class="pgal-tab active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ __('site.gallery.video_gallery') }}
                </a>
            </div>
        </div>

        @if ($videos->count())
            <div class="vgal-grid">
                @foreach ($videos as $video)
                    <div class="vgal-item" data-code="{{ $video->video_code }}" data-title="{{ $video->display_title }}" onclick="openVideoModal(this)">
                        <div class="vgal-imgbox">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->display_title }}" loading="lazy">
                            <div class="vgal-shade">
                                <span class="vgal-play">
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M18 3a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5h-12a5 5 0 0 1-5-5v-8a5 5 0 0 1 5-5zm-9 6v6a1 1 0 0 0 1.514.857l5-3a1 1 0 0 0 0-1.714l-5-3a1 1 0 0 0-1.514.857z"/></svg>
                                </span>
                            </div>
                        </div>
                        <div class="vgal-meta">
                            <h3>{{ $video->display_title }}</h3>
                            <p>{{ $video->display_date ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($videos->lastPage() > 1)
                <div class="pgal-pager">
                    <nav class="pgal-pager-nav">
                        @if ($videos->onFirstPage())
                            <span class="pgal-page-btn disabled">‹ {{ __('site.gallery.prev') }}</span>
                        @else
                            <a href="{{ $videos->previousPageUrl() }}" class="pgal-page-btn">‹ {{ __('site.gallery.prev') }}</a>
                        @endif
                        <div class="pgal-pages">
                            @foreach ($videos->getUrlRange(1, $videos->lastPage()) as $p => $url)
                                @if ($p == $videos->currentPage())
                                    <span class="pgal-page-num current">{{ $p }}</span>
                                @else
                                    <a href="{{ $url }}" class="pgal-page-num">{{ $p }}</a>
                                @endif
                            @endforeach
                        </div>
                        <span class="pgal-page-info">{{ __('site.gallery.page') }} {{ $videos->currentPage() }}/{{ $videos->lastPage() }}</span>
                        @if ($videos->hasMorePages())
                            <a href="{{ $videos->nextPageUrl() }}" class="pgal-page-btn">{{ __('site.gallery.next') }} ›</a>
                        @else
                            <span class="pgal-page-btn disabled">{{ __('site.gallery.next') }} ›</span>
                        @endif
                    </nav>
                </div>
            @endif
        @else
            <div class="pgal-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <p>{{ __('site.gallery.no_videos') }}</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <div id="videoModal" class="vgal-modal" onclick="closeVideoModal(event)">
        <div class="vgal-modal-inner" onclick="event.stopPropagation()">
            <iframe id="videoIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <div id="videoTitle" class="vgal-modal-title"></div>
        </div>
        <button class="pgal-lb-btn close" onclick="closeVideoModal(event)">✕</button>
    </div>
    <script>
        function openVideoModal(el) {
            document.getElementById('videoIframe').src = 'https://www.youtube.com/embed/' + el.getAttribute('data-code') + '?autoplay=1';
            document.getElementById('videoTitle').innerText = el.getAttribute('data-title');
            document.getElementById('videoModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal(e) {
            if (e && e.target.classList && e.target.classList.contains('pgal-lb-btn') && !e.target.classList.contains('close')) return;
            document.getElementById('videoIframe').src = '';
            document.getElementById('videoModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeVideoModal();
        });
    </script>
@endpush
