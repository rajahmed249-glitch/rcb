@extends('layouts.app')

@section('title', 'Latest News')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.news.title') }}</h1>
            <p>{{ __('site.news.subtitle') }}</p>
        </div>

        <div class="news-grid">
            @forelse ($posts as $post)
                <a href="{{ route('news.show', $post) }}" class="news-card">
                    <div class="news-thumb">
                        @if ($post->image)
                            <img src="{{ asset($post->image) }}" alt="{{ $post->display_title }}" loading="lazy">
                        @else
                            <div class="news-thumb-placeholder">📰</div>
                        @endif
                        <span class="news-date-pill">📅 {{ $post->published_at?->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="news-body">
                        <h3>{{ $post->display_title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($post->display_excerpt, 120) }}</p>
                        <div class="news-foot">
                            <span>{{ __('site.read_more') }}</span>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card"><p class="muted">{{ __('site.no_news') }}</p></div>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
@endsection
