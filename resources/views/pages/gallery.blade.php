@extends('layouts.app')

@section('title', __('site.gallery.photo_gallery') . ' - RCB')

@section('content')
    <div class="pgal-wrap">
        <div class="pgal-head">
            <div>
                <h2 class="pgal-title">🏆 {{ __('site.gallery.sports_highlights') }}</h2>
                <p class="pgal-sub">{{ __('site.gallery.relive_moments') }}</p>
            </div>
            <div class="pgal-tabs">
                <a href="{{ route('pages.gallery') }}" class="pgal-tab active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('site.gallery.photo_gallery') }}
                </a>
                <a href="{{ route('pages.gallery-videos') }}" class="pgal-tab">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ __('site.gallery.video_gallery') }}
                </a>
            </div>
        </div>

        @if ($photos->count())
            <div class="pgal-grid">
                @foreach ($photos as $photo)
                    <div class="pgal-item" onclick="openLightbox({{ $loop->index }})">
                        <div class="pgal-imgbox">
                            <img src="{{ asset($photo->image) }}" alt="{{ $photo->display_title }}" loading="lazy">
                            <div class="pgal-overlay">
                                <span class="pgal-zoom">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                </span>
                            </div>
                        </div>
                        <div class="pgal-meta">
                            <h3>{{ $photo->display_title }}</h3>
                            <p>{{ $photo->display_date ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($photos->lastPage() > 1)
                <div class="pgal-pager">
                    <nav class="pgal-pager-nav">
                        @if ($photos->onFirstPage())
                            <span class="pgal-page-btn disabled">‹ {{ __('site.gallery.prev') }}</span>
                        @else
                            <a href="{{ $photos->previousPageUrl() }}" class="pgal-page-btn">‹ {{ __('site.gallery.prev') }}</a>
                        @endif
                        <div class="pgal-pages">
                            @foreach ($photos->getUrlRange(1, $photos->lastPage()) as $p => $url)
                                @if ($p == $photos->currentPage())
                                    <span class="pgal-page-num current">{{ $p }}</span>
                                @else
                                    <a href="{{ $url }}" class="pgal-page-num">{{ $p }}</a>
                                @endif
                            @endforeach
                        </div>
                        <span class="pgal-page-info">{{ __('site.gallery.page') }} {{ $photos->currentPage() }}/{{ $photos->lastPage() }}</span>
                        @if ($photos->hasMorePages())
                            <a href="{{ $photos->nextPageUrl() }}" class="pgal-page-btn">{{ __('site.gallery.next') }} ›</a>
                        @else
                            <span class="pgal-page-btn disabled">{{ __('site.gallery.next') }} ›</span>
                        @endif
                    </nav>
                </div>
            @endif
        @else
            <div class="pgal-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p>{{ __('site.gallery.no_photos') }}</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <div id="lightboxModal" class="pgal-lightbox" onclick="closeLightbox(event)">
        <div class="pgal-lightbox-inner" onclick="event.stopPropagation()">
            <img id="lightboxImage" src="" alt="">
            <div class="pgal-lightbox-cap">
                <h3 id="lightboxTitle"></h3>
                <p id="lightboxDate"></p>
            </div>
        </div>
        <button class="pgal-lb-btn close" onclick="closeLightbox(event)">✕</button>
        <button class="pgal-lb-btn prev" onclick="stepLightbox(-1, event)">❮</button>
        <button class="pgal-lb-btn next" onclick="stepLightbox(1, event)">❯</button>
    </div>
    <script>
        var galleryData = @json($photos->map(fn ($p) => [
            'image' => asset($p->image),
            'title' => $p->display_title,
            'date' => $p->display_date ?? '',
        ]));
        var currentIndex = 0;

        function openLightbox(i) {
            currentIndex = i;
            updateLightbox();
            document.getElementById('lightboxModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox(e) {
            if (e && e.target.classList && e.target.classList.contains('pgal-lb-btn') && !e.target.classList.contains('close')) return;
            document.getElementById('lightboxModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function stepLightbox(dir, e) {
            if (e) e.stopPropagation();
            currentIndex = (currentIndex + dir + galleryData.length) % galleryData.length;
            updateLightbox();
        }

        function updateLightbox() {
            var d = galleryData[currentIndex];
            document.getElementById('lightboxImage').src = d.image;
            document.getElementById('lightboxTitle').innerText = d.title;
            document.getElementById('lightboxDate').innerText = d.date;
        }

        document.addEventListener('keydown', function (e) {
            if (!document.getElementById('lightboxModal').classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') stepLightbox(-1);
            if (e.key === 'ArrowRight') stepLightbox(1);
        });
    </script>
@endpush
